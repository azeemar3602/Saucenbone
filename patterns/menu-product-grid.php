<?php
/**
 * Title: Menu — Product Grid (Dynamic)
 * Description: Pulls live WooCommerce products into the SNB menu card style.
 *              Excludes merch automatically via [snb_products] default.
 *              For best results, organize Woo products into categories:
 *                wings, sides, drinks, sweet  (for menu)
 *                merch                        (for /shop/ page)
 */
?>
<!-- wp:shortcode -->
[snb_products limit="3" heading="Wings &amp; Tenders" anchor="bone-in" columns="3" category="wings,bone-in,boneless,tenders"]
<!-- /wp:shortcode -->

<!-- wp:shortcode -->
[snb_products limit="4" heading="Sides &amp; More" anchor="sides" columns="4" category="sides"]
<!-- /wp:shortcode -->

<!-- wp:shortcode -->
[snb_products limit="3" heading="Drinks &amp; Sweet" anchor="drinks" columns="3" category="drinks,sweet"]
<!-- /wp:shortcode -->
