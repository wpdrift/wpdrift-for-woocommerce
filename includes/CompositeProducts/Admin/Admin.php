<?php

namespace WPdrift\CompositeProducts\Admin;

/**
 * Admin class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Composite Products
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
use WC_Admin_Status;
use WPdrift\CompositeProducts\Module;
use WPdrift\CompositeProducts\ProductComposite;
use WPdrift\CompositeProducts\Compatibility\Core\Compatibility;


/**
 * Setup admin hooks.
 *
 * @class    Admin
 * @version  4.0.0
 */
class Admin {

	/**
	 * Setup admin hooks.
	 */
	public static function init() {

		add_action( 'init', array( __CLASS__, 'admin_init' ) );

		// Add a message in the WP Privacy Policy Guide page.
		add_action( 'admin_init', array( __CLASS__, 'add_privacy_policy_guide_content' ) );

		// Admin jQuery.
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'composite_admin_scripts' ) );

		// Template override scan path.
		add_filter( 'woocommerce_template_overrides_scan_paths', array( __CLASS__, 'composite_template_scan_path' ) );

		// Add CP debug data in the system status.
		add_action( 'woocommerce_system_status_report', array( __CLASS__, 'render_system_status_items' ) );
	}

	/**
	 * Admin init.
	 */
	public static function admin_init() {
		self::includes();
	}

	/**
	 * Include classes.
	 */
	public static function includes() {

		// Product Import/Export.
		if ( Compatibility::is_wc_version_gte( '3.1' ) ) {
			// require_once( 'export/class-wc-cp-product-export.php' );
			// require_once( 'import/class-wc-cp-product-import.php' );
		}

		// Post type stuff.
		PostTypes::init();

		// Metaboxes.
		MetaBoxes\ProductData::init();

		// Admin AJAX.
		Ajax::init();

		// Admin edit-order screen.
		if ( Compatibility::is_wc_version_gte( '3.2' ) ) {
			Order::init();
		}
	}

	/**
	 * Message to add in the WP Privacy Policy Guide page.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	protected static function get_privacy_policy_guide_message() {

		$content = '
			<div contenteditable="false">' .
				'<p class="wp-policy-help">' .
					__( 'Composite Products does not collect, store or share any personal data.', 'wpdrift-woocommerce-modules' ) .
				'</p>' .
			'</div>';

		return $content;
	}

	/**
	 * Add a message in the WP Privacy Policy Guide page.
	 *
	 * @since  1.0.0
	 */
	public static function add_privacy_policy_guide_content() {
		if ( function_exists( 'wp_add_privacy_policy_content' ) ) {
			wp_add_privacy_policy_content( 'WooCommerce Composite Products', self::get_privacy_policy_guide_message() );
		}
	}

	/**
	 * Include scripts.
	 */
	public static function composite_admin_scripts() {
		$meta_boxes_product_asset_file = \WPdrift\woocommerce_modules()->plugin_path() . '/build/meta-boxes-product.asset.php';
		$meta_boxes_order_asset_file   = \WPdrift\woocommerce_modules()->plugin_path() . '/build/meta-boxes-order.asset.php';

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_register_script(
			'wc-composite-admin-product-panel',
			\WPdrift\woocommerce_modules()->plugin_url() . '/build/meta-boxes-product.js',
			array( 'jquery', 'jquery-ui-datepicker', 'wp-util', 'wc-admin-product-meta-boxes' ),
			$meta_boxes_product_asset_file['version']
		);
		wp_register_script(
			'wc-composite-admin-order-panel',
			\WPdrift\woocommerce_modules()->plugin_url() . '/build/meta-boxes-order.js',
			array( 'wc-admin-order-meta-boxes' ),
			$meta_boxes_order_asset_file['version']
		);

		wp_register_style( 'wc-composite-admin-css', Module::instance()->plugin_url() . '/assets/css/admin/admin.css', array(), Module::instance()->version );
		wp_style_add_data( 'wc-composite-admin-css', 'rtl', 'replace' );

		wp_register_style( 'wc-composite-writepanel-css', Module::instance()->plugin_url() . '/assets/css/admin/meta-boxes-product.css', array( 'woocommerce_admin_styles' ), Module::instance()->version );
		wp_style_add_data( 'wc-composite-writepanel-css', 'rtl', 'replace' );

		wp_register_style( 'wc-composite-edit-order-css', Module::instance()->plugin_url() . '/assets/css/admin/meta-boxes-order.css', array( 'woocommerce_admin_styles' ), Module::instance()->version );
		wp_style_add_data( 'wc-composite-edit-order-css', 'rtl', 'replace' );

		wp_enqueue_style( 'wc-composite-admin-css' );

		// Get admin screen ID.
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		/*
		 * Enqueue styles.
		 */
		if ( in_array( $screen_id, array( 'edit-product', 'product' ) ) ) {
			wp_enqueue_style( 'wc-composite-writepanel-css' );
		} elseif ( in_array( $screen_id, array( 'shop_order', 'edit-shop_order', 'shop_subscription', 'edit-shop_subscription' ) ) ) {
			wp_enqueue_style( 'wc-composite-edit-order-css' );
		}

		/*
		 * Enqueue scripts.
		 */
		if ( 'product' === $screen_id ) {

			wp_enqueue_script( 'wc-composite-admin-product-panel' );

			$params = array(
				'save_composite_nonce'         => wp_create_nonce( 'wc_bto_save_composite' ),
				'add_component_nonce'          => wp_create_nonce( 'wc_bto_add_component' ),
				'get_product_categories_nonce' => wp_create_nonce( 'wc_bto_get_product_categories' ),
				'layouts'                      => array_keys( ProductComposite::get_layout_options() ),
				'wc_placeholder_img_src'       => wc_placeholder_img_src(),
				'is_first_composite'           => isset( $_GET['wc_cp_first_composite'] ) ? 'yes' : 'no',
				'is_wc_version_gte_3_2'        => Compatibility::is_wc_version_gte( '3.2' ) ? 'yes' : 'no',
				// Strings.
				'i18n_no_default'              => __( 'No default option&hellip;', 'wpdrift-woocommerce-modules' ),
				'i18n_all'                     => __( 'Any Product or Variation', 'wpdrift-woocommerce-modules' ),
				'i18n_none'                    => _x( 'No selection', 'optional component property controlled in scenarios', 'wpdrift-woocommerce-modules' ),
				'i18n_choose_component_image'  => __( 'Choose a Component Image', 'wpdrift-woocommerce-modules' ),
				'i18n_set_component_image'     => __( 'Set Component Image', 'wpdrift-woocommerce-modules' ),
				'i18n_defaults_unset'          => __( 'Please ensure that a Default Option is set in all non-optional Components before choosing \'Use Defaults\' as the preferred Catalog Price display method for this Composite.', 'wpdrift-woocommerce-modules' ),
				'i18n_set_defaults_static'     => __( 'The Default Option field cannot be cleared &ndash; you have added a single <strong>Component Option</strong> without checking the <strong>Optional</strong> box.', 'wpdrift-woocommerce-modules' ),
				'i18n_set_defaults'            => __( 'A Default Option must be chosen in all non-optional Components when the <strong>Catalog Price</strong> display method is set to <strong>Use Defaults</strong>.', 'wpdrift-woocommerce-modules' ),
				// Strings duplicated from core.
				'i18n_matches_1'               => _x( 'One result is available, press enter to select it.', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_matches_n'               => _x( '%qty% results are available, use up and down arrow keys to navigate.', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_no_matches'              => _x( 'No matches found', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_ajax_error'              => _x( 'Loading failed', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_input_too_short_1'       => _x( 'Please enter 1 or more characters', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_input_too_short_n'       => _x( 'Please enter %qty% or more characters', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_input_too_long_1'        => _x( 'Please delete 1 character', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_input_too_long_n'        => _x( 'Please delete %qty% characters', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_selection_too_long_1'    => _x( 'You can only select 1 item', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_selection_too_long_n'    => _x( 'You can only select %qty% items', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_load_more'               => _x( 'Loading more results&hellip;', 'enhanced select', 'wpdrift-woocommerce-modules' ),
				'i18n_searching'               => _x( 'Searching&hellip;', 'enhanced select', 'wpdrift-woocommerce-modules' ),
			);

			wp_localize_script( 'wc-composite-admin-product-panel', 'wc_composite_admin_params', $params );

		} elseif ( in_array( $screen_id, array( 'shop_order', 'shop_subscription' ) ) ) {

			wp_enqueue_script( 'wc-composite-admin-order-panel' );

			$params = array(
				'edit_composite_nonce'           => wp_create_nonce( 'wc_bto_edit_composite' ),
				'is_wc_version_gte_3_4'          => Compatibility::is_wc_version_gte( '3.4' ) ? 'yes' : 'no',
				'i18n_configure'                 => __( 'Configure', 'wpdrift-woocommerce-modules' ),
				'i18n_edit'                      => __( 'Edit', 'wpdrift-woocommerce-modules' ),
				'i18n_form_error'                => __( 'Failed to initialize form. If this issue persists, please reload the page and try again.', 'wpdrift-woocommerce-modules' ),
				'i18n_validation_error'          => __( 'Failed to validate configuration. If this issue persists, please reload the page and try again.', 'wpdrift-woocommerce-modules' ),
				'i18n_selection_request_timeout' => __( 'Your selection could not be updated. If the issue persists, please refresh the page and try again.', 'wpdrift-woocommerce-modules' ),
			);

			wp_localize_script( 'wc-composite-admin-order-panel', 'wc_composite_admin_order_params', $params );
		}
	}

	/**
	 * Support scanning for template overrides in extension.
	 *
	 * @param  array  $paths
	 * @return array
	 */
	public static function composite_template_scan_path( $paths ) {
		$paths['WooCommerce Composite Products'] = Module::instance()->plugin_path() . '/templates/';
		return $paths;
	}

	/**
	 * Add CP debug data in the system status.
	 *
	 * @since  1.0.0
	 */
	public static function render_system_status_items() {

		$debug_data = array(
			'db_version' => get_option( 'woocommerce_composite_products_db_version', null ),
			'overrides'  => self::get_template_overrides(),
		);

		include( 'views/html-admin-page-status-report.php' );
	}

	/**
	 * Determine which of our files have been overridden by the theme.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	private static function get_template_overrides() {

		$template_path    = Module::instance()->plugin_path() . '/templates/';
		$templates        = WC_Admin_Status::scan_template_files( $template_path );
		$wc_template_path = trailingslashit( WC()->template_path() );
		$theme_root       = trailingslashit( get_theme_root() );

		$overridden = array();

		foreach ( $templates as $file ) {

			$found_location  = false;
			$check_locations = array(
				get_stylesheet_directory() . "/{$file}",
				get_stylesheet_directory() . "/{$wc_template_path}{$file}",
				get_template_directory() . "/{$file}",
				get_template_directory() . "/{$wc_template_path}{$file}",
			);

			foreach ( $check_locations as $location ) {
				if ( is_readable( $location ) ) {
					$found_location = $location;
					break;
				}
			}

			if ( ! empty( $found_location ) ) {

				$core_version  = WC_Admin_Status::get_file_version( $template_path . $file );
				$found_version = WC_Admin_Status::get_file_version( $found_location );
				$is_outdated   = $core_version && ( empty( $found_version ) || version_compare( $found_version, $core_version, '<' ) );

				if ( false !== strpos( $found_location, '.php' ) ) {
					$overridden[] = array(
						'file'         => str_replace( $theme_root, '', $found_location ),
						'version'      => $found_version,
						'core_version' => $core_version,
						'is_outdated'  => $is_outdated,
					);
				}
			}
		}

		return $overridden;
	}
}
