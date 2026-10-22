# KLABEN Autos

**A custom WordPress automotive experience — from cinematic design to vehicle management and a working enquiry CRM.**

[**Explore the public demo**](https://klaben-autos.freepage.cc/) · [**Browse the code**](https://github.com/Susieko/klaben-autos)

<img width="1879" height="915" alt="KLABEN Autos homepage — cinematic automotive design" src="https://github.com/user-attachments/assets/e4ed19ea-e61e-4035-b4fb-3df6f8df622c" />

KLABEN Autos is a fictional Dutch car dealership concept that I designed and developed as a portfolio project. My goal was to build more than an attractive landing page: an automotive website with real interactions, editable vehicle content, meaningful form handling, and an administration workflow.

It started as a custom WordPress theme and grew into an exploration of **front-end design, PHP development, WordPress content modelling, form integrations, and backend workflow design**.

> **Project status:** The public GitHub repository currently contains the original custom-theme implementation. The newer **ACF-powered vehicle catalogue, Fluent Forms submissions, and standalone CRM plugin have been implemented and tested in my local WordPress environment**, but their complete source/configuration has not yet been synchronized to this repository. The public demo may therefore differ from the latest local build.

## What I built

| Area | Features |
| --- | --- |
| **Visual experience** | Cinematic hero, refined typography, responsive navigation, motion and polished vehicle cards. |
| **Vehicle browsing** | Catalogue search and filters, sorting, vehicle detail pages, image galleries, and related vehicle content. |
| **Selling journey** | Guided vehicle details, input validation, an illustrative valuation experience, and a demonstration enquiry form. |
| **Financing** | Interactive, illustrative monthly-payment calculations and vehicle-to-financing handoff. |
| **Editorial content** | WordPress-powered journal with articles, categories and pagination. |
| **Vehicle management** | ACF-backed vehicle content and editable WordPress vehicle records in the expanded local build. |
| **Enquiry handling** | Fluent Forms vehicle-information and sell-your-car submissions in the expanded local build. |
| **Custom CRM** | An admin-only WordPress plugin for viewing and tracking incoming demo enquiries, including saved statuses, search, filters and overview statistics. |

### A complete enquiry workflow

The latest local build connects the customer-facing experience to a WordPress administration workflow:

```text
Visitor explores a car or selling form
                  │
                  ▼
           Fluent Forms
          (forms #3 and #4)
                  │
                  ▼
       WordPress form entries
                  │
                  ▼
        Custom KLABEN CRM
         ├─ enquiry overview
         ├─ linked vehicle/details
         ├─ search and filters
         ├─ status management
         └─ summary statistics
```

The CRM was tested with fictional enquiries: existing entries could be imported, new submissions appeared automatically, and status changes persisted after refreshing. It stores the **reference to the original Fluent Forms submission and the enquiry workflow status** rather than duplicating the submitted contact details into a second CRM record.

**HubSpot is optional and disabled**; the working WordPress CRM does not depend on a third-party service.

## Technology

- **WordPress & PHP** — custom theme, templates, server-side filtering, WordPress hooks and admin functionality.
- **JavaScript** — interactive forms, UI behaviour, galleries and financing calculations.
- **HTML & CSS** — responsive layouts, component styling, accessibility considerations and motion details.
- **ACF** — editable vehicle data in the expanded local version.
- **Fluent Forms** — validated demonstration enquiries and stored form entries in the expanded local version.
- **Custom WordPress plugin** — CRM tracking, admin dashboard, workflow statuses and filters in the expanded local version.
- **Git & GitHub** — iterative development and project documentation.

There is no required Node.js build pipeline for the original theme. This project focuses on custom WordPress/PHP and vanilla front-end development rather than a framework-based interface.

## Technical approach

A few design and engineering decisions shaped the project:

1. **Build the interface first, then connect the content.** The original vehicle catalogue uses a PHP data adapter, which made the templates reusable while the design evolved.
2. **Make the experience interactive.** Catalogue filtering, detail views, guided form steps and financing calculations are implemented behaviours rather than static mock-ups.
3. **Keep content editable.** The expanded local build moves vehicle information into WordPress records and ACF fields so it can be managed in the CMS.
4. **Connect the front end to an admin workflow.** Fluent Forms submissions feed a lightweight CRM plugin where demo enquiries can be reviewed and tracked.
5. **Be explicit about prototype boundaries.** The interface demonstrates software behaviour, not real vehicle sales, lending, appraisals or dealer operations.

## Repository structure

The public repository currently contains the original theme:

```text
klaben-autos/
├── front-page.php             # Homepage
├── page-autos.php             # Catalogue and vehicle details
├── page-auto-verkopen.php     # Guided selling demo
├── page-financiering.php      # Financing calculator
├── page-nieuws.php            # Journal
├── single.php                 # Article view
├── functions.php              # Theme setup and PHP helpers
├── includes/                  # Asset loaders and shared logic
├── template-parts/            # Reusable sections
├── data/cars.php              # Example vehicles in the public version
└── assets/
    ├── css/
    ├── js/
    └── images/
```

The separate CRM plugin and local WordPress configuration are **not yet included in this tree**. ACF fields, Fluent Forms definitions and saved content live in the local WordPress environment and are not automatically included when exporting only a theme repository.

## Run the public theme locally

1. Set up a local WordPress installation, for example with [Local](https://localwp.com/).
2. Clone this repository into `wp-content/themes/` and activate **KLABEN Autos** under **Appearance → Themes**.
3. Create pages with the slugs `autos`, `auto-verkopen`, `financiering` and `nieuws`.
4. Configure a static homepage if needed under **Settings → Reading**.
5. Add some WordPress posts to populate the journal. Do not set `nieuws` as the designated WordPress Posts page.
6. Explore the responsive homepage, catalogue, vehicle detail views, selling journey and financing calculator.

The currently published theme includes example cars in `data/cars.php` and can be explored without installing ACF or Fluent Forms. **Reproducing the newer local ACF + CRM workflow requires the additional plugin and form configuration, which are not yet published here.**

## Demo and privacy boundaries

**KLABEN Autos is a fictional portfolio project, not a real dealership.** Vehicle listings, prices, valuations and financing examples are illustrative.

- No real vehicle valuation, RDW lookup, loan application, sale or financing offer takes place.
- The public theme's original selling flow demonstrates validation without creating real leads.
- The newer local Fluent Forms workflow intentionally creates *test* entries and is not a public customer-contact service.
- The CRM is administrator-only; sensitive form data remains within the original form-entry system.
- Screenshots and demonstrations should use fictional or redacted contact data.

## What this project taught me

KLABEN began as a visual design challenge and became a much broader full-stack WordPress exercise. I particularly enjoyed connecting the front-end details to useful PHP and WordPress logic: turning a good-looking car page into an editable record, a form into a trackable request, and a basic admin screen into a purposeful CRM dashboard.

The next repository milestone is publishing the latest theme changes, the separate CRM plugin source and a reproducible demonstration configuration.

---

**Designed and developed by [Susan Aben](https://github.com/Susieko).**  
Independent portfolio project · Fictional brand and vehicles.
