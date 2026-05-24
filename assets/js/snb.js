(function () {
	"use strict";

	function ready(fn) {
		if (document.readyState !== "loading") { fn(); }
		else { document.addEventListener("DOMContentLoaded", fn); }
	}

	ready(function () {
		// Pickup / Delivery toggle groups
		document.querySelectorAll('[data-snb="pickup-delivery"]').forEach(function (group) {
			group.querySelectorAll(".snb-toggle").forEach(function (toggle) {
				toggle.addEventListener("click", function () {
					group.querySelectorAll(".snb-toggle").forEach(function (t) {
						t.classList.remove("is-active");
					});
					toggle.classList.add("is-active");
					var input = toggle.querySelector("input");
					if (input) { input.checked = true; }
				});
			});
		});

		// Tip selector
		document.querySelectorAll('[data-snb="tip"]').forEach(function (group) {
			group.querySelectorAll(".snb-tip").forEach(function (tip) {
				tip.addEventListener("click", function () {
					group.querySelectorAll(".snb-tip").forEach(function (t) {
						t.classList.remove("is-active");
					});
					tip.classList.add("is-active");
				});
			});
		});

		// Flavor chip selection (single-select within a row)
		document.querySelectorAll(".snb-chip-row").forEach(function (row) {
			row.querySelectorAll(".snb-chip").forEach(function (chip) {
				chip.addEventListener("click", function () {
					if (chip.tagName !== "BUTTON") { return; }
					row.querySelectorAll(".snb-chip").forEach(function (c) {
						c.removeAttribute("aria-pressed");
					});
					chip.setAttribute("aria-pressed", "true");
				});
			});
		});

		// Category tab active state
		document.querySelectorAll(".snb-tabs").forEach(function (tabs) {
			tabs.querySelectorAll(".snb-tab").forEach(function (tab) {
				tab.addEventListener("click", function () {
					tabs.querySelectorAll(".snb-tab").forEach(function (t) {
						t.classList.remove("is-active");
					});
					tab.classList.add("is-active");
				});
			});
		});
	});
})();
