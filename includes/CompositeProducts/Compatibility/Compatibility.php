<?php

namespace WPdrift\CompositeProducts\Compatibility;

/**
 * Compatibility class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Composite Products
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 3rd-party Extensions Compatibility.
 *
 * @class    Compatibility
 * @version  0.1.0
 */
class Compatibility {

	/**
	 * Array of min required plugin versions.
	 * @var array
	 */
	private $required = array();

	/**
	 * The single instance of the class.
	 * @var Compatibility
	 *
	 * @since 0.1.0
	 */
	protected static $_instance = null;

	/**
	 * Main Compatibility instance.
	 *
	 * Ensures only one instance of Compatibility is loaded or can be loaded.
	 *
	 * @static
	 * @return Compatibility
	 * @since  0.1.0
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
	 * @since 0.1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wpdrift-woocommerce-modules' ), '0.1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 0.1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Foul!', 'wpdrift-woocommerce-modules' ), '0.1.0' );
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->required = array(
			'pb'     => '5.10.0',
			'addons' => '2.9.1',
		);

		// Initialize.
		$this->load_modules();
	}

	/**
	 * Initialize.
	 *
	 * @since  0.1.0
	 *
	 * @return void
	 */
	protected function load_modules() {
		// Initialize.
		add_action( 'plugins_loaded', array( $this, 'module_includes' ), 100 );
	}

	/**
	 * Init compatibility classes.
	 */
	public function module_includes() {
		$module_paths = array();

		/**
		 * 'woocommerce_composites_compatibility_modules' filter.
		 *
		 * Use this to filter the required compatibility modules.
		 *
		 * @since  0.1.0
		 * @param  array $module_paths
		 */
		$module_paths = apply_filters( 'woocommerce_composites_compatibility_modules', $module_paths );

		foreach ( $module_paths as $name => $path ) {
			require_once( $path );
		}
	}

	/**
	 * Rendering a PIP document?
	 *
	 * @since  0.1.0
	 *
	 * @param  string  $type
	 * @return boolean
	 */
	public function is_pip( $type = '' ) {
		return class_exists( 'PIP_Compatibility' ) && PIP_Compatibility::rendering_document( $type );
	}

	/**
	 * Checks if a product has (required) addons.
	 *
	 * @since  0.1.0
	 *
	 * @param  mixed    $product
	 * @param  boolean  $required
	 * @return boolean
	 */
	public function has_addons( $product, $required = false ) {

		if ( ! class_exists( 'Addons_Compatibility' ) ) {
			return false;
		}

		return Addons_Compatibility::has_addons( $product, $required );
	}

	/**
	 * Checks PHP version.
	 *
	 * @param  string  $version
	 * @return boolean
	 */
	public static function php_version_gte( $version ) {
		return function_exists( 'phpversion' ) && version_compare( phpversion(), $version, '>=' );
	}
}
