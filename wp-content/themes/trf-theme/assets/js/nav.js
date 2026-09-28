(function () {
	"use strict";

	var MQ = "(max-width: 74.99rem)";

	document.addEventListener("DOMContentLoaded", function () {
		var header = document.querySelector("[data-trf-header]");
		var toggle = document.querySelector("[data-trf-nav-toggle]");
		var nav = document.querySelector("[data-trf-nav]");
		if (!header || !toggle || !nav) {
			return;
		}

		var media = window.matchMedia(MQ);

		function setOpen(open) {
			header.classList.toggle("is-open", open);
			toggle.setAttribute("aria-expanded", open ? "true" : "false");
			document.body.classList.toggle("trf-nav-open", open);

			if (!open) {
				nav.querySelectorAll(".menu-item-has-children.is-open").forEach(function (item) {
					item.classList.remove("is-open");
					var btn = item.querySelector(".trf-nav__sub-toggle");
					if (btn) {
						btn.setAttribute("aria-expanded", "false");
					}
				});
			}
		}

		toggle.addEventListener("click", function () {
			setOpen(!header.classList.contains("is-open"));
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

		nav.addEventListener("click", function (event) {
			var link = event.target.closest("a");
			if (!link || !media.matches) {
				return;
			}
			var href = link.getAttribute("href");
			if (href && href !== "#") {
				setOpen(false);
			}
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape" && header.classList.contains("is-open")) {
				setOpen(false);
				toggle.focus();
			}
		});

		function onMqChange() {
			if (!media.matches) {
				setOpen(false);
			}
		}

		if (media.addEventListener) {
			media.addEventListener("change", onMqChange);
		} else if (media.addListener) {
			media.addListener(onMqChange);
		}
	});
})();
