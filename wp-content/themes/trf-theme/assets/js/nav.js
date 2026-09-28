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

		nav.querySelectorAll(".menu-item-has-children > a").forEach(function (link) {
			link.addEventListener("click", function (event) {
				if (window.matchMedia("(max-width: 980px)").matches) {
					event.preventDefault();
					link.parentElement.classList.toggle("is-open");
				}
			});
		});
	});
})();
