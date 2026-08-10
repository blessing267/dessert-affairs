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

$site_settings = get_page_by_path('site-settings');

if ($site_settings) {
    $context['footer_text'] = get_field(
        'footer_text',
        $site_settings->ID
    );

    $context['phone_number'] = get_field(
        'phone_number',
        $site_settings->ID
    );

    $context['email_address'] = get_field(
        'email_address',
        $site_settings->ID
    );

    $context['instagram_url'] = get_field(
        'instagram_url',
        $site_settings->ID
    );

    $context['facebook_url'] = get_field(
        'facebook_url',
        $site_settings->ID
    );

    $context['opening_hours'] = get_field(
        'opening_hours',
        $site_settings->ID
    );
}

if (is_front_page()) {

    $context['hero_title'] = get_field('hero_title');
    $context['hero_text'] = get_field('hero_text');
    $context['hero_image'] = get_field('hero_image');
    $context['hero_button_text'] = get_field('hero_button_text');

    $context['catering_title'] = get_field('catering_title');
    $context['catering_text'] = get_field('catering_text');
    $context['catering_image'] = get_field('catering_image');
    $context['catering_button_text'] = get_field('catering_button_text');

    $context['testimonials_title'] = get_field('testimonials_title');
    $context['testimonials'] = [
        [
            'text' => get_field('testimonial_1_text'),
            'name' => get_field('testimonial_1_name'),
        ],
        [
            'text' => get_field('testimonial_2_text'),
            'name' => get_field('testimonial_2_name'),
        ],
        [
            'text' => get_field('testimonial_3_text'),
            'name' => get_field('testimonial_3_name'),
        ],
    ];

    $context['about_title'] = get_field('about_title');
    $context['about_text'] = get_field('about_text');

    $context['gallery_title'] = get_field('gallery_title');

    $context['gallery_images'] = [
        get_field('gallery_image_1'),
        get_field('gallery_image_2'),
        get_field('gallery_image_3'),
        get_field('gallery_image_4'),
        get_field('gallery_image_5'),
        get_field('gallery_image_6'),
    ];

    Timber\Timber::render(
        'front-page.twig',
        $context
    );

    return;
}

Timber\Timber::render('index.twig', $context);