<?php

if (!defined('ABSPATH')) {
    exit;
}

if (
    ! class_exists('Timber\\Timber') &&
    ! class_exists('Timber')
) {
    get_header();

    echo '<main class="container">';
    echo '<h1>Timber plugin is not active.</h1>';
    echo '</main>';

    get_footer();
    return;
}

$context = Timber\Timber::context();
$context['post'] = new Timber\Post();
$context['posts'] = Timber\Timber::get_posts();

if (is_front_page()) {

    $context['hero_title'] = get_field('hero_title');
    $context['hero_text'] = get_field('hero_text');
    $context['hero_image'] = get_field('hero_image');
    $context['hero_button_text'] = get_field(
        'hero_button_text'
    );

    $context['about_title'] = get_field('about_title');
    $context['about_text'] = get_field('about_text');

    Timber\Timber::render(
        'front-page.twig',
        $context
    );

    return;
}

Timber\Timber::render('index.twig', $context);