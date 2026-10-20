<?php
/**
 * Klaben — guided fictional valuation journey.
 * Progressively enhanced: existing application form still works without JS.
 * This is NOT an RDW lookup, appraisal, quote, or real vehicle valuation.
 */
?>
<section class="sell-journey" id="waardebepaling" aria-labelledby="sell-journey-title" hidden>
  <div class="container sell-journey__layout">
    <div class="sell-journey__aside">
      <p class="sell-eyebrow">JOUW VERKOOPTRAJECT</p>
      <h2 id="sell-journey-title">Van kenteken tot <span>helder inzicht.</span></h2>
      <p>Vertel ons iets over jouw auto. Je krijgt een transparant <strong>fictief rekenvoorbeeld</strong> en kunt je gegevens daarna controleren in het aanvraagformulier.</p>
      <ol class="sell-journey__timeline" aria-label="Stappen">
        <li class="is-current" data-journey-mark="0"><span>01</span><div><strong>Jouw auto</strong><small>Voertuiggegevens</small></div></li>
        <li data-journey-mark="1"><span>02</span><div><strong>De details</strong><small>Staat en onderhoud</small></div></li>
        <li data-journey-mark="2"><span>03</span><div><strong>Jouw inzicht</strong><small>Demo-berekening en aanvraag</small></div></li>
      </ol>
      <p class="sell-journey__aside-note">Geen RDW-koppeling · Geen marktprijsdata · Geen opslag of verzending van ingevulde gegevens in deze stap</p>
    </div>

    <div class="sell-journey__panel">
      <div class="sell-journey__panel-head">
        <div><p class="sell-eyebrow">WAARDEBEPAALING · DEMO</p><h3 data-journey-panel-title>Vertel ons over je auto</h3></div>
        <span class="sell-journey__step-counter" data-journey-counter>01 / 03</span>
      </div>
      <div class="sell-journey__progress" aria-hidden="true"><span data-journey-progress></span></div>
      <div class="sell-journey__notice" role="status" aria-live="polite" data-journey-plate>Je kunt ook zonder kenteken doorgaan en alles zelf invullen.</div>

      <form id="klaben-valuation-form" novalidate>
        <fieldset class="sell-journey__pane" data-journey-step="0">
          <legend class="screen-reader-text">Voertuiggegevens</legend>
          <p class="sell-journey__hint">We vullen niets automatisch in: daarvoor zou een echte kentekenservice nodig zijn.</p>
          <div class="sell-journey__fields">
            <label><span class="sell-journey__label-text">Merk <b aria-hidden="true">*</b></span><input name="brand" type="text" maxlength="60" placeholder="Bijv. Audi" autocomplete="off" required></label>
            <label><span class="sell-journey__label-text">Model <b aria-hidden="true">*</b></span><input name="model" type="text" maxlength="80" placeholder="Bijv. A3 Sportback" required></label>
            <label><span class="sell-journey__label-text">Bouwjaar <b aria-hidden="true">*</b></span><input name="year" type="number" min="1950" max="<?php echo esc_attr((string) ((int) gmdate('Y') + 1)); ?>" placeholder="Bijv. 2020" inputmode="numeric" required></label>
            <label><span class="sell-journey__label-text">Kilometerstand <b aria-hidden="true">*</b></span><input name="mileage" type="number" min="0" max="9999999" placeholder="Bijv. 68500" inputmode="numeric" required></label>
            <label><span class="sell-journey__label-text">Brandstof <b aria-hidden="true">*</b></span><select name="fuel" required><option value="">Selecteer brandstof</option><option>Benzine</option><option>Diesel</option><option>Hybride</option><option>Elektrisch</option><option>Anders</option></select></label>
            <label><span class="sell-journey__label-text">Transmissie <small>(optioneel)</small></span><select name="transmission"><option value="">Selecteer transmissie</option><option>Automaat</option><option>Handgeschakeld</option></select></label>
          </div>
        </fieldset>

        <fieldset class="sell-journey__pane" data-journey-step="1" hidden disabled>
          <legend class="screen-reader-text">Staat en onderhoud</legend>
          <p class="sell-journey__hint">Deze keuzes laten zien hoe een aanvraagproces rekening kan houden met voertuigkenmerken.</p>
          <div class="sell-journey__choice-block">
            <p class="sell-journey__label">Hoe is de staat van de auto? <span aria-hidden="true">*</span></p>
            <div class="sell-journey__choices">
              <label><input type="radio" name="condition" value="excellent" required><span><strong>Uitstekend</strong><small>Goed onderhouden, weinig gebruikssporen</small></span></label>
              <label><input type="radio" name="condition" value="good"><span><strong>Goed</strong><small>Normale gebruikssporen</small></span></label>
              <label><input type="radio" name="condition" value="attention"><span><strong>Aandacht nodig</strong><small>Onderhoud of herstel gewenst</small></span></label>
            </div>
          </div>
          <div class="sell-journey__fields">
            <label><span class="sell-journey__label-text">Onderhoudshistorie</span><select name="maintenance"><option value="known">Onderhoud bekend</option><option value="partial">Gedeeltelijk bekend</option><option value="unknown">Onbekend</option></select></label>
            <label><span class="sell-journey__label-text">Wanneer wil je verkopen?</span><select name="timing"><option value="soon">Zo snel mogelijk</option><option value="month">Binnen een maand</option><option value="exploring">Ik oriënteer me nog</option></select></label>
          </div>
        </fieldset>

        <fieldset class="sell-journey__pane" data-journey-step="2" hidden disabled>
          <legend class="screen-reader-text">Fictieve voorbeeldwaarde</legend>
          <div class="sell-journey__result">
            <p class="sell-journey__result-tag">GESIMULEERDE VOORBEELDWAARDE · GEEN TAXATIE</p>
            <output class="sell-journey__range" data-journey-range aria-live="off">—</output>
            <p class="sell-journey__result-desc">Dit is uitsluitend een programmeerdemonstratie, berekend vanaf een <strong>vaste fictieve basiswaarde van € 28.000</strong> met eenvoudige correcties voor leeftijd, kilometers en staat. Het is <strong>geen inschatting van de marktwaarde van jouw auto</strong>.</p>
          </div>
          <div class="sell-journey__summary" data-journey-summary></div>
          <p class="sell-journey__next">Klaar? Neem je ingevulde voertuiggegevens mee naar het bestaande demo-aanvraagformulier. Je kunt ze daar nog aanpassen.</p>
        </fieldset>

        <div class="sell-journey__error" data-journey-error role="alert" hidden></div>
        <div class="sell-journey__actions">
          <button type="button" class="sell-journey__back" data-journey-back hidden>← Terug</button>
          <button type="button" class="sell-journey__next-btn" data-journey-next>Volgende stap <span aria-hidden="true">→</span></button>
          <button type="button" class="sell-journey__continue" data-journey-continue hidden>Bekijk demo-aanvraag <span aria-hidden="true">↗</span></button>
        </div>
      </form>
    </div>
  </div>
</section>
