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

	var i18n = trfCargoTracking.i18n || {};
	var defaultSubmitText = submitLabel ? submitLabel.textContent : "";
	var busySubmitText = i18n.busy || "در حال بررسی…";
	var lightbox = null;
	var lastFocus = null;

	function t(key, fallback) {
		return i18n[key] || fallback;
	}

	function escapeHtml(value) {
		var div = document.createElement("div");
		div.appendChild(document.createTextNode(value == null ? "" : String(value)));
		return div.innerHTML;
	}

	function toPersianDigits(value) {
		return String(value == null ? "" : value).replace(/[0-9]/g, function (digit) {
			return "۰۱۲۳۴۵۶۷۸۹"[digit];
		});
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

	function notice(kind, title, message, role) {
		var roleAttr = role ? ' role="' + role + '"' : "";
		var body = message ? "<p>" + escapeHtml(message) + "</p>" : "";
		return (
			'<div class="trf-track__notice trf-track__notice--' +
			kind +
			'"' +
			roleAttr +
			"><p class=\"trf-track__notice-title\">" +
			escapeHtml(title) +
			"</p>" +
			body +
			"</div>"
		);
	}

	function isPdf(url) {
		return /\.pdf(\?|#|$)/i.test(url || "");
	}

	function fileName(url) {
		try {
			var path = String(url || "").split(/[?#]/)[0];
			var name = decodeURIComponent(path.substring(path.lastIndexOf("/") + 1));
			return name || "PDF";
		} catch (e) {
			return "PDF";
		}
	}

	function headlineFromDescription(description) {
		var text = String(description || "").replace(/^\s+|\s+$/g, "");
		if (!text) {
			return t("found", "محموله یافت شد");
		}
		var line = text.split(/\r?\n/)[0].replace(/^\s+|\s+$/g, "");
		return line.length > 88 ? line.slice(0, 88) + "…" : line;
	}

	function updatesLabel(count) {
		count = Math.max(1, count);
		return toPersianDigits(count) + " " + t("updates", "به‌روزرسانی");
	}

	function renderFiles(files, code) {
		if (!files.length) {
			return "";
		}

		var items = files
			.map(function (url, index) {
				var safe = escapeHtml(url);

				if (isPdf(url)) {
					var pdfLabel = escapeHtml("فایل PDF " + (index + 1) + " محموله " + code);
					return (
						'<a class="trf-track__file" href="' +
						safe +
						'" target="_blank" rel="noopener noreferrer" aria-label="' +
						pdfLabel +
						'">' +
						'<span class="trf-track__file-badge">PDF</span>' +
						'<span class="trf-track__file-name">' +
						escapeHtml(fileName(url)) +
						"</span></a>"
					);
				}

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
					'" loading="lazy"></button>'
				);
			})
			.join("");

		return (
			'<section class="trf-track__files" aria-label="' +
			escapeHtml(t("documents", "مدارک")) +
			'">' +
			'<p class="trf-track__files-label">' +
			escapeHtml(t("documents", "مدارک")) +
			'</p><div class="trf-track__files-grid">' +
			items +
			"</div></section>"
		);
	}

	function renderEntries(entries, code) {
		return (
			'<ol class="trf-track__timeline">' +
			entries
				.map(function (entry, index) {
					var date = entry && entry.event_date ? String(entry.event_date) : "";
					var description = entry && entry.description ? String(entry.description) : "";
					var files = entry && Array.isArray(entry.gallery) ? entry.gallery : [];
					var isCurrent = index === 0;
					var dateHtml = date
						? '<time class="trf-track__date" datetime="' +
						  escapeHtml(date) +
						  '">' +
						  escapeHtml(toPersianDigits(date)) +
						  "</time>"
						: "";
					var flagHtml =
						isCurrent && entries.length > 1
							? '<p class="trf-track__event-flag">' + escapeHtml(t("latest", "آخرین وضعیت")) + "</p>"
							: "";
					var descHtml = description
						? '<div class="trf-track__description">' + escapeHtml(description).replace(/\n/g, "<br>") + "</div>"
						: "";

					return (
						'<li class="trf-track__event' +
						(isCurrent ? " is-current" : "") +
						'">' +
						'<span class="trf-track__dot" aria-hidden="true"></span>' +
						dateHtml +
						'<div class="trf-track__event-body">' +
						flagHtml +
						descHtml +
						renderFiles(files, code) +
						"</div></li>"
					);
				})
				.join("") +
			"</ol>"
		);
	}

	function render(payload, codeLabel) {
		root.classList.toggle("has-result", !!(payload && payload.status === "ok"));

		if (!payload || payload.status !== "ok") {
			var message = (payload && payload.description) || t("notFound", "کد رهگیری یافت نشد.");
			result.innerHTML = notice("error", message, t("errorHint", ""), "alert");
			return;
		}

		var code = payload.validation || "";
		var entries =
			Array.isArray(payload.entries) && payload.entries.length
				? payload.entries
				: [
						{
							description: payload.description || "",
							gallery: Array.isArray(payload.gallery) ? payload.gallery : [],
							event_date: payload.event_date || "",
						},
				  ];
		var latest = entries[0] || {};
		var latestDate = latest.event_date ? String(latest.event_date) : "";
		var headline = headlineFromDescription(latest.description);
		var meta = "";

		if (latestDate) {
			meta +=
				'<time datetime="' +
				escapeHtml(latestDate) +
				'">' +
				escapeHtml(toPersianDigits(latestDate)) +
				"</time>";
		}
		meta += "<span>" + escapeHtml(updatesLabel(entries.length)) + "</span>";

		result.innerHTML =
			'<article class="trf-track__shipment">' +
			'<header class="trf-track__summary">' +
			'<div class="trf-track__summary-main">' +
			'<p class="trf-track__kicker">' +
			escapeHtml(t("kicker", "وضعیت محموله")) +
			"</p>" +
			'<h3 class="trf-track__headline">' +
			escapeHtml(headline) +
			"</h3>" +
			'<p class="trf-track__meta">' +
			meta +
			"</p></div>" +
			'<div class="trf-track__summary-code">' +
			'<span class="trf-track__code-label">' +
			escapeHtml(codeLabel) +
			"</span>" +
			'<div class="trf-track__code-row">' +
			'<code class="trf-track__code-value" dir="ltr">' +
			escapeHtml(code) +
			'</code><button type="button" class="trf-track__copy" data-trf-copy="' +
			escapeHtml(code) +
			'">' +
			escapeHtml(t("copy", "کپی کد")) +
			"</button></div></div></header>" +
			'<h4 class="trf-track__section-title">' +
			escapeHtml(t("path", "مسیر وضعیت")) +
			"</h4>" +
			renderEntries(entries, code) +
			"</article>";
	}

	function copyCode(button) {
		var value = button.getAttribute("data-trf-copy") || "";
		if (!value) {
			return;
		}

		var done = function () {
			var original = button.getAttribute("data-trf-copy-label") || button.textContent;
			button.setAttribute("data-trf-copy-label", original);
			button.textContent = t("copied", "کپی شد");
			button.classList.add("is-copied");
			window.setTimeout(function () {
				button.textContent = original;
				button.classList.remove("is-copied");
			}, 1600);
		};

		var fallback = function () {
			var field = document.createElement("textarea");
			field.value = value;
			field.setAttribute("readonly", "");
			field.style.position = "fixed";
			field.style.insetInlineStart = "-9999px";
			document.body.appendChild(field);
			field.select();
			try {
				document.execCommand("copy");
			} catch (err) {
				/* ignore */
			}
			document.body.removeChild(field);
		};

		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(value).then(done).catch(function () {
				fallback();
				done();
			});
			return;
		}

		fallback();
		done();
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
		var copyBtn = event.target.closest("[data-trf-copy]");
		if (copyBtn && result.contains(copyBtn)) {
			event.preventDefault();
			copyCode(copyBtn);
			return;
		}

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
		root.classList.remove("has-result");
		result.innerHTML = '<div class="trf-track__notice trf-track__notice--busy"><p>' + escapeHtml(t("loading", "در حال دریافت وضعیت بار…")) + "</p></div>";

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
				if (result.scrollIntoView) {
					result.scrollIntoView({ behavior: "smooth", block: "start" });
				}
			})
			.catch(function () {
				result.innerHTML = notice("error", t("error", "خطایی رخ داد. دوباره تلاش کنید."), "", "alert");
			})
			.finally(function () {
				setBusy(false);
			});
	});
})();
