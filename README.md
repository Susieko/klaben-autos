# KLABEN Autos — WordPress theme (baseline)

**Status:** preserved starter from the October 19, 2026 audit. The design and behaviour are **unchanged**. The planned ACF/CPT implementation has **not yet been added**.

## Where everything lives

- `KLABEN/front-page.php` — landing page markup
- `KLABEN/includes/car-card.php` — reusable vehicle card
- `KLABEN/data/cars.php` — three hard-coded demo vehicles (to be migrated to WordPress)
- `KLABEN/assets/css/` — current design styles, including mobile media queries
- `KLABEN/assets/js/main.js` — menu, category tabs, favourites (session-only), reveal animation and Mercedes hover gallery
- `KLABEN/autos.php`, `auto.php`, `verkopen.php`, `financieren.php` — empty placeholders; WordPress does not automatically use these as custom-post archive/single templates

## Local installation

1. Keep the original Local WordPress project as a **backup**, private and outside Git.
2. Open the existing KLABEN site in the Local app; ensure WordPress can load it.
3. Copy the `KLABEN/` theme folder into `app/public/wp-content/themes/` **only after backing up the current theme**.
4. Activate **KLABEN** in WordPress Appearance > Themes (if not already active).
5. Set Settings > Reading > Your homepage displays to a static page if required by your local setup. This theme uses `front-page.php` for the homepage.
6. Verify the logo, hero, car cards, mobile nav and footer before making architectural changes.

**Repository:** create a GitHub repository for *this directory* (theme-only), never `app/`, the SQL database, the full `wp-content/uploads` folder, or `wp-config.php`.

## Two-day priorities

### Day 1: CMS architecture

- Define a `voertuig` Custom Post Type (ideally in a small site-specific plugin for portability).
- Register ACF groups for vehicle specifications and gallery; decide taxonomy strategy.
- Build WordPress `archive-voertuig.php` and `single-voertuig.php` templates.
- Replace the hard-coded demo data while preserving the existing car card styles.
- Add a `locatie` post type and ACF relationship only if multiple locations add practical value.

### Day 2: interaction and polish

- Make the search/filter form query the actual vehicle posts.
- Add a usable vehicle detail gallery with keyboard controls and responsive layouts.
- Persist favourites locally or explicitly mark them as prototype interaction.
- Make the financing panel interactive with transparent calculation assumptions (not an offer).
- Test responsiveness, accessibility, performance and graceful reduced-motion fallback.
- Set up meaningful incremental commits, deployment and project screenshots.

## Initial audit summary

Already in place: custom PHP theme, reusable card include, desktop/mobile navigation, advanced CSS, category filters, scroll reveals, hero panels and Mercedes hover-gallery logic.

Missing or incomplete: database-backed vehicles, detail/catalogue templates, working advanced search, permanent favourites, financing interaction, Vite/SCSS and Git. The ZIP includes the Local WordPress install plus SQL, so it must not be uploaded wholesale to GitHub.

## Quick checks

```bash
find KLABEN -name '*.php' -print0 | xargs -0 -n1 php -l
node --check KLABEN/assets/js/main.js
```
