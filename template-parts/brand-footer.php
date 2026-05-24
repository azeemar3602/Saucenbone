<?php
/**
 * Sauce N' Bone — brand footer block injected before </body>.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Footer logo URL. Falls back to the custom uploaded logo, then to the
 * theme's site icon. Override via the `snb_footer_logo_url` filter.
 */
$snb_footer_logo = apply_filters(
	'snb_footer_logo_url',
	'/wp-content/uploads/2026/05/ChatGPT-Image-May-21-2026-01_25_02-PM-e1779629478727.png'
);
?>
<footer class="snb-footer" role="contentinfo">

	<div class="snb-footer__top">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="snb-footer__mark" aria-label="Sauce N' Bone">
			<?php if ( $snb_footer_logo ) : ?>
				<img src="<?php echo esc_url( $snb_footer_logo ); ?>" alt="Sauce N' Bone">
			<?php else : ?>
				SNB
			<?php endif; ?>
		</a>

		<div class="snb-footer__social">
			<span class="snb-footer__social-label">Follow Us</span>
			<div class="snb-social">
				<a href="https://instagram.com/saucenbone" aria-label="Instagram" rel="noopener" target="_blank">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
				</a>
				<a href="https://tiktok.com/@saucenbone" aria-label="TikTok" rel="noopener" target="_blank">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12v6a3 3 0 1 0 3-3"/><path d="M15 3v10a4 4 0 0 0 4 4"/></svg>
				</a>
				<a href="https://facebook.com/saucenbone" aria-label="Facebook" rel="noopener" target="_blank">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 8h-2a2 2 0 0 0-2 2v3H7v3h3v6h3v-6h2.5l.5-3H13v-2.5a.5.5 0 0 1 .5-.5H16V8z"/></svg>
				</a>
			</div>
		</div>

		<span class="snb-footer__handle">@SAUCENBONE</span>

		<span class="snb-footer__site">
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/></svg>
			SAUCENBONE.COM
		</span>

		<div class="snb-footer__promise">
			Bold Flavors. Real Ingredients. No Shortcuts.<br>
			That's the <span>Sauce N' Bone</span> promise.
		</div>

	</div>

	<div class="snb-footer__bottom">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sauce N' Bone. All rights reserved.</span>
		<div class="snb-footer__legal">
			<a href="/privacy-policy/">Privacy Policy</a>
			<a href="/terms/">Terms of Service</a>
			<a href="/accessibility/">Accessibility</a>
		</div>
	</div>

</footer>
