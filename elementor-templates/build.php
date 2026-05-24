<?php
/**
 * Sauce N' Bone — Elementor template builder.
 *
 * Run from the command line:
 *   php elementor-templates/build.php
 *
 * Re-generates saucenbone-home.json and saucenbone-menu.json
 * from the HTML defined below. Each section becomes an Elementor
 * <section> wrapping a single <column> wrapping one HTML widget.
 */

if ( PHP_SAPI !== 'cli' ) { die( 'CLI only.' ); }

function snb_id( $prefix = '' ) {
	static $counter = 0;
	$counter++;
	return substr( md5( $prefix . $counter . microtime( true ) ), 0, 7 );
}

function snb_section( $html ) {
	return array(
		'id'       => snb_id( 'sec' ),
		'elType'   => 'section',
		'settings' => array(
			'stretch_section'      => 'section-stretched',
			'content_width'        => array( 'unit' => 'px', 'size' => '' ),
			'gap'                  => 'no',
			'background_background'=> 'classic',
			'background_color'     => '#0B0B0B',
		),
		'elements' => array(
			array(
				'id'       => snb_id( 'col' ),
				'elType'   => 'column',
				'settings' => array( '_column_size' => 100, '_inline_size' => null ),
				'elements' => array(
					array(
						'id'         => snb_id( 'wid' ),
						'elType'     => 'widget',
						'widgetType' => 'html',
						'settings'   => array( 'html' => trim( $html ) ),
					),
				),
				'isInner'  => false,
			),
		),
		'isInner'  => false,
	);
}

function snb_template( $title, $sections ) {
	return array(
		'version'       => '0.4',
		'title'         => $title,
		'type'          => 'page',
		'content'       => $sections,
		'page_settings' => array(),
	);
}

/* ---------- HTML SECTIONS ---------- */

$hero = <<<'HTML'
<section class="snb-hero">
	<div class="snb-hero__grid">
		<div class="snb-hero__copy-col">
			<span class="snb-eyebrow">Bold Flavors. No Shortcuts.</span>
			<h1 class="snb-hero__title">Flavor First.<br><span class="snb-accent">No Shortcuts.</span></h1>
			<p class="snb-hero__copy">Bold wings, real ingredients, and sauces built to hit hard. Pick your style, choose your heat, and get ready for flavor that does not play safe.</p>
			<div class="snb-hero__cta">
				<a href="/menu/" class="snb-btn">Order Now</a>
				<a href="/menu/" class="snb-btn snb-btn--ghost">View Menu</a>
			</div>
		</div>
		<div class="snb-hero__media" aria-hidden="true"></div>
	</div>
</section>
<span class="snb-drip" aria-hidden="true"></span>
HTML;

$pick = <<<'HTML'
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-section__head">
			<span class="snb-eyebrow">Choose Your Bite</span>
			<h2>Pick Your Style</h2>
			<p>Three ways to get saucy. Same promise: Flavor First. No Shortcuts.</p>
		</div>
		<div class="snb-grid snb-grid--3">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Bone-In Wings</h3><p class="snb-card__copy">Classic, crispy, flavor-loaded.</p><a href="/menu/?style=bone-in" class="snb-btn snb-card__cta">Choose Wings</a></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Boneless Wings</h3><p class="snb-card__copy">Big flavor, easy bite.</p><a href="/menu/?style=boneless" class="snb-btn snb-card__cta">Choose Boneless</a></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Tenders</h3><p class="snb-card__copy">Juicy, bold, and built for dipping.</p><a href="/menu/?style=tenders" class="snb-btn snb-card__cta">Choose Tenders</a></article>
		</div>
	</div>
</section>
HTML;

$signature = <<<'HTML'
<section class="snb-section snb-section--charcoal">
	<div class="snb-container">
		<div class="snb-section__head">
			<span class="snb-eyebrow">The Originals</span>
			<h2>Signature Flavors</h2>
			<p>Our originals. Made from real ingredients. Built for flavor.</p>
		</div>
		<div class="snb-grid snb-grid--3">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Strawberry Hot</h3><p class="snb-card__copy">Sweet heat with a bold berry kick.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Mango Habanero</h3><p class="snb-card__copy">Tropical up front, fire on the finish.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Pineapple Jalape&ntilde;o</h3><p class="snb-card__copy">Bright, juicy, and spicy.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Sweet &amp; Tangy</h3><p class="snb-card__copy">Smooth, sticky, crowd favorite.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Garlic Parmesan</h3><p class="snb-card__copy">Savory, buttery, and loaded.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Classic Hot</h3><p class="snb-card__copy">Real wing-shop heat.</p></article>
		</div>
		<div class="snb-text-center snb-mt-2"><a href="/menu/" class="snb-btn snb-btn--ghost">View All Flavors</a></div>
	</div>
</section>
HTML;

$viral = <<<'HTML'
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-section__head">
			<span class="snb-eyebrow">Fan Favorites</span>
			<h2>Viral Flavors</h2>
			<p>Bold. Unexpected. Built to break the internet.</p>
		</div>
		<div class="snb-grid snb-grid--4">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Blueberry Blaze</h3><p class="snb-card__copy">Berry-rich heat with a smoky finish.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Grape Inferno</h3><p class="snb-card__copy">Bold flavor. No mercy.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Orange Chili Rush</h3><p class="snb-card__copy">Citrus punch with chili fire.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Real Heat</h3><p class="snb-card__copy">For serious spice lovers.</p></article>
		</div>
	</div>
</section>
HTML;

$sds = <<<'HTML'
<section class="snb-section snb-section--charcoal">
	<div class="snb-container">
		<div class="snb-section__head">
			<span class="snb-eyebrow">Round Out The Order</span>
			<h2>Sides. Drinks. Sweet.</h2>
		</div>
		<div class="snb-grid snb-grid--3">
			<article class="snb-card"><h3 class="snb-card__title">Sides</h3><ul style="list-style:none;padding:0;margin:0;color:var(--snb-cream-dim);line-height:1.9;"><li>Corn Ribs</li><li>Fries</li><li>Loaded Fries</li><li>Mac &amp; Cheese</li></ul></article>
			<article class="snb-card"><h3 class="snb-card__title">Drinks</h3><ul style="list-style:none;padding:0;margin:0;color:var(--snb-cream-dim);line-height:1.9;"><li>Strawberry Lemonade</li><li>Mango Lemonade</li><li>Blue Raspberry Lemonade</li></ul></article>
			<article class="snb-card"><h3 class="snb-card__title">Sweet</h3><ul style="list-style:none;padding:0;margin:0;color:var(--snb-cream-dim);line-height:1.9;"><li>Caramel Popcorn Bites</li></ul></article>
		</div>
	</div>
</section>
HTML;

$merch = <<<'HTML'
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-section__head">
			<span class="snb-eyebrow">Brand Culture</span>
			<h2>Rep the Culture</h2>
			<p>Rep the culture with hoodies, tees, hats, and everyday merch made for real ones who put Flavor First.</p>
			<div class="snb-text-center" style="margin-top:1.5rem;"><a href="/shop/" class="snb-btn">Shop Merch</a></div>
		</div>
		<div class="snb-grid snb-grid--4">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Hoodie</h3><p class="snb-card__copy">Premium black hoodie, sauce-red drip.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">T-Shirt</h3><p class="snb-card__copy">Clean everyday black tee.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Snapback</h3><p class="snb-card__copy">Sauce-red SNB monogram.</p></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Tote Bag</h3><p class="snb-card__copy">Flavor First. No Shortcuts.</p></article>
		</div>
	</div>
</section>
HTML;

$loyalty = <<<'HTML'
<section class="snb-loyalty">
	<div class="snb-container">
		<span class="snb-eyebrow" style="color:var(--snb-cream);border-color:rgba(244,238,223,0.5);">Flavor Pays Back</span>
		<h2>Buy 8. Get 1 Free.</h2>
		<p>Flavor pays back.</p>
		<a href="/loyalty/" class="snb-btn snb-btn--gold">Join Loyalty</a>
	</div>
</section>
HTML;

$menu_hero = <<<'HTML'
<section class="snb-hero" style="padding-block:5rem;">
	<div class="snb-hero__grid" style="grid-template-columns:1fr;text-align:center;">
		<div>
			<span class="snb-eyebrow">The Menu</span>
			<h1 class="snb-hero__title" style="margin-inline:auto;">Pick Your <span class="snb-accent">Flavor.</span></h1>
			<p class="snb-hero__copy" style="margin-inline:auto;">Real ingredients. Bold flavors. Built for flavor that hits hard. Choose your style, your flavor, and get it your way.</p>
		</div>
	</div>
</section>
<span class="snb-drip" aria-hidden="true"></span>
HTML;

$menu_tabs = <<<'HTML'
<div class="snb-container">
	<nav class="snb-tabs" aria-label="Menu categories">
		<a href="#bone-in" class="snb-tab is-active">Bone-In Wings</a>
		<a href="#boneless" class="snb-tab">Boneless Wings</a>
		<a href="#tenders" class="snb-tab">Tenders</a>
		<a href="#sides" class="snb-tab">Sides</a>
		<a href="#drinks" class="snb-tab">Drinks</a>
		<a href="#sweet" class="snb-tab">Sweet</a>
		<a href="/shop/" class="snb-tab">Merch</a>
	</nav>
</div>
HTML;

$menu_grid = <<<'HTML'
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<h2 id="bone-in" style="margin-bottom:1.5rem;">Wings &amp; Tenders</h2>
		<div class="snb-grid snb-grid--3">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Bone-In Wings</h3><p class="snb-card__copy">Classic, crispy, flavor-loaded.</p><span class="snb-card__price">$15.99+</span><a href="?product=bone-in" class="snb-btn snb-card__cta">Add to Cart</a></article>
			<article class="snb-card" id="boneless"><div class="snb-card__media"></div><h3 class="snb-card__title">Boneless Wings</h3><p class="snb-card__copy">Big flavor, easy bite.</p><span class="snb-card__price">$15.99+</span><a href="?product=boneless" class="snb-btn snb-card__cta">Add to Cart</a></article>
			<article class="snb-card" id="tenders"><div class="snb-card__media"></div><h3 class="snb-card__title">Tenders</h3><p class="snb-card__copy">Juicy, bold, and built for dipping.</p><span class="snb-card__price">$15.99+</span><a href="?product=tenders" class="snb-btn snb-card__cta">Add to Cart</a></article>
		</div>
		<h2 id="sides" style="margin:3rem 0 1.5rem;">Sides</h2>
		<div class="snb-grid snb-grid--4">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Corn Ribs</h3><p class="snb-card__copy">Crispy. Seasoned. Obsessed.</p><span class="snb-card__price">$6.49</span><a href="?product=corn-ribs" class="snb-btn snb-card__cta">Add</a></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Fries</h3><p class="snb-card__copy">Crispy and perfectly seasoned.</p><span class="snb-card__price">$4.49</span><a href="?product=fries" class="snb-btn snb-card__cta">Add</a></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Loaded Fries</h3><p class="snb-card__copy">Fries, cheese sauce, bacon, and ranch.</p><span class="snb-card__price">$7.49</span><a href="?product=loaded-fries" class="snb-btn snb-card__cta">Add</a></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Mac &amp; Cheese</h3><p class="snb-card__copy">Creamy, cheesy, and always a win.</p><span class="snb-card__price">$6.49</span><a href="?product=mac-cheese" class="snb-btn snb-card__cta">Add</a></article>
		</div>
		<h2 id="drinks" style="margin:3rem 0 1.5rem;">Drinks</h2>
		<div class="snb-grid snb-grid--3">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Strawberry Lemonade</h3><span class="snb-card__price">$3.49</span><a href="?product=strawberry-lemonade" class="snb-btn snb-card__cta">Add</a></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Mango Lemonade</h3><span class="snb-card__price">$3.49</span><a href="?product=mango-lemonade" class="snb-btn snb-card__cta">Add</a></article>
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Blue Raspberry Lemonade</h3><span class="snb-card__price">$3.49</span><a href="?product=blue-raspberry-lemonade" class="snb-btn snb-card__cta">Add</a></article>
		</div>
		<h2 id="sweet" style="margin:3rem 0 1.5rem;">Sweet</h2>
		<div class="snb-grid snb-grid--3">
			<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title">Caramel Popcorn Bites</h3><p class="snb-card__copy">Sweet, crunchy, and totally addictive.</p><span class="snb-card__price">$4.49</span><a href="?product=popcorn-bites" class="snb-btn snb-card__cta">Add</a></article>
		</div>
	</div>
</section>
HTML;

$menu_flavors = <<<'HTML'
<section class="snb-section snb-section--charcoal">
	<div class="snb-container">
		<div class="snb-section__head"><span class="snb-eyebrow">Signature Flavors</span><h2>The Originals</h2></div>
		<div class="snb-chip-row">
			<button type="button" class="snb-chip">Strawberry Hot</button>
			<button type="button" class="snb-chip">Mango Habanero</button>
			<button type="button" class="snb-chip">Pineapple Jalape&ntilde;o</button>
			<button type="button" class="snb-chip">Sweet &amp; Tangy</button>
			<button type="button" class="snb-chip">Garlic Parmesan</button>
			<button type="button" class="snb-chip">Classic Hot</button>
		</div>
		<div class="snb-text-center" style="margin-top:1.5rem;"><a href="#" class="snb-btn snb-btn--ghost">View All Flavors</a></div>
		<div class="snb-section__head" style="margin-top:3rem;"><span class="snb-eyebrow">Viral Flavors</span><h2>Fan Favorites</h2></div>
		<div class="snb-chip-row">
			<button type="button" class="snb-chip snb-chip--grape">Blueberry Blaze</button>
			<button type="button" class="snb-chip snb-chip--grape">Grape Inferno</button>
			<button type="button" class="snb-chip">Orange Chili Rush</button>
			<button type="button" class="snb-chip">Real Heat</button>
		</div>
		<div class="snb-text-center" style="margin-top:1.5rem;"><a href="#" class="snb-btn snb-btn--ghost">View All Flavors</a></div>
		<div class="snb-mix-strip" style="margin-top:3rem;">
			<div><h3>Not sure? Try a mix of flavors!</h3><p>Half one flavor, half another. Because variety is flavor.</p></div>
			<a href="?mix=1" class="snb-btn">Mix Flavors</a>
		</div>
	</div>
</section>
HTML;

$menu_detail = <<<'HTML'
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-detail">
			<div class="snb-detail__media"></div>
			<div>
				<span class="snb-eyebrow">Featured</span>
				<h2 style="margin:0.5rem 0 0.75rem;">Bone-In Wings</h2>
				<p>Classic, crispy, and packed with bold flavor.</p>
				<div class="snb-detail__field">
					<label>Choose size</label>
					<div class="snb-chip-row" style="justify-content:flex-start;">
						<button type="button" class="snb-chip">6 pc</button>
						<button type="button" class="snb-chip" aria-pressed="true">10 pc</button>
						<button type="button" class="snb-chip">15 pc</button>
						<button type="button" class="snb-chip">20 pc</button>
					</div>
				</div>
				<div class="snb-detail__field">
					<label for="snb-flavor">Choose flavor</label>
					<select id="snb-flavor" class="snb-select">
						<option>Strawberry Hot</option><option>Mango Habanero</option><option>Pineapple Jalape&ntilde;o</option><option>Sweet &amp; Tangy</option><option>Garlic Parmesan</option><option>Classic Hot</option><option>Blueberry Blaze</option><option>Grape Inferno</option><option>Orange Chili Rush</option><option>Real Heat</option>
					</select>
				</div>
				<div class="snb-detail__field">
					<label>Mix it up</label>
					<div class="snb-chip-row" style="justify-content:flex-start;"><button type="button" class="snb-chip">Single Flavor</button><button type="button" class="snb-chip">Half &amp; Half</button><button type="button" class="snb-chip">Mix Flavors</button></div>
				</div>
				<div class="snb-detail__field">
					<label>Add-ons</label>
					<div class="snb-chip-row" style="justify-content:flex-start;"><button type="button" class="snb-chip">+ Ranch</button><button type="button" class="snb-chip">+ Garlic Parmesan</button><button type="button" class="snb-chip">+ Extra Sauce</button></div>
				</div>
				<a href="#" class="snb-btn snb-btn--block" style="margin-top:1rem;">Add to Cart &mdash; $17.99</a>
			</div>
		</div>
	</div>
</section>
HTML;

/* ---------- BUILD TEMPLATES ---------- */

$home = snb_template( "Sauce N' Bone — Home", array(
	snb_section( $hero ),
	snb_section( $pick ),
	snb_section( $signature ),
	snb_section( $viral ),
	snb_section( $sds ),
	snb_section( $merch ),
	snb_section( $loyalty ),
) );

$menu = snb_template( "Sauce N' Bone — Menu", array(
	snb_section( $menu_hero ),
	snb_section( $menu_tabs ),
	snb_section( $menu_grid ),
	snb_section( $menu_flavors ),
	snb_section( $menu_detail ),
	snb_section( $loyalty ),
) );

$out_dir = __DIR__;
file_put_contents( $out_dir . '/saucenbone-home.json', json_encode( $home, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
file_put_contents( $out_dir . '/saucenbone-menu.json', json_encode( $menu, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );

echo "Wrote:\n  - {$out_dir}/saucenbone-home.json\n  - {$out_dir}/saucenbone-menu.json\n";
