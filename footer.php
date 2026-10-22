</main>

<footer class="site-footer">

    <div class="site-footer__top">
        <div class="container site-footer__grid">

            <div class="site-footer__brand">

                <a
                    class="site-footer__logo-link"
                    href="<?php echo esc_url(home_url('/')); ?>"
                    aria-label="Ga naar de homepage van KLABEN Autos"
                >
                    <img
                        class="site-footer__logo"
                        src="<?php echo esc_url(
                            get_theme_file_uri(
                                '/assets/images/logo/klaben-logo.webp'
                            )
                        ); ?>"
                        alt="KLABEN Autos"
                    >
                </a>

                <p class="site-footer__intro">
                    Een persoonlijk automotive concept met zorgvuldig
                    geselecteerde occasions, klassieke iconen en auto’s
                    waar wij zelf enthousiast van worden.
                </p>

                <p class="site-footer__disclaimer">
                    KLABEN Autos is een fictief portfolio-project en
                    verkoopt geen echte voertuigen.
                </p>

            </div>

            <div class="site-footer__column">

                <h2 class="site-footer__heading">
                    Ontdek
                </h2>

                <nav
                    class="site-footer__nav"
                    aria-label="Footer navigatie"
                >
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        Home
                    </a>

                    <a href="<?php echo esc_url(home_url('/autos/')); ?>">
                        Alle auto’s
                    </a>

                    <a href="<?php echo esc_url(
                        home_url('/auto-verkopen/')
                    ); ?>">
                        Auto verkopen
                    </a>

                    <a href="<?php echo esc_url(
                        home_url('/financiering/')
                    ); ?>">
                        Financiering
                    </a>
                </nav>

            </div>

            <div class="site-footer__column">

                <h2 class="site-footer__heading">
                    Onze selectie
                </h2>

                <nav
                    class="site-footer__nav"
                    aria-label="Autocategorieën"
                >
                    <a href="<?php echo esc_url(
                        add_query_arg(
                            'carrosserie',
                            'sedan',
                            home_url('/autos/')
                        )
                    ); ?>">
                        Sedans
                    </a>

                    <a href="<?php echo esc_url(
                        add_query_arg(
                            'carrosserie',
                            'hatchback',
                            home_url('/autos/')
                        )
                    ); ?>">
                        Hatchbacks
                    </a>

                    <a href="<?php echo esc_url(
                        add_query_arg(
                            'carrosserie',
                            'cabriolet',
                            home_url('/autos/')
                        )
                    ); ?>">
                        Cabriolets
                    </a>

                    <a href="<?php echo esc_url(
                        home_url('/#uitgelicht')
                    ); ?>">
                        Uitgelichte auto’s
                    </a>
                </nav>

            </div>

            <div class="site-footer__column site-footer__contact">

                <h2 class="site-footer__heading">
                    KLABEN Autos
                </h2>

                <p>
                    Tilburg, Nederland
                </p>

                <div class="site-footer__concept" aria-label="Dit is een portfolio-demo">
                    <span class="site-footer__concept-mark" aria-hidden="true">K</span>
                    <span><strong>CONCEPT SHOWROOM</strong><small>Fictief portfolio-project · Geen echte verkoop</small></span>
                </div>

            </div>

            </div>

        </div>
    </div>

    <div class="site-footer__bottom">
        <div class="container site-footer__bottom-inner">

            <p>
                © <?php echo esc_html(date('Y')); ?>
                KLABEN Autos
            </p>

            <div class="site-footer__legal">

                <span>
                    Ontworpen als persoonlijk conceptproject
                </span>
            </div>

        </div>
    </div>

</footer>

<?php wp_footer(); ?>

</body>
</html>