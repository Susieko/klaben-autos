<?php
/** Only load the final-review enhancement on the Auto verkopen page. */
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', static function (): void {
    if (!is_page('auto-verkopen')) {
        return;
    }

    $style_path = '/assets/css/sell-review.css';
    $script_path = '/assets/js/sell-review.js';

    if (!is_file(get_theme_file_path($style_path)) || !is_file(get_theme_file_path($script_path))) {
        return;
    }

    wp_enqueue_style(
        'klaben-sell-review',
        get_theme_file_uri($style_path),
        ['klaben-sell'],
        (string) filemtime(get_theme_file_path($style_path))
    );
    wp_enqueue_script(
        'klaben-sell-review',
        get_theme_file_uri($script_path),
        ['klaben-sell'],
        (string) filemtime(get_theme_file_path($script_path)),
        true
    );
}, 30);
