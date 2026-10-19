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

        <?php
        // One source of truth for navigation labels, destinations and active states.
        $klaben_navigation = [
            ['label' => 'Home', 'url' => home_url('/'), 'active' => is_front_page()],
            ['label' => 'Auto’s', 'url' => home_url('/autos/'), 'active' => is_page('autos')],
            ['label' => 'Auto verkopen', 'url' => home_url('/auto-verkopen/'), 'active' => is_page('auto-verkopen')],
            ['label' => 'Financiering', 'url' => home_url('/financiering/'), 'active' => is_page('financiering')],
            ['label' => 'Nieuws', 'url' => home_url('/nieuws/'), 'active' => !is_front_page() && (is_page('nieuws') || is_singular('post') || is_category() || is_home())],
        ];
        ?>
        <nav class="site-nav" id="primary-navigation" aria-label="Hoofdnavigatie">
            <?php foreach ($klaben_navigation as $item): ?>
                <a
                    class="site-nav__link<?php echo $item['active'] ? ' site-nav__link--active' : ''; ?>"
                    href="<?php echo esc_url($item['url']); ?>"
                    <?php if ($item['active']): ?>aria-current="page"<?php endif; ?>
                >
                    <span class="site-nav__wheel" aria-hidden="true">
                        <svg class="site-nav__wheel-svg" viewBox="0 0 32 32" focusable="false" aria-hidden="true">
                            <circle cx="16" cy="16" r="14" fill="#0e1823" stroke="#647f9a" stroke-width="3"/>
                            <circle cx="16" cy="16" r="10.7" fill="#1a2b3d" stroke="#bacbdc" stroke-width="1"/>
                            <g fill="none" stroke="#d4e2ef" stroke-width="1.65" stroke-linecap="round">
                                <?php foreach ([0, 72, 144, 216, 288] as $angle): ?>
                                    <path d="M14.6 12.4 L13 6.3 M17.4 12.4 L19 6.3" transform="rotate(<?php echo (int) $angle; ?> 16 16)"/>
                                <?php endforeach; ?>
                            </g>
                            <circle cx="16" cy="16" r="3.6" fill="#627f9d" stroke="#dce8f3" stroke-width="1.2"/>
                            <circle cx="16" cy="16" r="1.1" fill="#eef5fc"/>
                        </svg>
                    </span>
                    <span class="site-nav__label"><?php echo esc_html($item['label']); ?></span>
                </a>
            <?php endforeach; ?>
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