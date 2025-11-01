<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Require Timber plugin to be active. If it's not, show an admin notice and stop.
 */
if ( ! class_exists( 'Timber\\Timber' ) && ! class_exists( 'Timber' ) ) {
  add_action( 'admin_notices', function() {
    echo '<div class="notice notice-error"><p><strong>Timber</strong> plugin is not active. Please activate it.</p></div>';
  } );
  return;
}

/**
 * Timber is available. Do NOT call Timber::init() – the plugin handles initialization.
 * Tell Timber to look for templates inside the "views" folder (optional but helpful).
 */
if ( class_exists( 'Timber\\Timber' ) ) {
  // Ensure Timber will search your theme's views/ directory
  \Timber\Timber::$dirname = array( 'views' );
}

/**
 * Theme setup: supports, menus, etc.
 */
add_action( 'after_setup_theme', function() {
  add_theme_support( 'title-tag' );
  add_theme_support( 'post-thumbnails' );
  // Register a primary menu (optional)
  register_nav_menus( [
    'primary' => __( 'Primary Menu', 'dessert-affairs-simple' ),
  ] );
} );

/**
 * Enqueue styles: prefer compiled assets/css/main.css if present, else fallback to style.css
 */
add_action( 'wp_enqueue_scripts', function() {
  $css_path = get_stylesheet_directory() . '/assets/css/main.css';
  if ( file_exists( $css_path ) ) {
    $ver = filemtime( $css_path );
    wp_enqueue_style( 'theme-main', get_stylesheet_directory_uri() . '/assets/css/main.css', [], $ver );
  } else {
    wp_enqueue_style( 'theme-style', get_stylesheet_uri(), [], wp_get_theme()->get( 'Version' ) );
  }
} );

