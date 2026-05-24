<?php
/**
 * Title: Home — Hero
 * Description: Sauce N' Bone homepage hero with tagline and CTAs.
 */
?>
<!-- wp:html -->
<section class="snb-hero">
	<div class="snb-hero__grid">
		<div class="snb-hero__copy-col">
			<span class="snb-eyebrow">Bold Flavors. No Shortcuts.</span>
			<h1 class="snb-hero__title">Flavor First.<br/><span class="snb-accent">No Shortcuts.</span></h1>
			<p class="snb-hero__copy">Bold wings, real ingredients, and sauces built to hit hard. Pick your style, choose your heat, and get ready for flavor that does not play safe.</p>
			<div class="snb-hero__cta">
				<a href="/menu/" class="snb-btn">Order Now</a>
				<a href="/menu/" class="snb-btn snb-btn--ghost">View Menu</a>
			</div>
		</div>
		<div class="snb-hero__media" aria-hidden="true">
			<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/hero-wings.jpg' ); ?>" alt="Sauce N' Bone wings" onerror="this.style.display='none'" />
		</div>
	</div>
</section>
<span class="snb-drip" aria-hidden="true"></span>
<!-- /wp:html -->
