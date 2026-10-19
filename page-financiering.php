<?php
/**
 * KLABEN Autos — Financing demo. WordPress page slug: financiering.
 * Uses illustrative input data only. No lender, application or personal-data processing.
 */
get_header();
$vehicles = klaben_get_vehicles();
$requested_slug = isset($_GET['auto']) && is_string($_GET['auto'])
    ? sanitize_title(wp_unslash($_GET['auto']))
    : '';
$selected_car = null;
foreach ($vehicles as $vehicle) {
    if ($requested_slug !== '' && $vehicle['slug'] === $requested_slug) {
        $selected_car = $vehicle;
        break;
    }
}
if ($selected_car === null && $vehicles) {
    $selected_car = $vehicles[0];
}
$start_price = $selected_car ? (int) $selected_car['price'] : 32950;
$start_down = min(5000, $start_price);
$car_image = $selected_car ? klaben_vehicle_images($selected_car)[0] : get_theme_file_uri('/assets/images/cars/mercedes.png');
$car_title = $selected_car ? $selected_car['brand'] . ' ' . $selected_car['model'] : 'Jouw droomauto';
?>
<section class="finance-hero">
    <div class="container finance-hero__grid">
        <div class="finance-hero__text reveal">
            <nav class="finance-breadcrumb" aria-label="Broodkruimelnavigatie">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span aria-hidden="true">/</span><span aria-current="page">Financiering</span>
            </nav>
            <p class="finance-eyebrow">FINANCIERING / REKENVOORBEELD</p>
            <h1>De auto die je wilt.<br><span>Een bedrag dat past.</span></h1>
            <p class="finance-hero__lead">Speel met de aanschafprijs, aanbetaling en looptijd. Bekijk direct hoe het voorbeeldbedrag per maand verandert.</p>
            <a href="#calculator" class="button button--primary">Bereken je maandbedrag <span aria-hidden="true">↗</span></a>
            <p class="finance-hero__footnote">Fictief automotive portfolio-concept · Geen financieringsaanbod</p>
        </div>
        <div class="finance-hero__image" aria-hidden="true">
            <span class="finance-hero__orbit finance-hero__orbit--one"></span>
            <span class="finance-hero__orbit finance-hero__orbit--two"></span>
            <img src="<?php echo esc_url($car_image); ?>" alt="" fetchpriority="high" data-finance-hero-image>
            <span class="finance-hero__floating finance-hero__floating--top"><span>01 / FLEXIBEL</span><strong>Jouw keuzes.</strong></span>
            <span class="finance-hero__floating finance-hero__floating--bottom"><span>02 / TRANSPARANT</span><strong>Jouw overzicht.</strong></span>
        </div>
    </div>
</section>

<section class="finance-workspace" id="calculator" aria-labelledby="finance-calculator-title">
    <div class="container">
        <div class="finance-workspace__intro">
            <div><p class="finance-eyebrow">INTERACTIEVE CALCULATOR</p><h2 id="finance-calculator-title">Jouw auto. <span>Jouw berekening.</span></h2></div>
            <p>Alle bedragen zijn uitsluitend illustratief. Er worden geen persoonsgegevens verzonden of opgeslagen.</p>
        </div>
        <div class="finance-layout" data-finance-calculator>
            <form class="finance-controls" id="finance-demo-form" novalidate>
                <div class="finance-controls__heading"><span class="finance-step-label">01</span><div><h3>Stel je berekening samen</h3><p>Pas de bedragen aan om verschillende scenario’s te vergelijken.</p></div></div>
                <div class="finance-fields">
                    <label class="finance-field"><span>Auto uit onze collectie</span>
                        <select id="finance-car" data-finance-car>
                            <?php foreach ($vehicles as $car): ?>
                                <option value="<?php echo esc_attr($car['slug']); ?>" data-price="<?php echo esc_attr((string) (int) $car['price']); ?>" data-image="<?php echo esc_url(klaben_vehicle_images($car)[0]); ?>" <?php selected($selected_car && $selected_car['slug'] === $car['slug']); ?>><?php echo esc_html($car['brand'] . ' ' . $car['model']); ?></option>
                            <?php endforeach; ?>
                            <option value="custom">Zelf bedrag invullen</option>
                        </select>
                    </label>
                    <label class="finance-field"><span>Aanschafprijs</span><div class="finance-field__currency"><span aria-hidden="true">€</span><input type="number" id="finance-price" min="1000" max="250000" step="50" inputmode="numeric" required value="<?php echo esc_attr((string) $start_price); ?>" aria-describedby="finance-price-hint"><span class="finance-field__unit">EUR</span></div><small id="finance-price-hint">Tussen € 1.000 en € 250.000</small></label>
                    <label class="finance-field"><span>Aanbetaling</span><div class="finance-field__currency"><span aria-hidden="true">€</span><input type="number" id="finance-down" min="0" max="<?php echo esc_attr((string) $start_price); ?>" step="50" inputmode="numeric" required value="<?php echo esc_attr((string) $start_down); ?>"><span class="finance-field__unit">EUR</span></div><small>Mag nooit hoger zijn dan de aanschafprijs.</small></label>
                    <fieldset class="finance-terms"><legend>Looptijd</legend><div class="finance-terms__options">
                        <?php foreach ([24, 36, 48, 60, 72] as $months): ?>
                            <label class="finance-term"><input type="radio" name="finance-term" value="<?php echo esc_attr((string) $months); ?>" <?php checked($months, 60); ?>><span><?php echo esc_html((string) $months); ?> <small>mnd</small></span></label>
                        <?php endforeach; ?>
                    </div></fieldset>
                    <div class="finance-rate"><span class="finance-rate__dot" aria-hidden="true"></span><p><strong>Rekenrente: 7,9% per jaar (nominaal)</strong><span>Fictieve vaste rente, uitsluitend voor deze demo. Geen werkelijke kredietvoorwaarden.</span></p></div>
                </div>
            </form>
            <aside class="finance-result" aria-label="Resultaat van je berekening">
                <div class="finance-result__heading"><p class="finance-eyebrow">JOUW REKENVOORBEELD</p><span class="finance-result__live"><span aria-hidden="true"></span> LIVE</span></div>
                <p class="finance-result__car" data-finance-car-label><?php echo esc_html($car_title); ?></p>
                <div class="finance-result__figure"><span>Geschat maandbedrag</span><div><strong data-finance-monthly aria-live="polite" aria-atomic="true">—</strong><span>/ maand</span></div><p>Bij gelijke maandelijkse betalingen, zonder bijkomende kosten.</p></div>
                <div class="finance-result__breakdown" aria-label="Financiering tegenover aanbetaling"><div class="finance-result__bar" aria-hidden="true"><span data-finance-bar-down></span><span data-finance-bar-loan></span></div><div class="finance-result__legend"><span><i class="finance-result__legend-dot finance-result__legend-dot--down"></i>Aanbetaling</span><span><i class="finance-result__legend-dot finance-result__legend-dot--loan"></i>Te financieren</span></div></div>
                <dl class="finance-result__values">
                    <div><dt>Aanschafprijs</dt><dd data-finance-price>—</dd></div>
                    <div><dt>Aanbetaling</dt><dd data-finance-down>—</dd></div>
                    <div><dt>Te financieren bedrag</dt><dd data-finance-principal>—</dd></div>
                    <div><dt>Looptijd</dt><dd data-finance-months>—</dd></div>
                    <div><dt>Totale rente (indicatie)</dt><dd data-finance-interest>—</dd></div>
                    <div class="finance-result__total"><dt>Totaal aan termijnen</dt><dd data-finance-total>—</dd></div>
                </dl>
                <p class="finance-result__disclaimer">Dit is een fictief rekenvoorbeeld, geen offerte of aanbod voor een lening. Werkelijke financiering, rente, kosten en acceptatie kunnen afwijken. Er wordt niets aangevraagd.</p>
                <a href="<?php echo esc_url(home_url('/autos/')); ?>" class="finance-result__browse">Bekijk alle auto’s <span aria-hidden="true">↗</span></a>
            </aside>
        </div>
        <p class="finance-error" data-finance-error role="alert" hidden>Vul geldige bedragen in: een aanschafprijs tussen € 1.000 en € 250.000 en een aanbetaling tussen € 0 en de aanschafprijs.</p>
    </div>
</section>

<section class="finance-outro"><div class="container finance-outro__inner"><div><p class="finance-eyebrow">DE AUTO'S STAAN AL KLAAR</p><h2>De volgende rit begint hier.</h2><p>Ontdek onze selectie, van sportieve hatchback tot stijlvolle sedan.</p></div><a class="button button--primary" href="<?php echo esc_url(home_url('/autos/')); ?>">Ontdek de collectie <span aria-hidden="true">↗</span></a></div></section>
<?php get_footer(); ?>
