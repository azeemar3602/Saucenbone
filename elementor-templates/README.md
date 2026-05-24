# Sauce N' Bone — Elementor Templates

These are Elementor-ready versions of the homepage and menu page sections.
Each section is wrapped in an Elementor HTML widget so you can drag-reorder
them, duplicate them, or replace pieces with native Elementor widgets later.

## How to import

1. In WP Admin, go to **Templates → Saved Templates**.
2. Click **Import Templates** (button near the top of the page).
3. Upload `saucenbone-home.json` and `saucenbone-menu.json` (one at a time).
4. To use on a page:
   - Edit a page with Elementor.
   - Click the folder icon (Add Template) in the Elementor canvas.
   - Go to **My Templates** tab.
   - Insert "Sauce N' Bone — Home" or "Sauce N' Bone — Menu".

## Editing

Each HTML widget contains one full section. To edit copy, double-click the
widget and edit the HTML directly. The brand CSS (colors, buttons, cards)
is all in the theme's style.css — you don't need to style anything inline.

## Why HTML widgets and not native widgets

This route delivers the exact same visual output as the Gutenberg patterns
shipped in `/patterns/`, with zero divergence between the two. If you
later want fully-native Elementor widgets (heading/button/image widgets),
ask Claude to rebuild a specific section.
