<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPdrift\CompositeProducts\Module;
use WPdrift\CompositeProducts\Admin\AdminNotices;
use WPdrift\CompositeProducts\Compatibility\Core\Compatibility;

/**
 * Install class.
 */
class Install {

	/** @var array DB updates and callbacks that need to be run per version */
	private static $db_updates = array(
		'3.7.0' => array(
			'wc_cp_update_370_main',
			'wc_cp_update_370_delete_unused_meta',
			'wc_cp_update_370_db_version',
		),
		'3.8.0' => array(
			'wc_cp_update_380_main',
			'wc_cp_update_380_delete_unused_meta',
			'wc_cp_update_380_db_version',
		),
	);

	/**
	 * Background update class.
	 * @var BackgroundUpdater
	 */
	private static $background_updater;

	/**
	 * Current plugin version.
	 * @var string
	 */
	private static $current_version;

	/**
	 * Current DB version.
	 * @var string
	 */
	private static $current_db_version;

	/**
	 * Hook in tabs.
	 */
	public static function init() {
		// Installation and DB updates handling.
		add_action( 'init', array( __CLASS__, 'init_background_updater' ), 5 );
		add_action( 'init', array( __CLASS__, 'define_updating_constant' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_install' ) );
		add_action( 'admin_init', array( __CLASS__, 'maybe_update' ) );

		// Adds support for the Composite type - added here instead of 'ProductData' as it's used in REST context.
		add_filter( 'product_type_selector', array( __CLASS__, 'add_composite_type' ) );
		add_filter( 'woocommerce_product_class', array( __CLASS__, 'filter_product_class' ), 10, 4 );

		// Get plugin version and DB version.
		self::$current_version    = get_option( 'woocommerce_composite_products_version', null );
		self::$current_db_version = get_option( 'woocommerce_composite_products_db_version', null );

		// include_once( 'class-wc-cp-background-updater.php' );
	}

	/**
	 * Adds support for the Composite type.
	 *
	 * @param  array  $types
	 * @return array
	 */
	public static function add_composite_type( $types ) {

		$types['composite'] = __( 'Composite product', 'wpdrift-woocommerce-modules' );

		return $types;
	}

	/**
	 * [filter_product_class description]
	 * @param  [type] $classname    [description]
	 * @param  [type] $product_type [description]
	 * @param  [type] $post_type    [description]
	 * @param  [type] $product_id   [description]
	 * @return [type]               [description]
	 */
	public static function filter_product_class( $classname, $product_type, $post_type, $product_id ) {
		if ( 'composite' === $product_type ) {
			return __NAMESPACE__ . '\ProductComposite';
		}

		return $classname;
	}

	/**
	 * Init background updates.
	 */
	public static function init_background_updater() {
		self::$background_updater = new BackgroundUpdater();
	}

	/**
	 * Installation needed?
	 *
	 * @return boolean
	 */
	private static function must_install() {
		return version_compare( self::$current_version, Module::instance()->plugin_version(), '<' );
	}

	/**
	 * Installation possible?
	 *
	 * @since  1.0.0
	 *
	 * @param  boolean  $check_installing
	 * @return boolean
	 */
	private static function can_install( $check_installing = true ) {

		if ( $check_installing && get_transient( 'wc_cp_installing' ) ) {
			return false;
		}

		return ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) && ! defined( 'IFRAME_REQUEST' ) && current_user_can( 'manage_woocommerce' );
	}

	/**
	 * Check version and run the installer if necessary.
	 *
	 * @since  1.0.0
	 */
	public static function maybe_install() {
		if ( self::can_install() && self::must_install() ) {
			self::install();
		}
	}

	/**
	 * DB update needed?
	 *
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	private static function must_update() {
		$db_update_versions = array_keys( self::$db_updates );
		$db_version_target  = end( $db_update_versions );
		return is_null( self::$current_db_version ) || version_compare( self::$current_db_version, $db_version_target, '<' );
	}

	/**
	 * DB update possible?
	 *
	 * @since  1.0.0
	 *
	 * @param  boolean  $check_installing
	 * @return boolean
	 */
	private static function can_update( $check_installing = true ) {
		return self::can_install( $check_installing ) && version_compare( self::$current_db_version, Module::instance()->plugin_version( true ), '<' );
	}

	/**
	 * Run the updater if triggered.
	 *
	 * @since  1.0.0
	 */
	public static function maybe_update() {
		if ( ! empty( $_GET['force_wc_cp_db_update'] ) && wp_verify_nonce( $_GET['_wc_cp_admin_nonce'], 'wc_cp_force_db_update_nonce' ) ) {
			if ( self::can_update() && self::must_update() ) {
				self::force_update();
			}
		} elseif ( ! empty( $_GET['trigger_wc_cp_db_update'] ) && wp_verify_nonce( $_GET['_wc_cp_admin_nonce'], 'wc_cp_trigger_db_update_nonce' ) ) {
			if ( self::can_update() && self::must_update() ) {
				self::trigger_update();
			}
		}
	}

	/**
	 * If the DB version is out-of-date, a DB update must be in progress: define a 'WC_CP_UPDATING' constant.
	 */
	public static function define_updating_constant() {
		if ( self::is_update_pending() ) {
			wc_maybe_define_constant( 'WC_CP_UPDATING', true );
		}
	}

	/**
	 * Install CP.
	 */
	public static function install() {

		// Running for the first time? Set a transient now. Used in 'can_install' to prevent race conditions.
		set_transient( 'wc_cp_installing', 'yes', 10 );

		// If the composite type does not exist, create it.
		if ( false === $composite_term_exists = get_term_by( 'slug', 'composite', 'product_type' ) ) {
			wp_insert_term( 'composite', 'product_type' );
		}

		if ( is_null( self::$current_version ) ) {
			// Add dismissible welcome notice.
			AdminNotices::add_maintenance_notice( 'welcome' );
		}

		// Update plugin version - once set, 'maybe_install' will not call 'install' again.
		self::update_version();

		// Queue upgrade tasks.
		if ( self::can_update( false ) ) {

			if ( $composite_term_exists && self::must_update() ) {

				// Add 'update' notice and save early -- saving on the 'shutdown' action will fail if a chained request arrives before the 'shutdown' hook fires.
				AdminNotices::add_maintenance_notice( 'update' );
				AdminNotices::save_notices();

				if ( self::auto_update_enabled() ) {
					self::update();
				} else {
					delete_transient( 'wc_cp_installing' );
					delete_option( 'wc_cp_update_init' );
				}

				// Nothing found - this is a new install :)
			} else {
				self::update_db_version();
			}
		}
	}

	/**
	 * Update WC CP version to current.
	 */
	private static function update_version() {
		delete_option( 'woocommerce_composite_products_version' );
		add_option( 'woocommerce_composite_products_version', Module::instance()->plugin_version() );
	}

	/**
	 * Push all needed DB updates to the queue for processing.
	 */
	private static function update() {

		if ( ! is_object( self::$background_updater ) ) {
			self::init_background_updater();
		}

		$update_queued = false;

		foreach ( self::$db_updates as $version => $update_callbacks ) {

			if ( version_compare( self::$current_db_version, $version, '<' ) ) {

				$update_queued = true;
				Compatibility::log( sprintf( 'Updating to version %s.', $version ), 'info', 'wc_cp_db_updates' );

				foreach ( $update_callbacks as $update_callback ) {
					Compatibility::log( sprintf( '- Queuing %s callback.', $update_callback ), 'info', 'wc_cp_db_updates' );
					self::$background_updater->push_to_queue( $update_callback );
				}
			}
		}

		if ( $update_queued ) {

			// Define 'WC_CP_UPDATING' constant.
			wc_maybe_define_constant( 'WC_CP_UPDATING', true );

			// Keep track of time.
			delete_option( 'wc_cp_update_init' );
			add_option( 'wc_cp_update_init', gmdate( 'U' ) );

			// Dispatch.
			self::$background_updater->save()->dispatch();
		}
	}

	/**
	 * Is auto-updating enabled?
	 *
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function auto_update_enabled() {
		return apply_filters( 'woocommerce_composite_auto_update_db', true );
	}

	/**
	 * Trigger DB update.
	 *
	 * @since  1.0.0
	 */
	public static function trigger_update() {
		self::update();
		wp_safe_redirect( admin_url() );
	}

	/**
	 * Force re-start the update cron if everything else fails.
	 */
	public static function force_update() {

		if ( ! is_object( self::$background_updater ) ) {
			self::init_background_updater();
		}

		/**
		 * Updater cron action.
		 */
		do_action( self::$background_updater->get_cron_hook_identifier() );
		wp_safe_redirect( admin_url() );
	}

	/**
	 * Updates plugin DB version when all updates have been processed.
	 */
	public static function update_complete() {
		Compatibility::log( 'Data update complete.', 'info', 'wc_cp_db_updates' );
		self::update_db_version();
		delete_option( 'wc_cp_update_init' );
		wp_cache_flush();
	}

	/**
	 * True if a DB update is pending.
	 *
	 * @return boolean
	 */
	public static function is_update_pending() {
		return self::must_update();
	}

	/**
	 * True if a DB update was started but not completed.
	 *
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_update_incomplete() {
		return false !== get_option( 'wc_cp_update_init', false );
	}

	/**
	 * True if an update is in progress.
	 *
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public static function is_update_queued() {
		return self::$background_updater->is_update_queued();
	}

	/**
	 * True if an update process is running.
	 *
	 * @return boolean
	 */
	public static function is_update_process_running() {
		return self::$background_updater->is_process_running();
	}

	/**
	 * Update DB version to current.
	 *
	 * @param  string  $version
	 */
	public static function update_db_version( $version = null ) {
		$version = is_null( $version ) ? Module::instance()->plugin_version() : $version;

		// Remove suffixes.
		$version = Module::instance()->plugin_version( true, $version );

		delete_option( 'woocommerce_composite_products_db_version' );
		add_option( 'woocommerce_composite_products_db_version', $version );

		Compatibility::log( sprintf( 'Database version is %s.', get_option( 'woocommerce_composite_products_db_version', 'unknown' ) ), 'info', 'wc_cp_db_updates' );
	}

	/**
	 * Get list of DB update callbacks.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_db_update_callbacks() {
		return self::$db_updates;
	}
}
