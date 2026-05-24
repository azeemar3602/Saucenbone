<?php
/**
 * Title: Menu — Product Grid (Dynamic)
 * Description: Pulls live WooCommerce products into the SNB menu card style.
 *              Uses the [snb_products] shortcode.
 */
?>
<!-- wp:shortcode -->
[snb_products limit="3" heading="Wings &amp; Tenders" anchor="bone-in" columns="3"]
<!-- /wp:shortcode -->

<!-- wp:shortcode -->
[snb_products limit="4" heading="Sides &amp; More" anchor="sides" columns="4" offset="3"]
<!-- /wp:shortcode -->

<!-- wp:shortcode -->
[snb_products limit="3" heading="Drinks &amp; Sweet" anchor="drinks" columns="3" offset="7"]
<!-- /wp:shortcode -->
