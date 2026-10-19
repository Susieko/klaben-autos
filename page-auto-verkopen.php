<?php
/**
 * KLABEN Autos — Auto verkopen.
 * Create a WordPress page with slug 'auto-verkopen'.
 * This is a fictional portfolio demo. No submissions or personal data are stored.
 */
get_header();
$status = isset($_GET['klaben_status']) && is_string($_GET['klaben_status'])
    ? sanitize_key(wp_unslash($_GET['klaben_status']))
    : '';
$hero_image = get_theme_file_uri('/assets/images/cars/mercedes.png');
?>
<section class="sell-hero">
    <div class="container sell-hero__layout">
        <div class="sell-hero__copy reveal">
            <nav class="sell-breadcrumb" aria-label="Broodkruimelnavigatie">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a><span aria-hidden="true">/</span><span aria-current="page">Auto verkopen</span>
            </nav>
            <p class="sell-eyebrow">KLABEN AUTOS &nbsp; / &nbsp; JOUW AUTO</p>
            <h1>Jouw auto.<br><span>Ons volgende verhaal.</span></h1>
            <p class="sell-hero__lead">Een auto verkopen mag helder en eenvoudig zijn. Vertel ons wat je rijdt en ontdek hoe een persoonlijke aanpak eruit kan zien.</p>
            <a class="button button--primary sell-hero__cta" href="#verkoopformulier">Start je aanvraag <span aria-hidden="true">↗</span></a>
            <p class="sell-hero__micro">Portfolio-concept · Geen daadwerkelijke taxatie of verkoop</p>
        </div>
        <div class="sell-hero__visual" aria-hidden="true">
            <span class="sell-hero__halo"></span>
            <img src="<?php echo esc_url($hero_image); ?>" alt="" fetchpriority="high">
            <span class="sell-hero__stamp"><strong>K</strong><span>THE DRIVE<br>IS PERSONAL.</span></span>
        </div>
    </div>
</section>

<section class="sell-process" aria-labelledby="sell-process-title">
    <div class="container">
        <div class="sell-section-heading">
            <div><p class="sell-eyebrow">ZO WERKT HET</p><h2 id="sell-process-title">Eenvoudig. Transparant. Persoonlijk.</h2></div>
            <p>Drie heldere stappen, zonder onnodige omwegen.</p>
        </div>
        <div class="sell-steps">
            <article class="sell-step reveal"><span class="sell-step__number">01</span><div><h3>Vertel over je auto</h3><p>Vul de belangrijkste voertuiggegevens in. Meer is niet nodig om te beginnen.</p></div></article>
            <article class="sell-step reveal"><span class="sell-step__number">02</span><div><h3>We bekijken de details</h3><p>In een echte verkoopdienst zou hier een persoonlijke beoordeling volgen.</p></div></article>
            <article class="sell-step reveal"><span class="sell-step__number">03</span><div><h3>Een duidelijk voorstel</h3><p>Geen verborgen stappen. Dit demo-project laat alleen de aanvraagervaring zien.</p></div></article>
        </div>
    </div>
</section>

<section id="verkoopformulier" class="sell-application" aria-labelledby="sell-application-title">
    <div class="container sell-application__layout">
        <div class="sell-application__main">
            <p class="sell-eyebrow">VERTEL ONS MEER</p>
            <h2 id="sell-application-title">De details maken<br><span>het verschil.</span></h2>
            <p class="sell-application__intro">Met een paar gegevens maken we een overzicht van jouw auto. Voor deze portfolio-demo worden gegevens niet verzonden of opgeslagen.</p>
            <?php if ($status === 'success'): ?>
                <div class="sell-alert sell-alert--success" role="status" tabindex="-1" id="form-status">
                    <strong>Demo-aanvraag voltooid.</strong>
                    <p>Je hebt alle stappen doorlopen. Er zijn geen gegevens opgeslagen of verstuurd.</p>
                    <a href="<?php echo esc_url(home_url('/auto-verkopen/#verkoopformulier')); ?>">Nog een keer proberen →</a>
                </div>
            <?php elseif ($status !== ''): ?>
                <div class="sell-alert sell-alert--error" role="alert" tabindex="-1" id="form-status">
                    <strong>Controleer het formulier.</strong>
                    <p>Niet alle verplichte velden waren geldig. Vul de gegevens opnieuw in en probeer het nog eens.</p>
                </div>
            <?php endif; ?>
            <form id="klaben-sell-form" class="sell-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <input type="hidden" name="action" value="klaben_demo_vehicle_submission">
                <?php wp_nonce_field('klaben_demo_vehicle_submission', 'klaben_demo_nonce'); ?>
                <div class="sell-honeypot" aria-hidden="true"><label for="sell-company">Laat dit veld leeg</label><input type="text" id="sell-company" name="klaben_company" tabindex="-1" autocomplete="off"></div>
                <fieldset class="sell-form__group">
                    <legend><span>01</span> Jouw voertuig</legend>
                    <div class="sell-fields">
                        <label class="sell-field"><span>Merk <b aria-hidden="true">*</b></span><input name="brand" id="sell-brand" type="text" maxlength="60" placeholder="Bijv. Audi" autocomplete="off" required></label>
                        <label class="sell-field"><span>Model <b aria-hidden="true">*</b></span><input name="model" id="sell-model" type="text" maxlength="80" placeholder="Bijv. A3 Sportback" required></label>
                        <label class="sell-field"><span>Bouwjaar <b aria-hidden="true">*</b></span><input name="year" id="sell-year" type="number" min="1950" max="<?php echo esc_attr((string) ((int) date('Y') + 1)); ?>" placeholder="Bijv. 2020" inputmode="numeric" required></label>
                        <label class="sell-field"><span>Kilometerstand <b aria-hidden="true">*</b></span><div class="sell-input-suffix"><input name="mileage" id="sell-mileage" type="number" min="0" max="9999999" placeholder="Bijv. 68500" inputmode="numeric" required><span>km</span></div></label>
                        <label class="sell-field"><span>Brandstof <b aria-hidden="true">*</b></span><select name="fuel" id="sell-fuel" required><option value="">Selecteer brandstof</option><option>Benzine</option><option>Diesel</option><option>Hybride</option><option>Elektrisch</option><option>Anders</option></select></label>
                        <label class="sell-field"><span>Transmissie</span><select name="transmission" id="sell-transmission"><option value="">Selecteer transmissie</option><option>Automaat</option><option>Handgeschakeld</option></select></label>
                    </div>
                </fieldset>
                <fieldset class="sell-form__group">
                    <legend><span>02</span> Extra informatie</legend>
                    <label class="sell-field"><span>Wat maakt jouw auto bijzonder? <small>(optioneel)</small></span><textarea name="notes" id="sell-notes" rows="4" maxlength="800" placeholder="Denk aan onderhoud, uitvoering of bijzonderheden..."></textarea></label>
                </fieldset>
                <fieldset class="sell-form__group">
                    <legend><span>03</span> Afronden</legend>
                    <div class="sell-demo-info"><span aria-hidden="true">ⓘ</span><p>Dit is een <strong>fictief portfolio-project</strong>. Er wordt geen taxatie uitgevoerd en je voertuig- of persoonsgegevens worden niet opgeslagen. Voor deze demo hoef je geen naam of e-mailadres in te vullen.</p></div>
                    <label class="sell-consent"><input type="checkbox" name="demo_ack" value="1" required><span>Ik begrijp dat dit uitsluitend een demo is en er geen aanvraag wordt verstuurd. <b aria-hidden="true">*</b></span></label>
                </fieldset>
                <div class="sell-submit"><button type="submit" class="button button--primary">Bekijk demo-bevestiging <span aria-hidden="true">↗</span></button><span>Verplichte velden zijn gemarkeerd met *</span></div>
            </form>
        </div>
        <aside class="sell-preview" aria-label="Jouw voertuigoverzicht">
            <div class="sell-preview__top"><p class="sell-eyebrow">LIVE VOORBEELD</p><span class="sell-preview__dot" aria-hidden="true"></span></div>
            <div class="sell-preview__image"><img src="<?php echo esc_url(get_theme_file_uri('/assets/images/cars/audi.png')); ?>" alt="Voorbeeldfoto van een Audi, niet jouw ingevoerde voertuig" loading="lazy"><span>VOORBEELDFOTO</span></div>
            <div class="sell-preview__content"><p class="sell-preview__hint">JOUW AUTO</p><h3 data-preview-title>Merk & model</h3><dl><div><dt>Bouwjaar</dt><dd data-preview-year>—</dd></div><div><dt>Kilometerstand</dt><dd data-preview-mileage>—</dd></div><div><dt>Brandstof</dt><dd data-preview-fuel>—</dd></div><div><dt>Transmissie</dt><dd data-preview-transmission>—</dd></div></dl><div class="sell-preview__progress"><div class="sell-preview__progress-meta"><span>Ingevulde voertuigvelden</span><strong data-preview-completed>0 / 5</strong></div><div class="sell-preview__progress-track" role="progressbar" aria-label="Ingevulde voertuigvelden" aria-valuemin="0" aria-valuemax="5" aria-valuenow="0" data-preview-progress><span></span></div></div><p class="sell-preview__footnote">Dit overzicht verandert wanneer je het formulier invult. De getoonde foto is ter illustratie.</p></div>
        </aside>
    </div>
</section>
<section class="sell-bottom"><div class="container sell-bottom__inner"><div><p class="sell-eyebrow">LIEVER RONDNEUZEN?</p><h2>Misschien staat jouw volgende auto al klaar.</h2></div><a class="button button--primary" href="<?php echo esc_url(home_url('/autos/')); ?>">Bekijk het aanbod <span aria-hidden="true">↗</span></a></div></section>
<?php get_footer(); ?>
