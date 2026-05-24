<?php
/**
 * Sauce N' Bone — Custom cart template
 * Overrides WooCommerce default cart/cart.php to match the SNB mockup:
 *  - Left column: cart line cards + promo code
 *  - Right column: Order Summary stack (totals, Get It Your Way,
 *    Proceed to Checkout, Apple Pay, Checkout as Guest, Secure Checkout)
 *  - Below: Make It Even Better + Promise bar
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

$cart_total = WC()->cart->get_cart_total();
?>

<section class="snb-section snb-section--black" style="padding-block:1rem 3rem;">
	<div class="snb-container snb-cart-grid">

		<div class="snb-cart-left">
			<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
				<?php do_action( 'woocommerce_before_cart_table' ); ?>

				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) :
					$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
					$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

					if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) { continue; }

					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					$thumb             = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'medium' ), $cart_item, $cart_item_key );
					$name              = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$line_subtotal     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
					$remove_url        = wc_get_cart_remove_url( $cart_item_key );
					?>
					<article class="snb-cartline" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">

						<div class="snb-cartline__thumb">
							<?php echo $product_permalink ? sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumb ) : $thumb; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>

						<div class="snb-cartline__body">
							<h3 class="snb-cartline__title"><?php echo wp_kses_post( $name ); ?></h3>

							<?php
							// Item meta (variations, addons)
							$item_data = wc_get_formatted_cart_item_data( $cart_item );
							if ( $item_data ) : ?>
								<div class="snb-cartline__meta"><?php echo wp_kses_post( $item_data ); ?></div>
							<?php endif; ?>

							<div class="snb-cartline__controls">
								<div class="snb-qty">
									<button type="button" class="snb-qty__btn" data-snb-qty="decrement">−</button>
									<input type="number" class="snb-qty__input snb-cartline__qtyinput" name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="1" step="1" aria-label="Quantity">
									<button type="button" class="snb-qty__btn" data-snb-qty="increment">+</button>
								</div>
								<a href="<?php echo esc_url( $product_permalink ); ?>" class="snb-cartline__edit">Edit</a>
								<a href="<?php echo esc_url( $remove_url ); ?>" class="snb-cartline__remove" aria-label="<?php esc_attr_e( 'Remove this item', 'woocommerce' ); ?>">Remove</a>
							</div>
						</div>

						<div class="snb-cartline__price">
							<?php echo wp_kses_post( $line_subtotal ); ?>
						</div>

					</article>
				<?php endforeach; ?>

				<?php do_action( 'woocommerce_cart_contents' ); ?>

				<?php if ( wc_coupons_enabled() ) : ?>
					<div class="snb-promo">
						<div class="snb-promo__icon">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 12V8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v4a2 2 0 0 1 0 4v4a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-4a2 2 0 0 1 0-4z"/><path d="M9 9h6M9 13h6"/></svg>
						</div>
						<div>
							<strong>Have a promo code?</strong>
							<span>Enter it here to apply to your order.</span>
						</div>
						<div class="snb-promo__form">
							<input type="text" name="coupon_code" class="snb-input" id="coupon_code" value="" placeholder="Enter promo code">
							<button type="submit" class="snb-btn snb-btn--sm" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>">Apply</button>
						</div>
					</div>
				<?php endif; ?>

				<input type="hidden" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>">
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

				<?php do_action( 'woocommerce_after_cart_table' ); ?>
			</form>
		</div>

		<aside class="snb-cart-right">
			<div class="snb-summary">

				<h3>Order Summary</h3>

				<div class="snb-summary__row">
					<span>Subtotal</span>
					<span><?php echo wp_kses_post( wc_price( WC()->cart->get_subtotal() ) ); ?></span>
				</div>

				<?php if ( WC()->cart->get_fees() ) : foreach ( WC()->cart->get_fees() as $fee ) : ?>
					<div class="snb-summary__row"><span><?php echo esc_html( $fee->name ); ?></span><span><?php echo wp_kses_post( wc_price( $fee->amount ) ); ?></span></div>
				<?php endforeach; endif; ?>

				<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
					<div class="snb-summary__row">
						<span>Taxes &amp; Fees <span class="snb-info" title="Includes sales tax and any service fees">ⓘ</span></span>
						<span><?php echo wp_kses_post( wc_price( WC()->cart->get_taxes_total() ) ); ?></span>
					</div>
				<?php endif; ?>

				<div class="snb-summary__row snb-summary__row--total">
					<span>Estimated Total</span>
					<span class="snb-summary__amount"><?php echo wp_kses_post( wc_price( WC()->cart->get_total( 'edit' ) ) ); ?></span>
				</div>

				<div class="snb-summary__divider"></div>

				<span class="snb-eyebrow" style="display:block;margin-bottom:0.5rem;">Get It Your Way</span>
				<div class="snb-toggle-group" data-snb="pickup-delivery">
					<label class="snb-toggle is-active">
						<span class="snb-toggle__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 7h14l-1 13H6z"/><path d="M9 7V4h6v3"/></svg></span>
						<div><strong>Pickup</strong><span>Ready in 25–30 mins. Pick up at your nearest Sauce N' Bone.</span></div>
						<input type="radio" name="snb_fulfillment" value="pickup" checked hidden>
					</label>
					<label class="snb-toggle">
						<span class="snb-toggle__icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 13l4-8h10l4 8M3 13v5h18v-5"/><circle cx="7" cy="18" r="2"/><circle cx="17" cy="18" r="2"/></svg></span>
						<div><strong>Delivery</strong><span>Deliver to your door. Available in select areas.</span></div>
						<input type="radio" name="snb_fulfillment" value="delivery" hidden>
					</label>
				</div>

				<details class="snb-accordion" style="margin:1rem 0;">
					<summary>Add delivery instructions</summary>
					<textarea class="snb-textarea" rows="2" placeholder="e.g. Leave at front door. Ring doorbell." style="margin-top:0.5rem;"></textarea>
				</details>

				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="snb-btn snb-btn--block" style="margin-top:0.5rem;">
					<span class="snb-flame"></span>Proceed to Checkout
				</a>

				<button type="button" class="snb-btn snb-btn--block snb-btn--apple" style="margin-top:0.5rem;">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 1a4 4 0 0 0-3 1.5 4 4 0 0 0-1 3 4 4 0 0 0 3-1.4A4 4 0 0 0 16 1zm3 7c-2 0-3 1-4 1s-2-1-4-1c-2 0-4 1.5-5 4-1 3 0 7 1.5 9 .8 1 1.5 2 2.5 2s1.5-.6 3-.6 1.8.6 3 .6c1 0 1.7-1 2.5-2 1.5-2 2-3 1-5-1.4-.6-2.5-2-2.5-4 0-2 1-3 2-4z"/></svg>
					Pay
				</button>

				<div class="snb-or">or</div>

				<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="snb-btn snb-btn--block snb-btn--ghost">Checkout as Guest</a>

				<div class="snb-trust" style="margin-top:1rem;">
					<span class="snb-trust__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V8a4 4 0 1 1 8 0v3"/></svg></span>
					<div><strong>Secure Checkout</strong><span>Your info is safe with us. 100% secure and encrypted.</span></div>
				</div>

			</div>
		</aside>

	</div>
</section>

<?php do_action( 'woocommerce_after_cart' ); ?>
