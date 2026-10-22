# KLABEN CRM — portfolio plugin (version 0.2)

A small, admin-only WordPress enquiry dashboard for the two **existing** Fluent Forms:

- **Form #3:** `Voertuiginformatie aanvragen` — fields `names.first_name`, `email`, `input_text_1`, `vehicle` (hidden post ID), `description`, `checkbox`.
- **Form #4:** `Auto verkopen - aanvraag` — fields `brand`, `model`, `year`, `mileage`, `fuel`, `transmission`, `notes`, `demo_ack`. **This form contains no name, email or phone.**

## What's new in 0.2

- Polished, responsive WordPress administrator dashboard (dark KLABEN header, four enquiry statistics, labelled status pills, better table spacing).
- Client-side search, workflow-status and form-type filters for the latest 100 enquiries.
- Clearer Fluent Forms links: **Bekijk inzending #N** refers to the *entry* number, not the form ID.
- Works with existing stored enquiries and statuses: no migration, new database tables or change to the form hooks.
- Assets only load on the KLABEN CRM admin screen.

### Upgrade from 0.1

Make a backup of the local site first. Go to **Plugins → Add New Plugin → Upload Plugin**, choose `klaben-crm-demo-v0.2.zip` and select **Replace current with uploaded** if WordPress detects the existing plugin. Your existing CRM references and statuses are stored in WordPress and are not deleted by replacing plugin files. Confirm the original two test entries, statuses and auto-submission still work after updating.

## Install

1. In your **local KLABEN WordPress**, go to **Plugins → Add New Plugin → Upload Plugin**.
2. Upload the ZIP containing the `klaben-crm` folder, install and activate.
3. In the WordPress admin left sidebar, click **KLABEN CRM**.
4. Click **Bestaande testaanvragen importeren** to import recent existing entries from yesterday. It imports up to the latest 100 entries from forms 3/4 and does not create duplicates.
5. Submit a *new* fictional test enquiry in form 3 or 4. It should appear automatically.
6. Change its workflow status (**Nieuw → Contact opnemen → In behandeling → Afgerond**) and click **Bewaar**. Reload to confirm it persists.

This plugin does not modify the forms or their success messages. Form entries continue to be stored by Fluent Forms itself. The dashboard stores references and workflow statuses in private WordPress posts; it fetches form contents from Fluent Forms for authenticated WP administrators only. No front-end lead listing is registered.

## Optional HubSpot ticket test (OFF by default)

The plugin includes an **optional** HubSpot Service Key test adapter for future use. It is NOT enabled by installing the ZIP. It can create **ticket records**, not contact records, and intentionally sends only an anonymous label plus form/entry numbers, **not names, emails, phone numbers, message text or vehicle details**.

For local testing only, create a HubSpot Service Key with `crm.objects.tickets.write`, find your ticket pipeline/stage IDs and add constants to **local `wp-config.php`** (NEVER commit to GitHub):

```php
define('WP_ENVIRONMENT_TYPE', 'local');
define('KLABEN_CRM_HUBSPOT_SYNC', true);
define('KLABEN_HUBSPOT_SERVICE_KEY', 'PASTE_YOUR_SECRET_HERE');
define('KLABEN_HUBSPOT_TICKET_PIPELINE', 'YOUR_PIPELINE_ID');
define('KLABEN_HUBSPOT_TICKET_STAGE', 'YOUR_STAGE_ID');
```

Use **fictional test values only**, while logged into WordPress as an admin. The test adapter is blocked outside the local environment and for submissions from users who aren't logged-in admins. It sends only once for each new submission and records the ticket ID or the HTTP code in the dashboard. It does **not** resync imported older entries. If unsure, leave this disabled until the local board is verified.

If HubSpot returns a 400/403 error, check your account's ticket pipeline/stage IDs, required validation rules, account entitlements, and the Service Key scope. The plugin deliberately does not log secrets or HubSpot response bodies.

## Important boundaries

- **Demo only:** KLABEN is fictional. Never treat these as real car leads or quotes.
- **Do not put HubSpot keys in PHP inside the theme or in the public repository.**
- **Do not enable external syncing on a public demo form without new, explicit consent and a privacy notice.** The current exported form checkboxes acknowledge a fictional demo, not third-party transfer.
- **No live testing performed:** PHP is syntax-checked but your local WordPress and HubSpot environments are not accessible from here.
- The dashboard displays the latest 100 CRM records; the importer reads the 100 most recent Fluent Forms entries. This is a small portfolio demo, not a full production CRM.
- Disabling or uninstalling this plugin does not delete Fluent Forms entries. Private CRM reference posts will remain until you remove them manually.

## Source documentation

- Fluent Forms: https://developers.fluentforms.com/hooks/actions/submission/
- Fluent Forms database: https://fluentforms.com/docs/database-tables-of-fluent-forms/
- HubSpot Service Keys: https://developers.hubspot.com/changelog/service-keys
- HubSpot API migration: https://developers.hubspot.com/changelog/legacy-apis-and-legacy-apps-whats-going-unsupported-and-when
