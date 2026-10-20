# KLABEN Autos

**A cinematic automotive experience, built as a custom WordPress theme.**

<img width="1879" height="915" alt="homepage" src="https://github.com/user-attachments/assets/e4ed19ea-e61e-4035-b4fb-3df6f8df622c" />


A fictional Dutch car dealership concept that combines visual storytelling with working browsing, vehicle-detail, selling and financing interactions. Built as a portfolio project to explore the space between interface design and front-end/back-end development.

**Stack:** WordPress · PHP · Vanilla JavaScript · CSS · HTML

> **Portfolio prototype, not a real dealership.** Vehicles, prices, valuations and finance examples are illustrative. There are no real listings, sales, loan applications or licence-plate lookups.

## The experience

| Section | What you can explore |
| --- | --- |
| **Homepage** | Layered, animated Mercedes hero; responsive vehicle search; featured cars; illustrated process; illustrative monthly-payment calculator. |
| **Car collection** | Search and filter by vehicle attributes, sort results, explore image galleries and open individual vehicle details. |
| **Vehicle detail** | Gallery thumbnails, specs, related vehicles and a direct handoff to financing with the chosen vehicle preselected. |
| **Sell your car** | Dutch licence-plate *format* validation, guided vehicle-information flow, illustrative valuation and a review/demo submission experience. |
| **Financing** | Interactive payment scenarios driven by purchase price, down payment and term, using a shared JavaScript calculation module. |
| **Journal** | Editorial article index powered by standard WordPress posts, with categories, pagination and single-article templates. |

The interface includes responsive navigation, small motion details and reduced-motion handling in relevant interactions.

## Technology and implementation

- **Custom WordPress theme:** page templates in PHP, shared header/footer and reusable template parts.
- **PHP data adapter:** catalogue filtering and sorting are handled on the server. The example cars currently live in `data/cars.php`.
- **Vanilla JavaScript:** mobile navigation, card interactions, guided selling steps, galleries and finance controls.
- **CSS:** custom responsive styling, typography and motion; no mandatory JavaScript build pipeline.
- **WordPress posts:** the journal uses WordPress queries, categories, post thumbnails and article pages.
- **Demo-safe submission:** the selling form validates input on the server but does **not** send email, store submissions or process real customer requests.

### Project structure

```text
klaben-autos/
├── front-page.php                 # Home / layered automotive hero
├── page-autos.php                 # Inventory + vehicle-detail routing
├── page-auto-verkopen.php         # Guided selling demo
├── page-financiering.php          # Interactive calculator
├── page-nieuws.php                # Journal archive
├── single.php                     # WordPress article template
├── functions.php                  # Theme setup, assets and helpers
├── includes/                      # Reusable components and asset loaders
├── template-parts/                # Homepage and selling sections
├── data/cars.php                  # Three illustrative vehicles
└── assets/
    ├── css/
    ├── js/
    └── images/
```

## Run locally

You'll need a local WordPress installation (for example, [Local](https://localwp.com/)) and a PHP environment supported by that WordPress version.

1. Clone this repository into your WordPress themes directory:

   ```bash
   cd wp-content/themes
   git clone https://github.com/Susieko/klaben-autos.git
   ```

2. In **Appearance → Themes**, activate **KLABEN Autos**.
3. Create WordPress pages with these **exact slugs**: `autos`, `auto-verkopen`, `financiering` and `nieuws`.
4. In **Settings → Reading**, set a static homepage if needed for your site setup. WordPress will use `front-page.php` for the front page.
5. Add standard WordPress posts (ideally with featured images) to populate the journal. Keep `nieuws` as a regular page; **do not** set it as the WordPress “Posts page.”
6. Open the homepage and test the car catalogue, selling journey, finance calculator and mobile menu.

The vehicle catalogue ships with three demo records. You do **not** need ACF, an external vehicle API or a production database import to try the included sample interactions. WordPress itself still needs its normal database.

## What is simulated?

This project intentionally separates real UI logic from fictional business operations:

- **Vehicle data:** bundled example cars in `data/cars.php`, not dealer stock or a custom post type.
- **Licence plates:** syntax/format checking only, **no RDW lookup**.
- **Valuation:** a guided, illustrative interface, **not a real appraisal**.
- **Selling form:** server-side validation with a demo response; **no storage, emails or leads**.
- **Financing:** illustrative amortisation calculations using a **7.9% nominal annual example rate**, **not a quote, lender integration or offer**.
- **Favourites:** UI interaction only; they are not a persistent account feature.

These boundaries are deliberate so a public demonstration cannot be mistaken for a live dealership or financial service.

## Future development

A logical next step would be moving the vehicle catalogue from the current PHP data adapter to a WordPress custom post type with ACF fields and reusable archive/detail templates. This is **not implemented in the current release**.

The current focus is an approachable, complete front-end experience with working demo interactions and clear technical scope.

---

**Designed and developed by [Susan Aben](https://github.com/Susieko).**  
Built as an independent portfolio concept. KLABEN Autos is a fictional brand.
