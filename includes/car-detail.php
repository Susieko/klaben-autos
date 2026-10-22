<?php
/** @var array<string,mixed> $car */
$images = klaben_vehicle_images($car);
$car_title = $car['brand'] . ' ' . $car['model'];
$similar = klaben_related_vehicle_records($car);
$related_articles = klaben_related_article_posts($car);
?>
<section class="vehicle-view container">
    <nav class="vehicle-breadcrumb" aria-label="Broodkruimelnavigatie">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span aria-hidden="true">/</span>
        <a href="<?php echo esc_url(home_url('/autos/')); ?>">Alle auto’s</a><span aria-hidden="true">/</span>
        <span aria-current="page"><?php echo esc_html($car_title); ?></span>
    </nav>
    <div class="vehicle-headline">
        <div><p class="inventory-eyebrow">KLABEN SELECTIE — <?php echo esc_html((string) $car['year']); ?></p>
            <h1><?php echo esc_html($car_title); ?></h1>
            <p><?php echo esc_html($car['body'] . '  ·  ' . $car['fuel'] . '  ·  ' . $car['transmission']); ?></p>
        </div>
        <div class="vehicle-headline__price"><span>Vraagprijs</span><strong>€ <?php echo esc_html(number_format((int) $car['price'], 0, ',', '.')); ?></strong></div>
    </div>
    <div class="vehicle-layout">
        <div>
            <div class="vehicle-gallery">
                <img class="vehicle-gallery__main" data-gallery-main src="<?php echo esc_url($images[0]); ?>" alt="<?php echo esc_attr($car_title); ?>" fetchpriority="high">
                <div class="vehicle-gallery__badge">KLABEN <span>SELECTIE</span></div>
                <?php if (count($images) > 1): ?><span class="vehicle-gallery__counter" data-gallery-counter>01 / <?php echo esc_html(sprintf('%02d', count($images))); ?></span><?php endif; ?>
            </div>
            <?php if (count($images) > 1): ?>
                <div class="vehicle-thumbs" role="group" aria-label="Foto’s van deze auto">
                    <?php foreach ($images as $index => $image): ?>
                        <button type="button" class="vehicle-thumb <?php echo $index === 0 ? 'is-active' : ''; ?>" data-gallery-thumb="<?php echo esc_attr((string) ($index + 1)); ?>" data-image="<?php echo esc_url($image); ?>" aria-label="Bekijk foto <?php echo esc_attr((string) ($index + 1)); ?>" aria-pressed="<?php echo $index === 0 ? 'true' : 'false'; ?>">
                            <img src="<?php echo esc_url($image); ?>" alt="" loading="lazy">
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            <section class="vehicle-about"><p class="inventory-eyebrow">OVER DEZE AUTO</p><h2>Een auto om enthousiast van te worden.</h2>
                <p>Maak kennis met de <?php echo esc_html($car_title); ?>. Bekijk de foto’s en voertuiggegevens, en ontdek of dit de auto is die bij je past.</p>
                <p class="vehicle-demo-note">Dit is een portfolio-demo. Voertuiggegevens en prijzen zijn fictieve voorbeeldinformatie, geen verkoopaanbod.</p>
            </section>
        </div>
        <aside class="vehicle-spec-panel">
            <p class="inventory-eyebrow">IN ÉÉN OOGOPSLAG</p><h2>De details.</h2>
            <dl class="vehicle-spec-list">
                <?php foreach (['Merk' => $car['brand'], 'Model' => $car['model'], 'Bouwjaar' => $car['year'], 'Kilometerstand' => number_format((int)$car['mileage'], 0, ',', '.') . ' km', 'Brandstof' => $car['fuel'], 'Transmissie' => $car['transmission'], 'Carrosserie' => $car['body'], 'Locatie' => $car['location']] as $label => $value): ?>
                    <div><dt><?php echo esc_html($label); ?></dt><dd><?php echo esc_html((string) $value); ?></dd></div>
                <?php endforeach; ?>
            </dl>
            <a class="button button--primary vehicle-spec-panel__cta" href="<?php echo esc_url(add_query_arg('auto', $car['slug'], home_url('/financiering/'))); ?>">Bereken maandbedrag ↗</a>
            <?php if (is_singular('klaben_auto') && shortcode_exists('fluentform')): ?>
                <a class="vehicle-enquiry__jump" href="#voertuig-aanvraag">Vraag informatie aan <span aria-hidden="true">â†—</span></a>
            <?php endif; ?>
            <a class="vehicle-finance-back" href="<?php echo esc_url(home_url('/autos/')); ?>">← Bekijk alle auto’s</a>
            <p class="vehicle-spec-panel__note">Dit project is een demonstratie van een fictieve autowebsite.</p>
        </aside>
    </div>
    <?php if (is_singular('klaben_auto') && shortcode_exists('fluentform')): ?>
        <?php require get_theme_file_path('/includes/vehicle-enquiry.php'); ?>
    <?php endif; ?>
    <?php if ($similar): ?>
        <section class="vehicle-related"><div class="vehicle-related__heading"><div><p class="inventory-eyebrow">OOK INTERESSANT</p><h2>Misschien iets voor jou.</h2></div><a href="<?php echo esc_url(home_url('/autos/')); ?>">Het hele aanbod →</a></div>
            <div class="car-grid inventory-grid">
                <?php foreach (array_slice($similar, 0, 2) as $index => $car): ?>
                    <?php $card_delay = $index * 75; require get_theme_file_path('/includes/car-card.php'); ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
    <?php if ($related_articles): ?>
        <section class="vehicle-articles" aria-labelledby="vehicle-articles-heading">
            <div class="vehicle-related__heading">
                <div><p class="inventory-eyebrow">KLABEN JOURNAL</p><h2 id="vehicle-articles-heading">Verder lezen.</h2></div>
                <a href="<?php echo esc_url(home_url('/nieuws/')); ?>">Alle verhalen →</a>
            </div>
            <div class="vehicle-articles__grid">
                <?php foreach ($related_articles as $article): ?>
                    <a class="vehicle-article" href="<?php echo esc_url(get_permalink($article->ID)); ?>">
                        <?php if (has_post_thumbnail($article->ID)): ?>
                            <?php echo get_the_post_thumbnail($article->ID, 'medium_large', ['loading' => 'lazy', 'decoding' => 'async']); ?>
                        <?php endif; ?>
                        <span class="vehicle-article__content">
                            <span class="inventory-eyebrow">KLABEN JOURNAL</span>
                            <strong><?php echo esc_html(get_the_title($article->ID)); ?></strong>
                            <span class="vehicle-article__link">Lees het verhaal ↗</span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</section>
