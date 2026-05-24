<?php
/**
 * Title: Menu — Product Detail Panel
 * Description: Featured product detail panel with size, flavor, add-ons, qty.
 */
?>
<!-- wp:html -->
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-detail">
			<div class="snb-detail__media"></div>
			<h2 class="snb-detail__title">Bone-In Wings</h2>
			<p class="snb-detail__copy">Classic, crispy, and packed with bold flavor.</p>

			<div class="snb-detail__field">
				<label class="snb-detail__label">Choose Size</label>
				<div class="snb-pill-row">
					<span class="snb-pill">6 pc<span class="snb-pill__sub">$10.99</span></span>
					<span class="snb-pill is-active">10 pc<span class="snb-pill__sub">$17.99</span></span>
					<span class="snb-pill">15 pc<span class="snb-pill__sub">$24.99</span></span>
					<span class="snb-pill">20 pc<span class="snb-pill__sub">$31.99</span></span>
				</div>
			</div>

			<div class="snb-detail__field">
				<label class="snb-detail__label" for="snb-flavor-select">Choose Your Flavor</label>
				<select id="snb-flavor-select" class="snb-select">
					<option>🍓 Strawberry Hot 🔥🔥</option>
					<option>🥭 Mango Habanero 🔥🔥🔥</option>
					<option>🍍 Pineapple Jalape&ntilde;o 🔥🔥</option>
					<option>🍯 Sweet &amp; Tangy 🔥</option>
					<option>🧄 Garlic Parmesan 🔥</option>
					<option>🌶️ Classic Hot 🔥🔥</option>
					<option>🫐 Blueberry Blaze 🔥🔥</option>
					<option>🍇 Grape Inferno 🔥🔥🔥</option>
					<option>🍊 Orange Chili Rush 🔥🔥</option>
					<option>💀 Real Heat 🔥🔥🔥🔥</option>
				</select>
				<a href="#" style="display:inline-flex;align-items:center;gap:0.4rem;color:var(--snb-sauce-red);margin-top:0.75rem;font-size:0.85rem;letter-spacing:0.12em;text-transform:uppercase;font-family:var(--snb-font-display);">
					<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4l16 16M20 4L4 20"/></svg>
					Mix Flavors (Half &amp; Half)
				</a>
			</div>

			<div class="snb-detail__field">
				<label class="snb-detail__label">Add-Ons</label>
				<div class="snb-addon-list">
					<label><input type="checkbox"> Ranch <span class="snb-addon-price">$0.75</span></label>
					<label><input type="checkbox"> Garlic Parmesan <span class="snb-addon-price">$1.00</span></label>
					<label><input type="checkbox"> Extra Sauce <span class="snb-addon-price">$1.00</span></label>
				</div>
			</div>

			<div class="snb-detail__field" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
				<label class="snb-detail__label" style="margin:0;">Quantity</label>
				<div class="snb-qty">
					<button type="button" aria-label="Decrease">−</button>
					<span class="snb-qty__value">1</span>
					<button type="button" aria-label="Increase">+</button>
				</div>
			</div>

			<a href="#" class="snb-btn snb-btn--block" style="margin-top:1rem;">Add to Cart &nbsp; <span style="opacity:0.85;">$17.99</span></a>
		</div>
	</div>
</section>
<!-- /wp:html -->
