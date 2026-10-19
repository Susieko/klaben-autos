<?php
/**
 * Vehicle inventory and details; maps to a WordPress Page with slug "autos".
 * Phase 1: uses our existing local demo dataset, no ACF dependency yet.
 */
$cars = klaben_get_vehicles();
$requested_slug = isset($_GET['auto']) && is_string($_GET['auto'])
    ? sanitize_title(wp_unslash($_GET['auto']))
    : '';

if ($requested_slug !== '') {
    $selected_car = null;
    foreach ($cars as $candidate) {
        if ($candidate['slug'] === $requested_slug) {
            $selected_car = $candidate;
            break;
        }
    }
    if ($selected_car === null) {
        status_header(404);
        nocache_headers();
    }
    get_header();
    if ($selected_car !== null) {
        $car = $selected_car;
        require get_theme_file_path('/includes/car-detail.php');
    } else { ?>
        <section class="inventory-empty container" style="padding:100px 0 130px">
            <p class="inventory-eyebrow">Niet gevonden</p>
            <h1>Deze auto staat niet (meer) in ons aanbod.</h1>
            <a class="button button--primary" href="<?php echo esc_url(home_url('/autos/')); ?>">Bekijk alle auto’s</a>
        </section>
    <?php }
    get_footer();
    return;
}

$filters = klaben_inventory_filters();
$results = klaben_filter_vehicles($cars, $filters);
$brands = array_unique(array_column($cars, 'brand'));
sort($brands, SORT_NATURAL | SORT_FLAG_CASE);
$body_types = array_unique(array_column($cars, 'body'));
sort($body_types, SORT_NATURAL | SORT_FLAG_CASE);
$get_count = count(array_filter(array_diff_key($filters, ['sort' => true]), static fn ($value) => $value !== ''));
get_header();
?>
<section class="inventory-heading">
    <div class="container inventory-heading__inner">
        <div class="inventory-heading__copy reveal">
            <p class="inventory-eyebrow">DE KLABEN SELECTIE <span aria-hidden="true">—</span> MET AANDACHT GEKOZEN</p>
            <h1>Ontdek jouw <span>volgende auto.</span></h1>
            <p>Van sportieve sedans tot karaktervolle cabrio’s. Ontdek onze zorgvuldig geselecteerde occasions.</p>
            <ul class="inventory-heading__perks" aria-label="Waarom KLABEN Autos">
                <li class="inventory-heading__perk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="M6 3h12l4 6-10 12L2 9l4-6Z"/><path d="M2 9h20M9 3 7 9l5 12 5-12-2-6"/>
                    </svg>
                    <span><strong>Geselecteerde kwaliteit</strong><small>Met aandacht gekozen</small></span>
                </li>
                <li class="inventory-heading__perk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m12 22 7-4V6l-7-4-7 4v12l7 4Z"/><path d="m9 12 2 2 4-4"/>
                    </svg>
                    <span><strong>Transparante historie</strong><small>Duidelijke informatie</small></span>
                </li>
                <li class="inventory-heading__perk">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                        <path d="m5 11 2-5h10l2 5M4 11h16v7H4zM7 18v2m10-2v2M4 14h3m10 0h3"/><path d="M10 14h4"/>
                    </svg>
                    <span><strong>Persoonlijk advies</strong><small>We denken met je mee</small></span>
                </li>
            </ul>
        </div>
        <div class="inventory-heading__stamp" aria-hidden="true"><strong><?php echo esc_html((string) count($cars)); ?></strong><span>UNIEKE<br>AUTO’S</span></div>
    </div>
</section>

<section class="inventory-section" aria-label="Ons autoaanbod">
    <div class="container inventory-layout">
        <aside class="inventory-sidebar">
            <div class="inventory-sidebar__heading">
                <h2>Vind jouw match</h2>
                <span><?php echo esc_html((string) $get_count); ?> actief</span>
            </div>
            <form class="inventory-form" method="get" action="<?php echo esc_url(home_url('/autos/')); ?>" id="inventory-form">
                <label class="inventory-field">
                    <span>Zoeken</span>
                    <input type="search" name="q" placeholder="Merk, model of locatie" value="<?php echo esc_attr($filters['q']); ?>">
                </label>
                <label class="inventory-field"><span>Merk</span>
                    <select name="merk"><option value="">Alle merken</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?php echo esc_attr(sanitize_title($brand)); ?>" <?php selected(sanitize_title($brand), sanitize_title($filters['merk'])); ?>><?php echo esc_html($brand); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="inventory-field"><span>Carrosserie</span>
                    <select name="carrosserie"><option value="">Alle types</option>
                        <?php foreach ($body_types as $body): ?>
                            <option value="<?php echo esc_attr(sanitize_title($body)); ?>" <?php selected(sanitize_title($body), sanitize_title($filters['carrosserie'])); ?>><?php echo esc_html($body); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <div class="inventory-fields-two">
                    <label class="inventory-field"><span>Vanaf bouwjaar</span>
                        <select name="bouwjaar"><option value="">Alle jaren</option>
                            <?php foreach ([2024, 2022, 2020, 2018, 2015, 1990] as $year): ?>
                                <option value="<?php echo esc_attr((string) $year); ?>" <?php selected((string) $year, $filters['bouwjaar']); ?>><?php echo esc_html((string) $year); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                    <label class="inventory-field"><span>Max. budget</span>
                        <select name="budget"><option value="">Elk budget</option>
                            <?php foreach ([15000, 20000, 25000, 30000, 35000, 50000] as $amount): ?>
                                <option value="<?php echo esc_attr((string) $amount); ?>" <?php selected((string) $amount, $filters['budget']); ?>>€ <?php echo esc_html(number_format($amount, 0, ',', '.')); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </div>
                <label class="inventory-field"><span>Brandstof</span>
                    <select name="brandstof"><option value="">Alle brandstoffen</option>
                        <?php foreach (['Benzine', 'Diesel', 'Hybride', 'Elektrisch'] as $fuel): ?>
                            <option value="<?php echo esc_attr(sanitize_title($fuel)); ?>" <?php selected(sanitize_title($fuel), sanitize_title($filters['brandstof'])); ?>><?php echo esc_html($fuel); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="inventory-field"><span>Transmissie</span>
                    <select name="transmissie"><option value="">Alle transmissies</option>
                        <?php foreach (['Automaat', 'Handgeschakeld'] as $transmission): ?>
                            <option value="<?php echo esc_attr(sanitize_title($transmission)); ?>" <?php selected(sanitize_title($transmission), sanitize_title($filters['transmissie'])); ?>><?php echo esc_html($transmission); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <?php if ($filters['model'] !== ''): ?>
                    <input type="hidden" name="model" value="<?php echo esc_attr($filters['model']); ?>">
                    <p class="inventory-applied">Model gezocht: <strong><?php echo esc_html($filters['model']); ?></strong></p>
                <?php endif; ?>
                <button class="button button--primary inventory-submit" type="submit">Bekijk auto’s <span aria-hidden="true">↗</span></button>
                <a class="inventory-reset" href="<?php echo esc_url(home_url('/autos/')); ?>">Wis alle filters</a>
            </form>
            <div class="inventory-sidebar__note"><span aria-hidden="true">✦</span><p><strong>Met zorg geselecteerd.</strong> Wij geloven in duidelijkheid over de auto die je koopt.</p></div>
        </aside>
        <div class="inventory-results">
            <div class="inventory-toolbar">
                <div><p class="inventory-eyebrow">ONS AANBOD</p><h2><?php echo esc_html((string) count($results)); ?> <?php echo count($results) === 1 ? 'auto gevonden' : 'auto’s gevonden'; ?></h2></div>
                <form method="get" action="<?php echo esc_url(home_url('/autos/')); ?>" class="inventory-sort-form">
                    <?php foreach ($filters as $key => $value): ?>
                        <?php if ($key !== 'sort' && $value !== ''): ?><input type="hidden" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>"><?php endif; ?>
                    <?php endforeach; ?>
                    <label for="sort-cars">Sorteer op</label>
                    <select id="sort-cars" name="sort" data-autosubmit>
                        <option value="" <?php selected('', $filters['sort']); ?>>Uitgelicht</option>
                        <option value="prijs-oplopend" <?php selected('prijs-oplopend', $filters['sort']); ?>>Prijs: laag naar hoog</option>
                        <option value="prijs-aflopend" <?php selected('prijs-aflopend', $filters['sort']); ?>>Prijs: hoog naar laag</option>
                        <option value="bouwjaar" <?php selected('bouwjaar', $filters['sort']); ?>>Nieuwste eerst</option>
                        <option value="kilometerstand" <?php selected('kilometerstand', $filters['sort']); ?>>Laagste kilometerstand</option>
                    </select>
                    <button class="inventory-sort-submit" type="submit">Sorteer</button>
                </form>
            </div>
            <?php if ($results): ?>
                <div class="car-grid inventory-grid">
                    <?php foreach ($results as $index => $car): ?>
                        <?php $card_delay = min($index, 5) * 75; require get_theme_file_path('/includes/car-card.php'); ?>
                    <?php endforeach; ?>
                </div>
                <p class="inventory-bottom-note">Elk voertuig heeft zijn eigen verhaal. Ontdek alle details door een auto te selecteren.</p>
            <?php else: ?>
                <div class="inventory-no-results" role="status">
                    <span aria-hidden="true">⌕</span>
                    <h3>Geen auto’s gevonden</h3>
                    <p>Er zijn op dit moment geen auto’s die bij je filters passen. Probeer een ander merk, budget of bouwjaar.</p>
                    <a class="button button--primary" href="<?php echo esc_url(home_url('/autos/')); ?>">Bekijk het volledige aanbod</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>
