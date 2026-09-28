(function () {
	var root = document.querySelector("[data-trf-track]");
	if (!root || typeof trfCargoTracking === "undefined") {
		return;
	}

	var form = root.querySelector("[data-trf-track-form]");
	var input = root.querySelector("#trf-track-code");
	var result = root.querySelector("[data-trf-track-result]");
	if (!form || !input || !result) {
		return;
	}

	function escapeHtml(value) {
		var div = document.createElement("div");
		div.appendChild(document.createTextNode(value == null ? "" : String(value)));
		return div.innerHTML;
	}

	function render(payload, codeLabel) {
		if (!payload || payload.status !== "ok") {
			var message = (payload && payload.description) || "";
			result.innerHTML = '<p class="trf-track__notice trf-track__notice--error">' + escapeHtml(message) + "</p>";
			return;
		}

		var images = Array.isArray(payload.gallery) ? payload.gallery : [];
		var gallery = "";
		if (images.length) {
			gallery =
				'<div class="trf-track__gallery">' +
				images
					.map(function (url) {
						var safe = escapeHtml(url);
						return (
							'<a href="' +
							safe +
							'" target="_blank" rel="noopener noreferrer"><img src="' +
							safe +
							'" alt=""></a>'
						);
					})
					.join("") +
				"</div>";
		}

		result.innerHTML =
			'<div class="trf-track__card">' +
			'<p class="trf-track__code"><span>' +
			escapeHtml(codeLabel) +
			":</span> " +
			escapeHtml(payload.validation) +
			"</p>" +
			'<div class="trf-track__description">' +
			escapeHtml(payload.description).replace(/\n/g, "<br>") +
			"</div>" +
			gallery +
			"</div>";
	}

	form.addEventListener("submit", function (event) {
		event.preventDefault();
		var code = (input.value || "").trim();
		if (!code) {
			return;
		}

		result.innerHTML = '<p class="trf-track__notice trf-track__notice--busy">…</p>';

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
				var label = form.querySelector("label");
				render(payload, label ? label.textContent : "");
				if (window.history && window.history.replaceState) {
					var url = new URL(window.location.href);
					url.searchParams.set("trf_track", code);
					window.history.replaceState({}, "", url.toString());
				}
			})
			.catch(function () {
				result.innerHTML =
					'<p class="trf-track__notice trf-track__notice--error">خطایی رخ داد. دوباره تلاش کنید.</p>';
			});
	});
})();
