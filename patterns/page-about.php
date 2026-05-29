<?php
/**
 * Title: Page — About Us
 * Description: Full About Us page. Uses the [snb_about] shortcode which
 *              renders the two-column story section (text + circular image).
 *              To set the circular photo: Appearance → Customize → set the
 *              "About Us Image" option, or replace the theme_mod key in
 *              functions.php with an ACF/CPT field.
 */
?>
<!-- wp:html -->
<section class="snb-hero" style="padding-block:3rem 2rem;">
	<div class="snb-hero__grid">
		<div>
			<h1 class="snb-hero__title" style="font-size:clamp(2.2rem,5vw,4.5rem);">
				ABOUT<br><span class="snb-accent">US.</span>
			</h1>
		</div>
		<div>
			<p style="color:var(--snb-cream-soft);font-size:1rem;line-height:1.6;margin:0;">
				Real ingredients. Real people. One obsession: flavor first.<br>
				Here's the story behind Sauce N' Bone.
			</p>
		</div>
	</div>
</section>
<!-- /wp:html -->

<!-- wp:shortcode -->
[snb_about]
<!-- /wp:shortcode -->
