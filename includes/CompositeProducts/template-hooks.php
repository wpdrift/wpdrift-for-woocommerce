<?php
namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*----------------------------------*/
/*  Single product template hooks.  */
/*----------------------------------*/

// Single product form content: Displayed in the Summary.
add_action( 'woocommerce_before_single_product_summary', __NAMESPACE__ . '\wc_cp_wrap_start_product_summary', 1 );

// Single product form content: Displayed after the Summary.
add_action( 'woocommerce_after_single_product_summary', __NAMESPACE__ . '\wc_cp_wrap_end_product_summary', 1 );

/*--------------------------------------*/
/*  Composited product template hooks.  */
/*--------------------------------------*/

// Composited product title.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_title', 5 );

// Composited product details wrapper open.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_wrapper_open', 10 );

// Composited product thumbnail.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_thumbnail', 20 );

// Composited product details wrapper close.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_wrapper_close', 100 );

// Composited product - Excerpt.
add_action( 'woocommerce_composited_product_details', __NAMESPACE__ . '\wc_cp_composited_product_excerpt', 10, 3 );

// Composited Simple product - Price.
add_action( 'woocommerce_composited_product_add_to_cart', __NAMESPACE__ . '\wc_cp_composited_product_price', 8, 3 );
