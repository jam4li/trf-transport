(function () {
	"use strict";

	document.addEventListener("DOMContentLoaded", function () {
		var dialog = document.querySelector("[data-trf-quote-modal]");
		if (!dialog) {
			return;
		}

		var header = document.querySelector("[data-trf-header]");
		var navToggle = document.querySelector("[data-trf-nav-toggle]");
		var openers = document.querySelectorAll("[data-trf-quote-open]");
		var closers = dialog.querySelectorAll("[data-trf-quote-close]");

		function closeNav() {
			if (!header || !header.classList.contains("is-open")) {
				return;
			}
			header.classList.remove("is-open");
			document.body.classList.remove("trf-nav-open");
			if (navToggle) {
				navToggle.setAttribute("aria-expanded", "false");
			}
		}

		function openModal() {
			closeNav();
			if (typeof dialog.showModal === "function") {
				if (!dialog.open) {
					dialog.showModal();
				}
			} else {
				dialog.setAttribute("open", "");
			}
			document.body.classList.add("trf-modal-open");
		}

		function closeModal() {
			if (typeof dialog.close === "function" && dialog.open) {
				dialog.close();
			} else {
				dialog.removeAttribute("open");
			}
			document.body.classList.remove("trf-modal-open");
		}

		openers.forEach(function (el) {
			el.setAttribute("aria-haspopup", "dialog");
			el.addEventListener("click", function (event) {
				event.preventDefault();
				openModal();
			});
		});

		closers.forEach(function (el) {
			el.addEventListener("click", function () {
				closeModal();
			});
		});

		dialog.addEventListener("click", function (event) {
			if (event.target === dialog) {
				closeModal();
			}
		});

		dialog.addEventListener("close", function () {
			document.body.classList.remove("trf-modal-open");
		});

		document.addEventListener("keydown", function (event) {
			if (event.key === "Escape" && dialog.open) {
				closeModal();
			}
		});

		var params = new URLSearchParams(window.location.search);
		if (params.get("trf_quote") || window.location.hash === "#quote") {
			openModal();
		}
	});
})();
