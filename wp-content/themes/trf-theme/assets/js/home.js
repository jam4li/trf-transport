(function () {
	"use strict";

	function activateTab(buttons, panels, idAttr, panelAttr, targetId) {
		buttons.forEach(function (btn) {
			var active = btn.getAttribute(idAttr) === targetId;
			btn.classList.toggle("is-active", active);
			btn.setAttribute("aria-selected", active ? "true" : "false");
			btn.setAttribute("tabindex", active ? "0" : "-1");
		});
		panels.forEach(function (panel) {
			var match = panel.getAttribute(panelAttr) === targetId;
			panel.classList.toggle("is-active", match);
			if (match) {
				panel.removeAttribute("hidden");
			} else {
				panel.setAttribute("hidden", "");
			}
		});
	}

	function bindTablist(root, buttonSel, panelSel, idAttr, panelAttr) {
		var buttons = Array.prototype.slice.call(root.querySelectorAll(buttonSel));
		var panels = Array.prototype.slice.call(root.querySelectorAll(panelSel));
		if (!buttons.length) {
			return;
		}

		buttons.forEach(function (btn) {
			btn.addEventListener("click", function () {
				activateTab(buttons, panels, idAttr, panelAttr, btn.getAttribute(idAttr));
			});

			btn.addEventListener("keydown", function (event) {
				var rtl = document.documentElement.getAttribute("dir") === "rtl";
				var next = rtl ? -1 : 1;
				var keys = { ArrowRight: next, ArrowLeft: -next, Home: "home", End: "end" };
				if (!keys[event.key]) {
					return;
				}
				event.preventDefault();
				var index = buttons.indexOf(btn);
				if (event.key === "Home") {
					index = 0;
				} else if (event.key === "End") {
					index = buttons.length - 1;
				} else {
					index = (index + keys[event.key] + buttons.length) % buttons.length;
				}
				buttons[index].focus();
				activateTab(buttons, panels, idAttr, panelAttr, buttons[index].getAttribute(idAttr));
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

	document.addEventListener("DOMContentLoaded", function () {
		var countries = document.querySelector("[data-trf-tabs]");
		if (countries) {
			bindTablist(countries, "[data-trf-tab]", "[data-trf-panel]", "data-trf-tab", "data-trf-panel");
		}

		var agents = document.querySelector("[data-trf-agent-tabs]");
		if (agents) {
			bindTablist(agents, "[data-trf-agent-tab]", "[data-trf-agent-panel]", "data-trf-agent-tab", "data-trf-agent-panel");
			agents.querySelectorAll("[data-trf-agent-panel]").forEach(initAgentCarousel);
		}
	});
})();
