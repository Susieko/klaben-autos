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
                                '/assets/images/logo/klaben-logo.png'
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
                            'type',
                            'sedan',
                            home_url('/autos/')
                        )
                    ); ?>">
                        Sedans
                    </a>

                    <a href="<?php echo esc_url(
                        add_query_arg(
                            'type',
                            'hatchback',
                            home_url('/autos/')
                        )
                    ); ?>">
                        Hatchbacks
                    </a>

                    <a href="<?php echo esc_url(
                        add_query_arg(
                            'type',
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

                <a href="mailto:info@klaben-autos.nl">
                    info@klaben-autos.nl
                </a>

                <div class="site-footer__socials">

                    <a
                        href="#"
                        aria-label="Instagram"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <rect
                                x="3"
                                y="3"
                                width="18"
                                height="18"
                                rx="5"
                            ></rect>

                            <circle
                                cx="12"
                                cy="12"
                                r="4"
                            ></circle>

                            <circle
                                cx="17.5"
                                cy="6.5"
                                r="1"
                                fill="currentColor"
                                stroke="none"
                            ></circle>
                        </svg>
                    </a>

                    <a
                        href="#"
                        aria-label="Facebook"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                d="M14 8h3V4h-3c-3 0-5 2-5 5v3H6v4h3v5h4v-5h3l1-4h-4V9c0-.7.3-1 1-1z"
                            ></path>
                        </svg>
                    </a>

                    <a
                        href="#"
                        aria-label="Pinterest"
                    >
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            ></circle>

                            <path
                                d="M9.5 18l1.2-5.2c-.8-1.4-.1-4.3 2.1-4.3 1.6 0 2.2 1.2 2.2 2.6 0 1.6-1 4-2.5 4-.8 0-1.4-.7-1.2-1.5"
                            ></path>
                        </svg>
                    </a>

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
                <a href="#">
                    Privacy
                </a>

                <a href="#">
                    Voorwaarden
                </a>

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