<?php

get_header();

$cars = require get_theme_file_path('/data/cars.php');

$featured_cars = array_filter(
    $cars,
    function (array $car): bool {
        return !empty($car['featured']);
    }
);

$featured_cars = array_slice($featured_cars, 0, 3);

$hero_image = get_theme_file_uri(
    '/assets/images/hero/klaben-hero.png'
);

?>

<section
    class="hero"
    style="--hero-image: url('<?php echo esc_url($hero_image); ?>');"
>
    <div class="hero__inner container">

        <div class="hero__content hero-intro">

            <p class="hero__eyebrow">
                Vertrouwd. Transparant. KLABEN.
            </p>

            <h1 class="hero__title">
                Vind jouw volgende
                <span>occasion.</span>
            </h1>

            <p class="hero__description">
                Zorgvuldig geselecteerde occasions, transparante
                informatie en auto’s waar wij zelf enthousiast van
                worden.
            </p>

            <div class="hero__actions">

                <a
                    class="button button--primary"
                    href="<?php echo esc_url(home_url('/autos/')); ?>"
                >
                    Bekijk auto’s
                </a>

                <a
                    class="button button--secondary"
                    href="#werkwijze"
                >
                    Zo werkt het

                    <span class="hero__play" aria-hidden="true">
                        ▶
                    </span>
                </a>

            </div>

        </div>

        <div
            class="hero__cards hero-cards-intro"
            aria-label="Uitgelichte auto-informatie"
        >

            <article class="hero-stat">

                <div class="hero-stat__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path d="M4 17a8 8 0 1 1 16 0"></path>
                        <path d="M12 13l3-3"></path>
                        <path d="M6 17h12"></path>
                    </svg>
                </div>

                <div>
                    <p class="hero-stat__label">
                        Kilometerstand
                    </p>

                    <p class="hero-stat__value">
                        34.250 km
                    </p>
                </div>

            </article>

            <article class="hero-stat">

                <div class="hero-stat__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3z"></path>
                        <path d="M9 12l2 2 4-5"></path>
                    </svg>
                </div>

                <div>
                    <p class="hero-stat__label">
                        Gecontroleerde historie
                    </p>

                    <p class="hero-stat__value hero-stat__value--small">
                        Onderhoud bekend
                    </p>
                </div>

            </article>

            <article class="hero-stat">

                <div class="hero-stat__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <rect x="3" y="5" width="18" height="14" rx="3"></rect>
                        <path d="M16 10h5"></path>
                        <circle cx="17" cy="12" r="1"></circle>
                    </svg>
                </div>

                <div>
                    <p class="hero-stat__label">
                        Vanaf
                    </p>

                    <p class="hero-stat__value">
                        €359
                        <span>/mnd</span>
                    </p>
                </div>

            </article>

            <article class="hero-stat">

                <div class="hero-stat__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path d="M6 3h8v18H6z"></path>
                        <path d="M8 7h4"></path>
                        <path d="M14 7h2l3 3v7a2 2 0 0 0 2 2"></path>
                    </svg>
                </div>

                <div>
                    <p class="hero-stat__label">
                        Verbruik
                    </p>

                    <p class="hero-stat__value hero-stat__value--small">
                        6,1 l/100 km
                    </p>
                </div>

            </article>

        </div>

        <div class="hero-search-wrap hero-search-intro">

            <form
                class="hero-search"
                action="<?php echo esc_url(home_url('/autos/')); ?>"
                method="get"
            >

                <label class="hero-search__field">
                    <span>Merk</span>

                    <select name="merk">
                        <option value="">Alle merken</option>
                        <option value="mercedes-benz">
                            Mercedes-Benz
                        </option>
                        <option value="bmw">BMW</option>
                        <option value="audi">Audi</option>
                        <option value="volkswagen">Volkswagen</option>
                    </select>
                </label>

                <label class="hero-search__field">
                    <span>Model</span>

                    <select name="model">
                        <option value="">Alle modellen</option>
                        <option value="cla">CLA</option>
                        <option value="a-klasse">A-Klasse</option>
                        <option value="3-serie">3 Serie</option>
                        <option value="a3">A3</option>
                    </select>
                </label>

                <label class="hero-search__field">
                    <span>Bouwjaar</span>

                    <select name="bouwjaar">
                        <option value="">Alle jaren</option>
                        <option value="2024">Vanaf 2024</option>
                        <option value="2022">Vanaf 2022</option>
                        <option value="2020">Vanaf 2020</option>
                        <option value="2018">Vanaf 2018</option>
                    </select>
                </label>

                <label class="hero-search__field">
                    <span>Budget</span>

                    <select name="budget">
                        <option value="">Elk budget</option>
                        <option value="15000">Tot €15.000</option>
                        <option value="25000">Tot €25.000</option>
                        <option value="35000">Tot €35.000</option>
                        <option value="50000">Tot €50.000</option>
                    </select>
                </label>

                <label class="hero-search__field">
                    <span>Brandstof</span>

                    <select name="brandstof">
                        <option value="">Alle brandstoffen</option>
                        <option value="benzine">Benzine</option>
                        <option value="diesel">Diesel</option>
                        <option value="hybride">Hybride</option>
                        <option value="elektrisch">Elektrisch</option>
                    </select>
                </label>

                <label class="hero-search__field">
                    <span>Transmissie</span>

                    <select name="transmissie">
                        <option value="">Alles</option>
                        <option value="automaat">Automaat</option>
                        <option value="handgeschakeld">
                            Handgeschakeld
                        </option>
                    </select>
                </label>

                <button
                    class="hero-search__button"
                    type="submit"
                >
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="M20 20l-4-4"></path>
                    </svg>

                    Zoeken
                </button>

            </form>

            <a
                class="hero-search__advanced"
                href="<?php echo esc_url(home_url('/autos/')); ?>"
            >
                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    aria-hidden="true"
                >
                    <path d="M4 6h16"></path>
                    <path d="M4 12h16"></path>
                    <path d="M4 18h16"></path>
                    <circle cx="9" cy="6" r="2"></circle>
                    <circle cx="15" cy="12" r="2"></circle>
                    <circle cx="7" cy="18" r="2"></circle>
                </svg>

                Uitgebreid zoeken
            </a>

        </div>

    </div>
</section>

<section
    class="trust-strip reveal"
    id="werkwijze"
    aria-label="Waarom kiezen voor KLABEN Autos"
>
    <div class="container">

        <div class="trust-strip__inner">

            <article class="trust-item">

                <div class="trust-item__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path d="M12 3l7 3v5c0 5-3 8-7 10-4-2-7-5-7-10V6l7-3z"></path>
                        <path d="M8.5 12l2.2 2.2L15.8 9"></path>
                    </svg>
                </div>

                <div>
                    <h2 class="trust-item__title">
                        150-puntencheck
                    </h2>

                    <p class="trust-item__text">
                        Zorgvuldig nagekeken
                    </p>
                </div>

            </article>

            <article class="trust-item">

                <div class="trust-item__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path d="M9 4h6"></path>
                        <path d="M9 2h6v4H9z"></path>
                        <path d="M6 4h12v17H6z"></path>
                        <path d="M9 11l2 2 4-5"></path>
                    </svg>
                </div>

                <div>
                    <h2 class="trust-item__title">
                        Historie gecontroleerd
                    </h2>

                    <p class="trust-item__text">
                        Duidelijke voertuiggegevens
                    </p>
                </div>

            </article>

            <article class="trust-item">

                <div class="trust-item__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <circle cx="12" cy="13" r="8"></circle>
                        <path d="M12 9v4l3 2"></path>
                        <path d="M9 3h6"></path>
                        <path d="M12 3v2"></path>
                    </svg>
                </div>

                <div>
                    <h2 class="trust-item__title">
                        7 dagen bedenktijd
                    </h2>

                    <p class="trust-item__text">
                        Rustig beslissen
                    </p>
                </div>

            </article>

            <article class="trust-item">

                <div class="trust-item__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <rect x="3" y="5" width="18" height="14" rx="3"></rect>
                        <path d="M16 10h5"></path>
                        <circle cx="17" cy="12" r="1"></circle>
                    </svg>
                </div>

                <div>
                    <h2 class="trust-item__title">
                        Financiering mogelijk
                    </h2>

                    <p class="trust-item__text">
                        Passend bij jouw budget
                    </p>
                </div>

            </article>

            <article class="trust-item">

                <div class="trust-item__icon">
                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        aria-hidden="true"
                    >
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M17 11l2 2 3-4"></path>
                    </svg>
                </div>

                <div>
                    <h2 class="trust-item__title">
                        Door ons geselecteerd
                    </h2>

                    <p class="trust-item__text">
                        Alleen onze favorieten
                    </p>
                </div>

            </article>

        </div>

    </div>
</section>

<section class="featured-cars reveal" id="uitgelicht">

    <div class="container">

        <div class="section-heading">

            <div>
                <p class="section-heading__eyebrow">
                    Onze selectie
                </p>

                <h2 class="section-heading__title">
                    Uitgelichte auto’s
                </h2>

                <p class="section-heading__description">
                    Een kleine selectie auto’s waar wij zelf
                    enthousiast van worden.
                </p>
            </div>

            <a
                class="section-heading__link"
                href="<?php echo esc_url(home_url('/autos/')); ?>"
            >
                Bekijk alle auto’s

                <span aria-hidden="true">→</span>
            </a>

        </div>

        <div
    class="car-filters"
    aria-label="Filter uitgelichte auto’s"
>
    <button
        class="car-filter is-active"
        type="button"
        data-filter="all"
    >
        Alle
    </button>

    <button
        class="car-filter"
        type="button"
        data-filter="sedan"
    >
        Sedan
    </button>

    <button
        class="car-filter"
        type="button"
        data-filter="hatchback"
    >
        Hatchback
    </button>

    <button
        class="car-filter"
        type="button"
        data-filter="cabriolet"
    >
        Cabriolet
    </button>
</div>

        <div class="car-grid">

    <?php foreach ($featured_cars as $index => $car): ?>

        <?php
        $card_delay = $index * 110;

        require get_theme_file_path(
            '/includes/car-card.php'
        );
        ?>

    <?php endforeach; ?>

</div>
    </div>

</section>

<section class="finance-section reveal" id="financiering">
    <div class="container">

        <div class="finance-panel">

            <div class="finance-panel__content">

                <p class="section-heading__eyebrow">
                    Flexibel onderweg
                </p>

                <h2 class="finance-panel__title">
                    Financiering die bij je past.
                </h2>

                <p class="finance-panel__description">
                    Bekijk rustig wat een auto ongeveer per maand kost
                    en kies een bedrag dat past bij jouw situatie.
                </p>

                <ul class="finance-panel__benefits">

                    <li>
                        <span aria-hidden="true">✓</span>
                        Flexibele looptijden
                    </li>

                    <li>
                        <span aria-hidden="true">✓</span>
                        Duidelijke maandbedragen
                    </li>

                    <li>
                        <span aria-hidden="true">✓</span>
                        Geen verborgen verrassingen
                    </li>

                </ul>

                <a
                    class="button button--primary"
                    href="<?php echo esc_url(
                        home_url('/financiering/')
                    ); ?>"
                >
                    Bekijk de mogelijkheden
                </a>

            </div>

            <div class="finance-calculator">

                <div class="finance-calculator__header">
                    <div>
                        <p class="finance-calculator__eyebrow">
                            Rekenvoorbeeld
                        </p>

                        <h3>
                            Jouw maandbedrag
                        </h3>
                    </div>

                    <div class="finance-calculator__icon">
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <rect
                                x="3"
                                y="5"
                                width="18"
                                height="14"
                                rx="3"
                            ></rect>

                            <path d="M16 10h5"></path>
                            <circle cx="17" cy="12" r="1"></circle>
                        </svg>
                    </div>
                </div>

                <div class="finance-calculator__row">
                    <span>Aanschafprijs</span>
                    <strong>€ 32.950</strong>
                </div>

                <div class="finance-calculator__row">
                    <span>Aanbetaling</span>
                    <strong>€ 5.000</strong>
                </div>

                <div class="finance-calculator__row">
                    <span>Looptijd</span>
                    <strong>60 maanden</strong>
                </div>

                <div class="finance-calculator__result">
                    <span>Indicatie vanaf</span>

                    <p>
                        € 459
                        <small>per maand</small>
                    </p>
                </div>

                <p class="finance-calculator__notice">
                    Dit is een fictief rekenvoorbeeld voor het
                    KLABEN Autos-concept.
                </p>

            </div>

        </div>

    </div>
</section>



<?php get_footer(); ?>