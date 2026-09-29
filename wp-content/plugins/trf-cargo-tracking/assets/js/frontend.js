(function () {
	var root = document.querySelector("[data-trf-track]");
	if (!root || typeof trfCargoTracking === "undefined") {
		return;
	}

	var form = root.querySelector("[data-trf-track-form]");
	var input = root.querySelector("#trf-track-code");
	var result = root.querySelector("[data-trf-track-result]");
	var submit = root.querySelector("[data-trf-track-submit]");
	var submitLabel = root.querySelector("[data-trf-track-submit-label]");
	if (!form || !input || !result) {
		return;
	}

	var defaultSubmitText = submitLabel ? submitLabel.textContent : "";
	var busySubmitText = "در حال بررسی…";
	var lightbox = null;
	var lastFocus = null;

	function escapeHtml(value) {
		var div = document.createElement("div");
		div.appendChild(document.createTextNode(value == null ? "" : String(value)));
		return div.innerHTML;
	}

	function setBusy(isBusy) {
		if (submit) {
			submit.disabled = !!isBusy;
		}
		if (submitLabel) {
			submitLabel.textContent = isBusy ? busySubmitText : defaultSubmitText;
		}
		input.setAttribute("aria-busy", isBusy ? "true" : "false");
	}

	function notice(kind, message, role) {
		var roleAttr = role ? ' role="' + role + '"' : "";
		return (
			'<div class="trf-track__notice trf-track__notice--' +
			kind +
			'"' +
			roleAttr +
			"><p>" +
			escapeHtml(message) +
			"</p></div>"
		);
	}

	function renderGallery(images, code) {
		if (!images.length) {
			return "";
		}

		var thumbs = images
			.map(function (url, index) {
				var safe = escapeHtml(url);
				var alt = escapeHtml("تصویر " + (index + 1) + " محموله " + code);
				return (
					'<button type="button" class="trf-track__thumb" data-trf-lightbox-src="' +
					safe +
					'" data-trf-lightbox-alt="' +
					alt +
					'">' +
					'<img src="' +
					safe +
					'" alt="' +
					alt +
					'" loading="lazy">' +
					"</button>"
				);
			})
			.join("");

		return (
			'<section class="trf-track__gallery" aria-label="تصاویر محموله">' +
			'<h3 class="trf-track__gallery-title">تصاویر محموله</h3>' +
			'<div class="trf-track__gallery-grid">' +
			thumbs +
			"</div></section>"
		);
	}

	function render(payload, codeLabel) {
		if (!payload || payload.status !== "ok") {
			var message = (payload && payload.description) || "کد رهگیری یافت نشد.";
			result.innerHTML = notice("error", message, "alert");
			return;
		}

		var images = Array.isArray(payload.gallery) ? payload.gallery : [];
		var code = payload.validation || "";

		result.innerHTML =
			'<article class="trf-track__card">' +
			'<header class="trf-track__card-head">' +
			'<p class="trf-track__badge">یافت شد</p>' +
			'<p class="trf-track__code">' +
			'<span class="trf-track__code-label">' +
			escapeHtml(codeLabel) +
			"</span>" +
			'<code class="trf-track__code-value" dir="ltr">' +
			escapeHtml(code) +
			"</code></p></header>" +
			'<div class="trf-track__description">' +
			escapeHtml(payload.description).replace(/\n/g, "<br>") +
			"</div>" +
			renderGallery(images, code) +
			"</article>";
	}

	function ensureLightbox() {
		if (lightbox) {
			return lightbox;
		}

		lightbox = document.createElement("div");
		lightbox.className = "trf-track-lightbox";
		lightbox.setAttribute("hidden", "");
		lightbox.innerHTML =
			'<div class="trf-track-lightbox__dialog" role="dialog" aria-modal="true" aria-label="تصویر محموله" tabindex="-1">' +
			'<button type="button" class="trf-track-lightbox__close" aria-label="بستن">&times;</button>' +
			'<img class="trf-track-lightbox__img" alt="">' +
			"</div>";
		document.body.appendChild(lightbox);

		lightbox.addEventListener("click", function (event) {
			if (event.target === lightbox) {
				closeLightbox();
			}
		});

		var closeBtn = lightbox.querySelector(".trf-track-lightbox__close");
		if (closeBtn) {
			closeBtn.addEventListener("click", closeLightbox);
		}

		return lightbox;
	}

	function openLightbox(src, alt) {
		var box = ensureLightbox();
		var img = box.querySelector(".trf-track-lightbox__img");
		var dialog = box.querySelector(".trf-track-lightbox__dialog");
		var closeBtn = box.querySelector(".trf-track-lightbox__close");

		lastFocus = document.activeElement;
		if (img) {
			img.src = src;
			img.alt = alt || "";
		}

		box.removeAttribute("hidden");
		box.classList.add("is-open");
		document.body.classList.add("trf-track-lightbox-open");

		if (closeBtn) {
			closeBtn.focus();
		} else if (dialog) {
			dialog.focus();
		}
	}

	function closeLightbox() {
		if (!lightbox || !lightbox.classList.contains("is-open")) {
			return;
		}

		lightbox.classList.remove("is-open");
		lightbox.setAttribute("hidden", "");
		document.body.classList.remove("trf-track-lightbox-open");

		var img = lightbox.querySelector(".trf-track-lightbox__img");
		if (img) {
			img.removeAttribute("src");
			img.alt = "";
		}

		if (lastFocus && typeof lastFocus.focus === "function") {
			lastFocus.focus();
		}
		lastFocus = null;
	}

	document.addEventListener("keydown", function (event) {
		if (event.key === "Escape") {
			closeLightbox();
		}
	});

	result.addEventListener("click", function (event) {
		var thumb = event.target.closest("[data-trf-lightbox-src]");
		if (!thumb || !result.contains(thumb)) {
			return;
		}
		event.preventDefault();
		openLightbox(thumb.getAttribute("data-trf-lightbox-src"), thumb.getAttribute("data-trf-lightbox-alt"));
	});

	form.addEventListener("submit", function (event) {
		event.preventDefault();
		var code = (input.value || "").trim();
		if (!code) {
			return;
		}

		setBusy(true);
		result.innerHTML = notice("busy", "در حال دریافت وضعیت بار…");

		var body = new FormData();
		body.append("action", trfCargoTracking.action);
		body.append("nonce", trfCargoTracking.nonce);
		body.append("code", code);
		body.append("validation", code);

		fetch(trfCargoTracking.ajaxUrl, {
			method: "POST",
			credentials: "same-origin",
			body: body,
		})
			.then(function (response) {
				return response.json();
			})
			.then(function (payload) {
				render(payload, trfCargoTracking.codeLabel || "");
				if (window.history && window.history.replaceState) {
					var url = new URL(window.location.href);
					url.searchParams.set("trf_track", code);
					window.history.replaceState({}, "", url.toString());
				}
			})
			.catch(function () {
				result.innerHTML = notice("error", "خطایی رخ داد. دوباره تلاش کنید.", "alert");
			})
			.finally(function () {
				setBusy(false);
			});
	});
})();
