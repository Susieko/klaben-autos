<?php
/**
 * Plugin Name: KLABEN CRM (Portfolio Demo)
 * Description: Admin-only enquiry tracker for Fluent Forms #3 and #4, with an optional local-only HubSpot ticket test.
 * Version: 0.2.0
 * Requires PHP: 7.4
 * Author: Susan Aben
 * License: GPL-2.0-or-later
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register a private record that stores ONLY the Fluent Forms entry reference
 * and workflow status. Sensitive form values remain in Fluent Forms' own table.
 */
add_action('init', function () {
    register_post_type('klaben_enquiry', [
        'labels' => ['name' => 'KLABEN aanvragen', 'singular_name' => 'KLABEN aanvraag'],
        'public' => false,
        'publicly_queryable' => false,
        'exclude_from_search' => true,
        'show_ui' => false,
        'show_in_rest' => false,
        'supports' => ['title'],
    ]);
});

function klaben_crm_statuses(): array
{
    return [
        'nieuw'           => 'Nieuw',
        'contact_opnemen' => 'Contact opnemen',
        'in_behandeling'  => 'In behandeling',
        'afgerond'        => 'Afgerond',
    ];
}

/** @return int|WP_Error */
function klaben_crm_record_entry(int $form_id, int $entry_id)
{
    if (!in_array($form_id, [3, 4], true) || $entry_id < 1) {
        return new WP_Error('klaben_invalid_entry', 'Unsupported form or entry');
    }

    $existing = get_posts([
        'post_type'   => 'klaben_enquiry',
        'post_status' => 'private',
        'numberposts' => 1,
        'fields'      => 'ids',
        'meta_query'  => [
            ['key' => '_klaben_form_id', 'value' => $form_id, 'compare' => '=', 'type' => 'NUMERIC'],
            ['key' => '_klaben_entry_id', 'value' => $entry_id, 'compare' => '=', 'type' => 'NUMERIC'],
        ],
    ]);

    if ($existing) {
        return (int) $existing[0];
    }

    $label = $form_id === 3 ? 'Voertuiginformatie' : 'Auto verkopen';
    $post_id = wp_insert_post([
        'post_type'   => 'klaben_enquiry',
        'post_status' => 'private',
        'post_title'  => $label . ' · aanvraag #' . $entry_id,
    ], true);

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    update_post_meta($post_id, '_klaben_form_id', $form_id);
    update_post_meta($post_id, '_klaben_entry_id', $entry_id);
    update_post_meta($post_id, '_klaben_status', 'nieuw');

    return (int) $post_id;
}

add_action('fluentform/submission_inserted', function ($entry_id, $form_data, $form): void {
    $form_id = isset($form->id) ? (int) $form->id : 0;
    if (!in_array($form_id, [3, 4], true)) {
        return;
    }
    $post_id = klaben_crm_record_entry($form_id, (int) $entry_id);

    // Optional HubSpot test is disabled by default and is restricted to a
    // local WordPress environment with a signed-in administrator.
    if (!is_wp_error($post_id) && is_array($form_data)) {
        klaben_crm_maybe_send_hubspot($post_id, $form_id, (int) $entry_id, $form_data);
    }
}, 20, 3);

/** Read one entry from the canonical Fluent Forms storage table. */
function klaben_crm_get_entry(int $entry_id, int $form_id): array
{
    global $wpdb;
    $table = $wpdb->prefix . 'fluentform_submissions';
    $raw = $wpdb->get_var($wpdb->prepare(
        "SELECT response FROM {$table} WHERE id = %d AND form_id = %d LIMIT 1",
        $entry_id,
        $form_id
    ));
    $decoded = is_string($raw) ? json_decode($raw, true) : null;
    return is_array($decoded) ? $decoded : [];
}

function klaben_crm_plain($value, int $length = 180): string
{
    if (!is_scalar($value)) {
        return '';
    }
    $clean = sanitize_text_field((string) $value);
    return function_exists('mb_substr') ? mb_substr($clean, 0, $length) : substr($clean, 0, $length);
}

/** @return array{vehicle: string, contact: string, details: string} */
function klaben_crm_summary(int $form_id, array $data): array
{
    if ($form_id === 3) {
        $name_fields = $data['names'] ?? [];
        if (is_string($name_fields)) {
            $decoded = json_decode($name_fields, true);
            $name_fields = is_array($decoded) ? $decoded : ['first_name' => $name_fields];
        }
        $name_fields = is_array($name_fields) ? $name_fields : [];
        $name = trim(klaben_crm_plain($name_fields['first_name'] ?? '') . ' ' . klaben_crm_plain($name_fields['last_name'] ?? ''));
        $email = sanitize_email(klaben_crm_plain($data['email'] ?? '', 250));
        $phone = klaben_crm_plain($data['input_text_1'] ?? '', 40);
        $vehicle_id = absint($data['vehicle'] ?? 0);
        $vehicle_title = $vehicle_id ? get_the_title($vehicle_id) : '';
        return [
            'vehicle' => $vehicle_id ? (klaben_crm_plain($vehicle_title) ?: 'Voertuig') . ' (#' . $vehicle_id . ')' : 'Niet gekoppeld',
            'contact' => implode(' · ', array_filter([$name, $email, $phone])),
            'details' => klaben_crm_plain($data['description'] ?? '', 600),
        ];
    }

    $brand = klaben_crm_plain($data['brand'] ?? '', 70);
    $model = klaben_crm_plain($data['model'] ?? '', 90);
    $year = absint($data['year'] ?? 0);
    $mileage = absint($data['mileage'] ?? 0);
    $vehicle = trim($brand . ' ' . $model);
    if ($year > 0) {
        $vehicle .= ' (' . $year . ')';
    }
    if ($mileage > 0) {
        $vehicle .= ' · ' . number_format_i18n($mileage) . ' km';
    }
    return [
        'vehicle' => $vehicle ?: 'Voertuig onbekend',
        'contact' => 'Niet ingevuld (dit formulier vraagt geen contactgegevens)',
        'details' => implode(' · ', array_filter([
            klaben_crm_plain($data['fuel'] ?? '', 30),
            klaben_crm_plain($data['transmission'] ?? '', 30),
            klaben_crm_plain($data['notes'] ?? '', 600),
        ])),
    ];
}

add_action('admin_menu', function (): void {
    add_menu_page(
        'KLABEN CRM',
        'KLABEN CRM',
        'manage_options',
        'klaben-crm',
        'klaben_crm_render_admin',
        'dashicons-car',
        27
    );
});

/** Get up to 100 existing Fluent Forms demo submissions into the dashboard. */
function klaben_crm_import_recent(): int
{
    global $wpdb;
    $table = $wpdb->prefix . 'fluentform_submissions';
    $items = $wpdb->get_results(
        "SELECT id, form_id FROM {$table} WHERE form_id IN (3, 4) AND status <> 'trashed' ORDER BY id DESC LIMIT 100"
    );
    if (!is_array($items)) {
        return 0;
    }
    $count = 0;
    foreach ($items as $entry) {
        $already = get_posts([
            'post_type'   => 'klaben_enquiry',
            'post_status' => 'private',
            'numberposts' => 1,
            'fields'      => 'ids',
            'meta_query'  => [
                ['key' => '_klaben_form_id', 'value' => (int) $entry->form_id, 'compare' => '=', 'type' => 'NUMERIC'],
                ['key' => '_klaben_entry_id', 'value' => (int) $entry->id, 'compare' => '=', 'type' => 'NUMERIC'],
            ],
        ]);
        if ($already) {
            continue;
        }
        if (!is_wp_error(klaben_crm_record_entry((int) $entry->form_id, (int) $entry->id))) {
            $count++;
        }
    }
    return $count;
}

add_action('admin_post_klaben_crm_import', function (): void {
    if (!current_user_can('manage_options')) {
        wp_die('No permission', '', ['response' => 403]);
    }
    check_admin_referer('klaben_crm_import');
    $count = klaben_crm_import_recent();
    wp_safe_redirect(add_query_arg(['page' => 'klaben-crm', 'imported' => $count], admin_url('admin.php')));
    exit;
});

add_action('admin_post_klaben_crm_update', function (): void {
    if (!current_user_can('manage_options')) {
        wp_die('No permission', '', ['response' => 403]);
    }
    $post_id = isset($_POST['lead_id']) ? absint($_POST['lead_id']) : 0;
    check_admin_referer('klaben_crm_update_' . $post_id);
    if (!$post_id || get_post_type($post_id) !== 'klaben_enquiry') {
        wp_die('Invalid request', '', ['response' => 400]);
    }
    $status = isset($_POST['status']) ? sanitize_key(wp_unslash($_POST['status'])) : '';
    if (!array_key_exists($status, klaben_crm_statuses())) {
        wp_die('Invalid status', '', ['response' => 400]);
    }
    update_post_meta($post_id, '_klaben_status', $status);
    wp_safe_redirect(add_query_arg(['page' => 'klaben-crm', 'updated' => 1], admin_url('admin.php')));
    exit;
});

/** Load dashboard assets only on our own administrator screen. */
add_action('admin_enqueue_scripts', function (string $hook): void {
    if ($hook !== 'toplevel_page_klaben-crm') {
        return;
    }
    wp_enqueue_style('klaben-crm-admin', plugin_dir_url(__FILE__) . 'assets/admin.css', [], '0.2.0');
    wp_enqueue_script('klaben-crm-admin', plugin_dir_url(__FILE__) . 'assets/admin.js', [], '0.2.0', true);
});

function klaben_crm_render_admin(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $leads = get_posts([
        'post_type' => 'klaben_enquiry',
        'post_status' => 'private',
        'numberposts' => 100,
        'orderby' => 'date',
        'order' => 'DESC',
    ]);
    $statuses = klaben_crm_statuses();
    $counts = array_fill_keys(array_keys($statuses), 0);
    $types = ['3' => 0, '4' => 0];
    foreach ($leads as $lead) {
        $status = (string) get_post_meta($lead->ID, '_klaben_status', true);
        if (array_key_exists($status, $counts)) {
            $counts[$status]++;
        }
        $form_id = (string) (int) get_post_meta($lead->ID, '_klaben_form_id', true);
        if (isset($types[$form_id])) {
            $types[$form_id]++;
        }
    }

    echo '<div class="wrap klaben-crm-dashboard">';
    echo '<header class="klaben-crm-hero">';
    echo '<div class="klaben-crm-hero-copy">';
    echo '<span class="klaben-crm-eyebrow"><span class="dashicons dashicons-car" aria-hidden="true"></span> KLABEN AUTOS <span class="klaben-crm-eyebrow-divider"></span> PORTFOLIO-DEMO</span>';
    echo '<h1>Aanvragenoverzicht</h1>';
    echo '<p>Alle voertuigaanvragen en verkoopverzoeken, overzichtelijk op één plek.</p>';
    echo '</div>';
    echo '<span class="klaben-crm-live"><span aria-hidden="true"></span> Fluent Forms gekoppeld</span>';
    echo '</header>';

    if (isset($_GET['imported'])) {
        echo '<div class="notice notice-success"><p>' . esc_html(absint($_GET['imported'])) . ' bestaande aanvragen toegevoegd.</p></div>';
    }
    if (isset($_GET['updated'])) {
        echo '<div class="notice notice-success"><p>Status bijgewerkt.</p></div>';
    }

    $metrics = [
        ['label' => 'Totaal aanvragen', 'value' => count($leads), 'icon' => 'dashicons-list-view', 'tone' => 'primary'],
        ['label' => 'Nieuw', 'value' => $counts['nieuw'], 'icon' => 'dashicons-email-alt', 'tone' => 'new'],
        ['label' => 'In behandeling', 'value' => $counts['in_behandeling'], 'icon' => 'dashicons-update', 'tone' => 'process'],
        ['label' => 'Afgerond', 'value' => $counts['afgerond'], 'icon' => 'dashicons-yes-alt', 'tone' => 'done'],
    ];
    echo '<section class="klaben-crm-metrics" aria-label="Statistieken aanvragen">';
    foreach ($metrics as $metric) {
        echo '<div class="klaben-crm-metric klaben-crm-metric--' . esc_attr($metric['tone']) . '">';
        echo '<span class="klaben-crm-metric-icon dashicons ' . esc_attr($metric['icon']) . '" aria-hidden="true"></span>';
        echo '<span class="klaben-crm-metric-label">' . esc_html($metric['label']) . '</span>';
        echo '<strong class="klaben-crm-metric-number">' . esc_html($metric['value']) . '</strong>';
        echo '</div>';
    }
    echo '</section>';

    echo '<section class="klaben-crm-panel" aria-labelledby="klaben-crm-panel-title">';
    echo '<div class="klaben-crm-panel-head">';
    echo '<div><span class="klaben-crm-section-kicker">OVERZICHT</span><h2 id="klaben-crm-panel-title">Binnengekomen aanvragen</h2>';
    echo '<p>Voertuiginformatie (' . esc_html($types['3']) . ') <span aria-hidden="true">·</span> Auto verkopen (' . esc_html($types['4']) . ')</p></div>';
    echo '<form class="klaben-crm-import" method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
    echo '<input type="hidden" name="action" value="klaben_crm_import">';
    wp_nonce_field('klaben_crm_import');
    echo '<button class="button" type="submit"><span class="dashicons dashicons-update" aria-hidden="true"></span> Bestaande aanvragen importeren</button>';
    echo '</form></div>';

    if (!$leads) {
        echo '<div class="klaben-crm-empty"><span class="dashicons dashicons-clipboard" aria-hidden="true"></span>';
        echo '<h3>Nog geen aanvragen</h3><p>Importeer bestaande testinzendingen of verstuur een nieuw formulier. Aanvragen uit formulier #3 en #4 verschijnen daarna automatisch.</p></div>';
        echo '</section><p class="klaben-crm-footer">Alleen voor beheerders · Testomgeving · Geen echte verkoopaanvragen</p></div>';
        return;
    }

    echo '<div class="klaben-crm-tools">';
    echo '<label class="klaben-crm-search"><span class="dashicons dashicons-search" aria-hidden="true"></span><span class="screen-reader-text">Zoek aanvragen</span><input id="klaben-crm-search" type="search" placeholder="Zoek op voertuig, contact of bericht…" autocomplete="off"></label>';
    echo '<label class="klaben-crm-filter"><span class="screen-reader-text">Filter op status</span><select id="klaben-crm-status-filter"><option value="all">Alle statussen</option>';
    foreach ($statuses as $key => $label) {
        echo '<option value="' . esc_attr($key) . '">' . esc_html($label) . '</option>';
    }
    echo '</select></label>';
    echo '<label class="klaben-crm-filter"><span class="screen-reader-text">Filter op type</span><select id="klaben-crm-type-filter"><option value="all">Alle aanvragen</option><option value="3">Voertuiginformatie</option><option value="4">Auto verkopen</option></select></label>';
    echo '<span id="klaben-crm-visible-count" class="klaben-crm-visible-count" aria-live="polite"></span>';
    echo '</div>';

    echo '<div class="klaben-crm-table-scroll"><table class="klaben-crm-table"><thead><tr>';
    foreach (['Aanvraag', 'Voertuig', 'Contact', 'Details', 'Status', 'HubSpot-test', 'Ontvangen'] as $heading) {
        echo '<th scope="col">' . esc_html($heading) . '</th>';
    }
    echo '</tr></thead><tbody>';

    foreach ($leads as $lead) {
        $form_id = (int) get_post_meta($lead->ID, '_klaben_form_id', true);
        $entry_id = (int) get_post_meta($lead->ID, '_klaben_entry_id', true);
        $summary = klaben_crm_summary($form_id, klaben_crm_get_entry($entry_id, $form_id));
        $status = (string) get_post_meta($lead->ID, '_klaben_status', true);
        if (!array_key_exists($status, $statuses)) {
            $status = 'nieuw';
        }
        $sync = (string) get_post_meta($lead->ID, '_klaben_hubspot_status', true);
        $ticket_id = (string) get_post_meta($lead->ID, '_klaben_hubspot_id', true);
        $label = $form_id === 3 ? 'Voertuiginformatie' : 'Auto verkopen';
        $entry_url = admin_url('admin.php?page=fluent_forms&route=entries&form_id=' . $form_id . '&entry_id=' . $entry_id);

        echo '<tr class="klaben-crm-record" data-status="' . esc_attr($status) . '" data-form="' . esc_attr($form_id) . '">';
        echo '<td><span class="klaben-crm-type-icon dashicons ' . esc_attr($form_id === 3 ? 'dashicons-car' : 'dashicons-money-alt') . '" aria-hidden="true"></span>';
        echo '<div class="klaben-crm-table-title"><strong>' . esc_html($label) . '</strong>';
        echo '<a href="' . esc_url($entry_url) . '">Bekijk inzending #' . esc_html($entry_id) . ' <span aria-hidden="true">↗</span></a></div></td>';
        echo '<td class="klaben-crm-vehicle">' . esc_html($summary['vehicle']) . '</td>';
        echo '<td class="klaben-crm-contact">' . esc_html($summary['contact']) . '</td>';
        echo '<td class="klaben-crm-detail">' . esc_html($summary['details'] ?: '—') . '</td>';
        echo '<td class="klaben-crm-workflow"><span class="klaben-crm-status klaben-crm-status--' . esc_attr($status) . '">' . esc_html($statuses[$status]) . '</span>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        echo '<input type="hidden" name="action" value="klaben_crm_update">';
        echo '<input type="hidden" name="lead_id" value="' . esc_attr($lead->ID) . '">';
        wp_nonce_field('klaben_crm_update_' . $lead->ID);
        echo '<select name="status" aria-label="Status van aanvraag ' . esc_attr($entry_id) . '">';
        foreach ($statuses as $value => $option_label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($status, $value, false) . '>' . esc_html($option_label) . '</option>';
        }
        echo '</select><button type="submit" class="button button-small">Opslaan</button></form></td>';
        echo '<td class="klaben-crm-sync"><span class="klaben-crm-sync-dot" aria-hidden="true"></span>' . esc_html($sync ?: 'Uitgeschakeld');
        if ($ticket_id !== '') {
            echo '<span class="klaben-crm-ticket">Ticket #' . esc_html($ticket_id) . '</span>';
        }
        echo '</td>';
        echo '<td class="klaben-crm-time">' . esc_html(get_the_date('d-m-Y H:i', $lead)) . '</td>';
        echo '</tr>';
    }
    echo '</tbody></table></div>';
    echo '<div id="klaben-crm-no-results" class="klaben-crm-no-results" hidden>Geen aanvragen gevonden met deze filters.</div>';
    echo '<div class="klaben-crm-panel-bottom"><span><span class="dashicons dashicons-lock" aria-hidden="true"></span> Alleen voor WordPress-beheerders. Contactgegevens blijven opgeslagen bij Fluent Forms.</span>';
    echo '<span>Nieuwe formulierinzendingen verschijnen automatisch.</span></div>';
    echo '</section><p class="klaben-crm-footer">KLABEN AUTOS · CRM PORTFOLIO-DEMO · GEEN ECHTE VERKOOPAANVRAGEN</p></div>';
}

/**
 * Optional privacy-preserving remote API test.
 * - Opt-in only; disabled without constants in wp-config.php.
 * - Local environment, signed-in admin, never public/live.
 * - Creates a HubSpot TICKET, not a contact, and does not send names,
 *   phone numbers, email addresses or free-text notes.
 * - Demo acknowledgements are required by the forms and checked here too.
 */
function klaben_crm_maybe_send_hubspot(int $post_id, int $form_id, int $entry_id, array $data): void
{
    if (wp_get_environment_type() !== 'local' || !current_user_can('manage_options')) {
        return;
    }
    if (!defined('KLABEN_CRM_HUBSPOT_SYNC') || KLABEN_CRM_HUBSPOT_SYNC !== true ||
        !defined('KLABEN_HUBSPOT_SERVICE_KEY') || !is_string(KLABEN_HUBSPOT_SERVICE_KEY) || KLABEN_HUBSPOT_SERVICE_KEY === '' ||
        !defined('KLABEN_HUBSPOT_TICKET_PIPELINE') || !defined('KLABEN_HUBSPOT_TICKET_STAGE')) {
        return;
    }
    $ack_key = $form_id === 3 ? 'checkbox' : 'demo_ack';
    if (empty($data[$ack_key]) || get_post_meta($post_id, '_klaben_hubspot_id', true)) {
        return;
    }
    $subject = ($form_id === 3 ? 'Voertuiginformatie' : 'Auto verkopen') . ' - KLABEN demo #' . $entry_id;
    $body = [
        'properties' => [
            'subject' => $subject,
            'content' => 'Fictieve KLABEN portfolio-aanvraag. Fluent Forms ID: ' . $form_id . '; Entry ID: ' . $entry_id . '. Geen persoonsgegevens verstuurd.',
            'hs_pipeline' => (string) KLABEN_HUBSPOT_TICKET_PIPELINE,
            'hs_pipeline_stage' => (string) KLABEN_HUBSPOT_TICKET_STAGE,
        ],
    ];
    $result = wp_remote_post('https://api.hubapi.com/crm/objects/2026-09/tickets', [
        'timeout' => 8,
        'redirection' => 0,
        'headers' => [
            'Authorization' => 'Bearer ' . KLABEN_HUBSPOT_SERVICE_KEY,
            'Content-Type' => 'application/json',
        ],
        'body' => wp_json_encode($body),
    ]);
    if (is_wp_error($result)) {
        update_post_meta($post_id, '_klaben_hubspot_status', 'Verbindingsfout');
        return;
    }
    $code = wp_remote_retrieve_response_code($result);
    $response = json_decode(wp_remote_retrieve_body($result), true);
    if ($code === 201 && is_array($response) && !empty($response['id'])) {
        update_post_meta($post_id, '_klaben_hubspot_id', sanitize_text_field((string) $response['id']));
        update_post_meta($post_id, '_klaben_hubspot_status', 'Gesynchroniseerd');
    } else {
        update_post_meta($post_id, '_klaben_hubspot_status', 'HTTP ' . (int) $code);
    }
}
