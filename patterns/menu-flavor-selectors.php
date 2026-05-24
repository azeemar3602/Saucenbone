<?php
/**
 * Title: Menu — Flavor Selectors
 * Description: Signature + Viral flavor lists side-by-side, with the Mix-Flavors strip.
 */
?>
<!-- wp:html -->
<section class="snb-section snb-section--charcoal" style="padding-block:2.5rem;">
	<div class="snb-container">

		<div class="snb-grid snb-grid--2">
			<div class="snb-card">
				<h3 class="snb-card__title" style="margin-bottom:0.5rem;">Signature Flavors</h3>
				<span class="snb-head__label-line" style="margin-bottom:1rem;"></span>
				<div class="snb-flavor-list">
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍓</span><span>Strawberry Hot</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🧄</span><span>Garlic Parmesan</span><span class="snb-flavor-row__heat">🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🥭</span><span>Mango Habanero</span><span class="snb-flavor-row__heat">🔥🔥🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🌶️</span><span>Classic Hot</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍍</span><span>Pineapple Jalape&ntilde;o</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍯</span><span>Sweet &amp; Tangy</span><span class="snb-flavor-row__heat">🔥</span></div>
				</div>
				<div class="snb-text-center" style="margin-top:1rem;"><a href="#" class="snb-btn snb-btn--ghost snb-btn--sm">View All Flavors</a></div>
			</div>

			<div class="snb-card">
				<h3 class="snb-card__title" style="margin-bottom:0.5rem;">Viral Flavors</h3>
				<span class="snb-head__label-line" style="margin-bottom:1rem;"></span>
				<div class="snb-flavor-list" style="grid-template-columns:1fr;">
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🫐</span><span>Blueberry Blaze</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍇</span><span>Grape Inferno</span><span class="snb-flavor-row__heat">🔥🔥🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍊</span><span>Orange Chili Rush</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
					<div class="snb-flavor-row"><span class="snb-flavor-row__icon">💀</span><span>Real Heat</span><span class="snb-flavor-row__heat">🔥🔥🔥🔥</span></div>
				</div>
				<div class="snb-text-center" style="margin-top:1rem;"><a href="#" class="snb-btn snb-btn--ghost snb-btn--sm">View All Flavors</a></div>
			</div>
		</div>

		<div class="snb-mix-strip">
			<div class="snb-mix-strip__icon">
				<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4l16 16M20 4L4 20"/></svg>
			</div>
			<div class="snb-mix-strip__copy">
				<strong>Not sure? Try a mix of flavors!</strong>
				<span>Half one flavor, half another. Because variety is flavor.</span>
			</div>
			<a href="?mix=1" class="snb-btn snb-btn--sm">Mix Flavors</a>
		</div>

	</div>
</section>
<!-- /wp:html -->
