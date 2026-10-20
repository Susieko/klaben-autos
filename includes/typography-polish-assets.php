<?php
/**
 * Klaben — final optional typography pass.
 * Loaded after page styles, without modifying any existing template or JS.
 */
if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', static function (): void {
    $relative = '/assets/css/typography-polish.css';
    $absolute = get_theme_file_path($relative);
    if (!is_file($absolute)) {
        return;
    }
    wp_enqueue_style(
        'klaben-typography-polish',
        get_theme_file_uri($relative),
        ['klaben-global'],
        (string) filemtime($absolute)
    );
}, 100);
