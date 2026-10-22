# KLABEN Autos — portfolio source sync notes

This package contains **theme source updates** from the most recent local KLABEN theme ZIP, plus the KLABEN CRM v0.2 plugin source. The ACF export was already committed to `docs/acf-export-2026-10-22.json` on the target GitHub feature branch.

## Expected WordPress configuration

- Custom post type `klaben_auto`, taxonomy `klaben_merk`, and ACF Free `Voertuiggegevens` fields. Import the ACF export using **ACF → Tools → Import**, if needed.
- Fluent Forms is an external WordPress dependency. Local form IDs **3** (vehicle information) and **4** (sell a car) are referenced in the theme/plugin; IDs may differ after importing to another installation.
- Install `plugins/klaben-crm` into `wp-content/plugins/klaben-crm` for WordPress to load the CRM. It is intentionally stored here as *portfolio source*, outside the WordPress theme.
- Existing demo WordPress data, uploads, plugin settings, and actual form entries are **not included** in the GitHub sync. No production secrets should be included.
- CRM HubSpot support is optional, disabled without explicitly supplied WordPress constants, and further restricted to a local environment.

## QA before merging

1. Review the proposed GitHub changes and confirm no private information was staged.
2. In **staging/local WordPress**, verify car list, vehicle single page, responsive views, enquiry forms #3/#4, form entries, CRM import, status updates, and admin permissions.
3. Confirm ACF field name alignment and ensure that the relevant site imports the correct field group, CPT, and taxonomy.
4. Only merge into `main` once tested. Uploading source to GitHub does **not** publish the running WordPress website.

## Intentional exclusions

Historical `.git` database, old `.bak` copies, patch files, one-off PowerShell fix/install scripts, locally embedded duplicate screenshot, and old local README (the current public README on GitHub is newer).
