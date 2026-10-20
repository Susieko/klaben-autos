<?php
/**
 * Homepage-only, additive mouse accent. Does not touch theme templates.
 */
add_action('wp_enqueue_scripts', static function (): void {
    if (!is_front_page()) {
        return;
    }
    $css = '/assets/css/homepage-wheel-pointer.css';
    $js  = '/assets/js/homepage-wheel-pointer.js';
    wp_enqueue_style(
        'klaben-homepage-wheel-pointer',
        get_theme_file_uri($css),
        [],
        (string) filemtime(get_theme_file_path($css))
    );
    wp_enqueue_script(
        'klaben-homepage-wheel-pointer',
        get_theme_file_uri($js),
        [],
        (string) filemtime(get_theme_file_path($js)),
        true
    );
}, 30);
