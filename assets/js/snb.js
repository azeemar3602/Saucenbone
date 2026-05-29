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

		// Strip border/background from any Kadence/WooCommerce wrapper injected
		// around our ORDER button.
		var resetProps = {
			border: 'none',
			borderRadius: '0',
			background: 'none',
			backgroundColor: 'transparent',
			boxShadow: 'none',
			padding: '0',
			outline: 'none'
		};
		document.querySelectorAll('.snb-order-btn').forEach(function (btn) {
			var el = btn.parentElement;
			for (var i = 0; i < 3; i++) {
				if (!el || el.tagName === 'LI' || el.tagName === 'ARTICLE') { break; }
				Object.assign(el.style, resetProps);
				el = el.parentElement;
			}
		});

		// ── Mobile hamburger button ──────────────────────────────────
		// Kadence or a plugin applies background/bar-color inline or via
		// a late-loading stylesheet that beats our CSS. JS inline styles
		// are the absolute highest priority — nothing can override them.
		var toggle = document.getElementById('mobile-toggle');
		if (toggle) {
			// Button itself: transparent background
			toggle.style.setProperty('background', 'transparent', 'important');
			toggle.style.setProperty('background-color', 'transparent', 'important');
			toggle.style.setProperty('background-image', 'none', 'important');
			toggle.style.setProperty('border', 'none', 'important');
			toggle.style.setProperty('box-shadow', 'none', 'important');
			toggle.style.setProperty('border-radius', '0', 'important');
			toggle.style.setProperty('outline', 'none', 'important');

			// All child elements: transparent bg, cream color
			toggle.querySelectorAll('*').forEach(function (child) {
				child.style.setProperty('background', 'transparent', 'important');
				child.style.setProperty('background-color', 'transparent', 'important');
				child.style.setProperty('background-image', 'none', 'important');
				child.style.setProperty('color', '#F4EEDF', 'important');
				child.style.setProperty('fill', '#F4EEDF', 'important');
				child.style.setProperty('stroke', '#F4EEDF', 'important');
				child.style.setProperty('border', 'none', 'important');
				child.style.setProperty('box-shadow', 'none', 'important');
			});

			// Hamburger bar spans specifically: cream background (they are the bars)
			toggle.querySelectorAll('span').forEach(function (span) {
				// Only target tiny bar spans (not text-containing spans)
				if (!span.textContent.trim()) {
					span.style.setProperty('background', '#F4EEDF', 'important');
					span.style.setProperty('background-color', '#F4EEDF', 'important');
				}
			});
		}
	});
})();
