<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Require Timber.
 */
if (
    ! class_exists('Timber\\Timber') &&
    ! class_exists('Timber')
) {
    add_action('admin_notices', function (): void {
        echo '<div class="notice notice-error">';
        echo '<p><strong>Timber</strong> must be active.</p>';
        echo '</div>';
    });

    return;
}

/**
 * Tell Timber where Twig templates are located.
 */
if (class_exists('Timber\\Timber')) {
    Timber\Timber::$dirname = ['views'];
}

/**
 * Configure theme features.
 */
function dessert_affairs_theme_setup(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');

    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    register_nav_menus([
        'primary' => __(
            'Primary Menu',
            'dessert-affairs'
        ),
    ]);
}

add_action(
    'after_setup_theme',
    'dessert_affairs_theme_setup'
);

/**
 * Load the theme stylesheet.
 */
function dessert_affairs_enqueue_styles(): void
{
    $css_path = get_stylesheet_directory()
        . '/assets/css/main.css';

    if (file_exists($css_path)) {
        wp_enqueue_style(
            'dessert-affairs-main',
            get_stylesheet_directory_uri()
                . '/assets/css/main.css',
            [],
            (string) filemtime($css_path)
        );

        return;
    }

    wp_enqueue_style(
        'dessert-affairs-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );
}

add_action(
    'wp_enqueue_scripts',
    'dessert_affairs_enqueue_styles'
);