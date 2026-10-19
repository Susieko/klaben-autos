<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<header class="site-header">
    <div class="site-header__inner container">

        <a
            class="site-brand"
            href="<?php echo esc_url(home_url('/')); ?>"
            aria-label="Ga naar de homepage van KLABEN Autos"
        >
            <img
                class="site-brand__logo"
                src="<?php echo esc_url(
                    get_theme_file_uri('/assets/images/logo/klaben-logo.png')
                ); ?>"
                alt="KLABEN Autos"
            >
        </a>

        <button
            class="menu-toggle"
            type="button"
            aria-expanded="false"
            aria-controls="primary-navigation"
            aria-label="Open navigatie"
        >
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav
            class="site-nav"
            id="primary-navigation"
            aria-label="Hoofdnavigatie"
        >
            <a
                class="site-nav__link <?php echo is_front_page()
                    ? 'site-nav__link--active'
                    : ''; ?>"
                href="<?php echo esc_url(home_url('/')); ?>"
            >
                Home
            </a>

            <a
                class="site-nav__link <?php echo is_page('autos') ? 'site-nav__link--active' : ''; ?>"
                href="<?php echo esc_url(home_url('/autos/')); ?>"
            >
                Auto’s

            </a>

            <a
                class="site-nav__link <?php echo is_page('auto-verkopen') ? 'site-nav__link--active' : ''; ?>"
                href="<?php echo esc_url(home_url('/auto-verkopen/')); ?>"
            >
                Auto verkopen
            </a>

            <a
                class="site-nav__link <?php echo is_page('financiering') ? 'site-nav__link--active' : ''; ?>"
                href="<?php echo esc_url(home_url('/financiering/')); ?>"
            >
                Financiering
            </a>

            <a
                class="site-nav__link <?php echo (is_page('nieuws') || is_singular('post')) ? 'site-nav__link--active' : ''; ?>"
                href="<?php echo esc_url(home_url('/nieuws/')); ?>"
            >
                Nieuws
            </a>
        </nav>

        <a
            class="button button--primary site-header__button"
            href="<?php echo esc_url(home_url('/auto-verkopen/')); ?>"
        >
            Plaats je auto
        </a>

    </div>
</header>

<main class="site-main">