<?php

namespace WPdrift\CompositeProducts\Admin;

/**
 * PostTypes class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Composite Products
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add hooks to the edit posts view for the 'product' post type.
 *
 * @class    PostTypes
 * @version  1.0.0
 */
class PostTypes {

	/**
	 * Hook in.
	 */
	public static function init() {

		// Add support for bulk editing Composite's Regular/Sale price.
		add_filter( 'woocommerce_bulk_edit_save_price_product_types', array( __CLASS__, 'bulk_edit_price' ), 10, 1 );
	}

	/**
	 * Add support for bulk editing Composite's Regular/Sale price.
	 *
	 * @param  array      $supported_product_types
	 * @return array
	 */
	public static function bulk_edit_price( $supported_product_types ) {

		$supported_product_types[] = 'composite';

		return $supported_product_types;
	}
}
