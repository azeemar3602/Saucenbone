<?php
/**
 * Sauce N' Bone — Kadence Child Theme
 * Bold flavors. Real ingredients. No shortcuts.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SNB_THEME_VERSION', '1.8.0' );

/**
 * Enqueue parent + child styles, fonts, and JS.
 */
add_action( 'wp_enqueue_scripts', 'snb_enqueue_styles', 20 );
function snb_enqueue_styles() {
	wp_enqueue_style(
		'snb-fonts',
		'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Permanent+Marker&family=Montserrat:wght@400;500;600;700;800&display=swap',
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
		wp_enqueue_script( 'snb-script', get_stylesheet_directory_uri() . '/assets/js/snb.js', array(), SNB_THEME_VERSION, true );
		wp_localize_script( 'snb-script', 'SNB', array(
			'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
			'nonce'    => wp_create_nonce( 'snb_nonce' ),
			'cartUrl'  => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' ),
		) );
	}
}

/**
 * Editor styles.
 */
add_action( 'after_setup_theme', 'snb_editor_styles' );
function snb_editor_styles() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'style.css' );
}

/**
 * Block pattern category + auto-register from /patterns/.
 */
add_action( 'init', 'snb_register_pattern_category' );
function snb_register_pattern_category() {
	if ( function_exists( 'register_block_pattern_category' ) ) {
		register_block_pattern_category( 'saucenbone', array( 'label' => __( "Sauce N' Bone", 'saucenbone-kadence' ) ) );
	}
}

add_action( 'init', 'snb_register_patterns' );
function snb_register_patterns() {
	if ( ! function_exists( 'register_block_pattern' ) ) { return; }
	$dir = get_stylesheet_directory() . '/patterns';
	if ( ! is_dir( $dir ) ) { return; }
	foreach ( (array) glob( $dir . '/*.php' ) as $file ) {
		$slug = basename( $file, '.php' );
		$data = snb_pattern_meta( $file );
		ob_start();
		include $file;
		$content = ob_get_clean();
		register_block_pattern( 'saucenbone/' . $slug, array(
			'title'       => $data['title'] ?: ucwords( str_replace( '-', ' ', $slug ) ),
			'description' => $data['description'],
			'categories'  => array( 'saucenbone' ),
			'content'     => $content,
		) );
	}
}

function snb_pattern_meta( $file ) {
	$out = array( 'title' => '', 'description' => '' );
	$head = file_get_contents( $file, false, null, 0, 1024 );
	if ( preg_match( '/Title:\s*(.+)/i', $head, $m ) )       { $out['title']       = trim( $m[1] ); }
	if ( preg_match( '/Description:\s*(.+)/i', $head, $m ) ) { $out['description'] = trim( $m[1] ); }
	return $out;
}

/**
 * Inject the SNB brand footer at the bottom of every page.
 */
add_action( 'wp_body_open', 'snb_body_class_marker' );
function snb_body_class_marker() {
	echo '<script>document.body.classList.add("snb-has-brand-footer");</script>';
}

add_action( 'wp_footer', 'snb_render_brand_footer', 5 );
function snb_render_brand_footer() {
	if ( is_admin() || apply_filters( 'snb_skip_brand_footer', false ) ) { return; }
	$template = get_stylesheet_directory() . '/template-parts/brand-footer.php';
	if ( file_exists( $template ) ) { include $template; }
}

/* ============================================================
   SHORTCODE: [snb_products] — dynamic Woo product grid
   ============================================================ */
add_shortcode( 'snb_products', 'snb_shortcode_products' );
function snb_shortcode_products( $atts ) {
	if ( ! class_exists( 'WC_Product' ) ) {
		return '<p style="color:#B8B0A0;font-size:0.9rem;">WooCommerce is not active.</p>';
	}
	$atts = shortcode_atts( array(
		'limit'           => 3,
		'category'        => '',
		'exclude_category'=> 'merch,apparel,accessories',
		'columns'         => 3,
		'heading'         => '',
		'anchor'          => '',
		'offset'          => 0,
	), $atts, 'snb_products' );

	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => intval( $atts['limit'] ),
		'offset'         => intval( $atts['offset'] ),
		'post_status'    => 'publish',
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	);

	$build_query = function( $args, $category, $exclude_category ) {
		$tax_query = array();
		if ( $category ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => array_map( 'trim', explode( ',', $category ) ),
			);
		} elseif ( $exclude_category ) {
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => array_map( 'trim', explode( ',', $exclude_category ) ),
				'operator' => 'NOT IN',
			);
		}
		if ( ! empty( $tax_query ) ) { $args['tax_query'] = $tax_query; }
		return $args;
	};

	$q = new WP_Query( $build_query( $args, $atts['category'], $atts['exclude_category'] ) );
	// Fallback: if the requested category returned nothing, fall back to "all non-merch"
	if ( $atts['category'] && ! $q->have_posts() ) {
		wp_reset_postdata();
		$q = new WP_Query( $build_query( $args, '', $atts['exclude_category'] ) );
	}
	if ( ! $q->have_posts() ) {
		wp_reset_postdata();
		return '';
	}

	$cols = max( 2, min( 4, intval( $atts['columns'] ) ) );

	ob_start();
	?>
	<div<?php echo $atts['anchor'] ? ' id="' . esc_attr( $atts['anchor'] ) . '"' : ''; ?>>
		<?php if ( $atts['heading'] ) : ?>
			<h2 class="snb-cat-title"><?php echo wp_kses_post( $atts['heading'] ); ?></h2>
		<?php endif; ?>
		<div class="snb-grid snb-grid--<?php echo intval( $cols ); ?>">
			<?php while ( $q->have_posts() ) : $q->the_post();
				global $product;
				if ( ! $product ) { $product = wc_get_product( get_the_ID() ); }
				if ( ! $product ) { continue; }
				$img = get_the_post_thumbnail_url( get_the_ID(), 'medium' );
				$desc = $product->get_short_description() ?: wp_trim_words( strip_tags( $product->get_description() ), 12, '...' );
				$add_url = '?add-to-cart=' . $product->get_id();
				?>
				<article class="snb-prod">
					<div class="snb-prod__media">
						<?php if ( $img ) : ?>
							<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
						<?php endif; ?>
					</div>
					<div class="snb-prod__body">
						<h3 class="snb-prod__title"><?php the_title(); ?></h3>
						<?php if ( $desc ) : ?>
							<p class="snb-prod__copy"><?php echo esc_html( $desc ); ?></p>
						<?php endif; ?>
						<div class="snb-prod__foot">
							<span class="snb-prod__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
							<a href="<?php echo esc_url( $add_url ); ?>" class="snb-prod__add" aria-label="Add to cart">+</a>
						</div>
					</div>
				</article>
			<?php endwhile; ?>
		</div>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}

/* ============================================================
   SHORTCODE: [snb_minicart] — sticky mini cart drawer
   ============================================================ */
add_shortcode( 'snb_minicart', 'snb_shortcode_minicart' );
function snb_shortcode_minicart() {
	if ( ! function_exists( 'WC' ) ) {
		return '<aside class="snb-minicart"><div class="snb-minicart__head">Your Order</div><p style="color:#B8B0A0;">Cart will appear once WooCommerce is active.</p></aside>';
	}
	$cart = WC()->cart;
	$items = $cart ? $cart->get_cart() : array();
	$count = $cart ? $cart->get_cart_contents_count() : 0;

	ob_start();
	?>
	<aside class="snb-minicart" id="snb-minicart">
		<div class="snb-minicart__head">
			<span>Your Order <?php if ( $count ) : ?><span class="snb-minicart__count"><?php echo intval( $count ); ?></span><?php endif; ?></span>
			<button type="button" class="snb-btn--icon-only" aria-label="Close" style="background:transparent;border:none;color:var(--snb-cream);font-size:1.2rem;cursor:pointer;">×</button>
		</div>

		<?php if ( empty( $items ) ) : ?>
			<p style="color:var(--snb-cream-dim);font-size:0.9rem;">Your cart is empty. Pick a flavor →</p>
		<?php else : ?>
			<?php foreach ( $items as $key => $item ) :
				$product = $item['data'];
				if ( ! $product ) { continue; }
				$thumb_id = $product->get_image_id();
				$thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'thumbnail' ) : '';
				$line_total = $cart->get_product_subtotal( $product, $item['quantity'] );
				?>
				<div class="snb-minicart__line">
					<div class="snb-minicart__thumb">
						<?php if ( $thumb_url ) : ?>
							<img src="<?php echo esc_url( $thumb_url ); ?>" alt="" style="width:100%;height:100%;object-fit:cover;">
						<?php endif; ?>
					</div>
					<div>
						<div class="snb-minicart__name"><?php echo esc_html( $product->get_name() ); ?></div>
						<div class="snb-minicart__meta"><?php echo intval( $item['quantity'] ); ?> &middot; <?php echo wp_kses_post( $line_total ); ?></div>
					</div>
					<a href="<?php echo esc_url( wc_get_cart_remove_url( $key ) ); ?>" aria-label="Remove" style="color:var(--snb-cream-dim);text-decoration:none;">×</a>
				</div>
			<?php endforeach; ?>

			<div class="snb-minicart__totals">
				<div class="snb-minicart__row"><span>Subtotal</span><span><?php echo wp_kses_post( wc_price( $cart->get_subtotal() ) ); ?></span></div>
				<?php if ( $cart->get_taxes_total() > 0 ) : ?>
					<div class="snb-minicart__row"><span>Tax</span><span><?php echo wp_kses_post( wc_price( $cart->get_taxes_total() ) ); ?></span></div>
				<?php endif; ?>
				<div class="snb-minicart__row snb-minicart__row--total"><span>Total</span><span class="snb-minicart__amount"><?php echo wp_kses_post( wc_price( $cart->get_total( 'edit' ) ) ); ?></span></div>
			</div>

			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="snb-btn snb-btn--block" style="margin-top:0.75rem;">View Cart &amp; Checkout</a>
		<?php endif; ?>
	</aside>
	<?php
	return ob_get_clean();
}

// Refresh mini-cart fragment on cart change.
add_filter( 'woocommerce_add_to_cart_fragments', 'snb_minicart_fragment' );
function snb_minicart_fragment( $fragments ) {
	ob_start();
	echo snb_shortcode_minicart();
	$fragments['#snb-minicart'] = ob_get_clean();
	return $fragments;
}

/**
 * Append exactly ONE trailing arrow to every shop loop button
 * ("Add to cart", "Select options", etc.).
 * The CSS ::after pseudo-elements are intentionally disabled so this
 * is the sole source of the arrow — no double-arrows.
 */
add_filter( 'woocommerce_product_add_to_cart_text', 'snb_add_to_cart_arrow' );
function snb_add_to_cart_arrow( $text ) {
	// Strip any pre-existing arrow (safety net), then add exactly one.
	return preg_replace( '/\s*→\s*$/', '', $text ) . ' →';
}

/* ============================================================
   THANK-YOU: hero + status tracker + loyalty stamps
   ============================================================ */
add_action( 'woocommerce_before_thankyou', 'snb_thankyou_hero', 5 );
function snb_thankyou_hero( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( ! $order ) { return; }
	$is_delivery = false; // toggle if you wire pickup/delivery custom field later
	?>
	<section class="snb-hero" style="padding-block:3rem;">
		<div class="snb-hero__grid">
			<div>
				<h1 class="snb-hero__title" style="font-size:clamp(3rem,8vw,7rem);"><span class="snb-accent">THANKS &mdash;</span><br>WE'RE ON IT.</h1>
				<p class="snb-hero__copy">Your order is confirmed and heading to our kitchen.<br>Bold flavor is on the way.</p>
			</div>
			<div class="snb-hero__media" aria-hidden="true"><span class="snb-hero__media-placeholder">WINGS</span></div>
		</div>
	</section>

	<section class="snb-section snb-section--black" style="padding-block:2rem;">
		<div class="snb-container">

			<div class="snb-order-strip">
				<div class="snb-order-strip__cell">
					<span class="snb-order-strip__label">Order Number</span>
					<span class="snb-order-strip__value">SNB-<?php echo esc_html( $order->get_order_number() ); ?></span>
				</div>
				<div class="snb-order-strip__cell">
					<span class="snb-order-strip__label">Order Date</span>
					<span class="snb-order-strip__value snb-order-strip__value--cream"><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'M j, Y' ) ); ?> &middot; <?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'g:i A' ) ); ?></span>
				</div>
				<div class="snb-order-strip__cell">
					<span class="snb-order-strip__label">Order Type</span>
					<span class="snb-order-strip__value snb-order-strip__value--cream">
						<?php echo $is_delivery ? '🛵 Delivery' : '🛍️ Pickup'; ?>
					</span>
				</div>
				<div class="snb-order-strip__cell">
					<span class="snb-order-strip__label">Estimated ETA</span>
					<span class="snb-order-strip__value"><?php echo $is_delivery ? '35–45 MIN' : '25–35 MIN'; ?></span>
				</div>
				<div class="snb-order-strip__cell">
					<span class="snb-order-strip__label">Status</span>
					<span class="snb-status-pill">In Kitchen</span>
				</div>
			</div>

			<div class="snb-tracker">
				<div class="snb-tracker__step is-done">
					<div class="snb-tracker__circle">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
					</div>
					<div class="snb-tracker__label">Order Received</div>
					<p class="snb-tracker__sub"><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'g:i A' ) ); ?></p>
				</div>
				<div class="snb-tracker__step is-current">
					<div class="snb-tracker__circle">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M5 8h14l-2 12H7L5 8z"/></svg>
					</div>
					<div class="snb-tracker__label">In Kitchen</div>
					<p class="snb-tracker__sub">We've got it cooking.</p>
				</div>
				<div class="snb-tracker__step">
					<div class="snb-tracker__circle">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13l4-8h10l4 8M3 13v5h18v-5M3 13h18"/></svg>
					</div>
					<div class="snb-tracker__label">Out for Delivery</div>
					<p class="snb-tracker__sub">On the way.</p>
				</div>
				<div class="snb-tracker__step">
					<div class="snb-tracker__circle">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 13l4 4L19 7"/></svg>
					</div>
					<div class="snb-tracker__label">Delivered</div>
					<p class="snb-tracker__sub">Enjoy the flavor.</p>
				</div>
			</div>

		</div>
	</section>
	<?php
}

/**
 * Action buttons + loyalty card after order details.
 */
add_action( 'woocommerce_thankyou', 'snb_thankyou_actions', 20 );
function snb_thankyou_actions( $order_id ) {
	?>
	<section class="snb-section snb-section--black" style="padding-top:1rem;padding-bottom:0;">
		<div class="snb-container">
			<div class="snb-grid snb-grid--3">
				<a href="#" class="snb-btn"><span class="snb-flame"></span>Track Order</a>
				<a href="/menu/" class="snb-btn snb-btn--cream">View Menu Again</a>
				<a href="#" class="snb-btn snb-btn--cream">Download Receipt</a>
			</div>
		</div>
	</section>

	<section class="snb-loyalty" style="margin-top:2rem;">
		<div class="snb-loyalty__inner" style="grid-template-columns:auto 1fr auto;">
			<div class="snb-loyalty__badge">LOYALTY<br>CARD</div>
			<div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;">
				<div class="snb-loyalty__stamps">
					<span class="snb-stamp is-filled">✓</span>
					<span class="snb-stamp"></span>
					<span class="snb-stamp"></span>
					<span class="snb-stamp"></span>
					<span class="snb-stamp"></span>
					<span class="snb-stamp"></span>
					<span class="snb-stamp"></span>
					<span class="snb-stamp"></span>
					<span class="snb-stamp is-free">FREE</span>
				</div>
				<div class="snb-loyalty__copy">
					<h2>BOLD FLAVOR.<br><span class="snb-accent">BIG REWARDS.</span></h2>
					<p style="margin-top:0.4rem;">Earn a stamp for this order — you're one step closer to a free item.</p>
					<a href="/loyalty/" class="snb-btn" style="margin-top:0.75rem;">Join Loyalty</a>
				</div>
			</div>
			<div class="snb-receipt-box" aria-hidden="true">
				<div class="snb-receipt-box__mark">SAUCE<span class="snb-accent">N'</span>BONE</div>
				<div class="snb-receipt-box__tag">FLAVOR FIRST.</div>
			</div>
		</div>
	</section>
	<?php
}

/* ============================================================
   CART: intro hero + recommended add-ons (most styling is in
   woocommerce/cart/cart.php template override)
   ============================================================ */
add_action( 'woocommerce_before_cart', 'snb_cart_intro', 5 );
function snb_cart_intro() {
	echo '<section class="snb-section snb-section--black" style="padding-block:2rem 1rem;"><div class="snb-container"><h1>YOUR <span class="snb-accent">CART.</span></h1><p style="color:var(--snb-cream-soft);margin-top:0.5rem;">Bold flavors. Real ingredients. No shortcuts.</p><a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" style="color:var(--snb-orange);display:inline-flex;align-items:center;gap:0.4rem;margin-top:0.75rem;font-family:var(--snb-font-display);letter-spacing:0.1em;text-transform:uppercase;font-size:0.95rem;">← Continue Shopping</a></div></section>';
}

/**
 * Empty cart state — SNB hero + recommended add-ons, since the standard
 * Woo empty cart message + Return-to-Shop button isn't enough.
 */
add_action( 'woocommerce_cart_is_empty', 'snb_cart_empty_state', 20 );
function snb_cart_empty_state() {
	?>
	<section class="snb-section snb-section--black" style="padding-block:1rem 3rem;">
		<div class="snb-container" style="text-align:center;">
			<div style="font-size:4rem;margin-bottom:1rem;">🛒</div>
			<h2>Your cart is empty.</h2>
			<p style="color:var(--snb-cream-dim);max-width:480px;margin:0.5rem auto 1.5rem;">Pick a flavor and let's get saucy. Bold wings, real ingredients, no shortcuts.</p>
			<a href="<?php echo esc_url( home_url( '/menu/' ) ); ?>" class="snb-btn"><span class="snb-flame"></span>Order Now</a>
		</div>
	</section>

	<section class="snb-section snb-section--charcoal">
		<div class="snb-container">
			<h2 class="snb-cat-title" style="margin-top:0;">Fan Favorites.</h2>
			<p style="color:var(--snb-cream-dim);margin-top:-0.5rem;">Start with these and you can't go wrong.</p>
			<?php echo do_shortcode( '[snb_products limit="6" columns="3" exclude_category="merch,apparel,accessories"]' ); ?>
		</div>
	</section>

	<div class="snb-promise-bar" style="margin-top:0;">
		<span class="snb-promise-bar__item">🔥 Bold Flavors.</span>
		<span class="snb-promise-bar__item">🌿 Real Ingredients.</span>
		<span class="snb-promise-bar__item">✕ No Shortcuts.</span>
		<span class="snb-promise-bar__tag">That's the Sauce N' Bone promise.</span>
	</div>
	<?php
}

// Cart pickup/delivery + secure-checkout cards are now rendered inline
// inside woocommerce/cart/cart.php instead of via hooks.

add_action( 'woocommerce_after_cart', 'snb_cart_recommended', 20 );
function snb_cart_recommended() {
	?>
	<section class="snb-section snb-section--charcoal" style="margin-top:2rem;border-radius:16px;">
		<div class="snb-container">
			<h2 class="snb-cat-title" style="margin-top:0;">Make It Even Better.</h2>
			<p style="color:var(--snb-cream-dim);margin-top:-0.5rem;">Add these fan favorites to complete your order.</p>
			<?php echo do_shortcode( '[snb_products limit="6" columns="3" exclude_category="merch,apparel,accessories"]' ); ?>
		</div>
	</section>

	<div class="snb-promise-bar" style="margin-top:2rem;border-radius:16px;">
		<span class="snb-promise-bar__item">🔥 Bold Flavors.</span>
		<span class="snb-promise-bar__item">🌿 Real Ingredients.</span>
		<span class="snb-promise-bar__item">✕ No Shortcuts.</span>
		<span class="snb-promise-bar__tag">That's the Sauce N' Bone promise.</span>
	</div>
	<?php
}

/* ============================================================
   CHECKOUT: trust badges, pickup toggle, tip selector
   ============================================================ */
add_action( 'woocommerce_before_checkout_form', 'snb_checkout_intro', 5 );
function snb_checkout_intro() {
	?>
	<section class="snb-section snb-section--black" style="padding-block:2rem;padding-top:0;">
		<div class="snb-container">
			<div style="display:flex;align-items:flex-end;justify-content:space-between;gap:2rem;flex-wrap:wrap;">
				<div>
					<h1>Checkout</h1>
					<p style="color:var(--snb-cream-soft);margin-top:0.5rem;">You're almost done. Bold flavor is on the way.</p>
				</div>
				<div class="snb-trust-row" style="margin:0;flex:1;min-width:280px;max-width:600px;">
					<div class="snb-trust"><span class="snb-trust__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V8a4 4 0 1 1 8 0v3"/></svg></span><div><strong>Secure Checkout</strong><span>Your info is always protected.</span></div></div>
					<div class="snb-trust"><span class="snb-trust__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span><div><strong>Fast &amp; Fresh</strong><span>Made to order. Never sitting.</span></div></div>
					<div class="snb-trust"><span class="snb-trust__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg></span><div><strong>Flavor First</strong><span>Real ingredients. No shortcuts.</span></div></div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

// SECTION 2 — Delivery or Pickup (rendered first, before contact details)
add_action( 'woocommerce_checkout_before_customer_details', 'snb_checkout_pickup_delivery' );
function snb_checkout_pickup_delivery() {
	?>
	<div class="snb-numhead"><span class="snb-numhead__num">2</span><h3>Delivery or Pickup</h3></div>
	<div class="snb-toggle-group" data-snb="pickup-delivery">
		<label class="snb-toggle is-active">
			<span class="snb-toggle__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 7h14l-1 13H6z"/><path d="M9 7V4h6v3"/></svg></span>
			<div><strong>Pickup</strong><span>Pick up at our location. Fast &amp; easy.</span></div>
			<input type="radio" name="snb_fulfillment" value="pickup" checked hidden>
		</label>
		<label class="snb-toggle">
			<span class="snb-toggle__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13l4-8h10l4 8M3 13v5h18v-5"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg></span>
			<div><strong>Delivery</strong><span>We bring the heat to you. Fees may apply.</span></div>
			<input type="radio" name="snb_fulfillment" value="delivery" hidden>
		</label>
	</div>

	<div class="snb-card" style="display:grid;grid-template-columns:80px 1fr auto;gap:1.25rem;align-items:center;margin-bottom:1.5rem;">
		<div style="width:80px;height:80px;background:var(--snb-charcoal-2);border-radius:var(--snb-radius-sm);display:grid;place-items:center;color:var(--snb-cream-dim);">SNB</div>
		<div>
			<strong style="display:block;font-family:var(--snb-font-display);letter-spacing:0.04em;text-transform:uppercase;color:var(--snb-cream);font-size:1.1rem;">Sauce N' Bone &mdash; Downtown</strong>
			<span style="color:var(--snb-cream-dim);font-size:0.9rem;">123 Flavor Way, Nashville, TN 37203</span><br>
			<a href="#" style="color:var(--snb-orange);font-size:0.85rem;">Change Location</a>
		</div>
		<div style="text-align:right;">
			<span class="snb-eyebrow" style="margin:0;">Est. Pickup Time</span>
			<div style="font-family:var(--snb-font-display);color:var(--snb-orange);font-size:1.5rem;letter-spacing:0.04em;">25&ndash;35 MIN</div>
		</div>
	</div>

	<div class="snb-numhead"><span class="snb-numhead__num">1</span><h3>Contact Information</h3></div>
	<?php
}

// Move contact heading to a more usable section; rename Woo billing label.
add_filter( 'woocommerce_checkout_fields', 'snb_checkout_field_labels' );
function snb_checkout_field_labels( $fields ) {
	if ( isset( $fields['billing']['billing_first_name'] ) ) {
		$fields['billing']['billing_first_name']['placeholder'] = 'Full Name';
		$fields['billing']['billing_first_name']['label'] = 'Full Name';
	}
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['placeholder'] = 'Email Address';
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['placeholder'] = 'Phone Number';
	}
	return $fields;
}

// SECTION 3 — Pickup Information (Pickup Name field)
add_action( 'woocommerce_after_checkout_billing_form', 'snb_checkout_pickup_info', 5 );
function snb_checkout_pickup_info() {
	?>
	<div class="snb-numhead"><span class="snb-numhead__num">3</span><h3>Pickup Information</h3></div>
	<input type="text" class="snb-input" name="snb_pickup_name" placeholder="Pickup Name (Optional)" style="margin-bottom:1rem;">
	<?php
}

// SECTION 4 — Special Instructions
add_action( 'woocommerce_after_checkout_billing_form', 'snb_checkout_after_billing', 10 );
function snb_checkout_after_billing() {
	?>
	<div class="snb-numhead"><span class="snb-numhead__num">4</span><h3>Special Instructions (Optional)</h3></div>
	<textarea class="snb-textarea" name="snb_special_instructions" rows="3" maxlength="250" placeholder="Add any special instructions, allergies, or requests..."></textarea>
	<div style="font-size:0.75rem;color:var(--snb-cream-dim);margin-top:0.25rem;">0 / 250</div>
	<?php
}

// SECTION 5 — Payment Method header (visually leads into Woo's payment_methods)
add_action( 'woocommerce_review_order_before_payment', 'snb_checkout_payment_head' );
function snb_checkout_payment_head() {
	?>
	<div class="snb-numhead"><span class="snb-numhead__num">5</span><h3>Payment Method</h3></div>
	<?php
}

// Estimated pickup time card in the right summary, just before Place Order
add_action( 'woocommerce_review_order_before_submit', 'snb_checkout_eta_card', 5 );
function snb_checkout_eta_card() {
	?>
	<div class="snb-eta-card">
		<span class="snb-flame"></span>
		<div>
			<span class="snb-eyebrow" style="margin:0;">Estimated Pickup Time</span>
			<div style="font-family:var(--snb-font-display);color:var(--snb-orange);font-size:1.6rem;letter-spacing:0.04em;line-height:1;margin:0.25rem 0;">25&ndash;35 MIN</div>
			<span style="color:var(--snb-cream-soft);font-size:0.85rem;">Today, 6:45 PM</span>
		</div>
	</div>
	<?php
}

// SECTION 6 — Tip (already exists, ensure number is 6)
add_action( 'woocommerce_review_order_before_submit', 'snb_checkout_tip_selector' );
function snb_checkout_tip_selector() {
	?>
	<div class="snb-numhead"><span class="snb-numhead__num">6</span><h3>Add a Tip (Optional)</h3></div>
	<p style="color:var(--snb-cream-dim);margin:0 0 0.75rem;font-size:0.85rem;">100% of tips go to our team.</p>
	<div class="snb-tip-group" data-snb="tip">
		<button type="button" class="snb-tip" data-tip="0">No Tip</button>
		<button type="button" class="snb-tip" data-tip="10">10%<span class="snb-tip__sub">est</span></button>
		<button type="button" class="snb-tip is-active" data-tip="15">15%<span class="snb-tip__sub">est</span></button>
		<button type="button" class="snb-tip" data-tip="20">20%<span class="snb-tip__sub">est</span></button>
		<button type="button" class="snb-tip" data-tip="other">Other</button>
	</div>
	<?php
}

add_action( 'woocommerce_after_checkout_form', 'snb_checkout_reassurance' );
function snb_checkout_reassurance() {
	?>
	<div class="snb-trust-row" style="max-width:1320px;margin:2rem auto;padding:0 2rem;">
		<div class="snb-trust"><span class="snb-trust__icon">🔥</span><div><strong>Made Fresh to Order</strong><span>Never frozen. Always bold.</span></div></div>
		<div class="snb-trust"><span class="snb-trust__icon">🛍️</span><div><strong>Easy Pickup</strong><span>Skip the line. Grab and go.</span></div></div>
		<div class="snb-trust"><span class="snb-trust__icon">❤</span><div><strong>Love It or Let Us Know</strong><span>We'll make it right.</span></div></div>
	</div>
	<?php
}

/* ============================================================
   THANK-YOU: payment status line beneath the order table
   ============================================================ */
add_action( 'woocommerce_order_details_after_order_table', 'snb_thankyou_payment_line', 5 );
function snb_thankyou_payment_line( $order ) {
	if ( ! $order || ! is_a( $order, 'WC_Order' ) ) { return; }
	$method = $order->get_payment_method_title();
	$status = $order->get_status();
	$is_paid = in_array( $status, array( 'processing', 'completed' ), true );
	?>
	<div class="snb-pay-line">
		<span class="snb-pay-line__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M9 12l2 2 4-4"/></svg></span>
		<span class="snb-pay-line__label">PAYMENT</span>
		<span class="snb-pay-line__method"><?php echo esc_html( $method ?: 'Card' ); ?></span>
		<span class="snb-pay-line__status<?php echo $is_paid ? ' is-paid' : ''; ?>"><?php echo esc_html( $is_paid ? 'PAID' : strtoupper( wc_get_order_status_name( $status ) ) ); ?></span>
	</div>
	<?php
}

/* ============================================================
   THANK-YOU: Delivery To + Need Help cards
   ============================================================ */
add_action( 'woocommerce_thankyou', 'snb_thankyou_info_cards', 15 );
function snb_thankyou_info_cards( $order_id ) {
	$order = wc_get_order( $order_id );
	if ( ! $order ) { return; }
	$first = $order->get_billing_first_name();
	$last  = $order->get_billing_last_name();
	$name  = trim( $first . ' ' . $last );
	$addr  = $order->get_formatted_billing_address();
	$phone = $order->get_billing_phone();
	$email = $order->get_billing_email();
	$is_delivery = false; // wire to your real fulfillment field later
	?>
	<section class="snb-section snb-section--black" style="padding-block:1rem 2rem;">
		<div class="snb-container">
			<div class="snb-grid snb-grid--2" style="gap:1.25rem;">

				<div class="snb-card">
					<div style="display:flex;align-items:center;justify-content:space-between;gap:1rem;margin-bottom:0.75rem;">
						<h3 class="snb-card__title" style="display:flex;align-items:center;gap:0.5rem;">
							<?php echo $is_delivery ? '🛵 Deliver To' : '🛍️ Pickup Info'; ?>
						</h3>
						<a href="#" class="snb-btn snb-btn--ghost snb-btn--sm"><span style="margin-right:0.4rem;">📍</span>Track on Map</a>
					</div>
					<?php if ( $name ) : ?><div style="color:var(--snb-cream);font-family:var(--snb-font-display);letter-spacing:0.04em;font-size:1.1rem;text-transform:uppercase;"><?php echo esc_html( $name ); ?></div><?php endif; ?>
					<?php if ( $addr ) : ?><div style="color:var(--snb-cream-dim);font-size:0.9rem;margin-top:0.25rem;"><?php echo wp_kses_post( $addr ); ?></div><?php endif; ?>
					<?php if ( $phone ) : ?><div style="color:var(--snb-cream-dim);font-size:0.9rem;"><?php echo esc_html( $phone ); ?></div><?php endif; ?>

					<?php if ( $is_delivery ) : ?>
						<hr style="border:none;border-top:1px solid var(--snb-line);margin:1rem 0;">
						<span class="snb-eyebrow" style="margin-bottom:0.25rem;">Delivery Instructions</span>
						<p style="color:var(--snb-cream-soft);margin:0 0 0.75rem;font-size:0.9rem;">Leave at front door. Ring doorbell, thanks!</p>
					<?php endif; ?>

					<span class="snb-eyebrow" style="margin-bottom:0.25rem;">Order Updates</span>
					<p style="color:var(--snb-cream-soft);margin:0;font-size:0.9rem;">We'll send updates to <?php echo esc_html( $email ); ?><?php if ( $phone ) echo ' and ' . esc_html( $phone ); ?>.</p>
				</div>

				<div class="snb-card">
					<h3 class="snb-card__title" style="margin-bottom:0.5rem;">Need Help?</h3>
					<p style="color:var(--snb-cream-dim);margin:0 0 1rem;font-size:0.9rem;">We're here for you.</p>
					<div class="snb-grid snb-grid--2" style="gap:0.75rem;">
						<a href="tel:+16155557626" class="snb-toggle">
							<span class="snb-toggle__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.8a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.9.34 1.84.57 2.8.7A2 2 0 0 1 22 16.92z"/></svg></span>
							<div><strong>Call Us</strong><span>(615) 555-SNBN</span></div>
						</a>
						<a href="sms:+16155557626" class="snb-toggle">
							<span class="snb-toggle__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg></span>
							<div><strong>Text Us</strong><span>(615) 555-7626</span></div>
						</a>
					</div>
				</div>

			</div>
		</div>
	</section>
	<?php
}

/* ============================================================
   MY-ACCOUNT page — branded hero + dark theme overrides
   ============================================================ */
add_action( 'woocommerce_before_account_navigation', 'snb_account_hero' );
function snb_account_hero() {
	$user = wp_get_current_user();
	$name = $user && $user->display_name ? $user->display_name : 'Wing Lover';
	?>
	<section class="snb-section snb-section--black" style="padding-block:2rem;padding-top:0;">
		<div class="snb-container">
			<span class="snb-eyebrow">Account</span>
			<h1>Hey, <span class="snb-accent"><?php echo esc_html( $name ); ?>.</span></h1>
			<p style="color:var(--snb-cream-soft);margin-top:0.5rem;">Manage your orders, addresses, and loyalty in one place.</p>
		</div>
	</section>
	<?php
}

add_action( 'woocommerce_before_customer_login_form', 'snb_account_login_hero' );
function snb_account_login_hero() {
	?>
	<section class="snb-section snb-section--black" style="padding-block:2rem;padding-top:0;">
		<div class="snb-container">
			<span class="snb-eyebrow">Members Only</span>
			<h1>Sign In to Get <span class="snb-accent">Saucy.</span></h1>
			<p style="color:var(--snb-cream-soft);margin-top:0.5rem;">Track orders, save favorites, and stack loyalty stamps.</p>
		</div>
	</section>
	<?php
}
