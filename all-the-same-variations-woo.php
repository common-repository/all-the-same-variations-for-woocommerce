<?php

/**
 * Plugin Name: All The Same Variations for WooCommerce
 * Plugin URI: 
 * Description: All The Same Variations for WooCommerce is a simple, lightweight plugin which helps the user by reducing time selecting all varations.
 * Version: 1.1.0
 * Tested up to: 6.0
 * WC requires at least: 3.0.0
 * WC tested up to: 6.6.0
 * Author: Blaze Concepts
 * Author URI: https://www.blazeconcepts.co.uk/
 *
 * Text Domain: all-the-same-variations-for-woocommerce
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Enqueue frontend JS file
 *
 * @param [string] $hook
 * @return void
 */
add_action('wp_enqueue_scripts', 'blz_ats_enqueue_scripts_styles');

function blz_ats_enqueue_scripts_styles($hook) {
    wp_enqueue_script('blz-vpc-script', plugins_url('/assets/js/ats_vars.js', __FILE__), array('jquery'));
}

/**
 * Add the option fields to the Advanced tab
 *
 * @return void
 */
add_action('woocommerce_product_options_reviews', 'blz_ats_add_fields_to_prod_editor' );

function blz_ats_add_fields_to_prod_editor() {
    // Checkbox
    woocommerce_wp_checkbox(
        array(
            'id' => '_allsame',
            'wrapper_class' => 'show_if_variable',
            'label' => __('\'All the same\' feature', 'all-the-same-variations-woo' ),
            'desc_tip'    => 'true',
            'description' => __( 'Tick this to enable the \'All the same\' feature', 'all-the-same-variations-woo' )
        )
    );

    // Textbox
    woocommerce_wp_text_input(
        array(
            'id'          => '_allsametext',
            'wrapper_class' => 'show_if_variable',
            'label'       => __('All the same text', 'all-the-same-variations-woo'),
            'placeholder' => 'E.g. All the same',
            'desc_tip'    => 'true',
            'description' => __('Text to show on the frontend', 'all-the-same-variations-woo')
        )
    );
}

/**
 * Saving the option fields as above
 *
 * @param [integer] $post_id
 * @return void
 */
add_action('woocommerce_process_product_meta', 'blz_ats_save_fields_from_prod_editor');

function blz_ats_save_fields_from_prod_editor($post_id) {
    $atstext = sanitize_text_field($_POST['_allsametext']);
    $allthesame = isset($_POST['_allsame']) ? 'yes' : 'no';
    $allsametext = isset($atstext) ? $atstext : '';
    update_post_meta($post_id, '_allsame', $allthesame);
    update_post_meta($post_id, '_allsametext', $allsametext);
}

/** 
 * Showing the tickbox on the frontend if product has it ticked
 * 
 * @return void
 */
add_action('woocommerce_before_variations_form', 'blz_add_ats_tickbox');

function blz_add_ats_tickbox() {
    global $product;
    $prodid = $product->get_id();
    $allthesame = get_post_meta($prodid, '_allsame', true);
    $allthesametext = get_post_meta($prodid, '_allsametext', true);
    if (!isset($allthesametext) || empty($allthesametext)) {
        $allthesametext = esc_html( __('All the same', 'all-the-same-variations-woo' ) );
    }
    $selectfirsttext = esc_html( __('Please select first option', 'all-the-same-variations-woo' ) );
    if ($allthesame == 'yes') {
        echo "<script>
                jQuery(document).ready(function () {
                    var firsttr = jQuery('table.variations tr:first');
                    jQuery(firsttr).append('<input type=\"checkbox\" name=\"all_same\" id=\"all_same\" value=\"1\"> <span id=\"all_same_text\">" . $allthesametext . "</span>');
                    jQuery(firsttr).append('<div id=\"ats_no_option\" class=\"woocommerce-error\" style=\"display: none;\"> " . $selectfirsttext . "</div>');
                });
            </script>";
    }
}