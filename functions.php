<?php
/**
 * Sauce N' Bone — Kadence Child Theme
 * Bold flavors. Real ingredients. No shortcuts.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SNB_THEME_VERSION', '1.0.0' );

/**
 * Enqueue parent + child styles and Google Fonts.
 */
add_action( 'wp_enqueue_scripts', 'snb_enqueue_styles', 20 );
function snb_enqueue_styles() {
	wp_enqueue_style(
		'snb-fonts',
		'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700;800&display=swap',
		array(),
		SNB_THEME_VERSION
	);

	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css', array(), null );
	wp_enqueue_style(
		'snb-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'parent-style', 'snb-fonts' ),
		SNB_THEME_VERSION
	);

	if ( file_exists( get_stylesheet_directory() . '/assets/js/snb.js' ) ) {
		wp_enqueue_script(
			'snb-script',
			get_stylesheet_directory_uri() . '/assets/js/snb.js',
			array(),
			SNB_THEME_VERSION,
			true
		);
	}
}

/**
 * Editor styles so the block editor matches the front-end.
 */
add_action( 'after_setup_theme', 'snb_editor_styles' );
function snb_editor_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
}

/**
 * Register block pattern category.
 */
add_action( 'init', 'snb_register_pattern_category' );
function snb_register_pattern_category() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category(
			'saucenbone',
			array( 'label' => __( "Sauce N' Bone", 'saucenbone-kadence' ) )
		);
	}
}

/**
 * Register block patterns from /patterns/ folder.
 */
add_action( 'init', 'snb_register_patterns' );
function snb_register_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) { return; }

	$patterns_dir = get_stylesheet_directory() . '/patterns';
	if ( ! is_dir( $patterns_dir ) ) { return; }

	$files = glob( $patterns_dir . '/*.php' );
	foreach ( (array) $files as $file ) {
		$slug = basename( $file, '.php' );
		$data = snb_get_pattern_meta( $file );
		ob_start();
		include $file;
		$content = ob_get_clean();

		register_block_pattern(
			'saucenbone/' . $slug,
			array(
				'title'       => $data['title'] ? $data['title'] : ucwords( str_replace( '-', ' ', $slug ) ),
				'description' => $data['description'],
				'categories'  => array( 'saucenbone' ),
				'content'     => $content,
			)
		);
	}
}

/**
 * Read `Title:` / `Description:` headers from a pattern file.
 */
function snb_get_pattern_meta( $file ) {
	$out = array( 'title' => '', 'description' => '' );
	$fp  = fopen( $file, 'r' );
	if ( ! $fp ) { return $out; }
	$head = fread( $fp, 1024 );
	fclose( $fp );
	if ( preg_match( '/Title:\s*(.+)/i', $head, $m ) )       { $out['title']       = trim( $m[1] ); }
	if ( preg_match( '/Description:\s*(.+)/i', $head, $m ) ) { $out['description'] = trim( $m[1] ); }
	return $out;
}

/**
 * Inject Sauce N' Bone footer markup at the closing body tag,
 * unless the theme already renders the brand footer.
 */
add_action( 'wp_footer', 'snb_render_brand_footer', 5 );
function snb_render_brand_footer() {
	if ( is_admin() || apply_filters( 'snb_skip_brand_footer', false ) ) { return; }
	$template = get_stylesheet_directory() . '/template-parts/brand-footer.php';
	if ( file_exists( $template ) ) {
		include $template;
	}
}

/**
 * WooCommerce: thank-you / order-received decoration.
 * Replaces the default heading with the SNB confirmation hero.
 */
add_action( 'woocommerce_before_thankyou', 'snb_thankyou_hero', 5 );
function snb_thankyou_hero( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( ! $order ) { return; }
	?>
	<section class="snb-section snb-section--charcoal" style="text-align:center;">
		<div class="snb-container">
			<span class="snb-eyebrow">Order Confirmed</span>
			<h1 class="snb-cream">Thanks — We're On It.</h1>
			<p style="max-width:600px;margin:0 auto;">Your order is confirmed and heading to our kitchen. Bold flavor is on the way.</p>

			<div class="snb-trust-row" style="max-width:900px;margin:2rem auto 0;">
				<div class="snb-trust"><strong>Order #<?php echo esc_html( $order->get_order_number() ); ?></strong><span><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span></div>
				<div class="snb-trust"><strong>Status</strong><span><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span></div>
				<div class="snb-trust"><strong>Total</strong><span><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span></div>
			</div>

			<div class="snb-tracker" style="max-width:900px;margin:2rem auto 0;">
				<div class="snb-tracker__step is-done">
					<span class="snb-tracker__num">1</span>
					<div class="snb-tracker__label">Order Received</div>
					<p class="snb-mb-0">We've got your order.</p>
				</div>
				<div class="snb-tracker__step is-current">
					<span class="snb-tracker__num">2</span>
					<div class="snb-tracker__label">In Kitchen</div>
					<p class="snb-mb-0">We've got it cooking.</p>
				</div>
				<div class="snb-tracker__step">
					<span class="snb-tracker__num">3</span>
					<div class="snb-tracker__label">Out for Delivery</div>
					<p class="snb-mb-0">On the way.</p>
				</div>
				<div class="snb-tracker__step">
					<span class="snb-tracker__num">4</span>
					<div class="snb-tracker__label">Delivered</div>
					<p class="snb-mb-0">Enjoy the flavor.</p>
				</div>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Loyalty promo after order details on thank-you.
 */
add_action( 'woocommerce_thankyou', 'snb_thankyou_loyalty', 25 );
function snb_thankyou_loyalty() {
	?>
	<section class="snb-loyalty" style="margin-top:2rem;border-radius:14px;">
		<h2>Bold Flavor. Big Rewards.</h2>
		<p>Earn a stamp for this order — you're one step closer to a free item.</p>
		<a href="#" class="snb-btn snb-btn--gold">Join Loyalty</a>
	</section>
	<?php
}

/**
 * Checkout: add SNB trust badges above the form.
 */
add_action( 'woocommerce_before_checkout_form', 'snb_checkout_trust_badges', 5 );
function snb_checkout_trust_badges() {
	?>
	<div class="snb-trust-row">
		<div class="snb-trust"><strong>Secure Checkout</strong><span>Your info is always protected.</span></div>
		<div class="snb-trust"><strong>Fast &amp; Fresh</strong><span>Made to order. Never sitting.</span></div>
		<div class="snb-trust"><strong>Flavor First</strong><span>Real ingredients. No shortcuts.</span></div>
	</div>
	<?php
}

/**
 * Cart: reassurance bar after cart totals.
 */
add_action( 'woocommerce_after_cart_totals', 'snb_cart_reassurance' );
function snb_cart_reassurance() {
	?>
	<div class="snb-trust-row" style="margin-top:1.5rem;">
		<div class="snb-trust"><strong>Made Fresh to Order</strong><span>Never frozen. Always bold.</span></div>
		<div class="snb-trust"><strong>Easy Pickup</strong><span>Skip the line. Grab and go.</span></div>
		<div class="snb-trust"><strong>Love It or Let Us Know</strong><span>We'll make it right.</span></div>
	</div>
	<?php
}

/**
 * Cart: pickup vs delivery selector above cart totals.
 */
add_action( 'woocommerce_before_cart_totals', 'snb_cart_pickup_delivery' );
function snb_cart_pickup_delivery() {
	?>
	<div class="snb-toggle-group" data-snb="pickup-delivery">
		<label class="snb-toggle is-active">
			<input type="radio" name="snb_fulfillment" value="pickup" checked hidden>
			<strong>Pickup</strong>
			<span>Ready in 25&ndash;30 mins. Pick up at your nearest Sauce N' Bone.</span>
		</label>
		<label class="snb-toggle">
			<input type="radio" name="snb_fulfillment" value="delivery" hidden>
			<strong>Delivery</strong>
			<span>Deliver to your door. Available in select areas.</span>
		</label>
	</div>
	<?php
}

/**
 * Cart: recommended add-ons section.
 */
add_action( 'woocommerce_after_cart', 'snb_cart_recommended_addons', 20 );
function snb_cart_recommended_addons() {
	?>
	<section class="snb-section snb-section--charcoal" style="margin-top:2.5rem;border-radius:14px;">
		<div class="snb-container">
			<div class="snb-section__head">
				<h2>Make it even better.</h2>
				<p>Add these fan favorites to complete your order.</p>
			</div>
			<div class="snb-grid snb-grid--3">
				<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Boneless Wings &mdash; Garlic Parmesan</h3><span class="snb-card__price">$12.99</span><a href="?add-to-cart=boneless-gp" class="snb-btn snb-card__cta">+ Add</a></article>
				<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Tenders &mdash; Classic Hot</h3><span class="snb-card__price">$11.49</span><a href="?add-to-cart=tenders-hot" class="snb-btn snb-card__cta">+ Add</a></article>
				<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Corn Ribs</h3><span class="snb-card__price">$5.99</span><a href="?add-to-cart=corn-ribs" class="snb-btn snb-card__cta">+ Add</a></article>
				<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Mac &amp; Cheese</h3><span class="snb-card__price">$4.49</span><a href="?add-to-cart=mac" class="snb-btn snb-card__cta">+ Add</a></article>
				<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Blue Raspberry Lemonade</h3><span class="snb-card__price">$3.49</span><a href="?add-to-cart=blue-rasp" class="snb-btn snb-card__cta">+ Add</a></article>
				<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Garlic Butter Dust</h3><span class="snb-card__price">$0.99</span><a href="?add-to-cart=garlic-butter" class="snb-btn snb-card__cta">+ Add</a></article>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Checkout: pickup vs delivery toggle (display-only; integrate with shipping later).
 */
add_action( 'woocommerce_checkout_before_customer_details', 'snb_checkout_fulfillment_toggle' );
function snb_checkout_fulfillment_toggle() {
	?>
	<div class="snb-toggle-group" data-snb="pickup-delivery">
		<label class="snb-toggle is-active">
			<input type="radio" name="snb_fulfillment" value="pickup" checked hidden>
			<strong>Pickup</strong>
			<span>Pick up at our location. Fast &amp; easy.</span>
		</label>
		<label class="snb-toggle">
			<input type="radio" name="snb_fulfillment" value="delivery" hidden>
			<strong>Delivery</strong>
			<span>We bring the heat to you. Fees may apply.</span>
		</label>
	</div>

	<div class="snb-card" style="margin-bottom:1.5rem;">
		<span class="snb-eyebrow">Pickup Location</span>
		<h3 class="snb-card__title">Sauce N' Bone &mdash; Downtown</h3>
		<p class="snb-card__copy">123 Flavor Way, Nashville, TN 37203</p>
		<p class="snb-card__copy"><strong class="snb-gold">Estimated pickup:</strong> 25&ndash;35 min &middot; Today</p>
		<a href="#" style="color:var(--snb-sauce-red);">Change Location</a>
	</div>
	<?php
}

/**
 * Checkout: tip selector before order review.
 */
add_action( 'woocommerce_review_order_before_submit', 'snb_checkout_tip_selector' );
function snb_checkout_tip_selector() {
	?>
	<div style="margin:1.25rem 0;">
		<span class="snb-eyebrow">Add a Tip</span>
		<p style="margin:0.4rem 0 0.8rem;color:var(--snb-cream-dim);">100% of tips go to our team.</p>
		<div class="snb-tip-group" data-snb="tip">
			<button type="button" class="snb-tip" data-tip="0">No Tip</button>
			<button type="button" class="snb-tip" data-tip="10">10%</button>
			<button type="button" class="snb-tip is-active" data-tip="15">15%</button>
			<button type="button" class="snb-tip" data-tip="20">20%</button>
			<button type="button" class="snb-tip" data-tip="other">Other</button>
		</div>
	</div>
	<?php
}
