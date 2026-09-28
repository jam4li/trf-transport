(function () {
	"use strict";

	function initCountryTabs() {
		var root = document.querySelector("[data-trf-tabs]");
		if (!root) {
			return;
		}

		var buttons = root.querySelectorAll("[data-trf-tab]");
		var panels = root.querySelectorAll("[data-trf-panel]");

		buttons.forEach(function (btn) {
			btn.addEventListener("click", function () {
				var id = btn.getAttribute("data-trf-tab");
				buttons.forEach(function (b) {
					var active = b === btn;
					b.classList.toggle("is-active", active);
					b.setAttribute("aria-selected", active ? "true" : "false");
				});
				panels.forEach(function (panel) {
					var match = panel.getAttribute("data-trf-panel") === id;
					panel.classList.toggle("is-active", match);
					if (match) {
						panel.removeAttribute("hidden");
					} else {
						panel.setAttribute("hidden", "");
					}
				});
			});
		});
	}

	function initAgentCarousel(root) {
		var track = root.querySelector("[data-trf-agents-track]");
		var prev = root.querySelector("[data-trf-agents-prev]");
		var next = root.querySelector("[data-trf-agents-next]");
		if (!track || !prev || !next) {
			return;
		}

		function step() {
			var card = track.querySelector(".trf-agent-card");
			if (!card) {
				return 280;
			}
			var styles = window.getComputedStyle(track);
			var gap = parseFloat(styles.columnGap || styles.gap || "16") || 16;
			return card.getBoundingClientRect().width + gap;
		}

		prev.addEventListener("click", function () {
			track.scrollBy({ left: step(), behavior: "smooth" });
		});

		next.addEventListener("click", function () {
			track.scrollBy({ left: -step(), behavior: "smooth" });
		});
	}

	function initAgentTabs() {
		var root = document.querySelector("[data-trf-agent-tabs]");
		if (!root) {
			return;
		}

		var buttons = root.querySelectorAll("[data-trf-agent-tab]");
		var panels = root.querySelectorAll("[data-trf-agent-panel]");

		panels.forEach(function (panel) {
			initAgentCarousel(panel);
		});

		buttons.forEach(function (btn) {
			btn.addEventListener("click", function () {
				var id = btn.getAttribute("data-trf-agent-tab");
				buttons.forEach(function (b) {
					var active = b === btn;
					b.classList.toggle("is-active", active);
					b.setAttribute("aria-selected", active ? "true" : "false");
				});
				panels.forEach(function (panel) {
					var match = panel.getAttribute("data-trf-agent-panel") === id;
					panel.classList.toggle("is-active", match);
					if (match) {
						panel.removeAttribute("hidden");
					} else {
						panel.setAttribute("hidden", "");
					}
				});
			});
		});
	}

	document.addEventListener("DOMContentLoaded", function () {
		initCountryTabs();
		initAgentTabs();
	});
})();
