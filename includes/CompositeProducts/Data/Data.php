<?php
namespace WPdrift\CompositeProducts\Data;

/**
 * Data class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Composite Products
 * @since    3.9.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Composite Products Data class.
 *
 * Composite Products Data filters and includes.
 *
 * @class    Data
 * @version  3.9.0
 */
class Data {

	public static function init() {

		// Composite Product CPT data store.
		// require_once( 'class-wc-product-composite-data-store-cpt.php' );

		// Register the Composite Product Custom Post Type data store.
		add_filter( 'woocommerce_data_stores', array( __CLASS__, 'register_composite_type_data_store' ), 10 );
	}

	/**
	 * Registers the Composite Product Custom Post Type data store.
	 *
	 * @param  array  $stores
	 * @return array
	 */
	public static function register_composite_type_data_store( $stores ) {

		$stores['product-composite'] = 'WPdrift\CompositeProducts\Data\DataStore';

		return $stores;
	}
}
