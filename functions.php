<?php
require_once get_theme_file_path('/includes/finance-home-assets.php');


function klaben_autos_asset_version(string $relative_path): string
{
    $absolute_path = get_theme_file_path($relative_path);

    if (file_exists($absolute_path)) {
        return (string) filemtime($absolute_path);
    }

    return wp_get_theme()->get('Version');
}

function klaben_autos_enqueue_assets(): void
{
    $theme_version = wp_get_theme()->get('Version');

    wp_enqueue_style(
        'klaben-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'klaben-global',
        get_theme_file_uri('/assets/css/global.css'),
        ['klaben-fonts'],
        $theme_version
    );

  wp_enqueue_style(
    'klaben-header',
    get_theme_file_uri('/assets/css/header.css'),
    ['klaben-global'],
    klaben_autos_asset_version('/assets/css/header.css')
);

wp_enqueue_style(
    'klaben-hero',
    get_theme_file_uri('/assets/css/hero.css'),
    ['klaben-global'],
    klaben_autos_asset_version('/assets/css/hero.css')
);

wp_enqueue_style(
    'klaben-cars',
    get_theme_file_uri('/assets/css/cars.css'),
    ['klaben-global'],
    klaben_autos_asset_version('/assets/css/cars.css')
);



wp_enqueue_style(
    'klaben-responsive',
    get_theme_file_uri('/assets/css/responsive.css'),
    ['klaben-global'],
    klaben_autos_asset_version('/assets/css/responsive.css')
);

    wp_enqueue_script(
        'klaben-main',
        get_theme_file_uri('/assets/js/main.js'),
        [],
        $theme_version,
        true
    );
}

add_action('wp_enqueue_scripts', 'klaben_autos_enqueue_assets');

function klaben_autos_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
}

add_action('after_setup_theme', 'klaben_autos_theme_setup');

/**
 * Phase 1 data adapter. Later we can replace this static source with WP_Query + ACF,
 * without rewriting archive/detail templates.
 * @return array<int, array<string, mixed>>
 */
function klaben_get_vehicles(): array
{
    return require get_theme_file_path('/data/cars.php');
}

/** @return string[] */
function klaben_vehicle_images(array $car): array
{
    $images = $car['images'] ?? [$car['image'] ?? ''];
    $urls = [];
    foreach ($images as $filename) {
        // File names are internal theme data, not user-provided paths.
        $relative = '/assets/images/cars/' . basename((string) $filename);
        if (is_file(get_theme_file_path($relative))) {
            $urls[] = get_theme_file_uri($relative);
        }
    }
    return $urls ?: [get_theme_file_uri('/assets/images/hero/klaben-hero.png')];
}

/**
 * GET filters shared by homepage search and the inventory form.
 * @return array<string, string>
 */
function klaben_inventory_filters(): array
{
    $keys = ['merk', 'model', 'bouwjaar', 'budget', 'brandstof', 'transmissie', 'carrosserie', 'q', 'sort'];
    $filters = [];
    foreach ($keys as $key) {
        $raw = $_GET[$key] ?? '';
        $filters[$key] = is_string($raw) ? sanitize_text_field(wp_unslash($raw)) : '';
    }
    return $filters;
}

/** @return array<int, array<string, mixed>> */
function klaben_filter_vehicles(array $cars, array $filters): array
{
    $cars = array_values(array_filter($cars, static function (array $car) use ($filters): bool {
        if ($filters['merk'] !== '' && sanitize_title($car['brand']) !== sanitize_title($filters['merk'])) return false;
        if ($filters['model'] !== '' && !preg_match('/(?:^|[^a-z0-9])' . preg_quote($filters['model'], '/') . '(?=$|[^a-z0-9])/i', $car['model'])) return false;
        if ($filters['bouwjaar'] !== '' && (int) $car['year'] < (int) $filters['bouwjaar']) return false;
        if ($filters['budget'] !== '' && (int) $car['price'] > (int) $filters['budget']) return false;
        if ($filters['brandstof'] !== '' && sanitize_title($car['fuel']) !== sanitize_title($filters['brandstof'])) return false;
        if ($filters['transmissie'] !== '' && sanitize_title($car['transmission']) !== sanitize_title($filters['transmissie'])) return false;
        if ($filters['carrosserie'] !== '' && sanitize_title($car['body']) !== sanitize_title($filters['carrosserie'])) return false;
        if ($filters['q'] !== '' && stripos(implode(' ', [$car['brand'], $car['model'], $car['location']]), $filters['q']) === false) return false;
        return true;
    }));

    $allowed_sorts = ['prijs-oplopend', 'prijs-aflopend', 'bouwjaar', 'kilometerstand'];
    if (in_array($filters['sort'], $allowed_sorts, true)) {
        usort($cars, static function (array $a, array $b) use ($filters): int {
            switch ($filters['sort']) {
                case 'prijs-oplopend': return $a['price'] <=> $b['price'];
                case 'prijs-aflopend': return $b['price'] <=> $a['price'];
                case 'bouwjaar': return $b['year'] <=> $a['year'];
                case 'kilometerstand': return $a['mileage'] <=> $b['mileage'];
                default: return 0;
            }
        });
    }
    return $cars;
}

function klaben_enqueue_inventory_assets(): void
{
    if (!is_page('autos')) return;
    wp_enqueue_style('klaben-inventory', get_theme_file_uri('/assets/css/inventory.css'), ['klaben-cars'], klaben_autos_asset_version('/assets/css/inventory.css'));
    wp_enqueue_script('klaben-inventory', get_theme_file_uri('/assets/js/inventory.js'), ['klaben-main'], klaben_autos_asset_version('/assets/js/inventory.js'), true);
}
add_action('wp_enqueue_scripts', 'klaben_enqueue_inventory_assets');


/** Sprint 02 — Auto verkopen page assets, loaded only where needed. */
function klaben_enqueue_sell_assets(): void
{
    if (!is_page('auto-verkopen')) return;
    wp_enqueue_style('klaben-sell', get_theme_file_uri('/assets/css/sell.css'), ['klaben-global'], klaben_autos_asset_version('/assets/css/sell.css'));
    wp_enqueue_script('klaben-sell', get_theme_file_uri('/assets/js/sell.js'), ['klaben-main'], klaben_autos_asset_version('/assets/js/sell.js'), true);
}
add_action('wp_enqueue_scripts', 'klaben_enqueue_sell_assets');

/**
 * Server-side validation for the demo. Intentionally no wp_mail() or database writes:
 * KLABEN Autos is fictional and must not silently collect real visitor data.
 */
function klaben_handle_demo_vehicle_submission(): void
{
    $back_url = home_url('/auto-verkopen/');
    $redirect = static function (string $status) use ($back_url): void {
        wp_safe_redirect(add_query_arg('klaben_status', $status, $back_url) . '#verkoopformulier', 303);
        exit;
    };

    if (!isset($_POST['klaben_demo_nonce']) || !is_string($_POST['klaben_demo_nonce']) ||
        !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['klaben_demo_nonce'])), 'klaben_demo_vehicle_submission')) {
        $redirect('invalid');
    }
    if (!empty($_POST['klaben_company'])) {
        // Ignore bots without exposing a detailed failure.
        $redirect('success');
    }

    $fields = [];
    foreach (['brand', 'model', 'year', 'mileage', 'fuel', 'transmission', 'notes'] as $key) {
        $raw = $_POST[$key] ?? '';
        if (!is_string($raw)) $redirect('invalid');
        $fields[$key] = sanitize_text_field(wp_unslash($raw));
    }
    $current_year = (int) gmdate('Y');
    $fuel_types = ['Benzine', 'Diesel', 'Hybride', 'Elektrisch', 'Anders'];
    $transmissions = ['', 'Automaat', 'Handgeschakeld'];
    if (strlen($fields['brand']) === 0 || strlen($fields['brand']) > 180 ||
        strlen($fields['model']) === 0 || strlen($fields['model']) > 240 ||
        !ctype_digit($fields['year']) || (int) $fields['year'] < 1950 || (int) $fields['year'] > $current_year + 1 ||
        !ctype_digit($fields['mileage']) || (int) $fields['mileage'] > 9999999 ||
        !in_array($fields['fuel'], $fuel_types, true) ||
        !in_array($fields['transmission'], $transmissions, true) ||
        strlen($fields['notes']) > 2400 ||
        !isset($_POST['demo_ack']) || $_POST['demo_ack'] !== '1') {
        $redirect('invalid');
    }
    // The validation was successful. No vehicle or personal data leave this request.
    $redirect('success');
}
add_action('admin_post_klaben_demo_vehicle_submission', 'klaben_handle_demo_vehicle_submission');
add_action('admin_post_nopriv_klaben_demo_vehicle_submission', 'klaben_handle_demo_vehicle_submission');


/** Sprint 03 — Interactive financing demo, only on the financing page. */
function klaben_enqueue_finance_assets(): void
{
    if (!is_page('financiering')) return;
    wp_enqueue_style(
        'klaben-finance',
        get_theme_file_uri('/assets/css/finance.css'),
        ['klaben-global'],
        klaben_autos_asset_version('/assets/css/finance.css')
    );
    wp_enqueue_script(
        'klaben-finance',
        get_theme_file_uri('/assets/js/finance.js'),
        ['klaben-main'],
        klaben_autos_asset_version('/assets/js/finance.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'klaben_enqueue_finance_assets');


/** Sprint 04 — Editorial archive + single WordPress posts. */
function klaben_enqueue_news_assets(): void
{
    if (!is_page('nieuws') && !is_singular('post')) return;
    wp_enqueue_style(
        'klaben-news',
        get_theme_file_uri('/assets/css/news.css'),
        ['klaben-global'],
        klaben_autos_asset_version('/assets/css/news.css')
    );
}
add_action('wp_enqueue_scripts', 'klaben_enqueue_news_assets');


/** Visual polish preview: a reversible layer, without touching the existing page templates. */
function klaben_enqueue_visual_polish(): void
{
    wp_enqueue_style(
        'klaben-polish',
        get_theme_file_uri('/assets/css/polish.css'),
        ['klaben-header', 'klaben-cars', 'klaben-responsive'],
        klaben_autos_asset_version('/assets/css/polish.css')
    );
    wp_enqueue_script(
        'klaben-polish',
        get_theme_file_uri('/assets/js/polish.js'),
        ['klaben-main'],
        klaben_autos_asset_version('/assets/js/polish.js'),
        true
    );
}
add_action('wp_enqueue_scripts', 'klaben_enqueue_visual_polish', 50);
