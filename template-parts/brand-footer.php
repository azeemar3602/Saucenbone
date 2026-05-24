<?php
/**
 * Sauce N' Bone — brand footer block injected before </body>.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<footer class="snb-footer" role="contentinfo">
	<div class="snb-footer__grid">

		<div class="snb-footer__brand">
			<h4>SAUCE<span class="snb-accent"> N'</span> BONE</h4>
			<p>Bold Flavors. Real Ingredients. No Shortcuts. That's the Sauce N' Bone promise.</p>
			<div class="snb-social" aria-label="Follow us">
				<a href="https://instagram.com/saucenbone" aria-label="Instagram" rel="noopener" target="_blank">IG</a>
				<a href="https://tiktok.com/@saucenbone" aria-label="TikTok" rel="noopener" target="_blank">TT</a>
				<a href="https://facebook.com/saucenbone" aria-label="Facebook" rel="noopener" target="_blank">FB</a>
			</div>
			<p style="margin-top:1rem;color:var(--snb-cream-dim);font-size:0.9rem;">@SAUCENBONE &middot; SAUCENBONE.COM</p>
		</div>

		<div>
			<h5>Order</h5>
			<ul>
				<li><a href="/menu/">Menu</a></li>
				<li><a href="/cart/">Cart</a></li>
				<li><a href="/checkout/">Checkout</a></li>
				<li><a href="/loyalty/">Loyalty</a></li>
			</ul>
		</div>

		<div>
			<h5>Brand</h5>
			<ul>
				<li><a href="/about/">About</a></li>
				<li><a href="/shop/">Merch</a></li>
				<li><a href="/contact/">Contact</a></li>
			</ul>
		</div>

		<div>
			<h5>Legal</h5>
			<ul>
				<li><a href="/privacy-policy/">Privacy Policy</a></li>
				<li><a href="/terms/">Terms of Service</a></li>
				<li><a href="/accessibility/">Accessibility</a></li>
			</ul>
		</div>

	</div>

	<div class="snb-footer__bottom">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sauce N' Bone. All rights reserved.</span>
		<span>Flavor First. No Shortcuts.</span>
	</div>
</footer>
