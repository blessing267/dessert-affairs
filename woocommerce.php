<?php

$context = Timber\Timber::context();

ob_start();

woocommerce_content();

$context['woocommerce_content'] = ob_get_clean();

Timber\Timber::render(
    'woocommerce.twig',
    $context
);