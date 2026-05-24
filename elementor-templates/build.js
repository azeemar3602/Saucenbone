// Sauce N' Bone — Elementor template builder.
// Run:  node elementor-templates/build.js
//
// Mirrors the Gutenberg patterns in ../patterns/*.php into Elementor template JSONs.
// Each section becomes an Elementor <section> with one HTML widget — same visual output.
const fs = require("fs");
const path = require("path");
const crypto = require("crypto");

let _counter = 0;
function id() {
	_counter++;
	return crypto.createHash("md5").update(String(_counter) + String(Date.now())).digest("hex").slice(0, 7);
}

function section(html) {
	return {
		id: id(),
		elType: "section",
		settings: {
			stretch_section: "section-stretched",
			content_width: { unit: "px", size: "" },
			gap: "no",
			background_background: "classic",
			background_color: "#0B0B0B",
		},
		elements: [{
			id: id(),
			elType: "column",
			settings: { _column_size: 100, _inline_size: null },
			elements: [{
				id: id(),
				elType: "widget",
				widgetType: "html",
				settings: { html: html.trim() },
			}],
			isInner: false,
		}],
		isInner: false,
	};
}

/**
 * Two-column section: a 65/35 split with a content widget on the left
 * and a sticky mini-cart widget on the right.
 */
function twoColSection(leftHtml, rightHtml) {
	return {
		id: id(),
		elType: "section",
		settings: {
			structure: "20",
			stretch_section: "section-stretched",
			content_width: { unit: "px", size: 1320 },
			gap: "default",
			background_background: "classic",
			background_color: "#0B0B0B",
		},
		elements: [
			{
				id: id(),
				elType: "column",
				settings: { _column_size: 66, _inline_size: 66 },
				elements: [{
					id: id(),
					elType: "widget",
					widgetType: "html",
					settings: { html: leftHtml.trim() },
				}],
				isInner: false,
			},
			{
				id: id(),
				elType: "column",
				settings: { _column_size: 34, _inline_size: 34, sticky: "top", sticky_offset: 100 },
				elements: [{
					id: id(),
					elType: "widget",
					widgetType: "html",
					settings: { html: rightHtml.trim() },
				}],
				isInner: false,
			},
		],
		isInner: false,
	};
}

function template(title, sections) {
	return { version: "0.4", title, type: "page", content: sections, page_settings: [] };
}

// ---------------- HOMEPAGE SECTIONS ----------------

const heroHome = `
<section class="snb-hero">
	<div class="snb-hero__grid">
		<div>
			<h1 class="snb-hero__title">FLAVOR FIRST.<br><span class="snb-accent">NO</span> SHORTCUTS.</h1>
			<p class="snb-hero__copy">Bold wings, real ingredients, and sauces built to hit hard. Pick your style, choose your heat, and get ready for flavor that does not play safe.</p>
			<div class="snb-hero__cta">
				<a href="/menu/" class="snb-btn">View Menu</a>
				<a href="/menu/" class="snb-btn snb-btn--ghost"><span class="snb-flame"></span>Order Now</a>
			</div>
		</div>
		<div class="snb-hero__media" aria-hidden="true"><span class="snb-hero__media-placeholder">WINGS</span></div>
	</div>
</section>`;

const pickStyle = `
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-head">
			<div class="snb-head__label">
				<h2>PICK<br>YOUR STYLE</h2>
				<span class="snb-head__label-line"></span>
				<p>Three ways to get saucy. Same promise: Flavor First. No Shortcuts.</p>
			</div>
			<div class="snb-head__content">
				<div class="snb-grid snb-grid--3">
					<article class="snb-style-card"><div class="snb-style-card__badge"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 4a4 4 0 1 1 4 4l-9 9-4 1 1-4z"/></svg></div><div class="snb-style-card__body"><h3>BONE-IN WINGS</h3><p>Classic, crispy, flavor-loaded.</p><a href="/menu/?style=bone-in" class="snb-btn snb-btn--sm">Choose Wings</a></div></article>
					<article class="snb-style-card"><div class="snb-style-card__badge"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M8 12h8M12 8v8"/></svg></div><div class="snb-style-card__body"><h3>BONELESS WINGS</h3><p>Big flavor, easy bite.</p><a href="/menu/?style=boneless" class="snb-btn snb-btn--sm">Choose Boneless</a></div></article>
					<article class="snb-style-card"><div class="snb-style-card__badge"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7c4 0 6 10 9 10s5-10 9-10"/></svg></div><div class="snb-style-card__body"><h3>TENDERS</h3><p>Juicy, bold, and built for dipping.</p><a href="/menu/?style=tenders" class="snb-btn snb-btn--sm">Choose Tenders</a></div></article>
				</div>
			</div>
		</div>
	</div>
</section>`;

const signature = `
<section class="snb-section snb-section--charcoal">
	<div class="snb-container">
		<div class="snb-head">
			<div class="snb-head__label">
				<h2>SIGNATURE<br>FLAVORS</h2>
				<span class="snb-head__label-line"></span>
				<p>Our originals. Made from real ingredients. Built for flavor.</p>
			</div>
			<div class="snb-head__content">
				<div class="snb-grid snb-grid--3">
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Strawberry Hot</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Mango Habanero</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Pineapple Jalape&ntilde;o</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Sweet &amp; Tangy</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Garlic Parmesan</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Classic Hot</h3></article>
				</div>
			</div>
		</div>
	</div>
</section>`;

const viral = `
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-head">
			<div class="snb-head__label">
				<h2>VIRAL<br>FLAVORS</h2>
				<span class="snb-head__label-line"></span>
				<p>Bold. Unexpected. Fan favorites.</p>
			</div>
			<div class="snb-head__content">
				<div class="snb-grid snb-grid--4">
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#6F39BE;">Blueberry Blaze</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#6F39BE;">Grape Inferno</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Orange Chili Rush</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="color:#E92A0A;">Real Heat</h3></article>
				</div>
			</div>
		</div>
	</div>
</section>`;

const sds = `
<section class="snb-section snb-section--charcoal">
	<div class="snb-container">
		<div class="snb-grid snb-grid--3">
			<article class="snb-card"><div style="display:grid;grid-template-columns:1fr 100px;gap:1rem;align-items:center;"><div><h3 class="snb-card__title" style="color:#C9A24A;">SIDES</h3><ul style="list-style:none;padding:0;margin:0.5rem 0 0;color:#B8B0A0;line-height:1.8;font-size:0.9rem;"><li>Corn Ribs</li><li>Fries</li><li>Loaded Fries</li><li>Mac &amp; Cheese</li></ul></div><div class="snb-card__media" style="margin:0;border-radius:50%;aspect-ratio:1;"></div></div></article>
			<article class="snb-card"><div style="display:grid;grid-template-columns:1fr 100px;gap:1rem;align-items:center;"><div><h3 class="snb-card__title" style="color:#C9A24A;">DRINKS</h3><ul style="list-style:none;padding:0;margin:0.5rem 0 0;color:#B8B0A0;line-height:1.8;font-size:0.9rem;"><li>Strawberry Lemonade</li><li>Mango Lemonade</li><li>Blue Raspberry Lemonade</li></ul></div><div class="snb-card__media" style="margin:0;border-radius:50%;aspect-ratio:1;"></div></div></article>
			<article class="snb-card"><div style="display:grid;grid-template-columns:1fr 100px;gap:1rem;align-items:center;"><div><h3 class="snb-card__title" style="color:#C9A24A;">SWEET</h3><ul style="list-style:none;padding:0;margin:0.5rem 0 0;color:#B8B0A0;line-height:1.8;font-size:0.9rem;"><li>Caramel Popcorn Bites</li></ul></div><div class="snb-card__media" style="margin:0;border-radius:50%;aspect-ratio:1;"></div></div></article>
		</div>
	</div>
</section>`;

const merch = `
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-head">
			<div class="snb-head__label">
				<h2>REP THE<br>CULTURE</h2>
				<span class="snb-head__label-line"></span>
				<p>Hoodies, tees, hats, and everyday merch made for real ones.</p>
				<a href="/shop/" class="snb-btn snb-btn--sm" style="margin-top:1rem;">Shop Merch</a>
			</div>
			<div class="snb-head__content">
				<div class="snb-grid snb-grid--6">
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="font-size:0.95rem;">Hoodie</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="font-size:0.95rem;">Snapback</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="font-size:0.95rem;">T-Shirt</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="font-size:0.95rem;">Tote</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="font-size:0.95rem;">Tumbler</h3></article>
					<article class="snb-card"><div class="snb-card__media"></div><h3 class="snb-card__title" style="font-size:0.95rem;">Badge</h3></article>
				</div>
			</div>
		</div>
	</div>
</section>`;

const loyalty = `
<section class="snb-loyalty">
	<div class="snb-loyalty__inner">
		<div class="snb-loyalty__badge">LOYALTY<br>CARD</div>
		<div style="display:flex;align-items:center;gap:1.5rem;flex-wrap:wrap;justify-content:center;">
			<div class="snb-loyalty__copy">
				<h2>BUY 8. GET <span class="snb-accent">1</span> FREE.</h2>
				<p>Flavor pays back.</p>
			</div>
			<div class="snb-loyalty__stamps">
				<span class="snb-stamp"></span><span class="snb-stamp"></span><span class="snb-stamp"></span><span class="snb-stamp"></span><span class="snb-stamp"></span><span class="snb-stamp"></span><span class="snb-stamp"></span><span class="snb-stamp"></span><span class="snb-stamp is-free">FREE</span>
			</div>
		</div>
		<a href="/loyalty/" class="snb-btn">Join Loyalty</a>
	</div>
</section>`;

// ---------------- MENU PAGE SECTIONS ----------------

const menuHero = `
<section class="snb-hero" style="padding-block:3rem;">
	<div class="snb-hero__grid">
		<div><h1 class="snb-hero__title" style="font-size:clamp(3rem,8vw,7rem);">PICK YOUR<br><span class="snb-accent">FLAVOR.</span></h1></div>
		<div style="display:grid;grid-template-columns:1fr 1.1fr;gap:1.5rem;align-items:center;">
			<div>
				<p style="color:#F4EEDF;font-family:var(--snb-font-display);letter-spacing:0.04em;font-size:1.2rem;line-height:1.2;margin:0 0 0.75rem;">Real ingredients. Bold flavors.<br>Built for flavor that hits hard.</p>
				<p class="snb-hero__copy" style="margin:0;font-size:0.95rem;">Choose your style, your flavor, and get it your way.</p>
			</div>
			<div class="snb-hero__media" aria-hidden="true"><span class="snb-hero__media-placeholder">WINGS</span></div>
		</div>
	</div>
</section>`;

/**
 * Combined left-column content for the menu page: tabs + product rows +
 * flavor selectors + mix strip. Used inside twoColSection alongside the
 * sticky mini-cart.
 */
const menuLeftContent = `
<nav class="snb-tabs" aria-label="Menu categories">
	<a href="#bone-in" class="snb-tab is-active"><span class="snb-tab__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 4a4 4 0 1 1 4 4l-9 9-4 1 1-4z"/></svg></span>Bone-In Wings</a>
	<a href="#boneless" class="snb-tab"><span class="snb-tab__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/></svg></span>Boneless Wings</a>
	<a href="#tenders" class="snb-tab"><span class="snb-tab__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 7c4 0 6 10 9 10s5-10 9-10"/></svg></span>Tenders</a>
	<a href="#sides" class="snb-tab"><span class="snb-tab__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="6" y="3" width="12" height="18" rx="2"/></svg></span>Sides</a>
	<a href="#drinks" class="snb-tab"><span class="snb-tab__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M7 4h10l-1 16H8z"/></svg></span>Drinks</a>
	<a href="#sweet" class="snb-tab"><span class="snb-tab__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="6"/></svg></span>Sweet</a>
	<a href="/shop/" class="snb-tab"><span class="snb-tab__icon"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 8l3-4h12l3 4-9 13z"/></svg></span>Merch</a>
</nav>

[snb_products limit="3" heading="Wings &amp; Tenders" anchor="bone-in" columns="3" category="wings,bone-in,boneless,tenders"]
[snb_products limit="4" heading="Sides &amp; More" anchor="sides" columns="4" category="sides"]
[snb_products limit="3" heading="Drinks &amp; Sweet" anchor="drinks" columns="3" category="drinks,sweet"]

<div style="margin-top:2.5rem;">
	<div class="snb-grid snb-grid--2">
		<div class="snb-card">
			<h3 class="snb-card__title" style="margin-bottom:0.5rem;">Signature Flavors</h3>
			<span class="snb-head__label-line" style="margin-bottom:1rem;"></span>
			<div class="snb-flavor-list">
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍓</span><span>Strawberry Hot</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🧄</span><span>Garlic Parmesan</span><span class="snb-flavor-row__heat">🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🥭</span><span>Mango Habanero</span><span class="snb-flavor-row__heat">🔥🔥🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🌶️</span><span>Classic Hot</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍍</span><span>Pineapple Jalape&ntilde;o</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍯</span><span>Sweet &amp; Tangy</span><span class="snb-flavor-row__heat">🔥</span></div>
			</div>
			<div class="snb-text-center" style="margin-top:1rem;"><a href="#" class="snb-btn snb-btn--ghost snb-btn--sm">View All Flavors</a></div>
		</div>
		<div class="snb-card">
			<h3 class="snb-card__title" style="margin-bottom:0.5rem;">Viral Flavors</h3>
			<span class="snb-head__label-line" style="margin-bottom:1rem;"></span>
			<div class="snb-flavor-list" style="grid-template-columns:1fr;">
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🫐</span><span>Blueberry Blaze</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍇</span><span>Grape Inferno</span><span class="snb-flavor-row__heat">🔥🔥🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">🍊</span><span>Orange Chili Rush</span><span class="snb-flavor-row__heat">🔥🔥</span></div>
				<div class="snb-flavor-row"><span class="snb-flavor-row__icon">💀</span><span>Real Heat</span><span class="snb-flavor-row__heat">🔥🔥🔥🔥</span></div>
			</div>
			<div class="snb-text-center" style="margin-top:1rem;"><a href="#" class="snb-btn snb-btn--ghost snb-btn--sm">View All Flavors</a></div>
		</div>
	</div>
	<div class="snb-mix-strip" style="margin-top:1.5rem;">
		<div class="snb-mix-strip__icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4l16 16M20 4L4 20"/></svg></div>
		<div class="snb-mix-strip__copy"><strong>Not sure? Try a mix of flavors!</strong><span>Half one flavor, half another. Because variety is flavor.</span></div>
		<a href="?mix=1" class="snb-btn snb-btn--sm">Mix Flavors</a>
	</div>
</div>`;

const menuRightContent = `
[snb_minicart]

<div class="snb-detail" style="margin-top:1rem;">
	<div class="snb-detail__media"></div>
	<h2 class="snb-detail__title" style="font-size:1.6rem;">Bone-In Wings</h2>
	<p class="snb-detail__copy" style="font-size:0.9rem;">Classic, crispy, and packed with bold flavor.</p>

	<div class="snb-detail__field">
		<label class="snb-detail__label">Choose Size</label>
		<div class="snb-pill-row">
			<span class="snb-pill">6 pc<span class="snb-pill__sub">$10.99</span></span>
			<span class="snb-pill is-active">10 pc<span class="snb-pill__sub">$17.99</span></span>
			<span class="snb-pill">15 pc<span class="snb-pill__sub">$24.99</span></span>
			<span class="snb-pill">20 pc<span class="snb-pill__sub">$31.99</span></span>
		</div>
	</div>

	<div class="snb-detail__field">
		<label class="snb-detail__label" for="snb-mflavor">Choose Your Flavor</label>
		<select id="snb-mflavor" class="snb-select">
			<option>🍓 Strawberry Hot 🔥🔥</option>
			<option>🥭 Mango Habanero 🔥🔥🔥</option>
			<option>🍍 Pineapple Jalape&ntilde;o 🔥🔥</option>
			<option>🍯 Sweet &amp; Tangy 🔥</option>
			<option>🧄 Garlic Parmesan 🔥</option>
			<option>🌶️ Classic Hot 🔥🔥</option>
		</select>
		<a href="#" style="display:inline-flex;align-items:center;gap:0.4rem;color:var(--snb-sauce-red);margin-top:0.5rem;font-size:0.8rem;letter-spacing:0.1em;text-transform:uppercase;font-family:var(--snb-font-display);">
			<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4l16 16M20 4L4 20"/></svg>
			Mix Flavors (Half &amp; Half)
		</a>
	</div>

	<div class="snb-detail__field">
		<label class="snb-detail__label">Add-Ons</label>
		<div class="snb-addon-list">
			<label><input type="checkbox"> Ranch <span class="snb-addon-price">$0.75</span></label>
			<label><input type="checkbox"> Garlic Parmesan <span class="snb-addon-price">$1.00</span></label>
			<label><input type="checkbox"> Extra Sauce <span class="snb-addon-price">$1.00</span></label>
		</div>
	</div>

	<div class="snb-detail__field" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
		<label class="snb-detail__label" style="margin:0;">Quantity</label>
		<div class="snb-qty">
			<button type="button" data-snb-qty="decrement">−</button>
			<span class="snb-qty__value">1</span>
			<button type="button" data-snb-qty="increment">+</button>
		</div>
	</div>

	<a href="#" class="snb-btn snb-btn--block" style="margin-top:0.75rem;">Add to Cart &nbsp; <span style="opacity:0.85;">$17.99</span></a>
</div>`;

const menuDetail = `
<section class="snb-section snb-section--black">
	<div class="snb-container">
		<div class="snb-detail">
			<div class="snb-detail__media"></div>
			<h2 class="snb-detail__title">Bone-In Wings</h2>
			<p class="snb-detail__copy">Classic, crispy, and packed with bold flavor.</p>
			<div class="snb-detail__field">
				<label class="snb-detail__label">Choose Size</label>
				<div class="snb-pill-row"><span class="snb-pill">6 pc<span class="snb-pill__sub">$10.99</span></span><span class="snb-pill is-active">10 pc<span class="snb-pill__sub">$17.99</span></span><span class="snb-pill">15 pc<span class="snb-pill__sub">$24.99</span></span><span class="snb-pill">20 pc<span class="snb-pill__sub">$31.99</span></span></div>
			</div>
			<div class="snb-detail__field">
				<label class="snb-detail__label" for="snb-flavor-select-2">Choose Your Flavor</label>
				<select id="snb-flavor-select-2" class="snb-select">
					<option>🍓 Strawberry Hot 🔥🔥</option><option>🥭 Mango Habanero 🔥🔥🔥</option><option>🍍 Pineapple Jalape&ntilde;o 🔥🔥</option><option>🍯 Sweet &amp; Tangy 🔥</option><option>🧄 Garlic Parmesan 🔥</option><option>🌶️ Classic Hot 🔥🔥</option><option>🫐 Blueberry Blaze 🔥🔥</option><option>🍇 Grape Inferno 🔥🔥🔥</option><option>🍊 Orange Chili Rush 🔥🔥</option><option>💀 Real Heat 🔥🔥🔥🔥</option>
				</select>
			</div>
			<div class="snb-detail__field">
				<label class="snb-detail__label">Add-Ons</label>
				<div class="snb-addon-list">
					<label><input type="checkbox"> Ranch <span class="snb-addon-price">$0.75</span></label>
					<label><input type="checkbox"> Garlic Parmesan <span class="snb-addon-price">$1.00</span></label>
					<label><input type="checkbox"> Extra Sauce <span class="snb-addon-price">$1.00</span></label>
				</div>
			</div>
			<div class="snb-detail__field" style="display:flex;align-items:center;justify-content:space-between;gap:1rem;">
				<label class="snb-detail__label" style="margin:0;">Quantity</label>
				<div class="snb-qty"><button type="button">−</button><span class="snb-qty__value">1</span><button type="button">+</button></div>
			</div>
			<a href="#" class="snb-btn snb-btn--block" style="margin-top:1rem;">Add to Cart &nbsp; <span style="opacity:0.85;">$17.99</span></a>
		</div>
	</div>
</section>`;

// ---------------- BUILD ----------------

const home = template("Sauce N' Bone — Home", [
	section(heroHome),
	section(pickStyle),
	section(signature),
	section(viral),
	section(sds),
	section(merch),
	section(loyalty),
]);

const menu = template("Sauce N' Bone — Menu", [
	section(menuHero),
	twoColSection(menuLeftContent, menuRightContent),
	section(loyalty),
]);

const outDir = __dirname;
fs.writeFileSync(path.join(outDir, "saucenbone-home.json"), JSON.stringify(home, null, 2));
fs.writeFileSync(path.join(outDir, "saucenbone-menu.json"), JSON.stringify(menu, null, 2));

console.log("Wrote:");
console.log("  -", path.join(outDir, "saucenbone-home.json"));
console.log("  -", path.join(outDir, "saucenbone-menu.json"));
