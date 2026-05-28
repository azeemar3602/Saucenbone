<?php
/**
 * Title: Page — Merch / Shop Landing
 * Description: Optional standalone merch page. Pulls Woo products tagged "merch".
 *              Use this only if you want a custom-styled merch page; otherwise
 *              point your "Merch" nav to /shop/ which uses the Woo default.
 */
?>
<!-- wp:html -->
<section class="snb-hero" style="padding-block:3rem;">
	<div class="snb-hero__grid">
		<div>
			<h1 class="snb-hero__title" style="font-size:clamp(3rem,8vw,7rem);">REP THE<br><span class="snb-accent">CULTURE.</span></h1>
		</div>
		<div style="display:grid;grid-template-columns:1fr 1.1fr;gap:1.5rem;align-items:center;">
			<div>
				<p style="color:#F4EEDF;font-family:var(--snb-font-display);letter-spacing:0.04em;font-size:1.2rem;line-height:1.2;margin:0 0 0.75rem;">Hoodies. Tees. Hats. Everyday merch.</p>
				<p class="snb-hero__copy" style="margin:0;font-size:0.95rem;">Built for real ones who put Flavor First.</p>
			</div>
			<div class="snb-hero__media" aria-hidden="true"><span class="snb-hero__media-placeholder">MERCH</span></div>
		</div>
	</div>
</section>
<!-- /wp:html -->

<!-- wp:shortcode -->
[snb_products limit="12" heading="" columns="4" category="merch,apparel,accessories" exclude_category=""]
<!-- /wp:shortcode -->
