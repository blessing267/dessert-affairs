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
 * Make Site Settings available to every Timber template.
 */
function dessert_affairs_add_site_settings_to_timber_context($context)
{
    if (!function_exists('get_field')) {
        return $context;
    }

    $site_settings = get_page_by_path('site-settings');

    if (!$site_settings) {
        return $context;
    }

    $settings_id = $site_settings->ID;

    // Shared footer and social links.
    $fields = [
        'footer_text',
        'phone_number',
        'email_address',
        'instagram_url',
        'facebook_url',
        'tiktok_url',
        'opening_hours',
    ];

    foreach ($fields as $field) {
        $context[$field] = get_field($field, $settings_id);
    }

    // Background music settings.
    $context['enable_background_music'] = (bool) get_field(
        'enable_background_music',
        $settings_id
    );

    $music = get_field('background_music', $settings_id);

    if (is_array($music) && !empty($music['url'])) {
        $context['background_music_url'] = $music['url'];
    } elseif (is_numeric($music)) {
        $context['background_music_url'] =
            wp_get_attachment_url((int) $music) ?: '';
    } elseif (is_string($music)) {
        $context['background_music_url'] = $music;
    } else {
        $context['background_music_url'] = '';
    }

    return $context;
}

add_filter(
    'timber/context',
    'dessert_affairs_add_site_settings_to_timber_context'
);

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
 * Remove the default WooCommerce sidebar.
 */
remove_action(
    'woocommerce_sidebar',
    'woocommerce_get_sidebar',
    10
);

add_filter( 'woocommerce_cart_needs_shipping', '__return_false' );

/**
 * Display product category navigation
 * on the Shop and category archive pages.
 */
function dessert_affairs_shop_categories(): void
{
    if (
        ! is_shop() &&
        ! is_product_category()
    ) {
        return;
    }

    $categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'exclude'    => [
            get_option('default_product_cat'),
        ],
    ]);

    if (
        is_wp_error($categories) ||
        empty($categories)
    ) {
        return;
    }

    $current_category = null;

    if (is_product_category()) {
        $current_category = get_queried_object();
    }

    echo '<nav class="shop-categories">';

    $all_class = is_shop()
        ? ' class="active"'
        : '';

    echo '<a'
        . $all_class
        . ' href="'
        . esc_url(wc_get_page_permalink('shop'))
        . '">All Desserts</a>';

    foreach ($categories as $category) {

        $link = get_term_link($category);

        if (is_wp_error($link)) {
            continue;
        }

        $active_class = (
            $current_category &&
            (int) $current_category->term_id ===
            (int) $category->term_id
        )
            ? ' class="active"'
            : '';

        echo '<a'
            . $active_class
            . ' href="'
            . esc_url($link)
            . '">'
            . esc_html($category->name)
            . '</a>';
    }

    echo '</nav>';
}

add_action(
    'woocommerce_before_shop_loop',
    'dessert_affairs_shop_categories',
    15
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

function dessert_affairs_enqueue_scripts(): void
{
    $script_path = get_stylesheet_directory()
        . '/assets/js/navigation.js';

    if (file_exists($script_path)) {
        wp_enqueue_script(
            'dessert-affairs-navigation',
            get_stylesheet_directory_uri()
                . '/assets/js/navigation.js',
            [],
            (string) filemtime($script_path),
            true
        );
    }
}

add_action(
    'wp_enqueue_scripts',
    'dessert_affairs_enqueue_scripts'
);

/**
 * Display WhatsApp button on the WooCommerce
 * Order Received / Thank You page.
 */
add_action( 'woocommerce_thankyou', 'dessert_affairs_whatsapp_order_button', 20 );


function dessert_affairs_whatsapp_order_button($order_id) {

    if (!$order_id) {
        return;
    }

    $order = wc_get_order($order_id);

    if (!$order) {
        return;
    }

    $whatsapp_number = '2348114643319';

    // Identify whether the optional delivery fee was added.
    $delivery_included = false;

    foreach ($order->get_items('fee') as $fee) {
        if (
            $fee->get_name() === 'Delivery fee' &&
            (float) $fee->get_total() > 0
        ) {
            $delivery_included = true;
            break;
        }
    }

    // Build the product list.
    $items = [];

    foreach ($order->get_items('line_item') as $item) {
        $items[] = sprintf(
            '%s x %d - %s',
            $item->get_name(),
            $item->get_quantity(),
            wp_strip_all_tags(
                wc_price(
                    $item->get_total(),
                    ['currency' => $order->get_currency()]
                )
            )
        );
    }

    $items_text = !empty($items)
        ? implode("\n", $items)
        : 'No items found';

    // Prefer the formatted billing name.
    $customer_name = trim(
        $order->get_billing_first_name() . ' ' .
        $order->get_billing_last_name()
    );

    $customer_phone = $order->get_billing_phone();
    $customer_email = $order->get_billing_email();

    // Include the delivery address only when delivery is selected.
    $delivery_address = '';

    if ($delivery_included) {
        $delivery_address = $order->get_formatted_shipping_address();

        if (!$delivery_address) {
            $delivery_address = $order->get_formatted_billing_address();
        }

        $delivery_address = $delivery_address
            ? wp_strip_all_tags(
                str_replace('<br/>', ', ', $delivery_address)
            )
            : 'Not provided';
    }

    // Use the gateway transaction reference when available.
    $payment_reference = $order->get_transaction_id();

    $payment_reference = $payment_reference
        ? $payment_reference
        : 'Not provided';

    $order_note = $order->get_customer_note();

    $order_note = $order_note
        ? $order_note
        : 'None';

    $amount = wp_strip_all_tags(
        wc_price(
            $order->get_total(),
            ['currency' => $order->get_currency()]
        )
    );

    // Be accurate: an unpaid order must not claim payment succeeded.
    $is_paid = $order->is_paid();

    $message_lines = [
        'Hello Dessert Affairs,',
        '',
        $is_paid
            ? (
                $delivery_included
                    ? 'I have successfully paid for my order and selected delivery.'
                    : 'I have successfully paid for my order and would like to arrange how I will receive it.'
            )
            : 'I have placed my order and would like to confirm the arrangements.',
        '',
        'Order number: ' . $order->get_order_number(),
        'Name: ' . ($customer_name ?: 'Not provided'),
        'Phone: ' . ($customer_phone ?: 'Not provided'),
        'Email: ' . ($customer_email ?: 'Not provided'),
        'Amount: ' . $amount,
        'Payment reference: ' . $payment_reference,
        '',
        'Items:',
        $items_text,
        '',
        'Order note: ' . $order_note,
    ];

    if ($delivery_included) {
        $message_lines[] = '';
        $message_lines[] = 'Delivery address: ' . $delivery_address;
        $message_lines[] = '';
        $message_lines[] =
            'Delivery information: I understand that delivery takes 1-2 working days.';
    }

    $message_lines[] = '';
    $message_lines[] = 'Please confirm my order. Thank you.';

    $message = implode("\n", $message_lines);

    $whatsapp_url = 'https://wa.me/' . $whatsapp_number
        . '?text=' . rawurlencode($message);

    ?>

    <section class="whatsapp-order">
        <h2>Confirm your order</h2>

        <p>
            <?php
            echo $delivery_included
                ? 'Your order includes the delivery fee. Send your order details to us on WhatsApp.'
                : 'Your order does not include the delivery fee. Contact us on WhatsApp to arrange collection or delivery.';
            ?>
        </p>

        <a
            href="<?php echo esc_url($whatsapp_url); ?>"
            class="button whatsapp-order__button"
            target="_blank"
            rel="noopener noreferrer"
        >
            Confirm on WhatsApp
        </a>
    </section>

    <?php
}



/**
 * Optional delivery fee settings and checkout option.
 */

/**
 * Add an editable delivery fee field to WooCommerce settings.
 * Location: WooCommerce > Settings > General.
 */
add_filter(
    'woocommerce_get_settings_general',
    'dessert_affairs_delivery_fee_admin_setting'
);

function dessert_affairs_delivery_fee_admin_setting($settings) {

    $settings[] = [
        'title' => 'Optional Delivery Fee',
        'type'  => 'title',
        'id'    => 'dessert_affairs_delivery_fee_options',
        'desc'  => 'Configure the optional delivery fee shown at checkout.',
    ];

    $settings[] = [
        'title'             => 'Delivery fee amount',
        'desc'              => 'Amount charged when a customer selects delivery.',
        'id'                => 'dessert_affairs_delivery_fee_amount',
        'type'              => 'number',
        'default'           => '1500',
        'css'               => 'width:100px;',
        'custom_attributes' => [
            'min'  => '0',
            'step' => '0.01',
        ],
    ];

    $settings[] = [
        'type' => 'sectionend',
        'id'   => 'dessert_affairs_delivery_fee_options',
    ];

    return $settings;
}


/**
 * Display the optional delivery checkbox at checkout.
 */
add_action(
    'woocommerce_review_order_before_payment',
    'dessert_affairs_display_delivery_fee_option'
);

function dessert_affairs_display_delivery_fee_option() {

    if (!WC()->session) {
        return;
    }

    $fee_amount = (float) get_option(
        'dessert_affairs_delivery_fee_amount',
        1500
    );

    $selected = WC()->session->get(
        'dessert_affairs_add_delivery_fee',
        'no'
    );

    echo '<div class="dessert-affairs-delivery-option">';

    echo '<label for="dessert_affairs_add_delivery_fee">';

    echo '<input type="checkbox"'
        . ' id="dessert_affairs_add_delivery_fee"'
        . ' name="dessert_affairs_add_delivery_fee"'
        . ' value="yes"'
        . checked($selected, 'yes', false)
        . ' /> ';

    echo 'Include delivery fee ('
        . wp_kses_post(wc_price($fee_amount))
        . ')';

    echo '</label>';

    echo '</div>';
}


/**
 * Save the customer's selection during checkout updates.
 */
add_action(
    'woocommerce_checkout_update_order_review',
    'dessert_affairs_save_delivery_fee_selection'
);

function dessert_affairs_save_delivery_fee_selection($post_data) {

    if (!WC()->session) {
        return;
    }

    parse_str($post_data, $checkout_data);

    $selected = isset(
        $checkout_data['dessert_affairs_add_delivery_fee']
    ) && $checkout_data['dessert_affairs_add_delivery_fee'] === 'yes'
        ? 'yes'
        : 'no';

    WC()->session->set(
        'dessert_affairs_add_delivery_fee',
        $selected
    );
}


/**
 * Add the delivery fee to the cart only when selected.
 */
add_action(
    'woocommerce_cart_calculate_fees',
    'dessert_affairs_apply_optional_delivery_fee'
);

function dessert_affairs_apply_optional_delivery_fee($cart) {

    if (is_admin() && !defined('DOING_AJAX')) {
        return;
    }

    if (!WC()->session) {
        return;
    }

    if (
        WC()->session->get(
            'dessert_affairs_add_delivery_fee',
            'no'
        ) !== 'yes'
    ) {
        return;
    }

    $fee_amount = (float) get_option(
        'dessert_affairs_delivery_fee_amount',
        1500
    );

    if ($fee_amount <= 0) {
        return;
    }

    $cart->add_fee(
        'Delivery fee',
        $fee_amount,
        false
    );
}


/**
 * Refresh the checkout total when the checkbox changes.
 */
add_action(
    'wp_footer',
    'dessert_affairs_delivery_fee_checkout_script'
);

function dessert_affairs_delivery_fee_checkout_script() {

    if (!is_checkout() || is_order_received_page()) {
        return;
    }
    ?>
    <script>
    jQuery(function ($) {
        $(document.body).on(
            'change',
            '#dessert_affairs_add_delivery_fee',
            function () {
                $(document.body).trigger('update_checkout');
            }
        );
    });
    </script>
    <?php
}
