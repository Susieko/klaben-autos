<?php
/**
 * KLABEN Autos — Fluent Forms Free enquiry widget (form ID 3).
 * Only included on a published single klaben_auto page with Fluent Forms active.
 * The hidden field in form #3 must use the smart code {embed_post.ID}.
 * Do not treat this hidden value as trusted when wiring a future CRM integration.
 */
if (!is_singular('klaben_auto') || !shortcode_exists('fluentform')) {
    return;
}
?>
<section class="vehicle-enquiry" id="voertuig-aanvraag" aria-labelledby="vehicle-enquiry-title">
    <div class="vehicle-enquiry__intro">
        <p class="inventory-eyebrow">PERSOONLIJK CONTACT <span aria-hidden="true">/</span> KLABEN AUTOS</p>
        <h2 id="vehicle-enquiry-title">Meer weten over deze auto?</h2>
        <p>Stel je vraag over de <?php echo esc_html($car_title); ?>. Het voertuig is alvast geselecteerd.</p>
        <div class="vehicle-enquiry__vehicle">
            <span>JOUW SELECTIE</span>
            <strong><?php echo esc_html($car_title); ?></strong>
            <small>€ <?php echo esc_html(number_format((int) $car['price'], 0, ',', '.')); ?> <span aria-hidden="true">·</span> <?php echo esc_html((string) $car['year']); ?></small>
        </div>
        <p class="vehicle-enquiry__disclaimer">Portfolio-demo: gebruik uitsluitend fictieve testgegevens. Fluent Forms slaat inzendingen op in de lokale WordPress-database; er wordt geen echte aanvraag verwerkt.</p>
    </div>
    <div class="vehicle-enquiry__form">
        <?php echo do_shortcode('[fluentform id="3"]'); ?>
    </div>
</section>
