<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// If Timber isn't available, fall back to classic WP templates
if ( ! class_exists('Timber\\Timber') && ! class_exists('Timber') ) {
  get_header();
  echo '<main><h1>Timber plugin not active</h1></main>';
  get_footer();
  return;
}

$context = Timber\Timber::context();

// Front page (static Home)
if ( is_front_page() ) {
  $context['post'] = new Timber\Post();
  Timber\Timber::render( 'front-page.twig', $context );
  return;
}

// Single product (single-product.twig)
if ( is_singular( 'product' ) ) {
  $context['post'] = new Timber\Post();
  Timber\Timber::render( 'single-product.twig', $context );
  return;
}

// Product archive (archive-product.twig)
if ( is_post_type_archive( 'product' ) ) {
  $context['posts'] = Timber\Timber::get_posts();
  Timber\Timber::render( 'archive-product.twig', $context );
  return;
}

// Fallback: render index.twig
$context['post']  = new Timber\Post();
$context['posts'] = Timber\Timber::get_posts();
Timber\Timber::render( 'index.twig', $context );
