(function () {
	"use strict";

	document.addEventListener("DOMContentLoaded", function () {
		var header = document.querySelector("[data-trf-header]");
		var toggle = document.querySelector("[data-trf-nav-toggle]");
		var nav = document.querySelector("[data-trf-nav]");
		if (!header || !toggle || !nav) {
			return;
		}

		toggle.addEventListener("click", function () {
			var open = header.classList.toggle("is-open");
			toggle.setAttribute("aria-expanded", open ? "true" : "false");
		});

		nav.querySelectorAll(".trf-nav__sub-toggle").forEach(function (btn) {
			btn.addEventListener("click", function () {
				var item = btn.closest(".menu-item-has-children");
				if (!item) {
					return;
				}
				var open = item.classList.toggle("is-open");
				btn.setAttribute("aria-expanded", open ? "true" : "false");
			});
		});
	});
})();
