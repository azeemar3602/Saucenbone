<?php
/**
 * Title: Menu — Product Detail Panel
 * Description: Featured product detail panel with size, flavor, add-ons, and quantity controls.
 */
?>
<!-- wp:html -->
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-detail">
			<div class="snb-detail__media"></div>
			<div>
				<span class="snb-eyebrow">Featured</span>
				<h2 style="margin:0.5rem 0 0.75rem;">Bone-In Wings</h2>
				<p>Classic, crispy, and packed with bold flavor.</p>

				<div class="snb-detail__field">
					<label>Choose size</label>
					<div class="snb-chip-row" style="justify-content:flex-start;">
						<button type="button" class="snb-chip">6 pc</button>
						<button type="button" class="snb-chip" aria-pressed="true">10 pc</button>
						<button type="button" class="snb-chip">15 pc</button>
						<button type="button" class="snb-chip">20 pc</button>
					</div>
				</div>

				<div class="snb-detail__field">
					<label for="snb-flavor">Choose flavor</label>
					<select id="snb-flavor" class="snb-select">
						<option>Strawberry Hot</option>
						<option>Mango Habanero</option>
						<option>Pineapple Jalape&ntilde;o</option>
						<option>Sweet &amp; Tangy</option>
						<option>Garlic Parmesan</option>
						<option>Classic Hot</option>
						<option>Blueberry Blaze</option>
						<option>Grape Inferno</option>
						<option>Orange Chili Rush</option>
						<option>Real Heat</option>
					</select>
				</div>

				<div class="snb-detail__field">
					<label>Mix it up</label>
					<div class="snb-chip-row" style="justify-content:flex-start;">
						<button type="button" class="snb-chip">Single Flavor</button>
						<button type="button" class="snb-chip">Half &amp; Half</button>
						<button type="button" class="snb-chip">Mix Flavors</button>
					</div>
				</div>

				<div class="snb-detail__field">
					<label>Add-ons</label>
					<div class="snb-chip-row" style="justify-content:flex-start;">
						<button type="button" class="snb-chip">+ Ranch</button>
						<button type="button" class="snb-chip">+ Garlic Parmesan</button>
						<button type="button" class="snb-chip">+ Extra Sauce</button>
					</div>
				</div>

				<div class="snb-detail__field" style="display:flex;align-items:center;gap:1rem;">
					<label style="margin:0;">Qty</label>
					<div class="snb-chip-row" style="justify-content:flex-start;gap:0;">
						<button type="button" class="snb-chip" style="border-radius:8px 0 0 8px;">&minus;</button>
						<span class="snb-chip" style="border-radius:0;background:var(--snb-charcoal-2);">1</span>
						<button type="button" class="snb-chip" style="border-radius:0 8px 8px 0;">+</button>
					</div>
				</div>

				<a href="#" class="snb-btn snb-btn--block" style="margin-top:1rem;">Add to Cart &mdash; $17.99</a>
			</div>
		</div>
	</div>
</section>
<!-- /wp:html -->
