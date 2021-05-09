<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPdrift\CompositeProducts\Cart;
use WPdrift\CompositeProducts\Compatibility\Compatibility;
use WPdrift\CompositeProducts\Admin\Admin;
use WPdrift\CompositeProducts\Admin\License;
use WPdrift\CompositeProducts\Data\Data;

/**
 * Main plugin class.
 */
class Module {

	public $version  = '4.0.6';
	public $required = '3.1.0';

	/**
	 * The single instance of the class.
	 * @var Init
	 *
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Init instance.
	 *
	 * Ensures only one instance of Init is loaded or can be loaded - @see 'Module::instance()'.
	 *
	 * @since  1.0.0
	 *
	 * @static
	 * @return Init - Main instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wpdrift-woocommerce-modules' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wpdrift-woocommerce-modules' ), '1.0.0' );
	}

	/**
	 * Contructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Entry point.
		add_action( 'init', array( $this, 'initialize_plugin' ), 9 );
	}

	/**
	 * Auto-load in-accessible properties.
	 *
	 * @param  mixed  $key
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'api', 'compatibility', 'cart', 'order', 'display' ), true ) ) {
			switch ( $key ) {
				case 'api':
					return call_user_func( array( __NAMESPACE__ . '\API', 'instance' ) );
					break;

				case 'compatibility':
					return call_user_func( array( __NAMESPACE__ . '\Compatibility\Compatibility', 'instance' ) );
					break;

				case 'cart':
					return call_user_func( array( __NAMESPACE__ . '\Cart', 'instance' ) );
					break;

				case 'order':
					return call_user_func( array( __NAMESPACE__ . '\Order', 'instance' ) );
					break;

				default:
					return call_user_func( array( __NAMESPACE__ . '\Display', 'instance' ) );
					break;
			}
		}
	}

	/**
	 * Gets the plugin url.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function plugin_url() {
		return plugins_url( basename( plugin_dir_path( WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_BASENAME ) ) . '/includes/CompositeProducts', basename( __FILE__ ) );
	}

	/**
	 * Gets the plugin path.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function plugin_path() {
		return untrailingslashit( plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Plugin base path name getter.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function plugin_basename() {
		return plugin_basename( WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_FILE );
	}

	/**
	 * Plugin version getter.
	 *
	 * @since  1.0.0
	 *
	 * @param  boolean  $base
	 * @param  string   $version
	 * @return string
	 */
	public function plugin_version( $base = false, $version = '' ) {

		$version = $version ? $version : $this->version;

		if ( $base ) {
			$version_parts = explode( '-', $version );
			$version       = sizeof( $version_parts ) === 2 ? $version_parts[0] : $version;
		}

		return $version;
	}

	/**
	 * Fire in the hole!
	 */
	public function initialize_plugin() {
		$this->define_constants();
		$this->includes();

		// Compatibility::instance();
		// Order::instance();

		Admin::init();
	}

	/**
	 * Constants.
	 */
	public function define_constants() {

		wc_maybe_define_constant( 'WC_CP_SUPPORT_URL', 'https://woocommerce.com/my-account/marketplace-ticket-form/' );
		wc_maybe_define_constant( 'WC_CP_ABSPATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );

		if ( 'yes' === get_option( 'woocommerce_composite_products_debug_query_transients', null ) ) {
			/**
			 * 'WC_CP_DEBUG_QUERY_TRANSIENTS' constant.
			 *
			 * Disables the query transients cache.
			 */
			wc_maybe_define_constant( 'WC_CP_DEBUG_QUERY_TRANSIENTS', true );
		}

		if ( 'yes' === get_option( 'woocommerce_composite_products_debug_runtime_cache', null ) ) {
			/**
			 * 'WC_CP_DEBUG_RUNTIME_CACHE' constant.
			 *
			 * Disables the runtime object cache.
			 */
			wc_maybe_define_constant( 'WC_CP_DEBUG_RUNTIME_CACHE', true );
		}
	}

	/**
	 * Includes.
	 */
	public function includes() {
		// Install.
		Install::init();
		// CRUD.
		Data::init();

		// CP functions.
		require_once( 'functions.php' );

		// PHP 5.5+ cartesian product generator function.
		if ( Compatibility::php_version_gte( '5.5.0' ) ) {
			require_once( 'generator-functions.php' );
		}

		// Products.
		Products::init();
		// Cart-related functions and filters.
		Cart::instance();
		// Order-again functions and filters.
		OrderAgain::init();
		// Coupon-related composite functions and hooks.
		Coupon::init();
		// Front-end functions and filters.
		Display::instance();
		// REST API hooks.
		RestAPI::init();
		// Popup.
		CompositePopup::init();
	}
}
