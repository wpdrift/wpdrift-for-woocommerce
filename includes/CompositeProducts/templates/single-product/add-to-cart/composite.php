<?php
/**
 * Composite Product template
 *
 * Override this template by copying it to 'yourtheme/woocommerce/single-product/add-to-cart/composite.php'.
 *
 * On occasion, this template file may need to be updated and you (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 * We try to do this as little as possible, but it does happen.
 * When this occurs the version of the template file will be bumped and the readme will list any important changes.
 *
 * @since    2.4.0
 * @version  3.12.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPdrift\CompositeProducts\Module;

do_action( 'woocommerce_before_add_to_cart_form' );
printf(
	'<div data-product_id="%s" data-product_data="%s" data-product_price_data="%s" data-product_components="%s" id="%s"></div>',
	esc_attr( $product->get_id() ),
	esc_attr( json_encode( $product_data ) ),
	esc_attr( json_encode( $price_data ) ),
	htmlspecialchars( json_encode( $components ) ),
	esc_attr( 'product-options' )
);
do_action( 'woocommerce_after_add_to_cart_form' );
