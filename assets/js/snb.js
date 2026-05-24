(function () {
	"use strict";

	function ready(fn) {
		if (document.readyState !== "loading") { fn(); }
		else { document.addEventListener("DOMContentLoaded", fn); }
	}

	function activateExclusive(group, target, activeClass) {
		group.querySelectorAll("." + activeClass).forEach(function (el) {
			el.classList.remove(activeClass);
		});
		target.classList.add(activeClass);
	}

	ready(function () {
		// Pickup / Delivery toggle groups
		document.querySelectorAll('[data-snb="pickup-delivery"]').forEach(function (group) {
			group.querySelectorAll(".snb-toggle").forEach(function (toggle) {
				toggle.addEventListener("click", function () {
					activateExclusive(group, toggle, "is-active");
					var input = toggle.querySelector("input");
					if (input) { input.checked = true; }
				});
			});
		});

		// Tip selector
		document.querySelectorAll('[data-snb="tip"]').forEach(function (group) {
			group.querySelectorAll(".snb-tip").forEach(function (tip) {
				tip.addEventListener("click", function () {
					activateExclusive(group, tip, "is-active");
				});
			});
		});

		// Flavor chips (single-select within a row)
		document.querySelectorAll(".snb-chip-row").forEach(function (row) {
			row.querySelectorAll(".snb-chip").forEach(function (chip) {
				if (chip.tagName !== "BUTTON") { return; }
				chip.addEventListener("click", function () {
					row.querySelectorAll(".snb-chip").forEach(function (c) {
						c.removeAttribute("aria-pressed");
						c.classList.remove("is-active");
					});
					chip.setAttribute("aria-pressed", "true");
					chip.classList.add("is-active");
				});
			});
		});

		// Category tabs
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

		// Size pills in product detail
		document.querySelectorAll(".snb-pill-row").forEach(function (row) {
			row.querySelectorAll(".snb-pill").forEach(function (pill) {
				pill.addEventListener("click", function () {
					row.querySelectorAll(".snb-pill").forEach(function (p) {
						p.classList.remove("is-active");
					});
					pill.classList.add("is-active");
				});
			});
		});

		// Quantity steppers
		document.querySelectorAll(".snb-qty").forEach(function (qty) {
			var value = qty.querySelector(".snb-qty__value");
			if (!value) { return; }
			var buttons = qty.querySelectorAll("button");
			if (buttons.length < 2) { return; }
			buttons[0].addEventListener("click", function () {
				var n = Math.max(1, parseInt(value.textContent || "1", 10) - 1);
				value.textContent = n;
			});
			buttons[1].addEventListener("click", function () {
				var n = parseInt(value.textContent || "1", 10) + 1;
				value.textContent = n;
			});
		});

		// Payment method selector
		document.querySelectorAll(".snb-pay-row").forEach(function (row) {
			row.querySelectorAll(".snb-pay").forEach(function (pay) {
				pay.addEventListener("click", function () {
					row.querySelectorAll(".snb-pay").forEach(function (p) {
						p.classList.remove("is-active");
					});
					pay.classList.add("is-active");
				});
			});
		});

		// Special instructions char counter
		document.querySelectorAll('textarea[name="snb_special_instructions"]').forEach(function (ta) {
			var counter = ta.nextElementSibling;
			if (!counter || !counter.textContent.match(/\/\s*250/)) { return; }
			function update() { counter.textContent = ta.value.length + " / 250"; }
			ta.addEventListener("input", update);
			update();
		});
	});
})();
