<?php
/**
 * Final review of values entered in the guided selling journey.
 * Hidden until the JavaScript wizard hands values to the original form.
 * The original validated fields remain inside the form for edits/submission.
 */
?>
<section class="sell-review" id="sell-review" data-sell-review aria-labelledby="sell-review-title" hidden>
    <div class="sell-review__top">
        <div>
            <p class="sell-review__eyebrow">JOUW GEGEVENS OP EEN RIJ</p>
            <h3 id="sell-review-title" tabindex="-1">Nog één keer controleren.</h3>
            <p>Dit heb je zojuist ingevuld. Klopt er iets niet? Pas het hieronder aan.</p>
        </div>
        <span class="sell-review__check" aria-hidden="true">✓</span>
    </div>

    <dl class="sell-review__details">
        <div><dt>Merk</dt><dd data-sell-review-value="brand">—</dd></div>
        <div><dt>Model</dt><dd data-sell-review-value="model">—</dd></div>
        <div><dt>Bouwjaar</dt><dd data-sell-review-value="year">—</dd></div>
        <div><dt>Kilometerstand</dt><dd data-sell-review-value="mileage">—</dd></div>
        <div><dt>Brandstof</dt><dd data-sell-review-value="fuel">—</dd></div>
        <div><dt>Transmissie</dt><dd data-sell-review-value="transmission">—</dd></div>
        <div><dt>Staat</dt><dd data-sell-review-value="condition">—</dd></div>
        <div><dt>Onderhoud</dt><dd data-sell-review-value="maintenance">—</dd></div>
        <div><dt>Verkoopmoment</dt><dd data-sell-review-value="timing">—</dd></div>
    </dl>

    <button class="sell-review__edit" type="button" data-sell-review-edit aria-expanded="false" aria-controls="sell-vehicle-fields">
        <span aria-hidden="true">✎</span> Gegevens aanpassen
    </button>
    <p class="sell-review__notice">Je gegevens blijven alleen in deze browserpagina tijdens de demo. Er wordt niets verzonden of opgeslagen via deze controle.</p>
</section>
