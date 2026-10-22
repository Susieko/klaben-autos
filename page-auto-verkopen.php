<?php
/**
 * KLABEN Autos — Auto verkopen.
 * Create a WordPress page with slug 'auto-verkopen'.
 * Fictional portfolio demo. Fluent Forms stores explicitly submitted local test entries.
 */
get_header();
$status = isset($_GET['klaben_status']) && is_string($_GET['klaben_status'])
    ? sanitize_key(wp_unslash($_GET['klaben_status']))
    : '';
$hero_image = get_theme_file_uri('/assets/images/cars/mercedes.webp');
?>
<section class="sell-hero sell-hero--plate" aria-labelledby="sell-hero-title">
    <div class="container sell-hero__layout sell-hero-v2__layout">
        <div class="sell-hero__copy sell-hero-v2__copy">
            <nav class="sell-breadcrumb" aria-label="Broodkruimelnavigatie">
                <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                <span aria-hidden="true">/</span>
                <span aria-current="page">Auto verkopen</span>
            </nav>
            <p class="sell-eyebrow sell-hero-v2__eyebrow">EENVOUDIG VERKOPEN · KLABEN AUTOS</p>
            <h1 id="sell-hero-title">Jouw auto<br><span>Onze interesse</span></h1>
            <p class="sell-hero__lead">Benieuwd naar de mogelijkheden voor jouw auto? Begin met je kenteken en vertel ons daarna wat meer over jouw voertuig.</p>
            <div class="sell-hero-v2__benefits" aria-label="Voordelen van de aanpak">
                <span><svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.65"><path d="M12 3 20 7v5c0 5-4 8-8 9-4-1-8-4-8-9V7l8-4Z"/><path d="m9 12 2 2 4-4"/></svg> Helder proces</span>
                <span><svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.65"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l4 2"/></svg> In jouw tempo</span>
            </div>
            <a class="sell-hero-v2__manual" href="#verkoopformulier">Of vul direct je gegevens in <span aria-hidden="true">↘</span></a>
            <p class="sell-hero__micro">Fictief portfolio-concept · Geen live kentekencheck of taxatie</p>
        </div>
        <div class="sell-hero-v2__right">
            <div class="sell-plate-card" aria-labelledby="sell-plate-title">
                <div class="sell-plate-card__top">
                    <span class="sell-plate-card__step"><span aria-hidden="true" class="sell-plate-card__step-dot"></span> STAP 01 / JOUW VOERTUIG</span>
                    <span class="sell-plate-card__scan" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M7 3H5a2 2 0 0 0-2 2v2m14-4h2a2 2 0 0 1 2 2v2M3 17v2a2 2 0 0 0 2 2h2m14-4v2a2 2 0 0 1-2 2h-2M5 12h14M8 9h8M8 15h8"/></svg></span>
                </div>
                <h2 id="sell-plate-title">Start met je kenteken.</h2>
                <p class="sell-plate-card__lead">Een goed vertrekpunt voor een persoonlijke waardebepaling.</p>
                <form class="sell-plate-card__form" id="sell-plate-form" novalidate>
                    <label for="sell-license">Kenteken</label>
                    <div class="sell-license-field">
                        <span class="sell-license-field__eu" aria-hidden="true"><span class="sell-license-field__stars">✦</span><strong>NL</strong></span>
                        <input id="sell-license" name="kenteken" type="text" placeholder="AB-123-C" maxlength="9" spellcheck="false" autocomplete="off" autocapitalize="characters" aria-describedby="sell-license-hint sell-license-feedback" required>
                    </div>
                    <p class="sell-plate-card__hint" id="sell-license-hint">Voorbeeld: AB-123-C · Ook zonder streepjes</p>
                    <button class="sell-plate-card__submit" type="submit">Ga verder met kenteken <span aria-hidden="true">→</span></button>
                    <div class="sell-plate-card__feedback" id="sell-license-feedback" aria-live="polite" role="status"></div>
                </form>
                <div class="sell-plate-card__footer"><span aria-hidden="true">◎</span> Dit is een demo. We controleren alleen de opmaak; er worden geen voertuiggegevens opgehaald of kentekens opgeslagen.</div>
            </div>
            <div class="sell-hero-v2__under"><span aria-hidden="true">↗</span> VAN KENTEKEN NAAR HELDER INZICHT</div>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/sell-journey'); ?>

<section id="verkoopformulier" class="sell-application" aria-labelledby="sell-application-title">
    <div class="container sell-application__layout">
        <div class="sell-application__main">
            <p class="sell-eyebrow">VERTEL ONS MEER</p>
            <h2 id="sell-application-title">De details maken<br><span>het verschil.</span></h2>
            <p class="sell-application__intro">Met een paar gegevens maken we een overzicht van jouw auto. Dit is een fictieve portfolio-demo. Verzonden testaanvragen worden opgeslagen in de lokale WordPress-database; er vindt geen echte taxatie plaats.</p>
            <div id="klaben-fluent-sell" class="klaben-fluent-sell">
                <?php get_template_part('template-parts/sell-review'); ?>
                <?php if (shortcode_exists('fluentform')): ?>
                    <?php echo do_shortcode('[fluentform id="4"]'); ?>
                <?php else: ?>
                    <p class="klaben-form-unavailable" role="status">De demonstratieaanvraag is momenteel niet beschikbaar.</p>
                <?php endif; ?>
            </div>
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
