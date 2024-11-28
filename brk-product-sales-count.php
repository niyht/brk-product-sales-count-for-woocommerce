<?php
/*
Plugin Name: BRK Product Sales Count for WooCommerce
Plugin URI: https://brksoft.com
Description: BRK Product Sales Count for WooCommerce displays actual or manual sales counts of products to your customers on the product page.
Version: 1.0.0
Author: Brksoft
Author URI: https://brksoft.com/
Text Domain: brk-product-sales-count
Domain Path: /languages/
Requires at least: 4.0
Tested up to: 6.7
WC requires at least: 3.0
WC tested up to: 9.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

defined('ABSPATH') || exit;

// WooCommerce compatibility
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

// Load text domain
function brkpsc_load_textdomain() {
    load_plugin_textdomain('brk-product-sales-count', false, dirname(plugin_basename(__FILE__)) . '/languages');
}
add_action('plugins_loaded', 'brkpsc_load_textdomain');

// Add metabox
function brkpsc_add_meta_box() {
    add_meta_box(
        'brkpsc_meta_box',
        __('Sales Count Manager', 'brk-product-sales-count'),
        'brkpsc_meta_box_callback',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'brkpsc_add_meta_box');

// Metabox callback
function brkpsc_meta_box_callback($post) {
    wp_nonce_field('brkpsc_save_meta_box', 'brkpsc_meta_box_nonce');

    $type = get_post_meta($post->ID, 'brkpsc_type', true);
    $display = get_post_meta($post->ID, 'brkpsc_display', true);
    $min = get_post_meta($post->ID, 'brkpsc_min', true);
    $max = get_post_meta($post->ID, 'brkpsc_max', true);

    ?>
    <p>
        <label for="brkpsc_type"><?php esc_html_e('Set Type', 'brk-product-sales-count'); ?></label>
        <select name="brkpsc_type" id="brkpsc_type">
            <option value="real" <?php selected($type, 'real'); ?>><?php esc_html_e('Real', 'brk-product-sales-count'); ?></option>
            <option value="custom" <?php selected($type, 'custom'); ?>><?php esc_html_e('Custom', 'brk-product-sales-count'); ?></option>
        </select>
    </p>
    <p>
        <label for="brkpsc_display"><?php esc_html_e('Set Visibility', 'brk-product-sales-count'); ?></label>
        <select name="brkpsc_display" id="brkpsc_display">
            <option value="hide" <?php selected($display, 'hide'); ?>><?php esc_html_e('Hide', 'brk-product-sales-count'); ?></option>
            <option value="show" <?php selected($display, 'show'); ?>><?php esc_html_e('Show', 'brk-product-sales-count'); ?></option>
        </select>
    </p>
    <p>
        <label for="brkpsc_min"><?php esc_html_e('Minimum Number', 'brk-product-sales-count'); ?></label>
        <input type="number" name="brkpsc_min" id="brkpsc_min" value="<?php echo esc_attr($min); ?>" min="0">
    </p>
    <p>
        <label for="brkpsc_max"><?php esc_html_e('Maximum Number', 'brk-product-sales-count'); ?></label>
        <input type="number" name="brkpsc_max" id="brkpsc_max" value="<?php echo esc_attr($max); ?>" min="0">
    </p>
    <?php
}

// Save metabox
function brkpsc_save_meta_box($post_id) {
    if (!isset($_POST['brkpsc_meta_box_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['brkpsc_meta_box_nonce'])), 'brkpsc_save_meta_box')) {
        return;
    }    

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $type = isset($_POST['brkpsc_type']) ? sanitize_text_field(wp_unslash($_POST['brkpsc_type'])) : '';
    $display = isset($_POST['brkpsc_display']) ? sanitize_text_field(wp_unslash($_POST['brkpsc_display'])) : '';
    $min = isset($_POST['brkpsc_min']) ? absint($_POST['brkpsc_min']) : 0;
    $max = isset($_POST['brkpsc_max']) ? absint($_POST['brkpsc_max']) : 0;

    update_post_meta($post_id, 'brkpsc_type', $type);
    update_post_meta($post_id, 'brkpsc_display', $display);
    update_post_meta($post_id, 'brkpsc_min', $min);
    update_post_meta($post_id, 'brkpsc_max', $max);

    delete_transient('brkpsc_' . $post_id);
}
add_action('save_post', 'brkpsc_save_meta_box');

// Display sales count on product page
function brkpsc_display_sales_count() {
    global $post;

    $type = get_post_meta($post->ID, 'brkpsc_type', true);
    $display = get_post_meta($post->ID, 'brkpsc_display', true);

    if ($display !== 'show') {
        return;
    }

    if ($type === 'custom') {
        $min = get_post_meta($post->ID, 'brkpsc_min', true);
        $max = get_post_meta($post->ID, 'brkpsc_max', true);

        $transient_key = 'brkpsc_' . $post->ID;
        $count = get_transient($transient_key);

        if ($count === false) {
            $count = wp_rand($min, $max);
            set_transient($transient_key, $count, DAY_IN_SECONDS);
        }

        echo esc_html($count . ' ' . __('people bought this product today', 'brk-product-sales-count'));
    } elseif ($type === 'real') {
        $args = [
            'status' => ['wc-completed', 'wc-processing'],
            'limit' => -1,
        ];

        $orders = wc_get_orders($args);
        $count = 0;

        foreach ($orders as $order_id) {
            $order = wc_get_order($order_id);
            foreach ($order->get_items() as $item) {
                if ((int) $item->get_product_id() === (int) $post->ID) {
                    $count += $item->get_quantity();
                }
            }
        }

        echo esc_html($count . ' ' . __('people bought this product today', 'brk-product-sales-count'));
    }
}
add_action('woocommerce_before_add_to_cart_form', 'brkpsc_display_sales_count');
