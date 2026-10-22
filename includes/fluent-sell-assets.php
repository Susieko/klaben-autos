<?php
/**
 * Fluent Forms Free integration for the KLABEN selling journey.
 * The native vehicle preview and wizard remain in assets/js/sell.js.
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', static function (): void {
    if (!is_page('auto-verkopen')) {
        return;
    }

    // The original review script targets the old native form markup.
    // Keep its CSS and replace only its behaviour when Fluent Form #4 is used.
    wp_dequeue_script('klaben-sell-review');

    $css = '/assets/css/fluent-sell.css';
    $js  = '/assets/js/fluent-sell-review.js';
    wp_enqueue_style(
        'klaben-fluent-sell',
        get_theme_file_uri($css),
        ['klaben-sell-review'],
        (string) filemtime(get_theme_file_path($css))
    );
    wp_enqueue_script(
        'klaben-fluent-sell-review',
        get_theme_file_uri($js),
        ['klaben-sell'],
        (string) filemtime(get_theme_file_path($js)),
        true
    );
}, 100);
