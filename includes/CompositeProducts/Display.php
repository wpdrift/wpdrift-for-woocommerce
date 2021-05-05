<?php
namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WPdrift\CompositeProducts\Compatibility\Core\Compatibility;

/**
 * Display class.
 */
class Display {

	/**
	 * Keep track of whether the bundled table JS has already been enqueued.
	 * @var boolean
	 */
	private $enqueued_composited_table_item_js = false;

	/**
	 * Workaround for $order arg missing from 'woocommerce_order_item_name' filter - set within the 'woocommerce_order_item_class' filter - @see 'order_item_class()'.
	 * @var false|WC_Order
	 */
	private $order_item_order = false;

	/**
	 * The single instance of the class.
	 * @var Display
	 *
	 * @since 0.1.0
	 */
	protected static $_instance = null;

	/**
	 * Main Display instance.
	 *
	 * Ensures only one instance of Display is loaded or can be loaded.
	 *
	 * @static
	 * @return Display
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
		// Single product template functions and hooks.
		require_once( 'template-functions.php' );
		require_once( 'template-hooks.php' );

		// Front end scripts and JS templates.
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		/**
		 * Products page.
		 */

		// Update add to cart link.
		add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'add_to_cart_link' ), 10, 3 );

		/*
		 * Single-product.
		 */

		// Display notice when editing a composite product from the cart.
		add_action( 'woocommerce_before_single_product', array( $this, 'add_edit_in_cart_notice' ), 0 );

		// Display info notice when viewing a composite product whose catalog price is being calculated in the background.
		add_action( 'woocommerce_before_single_product', array( $this, 'add_price_calc_task_notice' ), 0 );

		// Modify composite products structured data.
		add_filter( 'woocommerce_structured_data_product_offer', array( $this, 'structured_product_data' ), 10, 2 );

		/*
		 * Cart.
		 */

		// Filter cart item price.
		add_filter( 'woocommerce_cart_item_price', array( $this, 'cart_item_price' ), 11, 3 );

		// Filter cart item subtotals.
		add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'cart_item_subtotal' ), 11, 3 );
		add_filter( 'woocommerce_checkout_item_subtotal', array( $this, 'cart_item_subtotal' ), 11, 3 );

		// Keep quantities in sync.
		add_filter( 'woocommerce_cart_item_quantity', array( $this, 'cart_item_quantity' ), 10, 2 );
		add_filter( 'woocommerce_cart_item_remove_link', array( $this, 'cart_item_remove_link' ), 10, 2 );

		// Add table item classes.
		add_filter( 'woocommerce_cart_item_class', array( $this, 'cart_item_class' ), 10, 2 );

		// Add component name to child line items.
		add_filter( 'woocommerce_cart_item_name', array( $this, 'cart_item_component_name' ), 11, 3 );
		add_filter( 'woocommerce_checkout_cart_item_quantity', array( $this, 'cart_item_component_quantity' ), 10, 3 );

		// Filter cart item count.
		add_filter( 'woocommerce_cart_contents_count', array( $this, 'cart_contents_count' ) );

		// Filter cart widget items.
		add_action( 'woocommerce_before_mini_cart', array( $this, 'add_cart_widget_filters' ) );
		add_action( 'woocommerce_after_mini_cart', array( $this, 'remove_cart_widget_filters' ) );

		/*
		 * Orders.
		 */

		// Filter order item subtotals.
		add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'order_item_subtotal' ), 10, 3 );

		// Add component name to child line items.
		add_filter( 'woocommerce_order_item_name', array( $this, 'order_item_component_name' ), 10, 2 );
		add_filter( 'woocommerce_order_item_quantity_html', array( $this, 'order_item_component_quantity' ), 10, 2 );

		// Add table item classes.
		add_filter( 'woocommerce_order_item_class', array( $this, 'order_item_class' ), 10, 3 );

		// Filter order item count in the front-end.
		add_filter( 'woocommerce_get_item_count', array( $this, 'order_item_count' ), 10, 3 );

		// Indent child items in emails.
		add_action( 'woocommerce_email_styles', array( $this, 'email_styles' ) );

		/*
		 * Blocks.
		 */

		// Filter grid item html.
		add_filter( 'woocommerce_blocks_product_grid_item_html', array( $this, 'blocks_product_grid_item_html' ), 10, 3 );
	}

	/**
	 * Front-end styles and scripts.
	 */
	public function frontend_scripts() {
		global $wp_query;

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		// Asset file.
		$asset_file = include( WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_DIR . '/build/index.asset.php' );
		$settings   = array(
			'root'  => esc_url_raw( rest_url() ),
			'nonce' => wp_create_nonce( 'wp_rest' ),
		);

		// Is product.
		if ( is_product() ) {
			wp_enqueue_script(
				'wpdrift-woocommerce-modules', // Handle.
				WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_URL . '/build/index.js',
				$asset_file['dependencies'], // Dependencies, defined above.
				$asset_file['version'] // Version: File modification time.
			);

			wp_localize_script(
				'wpdrift-woocommerce-modules',
				'wpdriftSettings',
				$settings
			);
		}

		if ( \WPdrift\is_woocommerce_page() ) {
			wp_enqueue_script(
				'wpdrift-woocommerce-modules-composite-popup', // Handle.
				WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_URL . '/build/popup.js',
				$asset_file['dependencies'], // Dependencies, defined above.
				$asset_file['version'] // Version: File modification time.
			);

			wp_localize_script(
				'wpdrift-woocommerce-modules-composite-popup',
				'wpdriftSettings',
				$settings
			);

			/**
			 * Filter front-end params.
			 *
			 * @param  array  $params
			 */
			$params = apply_filters(
				'woocommerce_composite_front_end_params',
				array(
					'small_width_threshold'              => 480,
					'full_width_threshold'               => 480,
					'legacy_width_threshold'             => 0,
					'scroll_viewport_top_offset'         => 50,
					'i18n_strikeout_price_string'        => sprintf( _x( '<del>%1$s</del> <ins>%2$s</ins>', 'Sale/strikeout price', 'wpdrift-woocommerce-modules' ), '%f', '%t' ),
					'i18n_price_format'                  => sprintf( _x( '%1$s%2$s%3$s', '"Total" string followed by price followed by price suffix', 'wpdrift-woocommerce-modules' ), '', '%p', '%s' ),
					'i18n_price_signed'                  => sprintf( _x( '%1$s%2$s', 'relative price (signed)', 'wpdrift-woocommerce-modules' ), '%s', '%p' ),
					'i18n_price_string'                  => sprintf( _x( '%1$s %2$s %3$s', 'option price followed by per unit suffix and discount', 'wpdrift-woocommerce-modules' ), '%p', '%q', '%d' ),
					'i18n_price_range_string_plain'      => sprintf( _x( '%1$s &hellip; %2$s', 'Price range (plain)', 'wpdrift-woocommerce-modules' ), '%f', '%t' ),
					'i18n_price_range_string_absolute'   => sprintf( _x( '%1$s &ndash; %2$s', 'Price range', 'wpdrift-woocommerce-modules' ), '%f', '%t' ),
					'i18n_price_range_string'            => sprintf( _x( '%1$s &hellip; %2$s', 'Price range (relative)', 'wpdrift-woocommerce-modules' ), '%f', '%t' ),
					'i18n_price_upto_string_plain'       => sprintf( _x( '&hellip; %s', 'Price range - up to', 'wpdrift-woocommerce-modules' ), '%p' ),
					'i18n_price_upto_string'             => sprintf( _x( 'Up to: %s', 'Price range - up to', 'wpdrift-woocommerce-modules' ), '%p' ),
					'i18n_price_from_string_plain'       => sprintf( _x( '%s &hellip;', 'Price range - from', 'wpdrift-woocommerce-modules' ), '%p' ),
					'i18n_price_from_string'             => sprintf( _x( 'From: %s', 'Price range - From:', 'wpdrift-woocommerce-modules' ), '%p' ),
					'i18n_qty_string'                    => _x( ' &times; %s', 'qty string', 'wpdrift-woocommerce-modules' ),
					'i18n_per_unit_string'               => __( '/ pc.', 'wpdrift-woocommerce-modules' ),
					'i18n_discount_string'               => sprintf( __( '(%s%% off)', 'wpdrift-woocommerce-modules' ), '%s' ),
					'i18n_title_string'                  => sprintf( _x( '%1$s%2$s%3$s', 'title quantity price', 'wpdrift-woocommerce-modules' ), '%t', '%q', '%p' ),
					'i18n_selected_product_string'       => sprintf( _x( '%1$s%2$s', 'product title followed by details', 'wpdrift-woocommerce-modules' ), '%t', '%m' ),
					'i18n_free'                          => __( 'Free!', 'wpdrift-woocommerce-modules' ),
					'i18n_total'                         => __( 'Total: ', 'wpdrift-woocommerce-modules' ),
					'i18n_subtotal'                      => __( 'Subtotal: ', 'wpdrift-woocommerce-modules' ),
					'i18n_no_options'                    => __( 'No options available&hellip;', 'wpdrift-woocommerce-modules' ),
					'i18n_no_selection'                  => __( 'No selection', 'wpdrift-woocommerce-modules' ),
					'i18n_no_option'                     => _x( 'No %s', 'dropdown empty-value option: optional selection (%s replaced by component title)', 'wpdrift-woocommerce-modules' ),
					'i18n_dropdown_title_price'          => sprintf( _x( '%1$s &nbsp;&ndash;&nbsp; %2$s', 'dropdown option title, followed by price', 'wpdrift-woocommerce-modules' ), '%t', '%p' ),
					'i18n_dropdown_title_relative_price' => sprintf( _x( '%1$s: &nbsp;%2$s', 'dropdown option title, followed by relative price', 'wpdrift-woocommerce-modules' ), '%t', '%p' ),
					'i18n_configure_option_button'       => _x( 'Configure', 'thumbnail option configure', 'wpdrift-woocommerce-modules' ),
					'i18n_select_option_button'          => _x( 'Select', 'thumbnail option select', 'wpdrift-woocommerce-modules' ),
					'i18n_option_action_label'           => sprintf( _x( '%1$s %2$s', 'thumbnail action aria label', 'wpdrift-woocommerce-modules' ), '%a', '%o' ),
					'i18n_select_option'                 => _x( 'Choose %s&hellip;', 'dropdown empty-value option: mandatory selection (%s replaced by component title)', 'wpdrift-woocommerce-modules' ),
					'i18n_previous_step'                 => _x( '%s', 'previous step navigation button text', 'wpdrift-woocommerce-modules' ),
					'i18n_next_step'                     => _x( '%s', 'next step navigation button text', 'wpdrift-woocommerce-modules' ),
					'i18n_previous_step_label'           => _x( 'Go to %s', 'previous step navigation button aria label', 'wpdrift-woocommerce-modules' ),
					'i18n_next_step_label'               => _x( 'Go to %s', 'next step navigation button aria label', 'wpdrift-woocommerce-modules' ),
					'i18n_final_step'                    => _x( 'Review Configuration', 'final step navigation button text', 'wpdrift-woocommerce-modules' ),
					'i18n_reset_selection'               => __( 'Reset selection', 'wpdrift-woocommerce-modules' ),
					'i18n_clear_selection'               => __( 'Clear selection', 'wpdrift-woocommerce-modules' ),
					'i18n_validation_issues_for'         => sprintf( __( '<span class="msg-source">%1$s</span> &rarr; <span class="msg-content">%2$s</span>', 'wpdrift-woocommerce-modules' ), '%c', '%e' ),
					'i18n_validation_issues'             => __( 'Please resolve all pending configuration issues before adding this product to your cart.', 'wpdrift-woocommerce-modules' ),
					'i18n_item_unavailable_text'         => __( 'The selected item cannot be purchased at the moment.', 'wpdrift-woocommerce-modules' ),
					'i18n_unavailable_text'              => __( 'This product cannot be purchased at the moment.', 'wpdrift-woocommerce-modules' ),
					'i18n_select_component_option'       => __( 'Please choose an option to continue&hellip;', 'wpdrift-woocommerce-modules' ),
					'i18n_select_component_option_for'   => __( 'Please choose an option.', 'wpdrift-woocommerce-modules' ),
					'i18n_selected_product_invalid'      => __( 'The chosen option is incompatible with your current configuration.', 'wpdrift-woocommerce-modules' ),
					'i18n_selected_product_options_invalid' => __( 'The chosen product options are incompatible with your current configuration.', 'wpdrift-woocommerce-modules' ),
					'i18n_selected_product_stock_insufficient' => __( 'The selected option does not have enough stock. Please choose another option to continue&hellip;', 'wpdrift-woocommerce-modules' ),
					'i18n_select_product_options'        => __( 'Please choose product options to continue&hellip;', 'wpdrift-woocommerce-modules' ),
					'i18n_select_product_options_for'    => __( 'Please choose product options.', 'wpdrift-woocommerce-modules' ),
					'i18n_select_product_addons'         => __( 'Please configure all required product fields to continue&hellip;', 'wpdrift-woocommerce-modules' ),
					'i18n_select_product_addons_for'     => __( 'Please configure all required product fields.', 'wpdrift-woocommerce-modules' ),
					'i18n_summary_empty_component'       => _x( 'Configure', 'summary element configure action text', 'wpdrift-woocommerce-modules' ),
					'i18n_summary_configured_component'  => _x( 'Edit', 'summary element edit action text', 'wpdrift-woocommerce-modules' ),
					'i18n_summary_static_component'      => _x( 'View', 'summary element view action text', 'wpdrift-woocommerce-modules' ),
					'i18n_summary_action_label'          => sprintf( _x( '%1$s %2$s', 'summary element action aria label', 'wpdrift-woocommerce-modules' ), '%a', '%c' ),
					'i18n_insufficient_stock'            => sprintf( _x( '<p class="stock out-of-stock insufficient-stock">%1$s &rarr; %2$s</p>', 'insufficient stock - composite template', 'wpdrift-woocommerce-modules' ), __( 'Insufficient stock', 'wpdrift-woocommerce-modules' ), '%s' ),
					'i18n_comma_sep'                     => sprintf( _x( '%1$s, %2$s', 'comma-separated items', 'wpdrift-woocommerce-modules' ), '%s', '%v' ),
					'i18n_reload_threshold_exceeded'     => __( 'Loading &quot;%s&quot; options is taking a bit longer than usual. Would you like to keep trying?', 'wpdrift-woocommerce-modules' ),
					'i18n_step_not_accessible'           => __( 'The configuration step you have requested to view (&quot;%s&quot;) is currently not accessible.', 'wpdrift-woocommerce-modules' ),
					'i18n_page_of_pages'                 => sprintf( __( 'Page %1$s of %2$s', 'wpdrift-woocommerce-modules' ), '%p', '%t' ),
					'i18n_loading_options'               => __( '<span class="source">%s</span> &rarr; updating options&hellip;', 'wpdrift-woocommerce-modules' ),
					'i18n_selection_request_timeout'     => __( 'Your selection could not be updated. If the issue persists, please refresh the page and try again.', 'wpdrift-woocommerce-modules' ),
					'currency_symbol'                    => get_woocommerce_currency_symbol(),
					'currency_position'                  => stripslashes( get_option( 'woocommerce_currency_pos' ) ),
					'currency_format_num_decimals'       => absint( get_option( 'woocommerce_price_num_decimals' ) ),
					'currency_format_decimal_sep'        => stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ),
					'currency_format_thousand_sep'       => stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ),
					'currency_format_trim_zeros'         => false === apply_filters( 'woocommerce_price_trim_zeros', false ) ? 'no' : 'yes',
					'script_debug_level'                 => array(), /* 'debug', 'debug:views', 'debug:events', 'debug:models', 'debug:scenarios' */
					'show_quantity_buttons'              => 'no',
					'is_pao_installed'                   => class_exists( 'WC_Product_Addons' ) && defined( 'WC_PRODUCT_ADDONS_VERSION' ) ? 'yes' : 'no',
					'relocated_content_reset_on_return'  => 'yes',
					'is_wc_version_gte_2_3'              => 'yes',
					'is_wc_version_gte_2_4'              => 'yes',
					'is_wc_version_gte_2_7'              => 'yes',
					'use_wc_ajax'                        => Compatibility::use_wc_ajax() ? 'yes' : 'no',
					'price_display_suffix'               => get_option( 'woocommerce_price_display_suffix' ),
					'prices_include_tax'                 => wc_cp_prices_include_tax(),
					'tax_display_shop'                   => wc_cp_tax_display_shop(),
					'calc_taxes'                         => wc_cp_calc_taxes(),
					'photoswipe_enabled'                 => current_theme_supports( 'wc-product-gallery-lightbox' ) ? 'yes' : 'no',
					'empty_product_data'                 => Product::get_placeholder_product_data( 'no-product' ),
					'force_min_max_qty_input'            => 'yes',
					'accessible_focus_enabled'           => 'yes',
					'composite_config'                   => $this->wc_cp_get_composite_config(),
				)
			);

			wp_localize_script( 'wpdrift-woocommerce-modules', 'wc_composite_params', $params );
			wp_localize_script( 'wpdrift-woocommerce-modules-composite-popup', 'wc_composite_params', $params );

			$posts         = [];
			$products_data = [];
			$posts         = is_shop() ? $wp_query->posts : get_posts(
				[
					'post_type'   => 'product',
					'numberposts' => -1,
				]
			);

			foreach ( $posts as $post ) {
				$product = wc_get_product( $post->ID );
				if ( ! $product->is_type( 'composite' ) ) {
					continue;
				}

				$image_src = '';

				if ( $product->get_image_id() ) {
					$image_src = wp_get_attachment_image_src( $product->get_image_id(), 'medium' );
				}

				$product_data = [
					'title'        => $product->get_title(),
					'description'  => $product->get_short_description(),
					'min_quantity' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
					'max_quantity' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
					'layout_style' => $product->get_composite_layout_style(),
					'image_src'    => $image_src ? $image_src[0] : wc_placeholder_img_src(),
				];

				$components          = $product->get_components();
				$prepared_components = [];
				foreach ( $components as $coponent_id => $component ) {
					$prepared_components[] = [
						'id'              => $component->get_id(),
						'title'           => $component->get_title(),
						'description'     => wp_strip_all_tags( $component->get_description() ),
						'options'         => $component->view->get_options_data(),
						'options_style'   => $component->get_options_style(),
						'multiple'        => $component->is_multiple(),
						'selected_option' => $component->view->get_selected_option(),
						'product_id'      => $component->get_default_option(),
						'quantity'        => $component->get_quantity( 'min' ),
						'quantity_min'    => $component->get_quantity( 'min' ),
						'quantity_max'    => $component->get_quantity( 'max' ),
						'discount'        => $component->get_discount(),
						'optional'        => $component->is_optional() ? 'yes' : 'no',
						'static'          => $component->is_static() ? 'yes' : 'no',
						'title'           => $component->get_title(),
						'composite_id'    => $product->get_id(),
						'type'            => $product->get_type(),
					];
				}

				$product_data['add_to_cart_text'] = $product->add_to_cart_text();
				$product_data['add_to_cart_url']  = esc_url( $product->add_to_cart_url() );
				$product_data['price_data']       = $product->get_composite_price_data();
				$product_data['components']       = $prepared_components;
				$product_data['quantity']         = 1;

				$products_data[ $post->ID ] = $product_data;
			}

			wp_localize_script( 'wpdrift-woocommerce-modules-composite-popup', 'productsData', $products_data );

			wp_enqueue_style(
				'wpdrift-woocommerce-modules', // Handle.
				WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_URL . '/build/index.css',
				[ 'dashicons' ], // Dependency to include the CSS after it.
				$asset_file['version'] // Version: File modification time.
			);
		}
	}

	/**
	 * Enqeue js that wraps child line items in a div in order to apply indentation reliably.
	 * This obviously sucks but if you can find a CSS-only way to do it better that works reliably with any theme out there, drop us a line, will you?
	 *
	 * @return void
	 */
	private function enqueue_composited_table_item_js() {
		/**
		 * 'woocommerce_composited_table_item_js_enqueued' filter.
		 *
		 * Use this filter to get rid of this ugly hack:
		 * Return 'false' and add your own CSS to indent '.component_table_item' elements.
		 *
		 * @since  0.1.0
		 *
		 * @param  boolean  $is_enqueued
		 */
		$is_enqueued = apply_filters( 'woocommerce_composited_table_item_js_enqueued', $this->enqueued_composited_table_item_js );

		if ( ! $is_enqueued ) {

			wc_enqueue_js(
				"
				var wc_cp_wrap_composited_table_item = function() {
					jQuery( '.component_table_item td.product-name' ).each( function() {
						var el = jQuery( this );
						if ( el.find( '.component-name' ).length === 0 ) {
							el.wrapInner( '<div class=\"component-name component_table_item_indent\"></div>' );
						}
					} );
				};

				jQuery( 'body' ).on( 'updated_checkout updated_cart_totals', function() {
					wc_cp_wrap_composited_table_item();
				} );

				wc_cp_wrap_composited_table_item();
			"
			);

			$this->enqueued_composited_table_item_js = true;
		}
	}

	/*
	|--------------------------------------------------------------------------
	| Single-product.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Display info notice when calculating the price of a composite product.
	 */
	public function add_price_calc_task_notice() {
		global $product;

		if ( is_object( $product ) && $product->is_type( 'composite' ) && current_user_can( 'manage_woocommerce' ) ) {

			$shop_price_calc_notice = '';
			$shop_price_calc_status = $product->get_shop_price_calc_status();

			if ( 'pending' === $shop_price_calc_status ) {
				$shop_price_calc_notice = sprintf( __( 'The catalog price of &quot;%s&quot; is currently being calculated in the background. During this time, its price will be hidden. <strong>Note</strong>: This message is visible to store managers only.', 'wpdrift-woocommerce-modules' ), $product->get_title() );
			} elseif ( 'failed' === $shop_price_calc_status ) {
				$shop_price_calc_notice = sprintf( __( 'The catalog price of &quot;%s&quot; could not be calculated within the default time limit. This may happen when adding Scenarios to Composite Products that contain many Components and a large number of product/variation options. For assistance, please check out the <a href="https://docs.woocommerce.com/document/composite-products/composite-products-configuration/#catalog-price" target="_blank">documentation</a>, or <a href="https://woocommerce.com/my-account/marketplace-ticket-form/" target="_blank">get in touch with support</a>. <strong>Note</strong>: This message is visible to store managers only.', 'wpdrift-woocommerce-modules' ), $product->get_title() );
			}

			if ( $shop_price_calc_notice ) {
				wc_add_notice( $shop_price_calc_notice, 'notice' );
			}
		}
	}

	/**
	 * Display info notice when editing a composite product.
	 */
	public function add_edit_in_cart_notice() {
		global $product;

		if ( $product->is_type( 'composite' ) && isset( $_GET['update-composite'] ) ) {
			$updating_cart_key = wc_clean( $_GET['update-composite'] );
			if ( isset( WC()->cart->cart_contents[ $updating_cart_key ] ) ) {
				$notice = sprintf( __( 'You are currently editing &quot;%1$s&quot;. When finished, click the <strong>Update Cart</strong> button.', 'wpdrift-woocommerce-modules' ), $product->get_title() );
				wc_add_notice( $notice, 'notice' );
			}
		}
	}

	/**
	 * Modify structured data for composite products.
	 *
	 * @param  array       $data
	 * @param  WC_Product  $product
	 * @return array
	 */
	public function structured_product_data( $data, $product ) {
		if ( is_object( $product ) && $product->is_type( 'composite' ) ) {
			$data['price'] = $product->get_composite_price();
		}

		return $data;
	}

	/*
	|--------------------------------------------------------------------------
	| Cart.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Outputs a formatted subtotal.
	 *
	 * @param  WC_Product  $product
	 * @param  string      $subtotal
	 * @return string
	 */
	public function format_subtotal( $product, $subtotal ) {
		$cart               = WC()->cart;
		$taxable            = $product->is_taxable();
		$formatted_subtotal = wc_price( $subtotal );

		if ( $taxable ) {

			$tax_subtotal = Compatibility::is_wc_version_gte( '3.2' ) ? $cart->get_subtotal_tax() : $cart->tax_total;

			if ( 'excl' === get_option( 'woocommerce_tax_display_cart' ) ) {

				if ( wc_prices_include_tax() && $tax_subtotal > 0 ) {
					$formatted_subtotal .= ' <small class="tax_label">' . WC()->countries->ex_tax_or_vat() . '</small>';
				}
			} else {

				if ( ! wc_prices_include_tax() && $tax_subtotal > 0 ) {
					$formatted_subtotal .= ' <small class="tax_label">' . WC()->countries->inc_tax_or_vat() . '</small>';
				}
			}
		}

		return $formatted_subtotal;
	}

	/**
	 * Modifies the cart.php & review-order.php templates formatted html prices visibility depending on pricing strategy.
	 *
	 * @param  string  $price
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_price( $price, $cart_item, $cart_item_key ) {
		if ( empty( WC()->cart ) ) {
			return $price;
		}

		if ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$price = $this->get_child_cart_item_price( $price, $cart_item, $cart_item_key );
		} elseif ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {
			$price = $this->get_container_cart_item_price( $price, $cart_item, $cart_item_key );
		}

		return $price;
	}

	/**
	 *
	 * Aggregates parent + child cart item prices.
	 *
	 * @param  string  $price
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	private function get_container_cart_item_price( $price, $cart_item, $cart_item_key ) {
		if ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {

			$aggregate_prices = apply_filters( 'woocommerce_add_composited_cart_item_prices', true, $cart_item, $cart_item_key );

			if ( $aggregate_prices ) {

				$tax_display_cart  = get_option( 'woocommerce_tax_display_cart' );
				$child_items_price = 0.0;
				$calc_type         = 'excl' === $tax_display_cart ? 'excl_tax' : 'incl_tax';
				$composite_price   = Products::get_product_price(
					$cart_item['data'],
					array(
						'price' => $cart_item['data']->get_price(),
						'calc'  => $calc_type,
					)
				);
				$child_cart_items  = wc_cp_get_composited_cart_items( $cart_item, WC()->cart->cart_contents, false, true );

				foreach ( $child_cart_items as $child_cart_item ) {

					$child_item_qty     = $child_cart_item['data']->is_sold_individually() ? 1 : $child_cart_item['quantity'] / $cart_item['quantity'];
					$child_item_price   = Products::get_product_price(
						$child_cart_item['data'],
						array(
							'price' => $child_cart_item['data']->get_price(),
							'calc'  => $calc_type,
							'qty'   => $child_item_qty,
						)
					);
					$child_items_price += wc_format_decimal( (float) $child_item_price, wc_cp_price_num_decimals() );
				}

				$price = wc_price( (float) $composite_price + $child_items_price );

			} elseif ( empty( $cart_item['line_subtotal'] ) ) {

				$child_items          = wc_cp_get_composited_cart_items( $cart_item, WC()->cart->cart_contents, false, true );
				$child_item_subtotals = wp_list_pluck( $child_items, 'line_subtotal' );

				if ( array_sum( $child_item_subtotals ) > 0 ) {
					$price = '';
				}
			}
		}

		return $price;
	}

	/**
	 *
	 * Modifies child cart item prices.
	 *
	 * @since  3.14.0
	 *
	 * @param  string  $price
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	private function get_child_cart_item_price( $price, $cart_item, $cart_item_key ) {
		if ( $composite_container_item_key = wc_cp_get_composited_cart_item_container( $cart_item, WC()->cart->cart_contents, true ) ) {

			$composite_container_item = WC()->cart->cart_contents[ $composite_container_item_key ];

			$product_id       = $cart_item['product_id'];
			$component_id     = $cart_item['composite_item'];
			$component_option = $composite_container_item['data']->get_component_option( $component_id, $product_id );

			if ( $component_option ) {
				if ( false === $component_option->is_priced_individually() && empty( $cart_item['line_subtotal'] ) ) {
					$price = '';
				} elseif ( false === $component_option->get_component()->is_subtotal_visible( 'cart' ) ) {
					$price = '';
				} elseif ( $price && apply_filters( 'woocommerce_add_composited_cart_item_prices', true, $composite_container_item, $composite_container_item_key ) ) {
					$price = '<span class="component_' . ( $this->is_cart_widget() ? 'mini_cart' : 'table' ) . '_item_price">' . $price . '</span>';
				}
			}
		}

		return $price;
	}

	/**
	 *
	 * Aggregates parent + child cart item subtotals.
	 *
	 * @param  string  $subtotal
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	private function get_container_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {
		if ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {

			/**
			 * Controls whether to include composited cart item subtotals in the container cart item subtotal.
			 *
			 * @param  boolean  $add
			 * @param  array    $container_cart_item
			 * @param  string   $container_cart_item_key
			 */
			$aggregate_subtotals = apply_filters( 'woocommerce_add_composited_cart_item_subtotals', true, $cart_item, $cart_item_key );

			if ( $aggregate_subtotals ) {

				$tax_display_cart  = get_option( 'woocommerce_tax_display_cart' );
				$child_items_price = 0.0;
				$calc_type         = 'excl' === $tax_display_cart ? 'excl_tax' : 'incl_tax';
				$composite_price   = Products::get_product_price(
					$cart_item['data'],
					array(
						'price' => $cart_item['data']->get_price(),
						'calc'  => $calc_type,
						'qty'   => $cart_item['quantity'],
					)
				);
				$child_cart_items  = wc_cp_get_composited_cart_items( $cart_item, WC()->cart->cart_contents, false, true );

				foreach ( $child_cart_items as $child_cart_item ) {

					$child_item_price   = Products::get_product_price(
						$child_cart_item['data'],
						array(
							'price' => $child_cart_item['data']->get_price(),
							'calc'  => $calc_type,
							'qty'   => $child_cart_item['quantity'],
						)
					);
					$child_items_price += wc_format_decimal( (float) $child_item_price, wc_cp_price_num_decimals() );
				}

				$subtotal = (float) $composite_price + $child_items_price;
				$subtotal = $this->format_subtotal( $cart_item['data'], $subtotal );

			} elseif ( empty( $cart_item['line_subtotal'] ) ) {

				$child_items          = wc_cp_get_composited_cart_items( $cart_item, WC()->cart->cart_contents, false, true );
				$child_item_subtotals = wp_list_pluck( $child_items, 'line_subtotal' );

				if ( array_sum( $child_item_subtotals ) > 0 ) {
					$subtotal = '';
				}
			}
		}

		return $subtotal;
	}

	/**
	 *
	 * Modifies child cart item subtotals.
	 *
	 * @since  3.14.0
	 *
	 * @param  string  $subtotal
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	private function get_child_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {

		if ( $composite_container_item_key = wc_cp_get_composited_cart_item_container( $cart_item, WC()->cart->cart_contents, true ) ) {

			$composite_container_item = WC()->cart->cart_contents[ $composite_container_item_key ];

			$product_id   = $cart_item['product_id'];
			$component_id = $cart_item['composite_item'];

			if ( $component_option = $composite_container_item['data']->get_component_option( $component_id, $product_id ) ) {

				if ( false === $component_option->get_component()->is_subtotal_visible( 'cart' ) ) {
					$subtotal = '';
				} elseif ( false === $component_option->is_priced_individually() && empty( $cart_item['line_subtotal'] ) ) {
					$subtotal = '';
				} elseif ( $subtotal && apply_filters( 'woocommerce_add_composited_cart_item_subtotals', true, $composite_container_item, $composite_container_item_key ) ) {
					$subtotal_string = $this->is_cart_widget() ? $subtotal : sprintf( _x( '%1$s: %2$s', 'component subtotal', 'wpdrift-woocommerce-modules' ), __( 'Subtotal', 'wpdrift-woocommerce-modules' ), $subtotal );
					$subtotal        = '<span class="component_' . ( $this->is_cart_widget() ? 'mini_cart' : 'table' ) . '_item_subtotal">' . $subtotal_string . '</span>';
				}
			}
		}

		return $subtotal;
	}

	/**
	 * Modifies line item subtotals in the 'cart.php' & 'review-order.php' templates.
	 *
	 * @param  string  $price
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_subtotal( $subtotal, $cart_item, $cart_item_key ) {

		if ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$subtotal = $this->get_child_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key );
		} elseif ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {
			$subtotal = $this->get_container_cart_item_subtotal( $subtotal, $cart_item, $cart_item_key );
		}

		return $subtotal;
	}

	/**
	 * Composited item quantities may be changed between min_q and max_q.
	 *
	 * @param  string  $quantity
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_quantity( $quantity, $cart_item_key ) {

		$cart_item = WC()->cart->cart_contents[ $cart_item_key ];

		if ( $parent = wc_cp_get_composited_cart_item_container( $cart_item ) ) {

			$component_id       = $cart_item['composite_item'];
			$composite_item_key = array_search( $cart_item['product_id'], array_column( $cart_item['composite_data'][ $component_id ], 'product_id' ) );

			if ( $cart_item['composite_data'][ $component_id ][ $composite_item_key ]['quantity_min'] === $cart_item['composite_data'][ $component_id ][ $composite_item_key ]['quantity_max'] ) {

				$quantity = $cart_item['quantity'];

			} else {

				$parent_quantity = $parent['quantity'];
				$max_stock       = $cart_item['data']->managing_stock() && ! $cart_item['data']->backorders_allowed() ? $cart_item['data']->get_stock_quantity() : '';
				$max_stock       = $max_stock === null ? '' : $max_stock;

				if ( '' !== $max_stock ) {
					$max_qty = '' !== $cart_item['composite_data'][ $component_id ][ $composite_item_key ]['quantity_max'] ? min( $max_stock, $parent_quantity * $cart_item['composite_data'][ $component_id ][ $composite_item_key ]['quantity_max'] ) : $max_stock;
				} else {
					$max_qty = '' !== $cart_item['composite_data'][ $component_id ][ $composite_item_key ]['quantity_max'] ? $parent_quantity * $cart_item['composite_data'][ $component_id ][ $composite_item_key ]['quantity_max'] : '';
				}

				$min_qty = $parent_quantity * $cart_item['composite_data'][ $component_id ][ $composite_item_key ]['quantity_min'];

				if ( ( $max_qty > $min_qty || '' === $max_qty ) && ! $cart_item['data']->is_sold_individually() ) {

					$component_quantity = woocommerce_quantity_input(
						array(
							'input_name'  => "cart[{$cart_item_key}][qty]",
							'input_value' => $cart_item['quantity'],
							'min_value'   => $min_qty,
							'max_value'   => $max_qty,
							'step'        => $parent_quantity,
						),
						$cart_item['data'],
						false
					);

					$quantity = $component_quantity;

				} else {
					$quantity = $cart_item['quantity'];
				}
			}
		}

		return $quantity;
	}

	/**
	 * Composited items can't be removed individually from the cart.
	 *
	 * @param  string  $link
	 * @param  string  $cart_item_key
	 * @return string
	 */
	public function cart_item_remove_link( $link, $cart_item_key ) {

		if ( isset( WC()->cart->cart_contents[ $cart_item_key ]['composite_data'] ) && ! empty( WC()->cart->cart_contents[ $cart_item_key ]['composite_parent'] ) ) {

			$parent_key = WC()->cart->cart_contents[ $cart_item_key ]['composite_parent'];

			if ( isset( WC()->cart->cart_contents[ $parent_key ] ) ) {
				return '';
			}
		}

		return $link;
	}

	/**
	 * Change the tr class of composite parent/child items in cart templates to allow their styling.
	 *
	 * @param  string  $classname
	 * @param  array   $cart_item
	 * @return string
	 */
	public function cart_item_class( $classname, $cart_item ) {

		if ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$classname .= ' component_table_item component_parent_' . $cart_item['composite_parent'];
		} elseif ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {
			$classname .= ' component_container_table_item';
		}

		return $classname;
	}

	/**
	 * Change the li class of composite parent/child items in mini-cart templates to allow their styling.
	 *
	 * @param  string  $classname
	 * @param  array   $cart_item
	 * @return string
	 */
	public function mini_cart_item_class( $classname, $cart_item ) {

		if ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$classname .= ' component_mini_cart_item';
		} elseif ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {
			$classname .= ' component_container_mini_cart_item';
		}

		return $classname;
	}

	/**
	 * Adds order item title preambles to cart items ( Composite Attribute Descriptions ).
	 *
	 * @param  string   $content
	 * @param  array    $cart_item
	 * @param  string   $cart_item_key
	 * @return string
	 */
	public function cart_item_component_name( $content, $cart_item, $cart_item_key, $append_qty = false ) {

		if ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {

			$product = $cart_item['data'];

			if ( function_exists( 'is_cart' ) && is_cart() && ! $this->is_cart_widget() && $product->is_type( 'composite' ) ) {

				if ( $product->is_editable_in_cart() ) {

					$edit_in_cart_link = esc_url( add_query_arg( array( 'update-composite' => $cart_item_key ), $product->get_permalink( $cart_item ) ) );
					$edit_in_cart_text = _x( 'Edit', 'edit in cart link text', 'wpdrift-woocommerce-modules' );
					$content           = sprintf( _x( '%1$s<br/><a class="edit_composite_in_cart_text edit_in_cart_text" href="%2$s"><small>%3$s</small></a>', 'edit in cart text', 'wpdrift-woocommerce-modules' ), $content, $edit_in_cart_link, $edit_in_cart_text );
				}

				/**
				 * 'woocommerce_display_composite_container_cart_item_data' filter.
				 *
				 * @since  3.14.0
				 *
				 * @param  array   $cart_item
				 * @param  string  $cart_item_key
				 */
				if ( apply_filters( 'woocommerce_display_composite_container_cart_item_data', false, $cart_item, $cart_item_key ) ) {
					$content .= $this->get_composite_container_cart_item_data( $cart_item, true );
				}
			}
		} elseif ( $composite_container_item = wc_cp_get_composited_cart_item_container( $cart_item ) ) {

			$component_id    = $cart_item['composite_item'];
			$component       = $composite_container_item['data']->get_component( $component_id );
			$component_title = $component ? $component->get_title() : '';

			if ( ! $component_title ) {
				return $content;
			}

			if ( is_checkout() || ( isset( $_REQUEST['action'] ) && 'woocommerce_update_order_review' === $_REQUEST['action'] ) ) {
				$append_qty = true;
			}

			if ( $append_qty ) {

				/**
				 * Filter qty html.
				 *
				 * @param  array   $cart_item
				 * @param  string  $cart_item_key
				 */
				$item_quantity = apply_filters( 'woocommerce_composited_cart_item_quantity_html', '<strong class="composited_product_quantity">' . sprintf( _x( ' &times; %s', 'qty string', 'wpdrift-woocommerce-modules' ), $cart_item['quantity'] ) . '</strong>', $cart_item, $cart_item_key );

			} else {

				$item_quantity = '';
			}

			$product_title = $content . $item_quantity;
			$item_data     = array(
				'key'   => $component_title,
				'value' => $product_title,
			);

			$this->enqueue_composited_table_item_js();

			ob_start();

			wc_get_template( 'component-item.php', array( 'component_data' => $item_data ), '', Module::instance()->plugin_path() . '/templates/' );

			$content = apply_filters( 'woocommerce_composited_cart_item_name', ob_get_clean(), $content, $cart_item, $cart_item_key, $item_quantity );
		}

		return $content;
	}

	/**
	 * Delete composited item quantity from the review-order.php template. Quantity is inserted into the product name by 'cart_item_component_name'.
	 *
	 * @param  string   $quantity
	 * @param  array    $cart_item
	 * @param  string   $cart_key
	 * @return string
	 */
	public function cart_item_component_quantity( $quantity, $cart_item, $cart_key ) {

		if ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$quantity = '';
		}

		return $quantity;
	}

	/**
	 * Filters the reported number of cart items - counts only composite containers.
	 *
	 * @param  int       $count
	 * @param  WC_Order  $order
	 * @return int
	 */
	public function cart_contents_count( $count ) {

		$cart     = WC()->cart->get_cart();
		$subtract = 0;

		foreach ( $cart as $key => $value ) {

			if ( wc_cp_is_composited_cart_item( $value ) ) {
				$subtract += $value['quantity'];
			}
		}

		return $count - $subtract;
	}

	/**
	 * Rendering cart widget?
	 *
	 * @since  3.14.0
	 * @return boolean
	 */
	protected function is_cart_widget() {
		return did_action( 'woocommerce_before_mini_cart' ) > did_action( 'woocommerce_after_mini_cart' );
	}

	/**
	 * Add cart widget filters.
	 */
	public function add_cart_widget_filters() {
		add_filter( 'woocommerce_widget_cart_item_visible', array( $this, 'cart_widget_item_visible' ), 10, 3 );
		add_filter( 'woocommerce_mini_cart_item_class', array( $this, 'mini_cart_item_class' ), 10, 2 );
		add_filter( 'woocommerce_widget_cart_item_quantity', array( $this, 'cart_widget_item_qty' ), 10, 3 );
		add_filter( 'woocommerce_cart_item_name', array( $this, 'cart_widget_item_name' ), 10, 3 );
		add_filter( 'woocommerce_get_item_data', array( $this, 'cart_widget_container_item_data' ), 10, 2 );
	}

	/**
	 * Remove cart widget filters.
	 */
	public function remove_cart_widget_filters() {
		remove_filter( 'woocommerce_widget_cart_item_visible', array( $this, 'cart_widget_item_visible' ), 10, 3 );
		remove_filter( 'woocommerce_mini_cart_item_class', array( $this, 'mini_cart_item_class' ), 10, 2 );
		remove_filter( 'woocommerce_widget_cart_item_quantity', array( $this, 'cart_widget_item_qty' ), 10, 3 );
		remove_filter( 'woocommerce_cart_item_name', array( $this, 'cart_widget_item_name' ), 10, 3 );
		remove_filter( 'woocommerce_get_item_data', array( $this, 'cart_widget_container_item_data' ), 10, 2 );
	}

	/**
	 * Tweak composite container qty.
	 *
	 * @param  bool    $qty
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return bool
	 */
	public function cart_widget_item_qty( $qty, $cart_item, $cart_item_key ) {

		if ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {

			if ( Module::instance()->cart->container_cart_item_contains( $cart_item, 'sold_individually' ) ) {
				$qty = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $cart_item['data'], $cart_item['quantity'] ), $cart_item, $cart_item_key );
			}
		} elseif ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$qty = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $cart_item['data'], $cart_item['quantity'] ), $cart_item, $cart_item_key );
		}

		return $qty;
	}

	/**
	 * Do not show composited items.
	 *
	 * @param  bool    $qty
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return bool
	 */
	public function cart_widget_item_visible( $show, $cart_item, $cart_item_key ) {

		if ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$show = false;
		}

		return $show;
	}

	/**
	 * Tweak composite container/child name.
	 *
	 * @param  bool    $qty
	 * @param  array   $cart_item
	 * @param  string  $cart_item_key
	 * @return bool
	 */
	public function cart_widget_item_name( $name, $cart_item, $cart_item_key ) {

		if ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {

			if ( $cart_item['quantity'] > 1 && Module::instance()->cart->container_cart_item_contains( $cart_item, 'sold_individually' ) ) {
				$name = Product::get_title_string( $name, $cart_item['quantity'] );
			}
		} elseif ( wc_cp_is_composited_cart_item( $cart_item ) ) {
			$name = Product::get_title_string( $name, $cart_item['quantity'] );
		}

		return $name;
	}

	/**
	 * Adds content data as parent item meta in the mini-cart.
	 *
	 * @param  array  $data
	 * @param  array  $cart_item
	 * @return array
	 */
	public function cart_widget_container_item_data( $data, $cart_item ) {

		if ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {
			$data = array_merge( $data, $this->get_composite_container_cart_item_data( $cart_item ) );
		}

		return $data;
	}

	/**
	 * Gets bundled content data.
	 *
	 * @since  3.14.0
	 *
	 * @param  array  $cart_item
	 * @return array
	 */
	public function get_composite_container_cart_item_data( $cart_item, $formatted = false ) {

		$data = array();

		$child_cart_items = wc_cp_get_composited_cart_items( $cart_item );

		if ( ! empty( $child_cart_items ) ) {

			$child_item_descriptions = array();

			foreach ( $child_cart_items as $child_cart_item_key => $child_cart_item ) {

				$component_id           = $child_cart_item['composite_item'];
				$child_item_description = '';

				if ( $component = $cart_item['data']->get_component( $component_id ) ) {

					$child_item_title       = $component->get_title();
					$child_item_description = Product::get_title_string( $child_cart_item['data']->get_name(), $child_cart_item['quantity'] );

					/**
					 * 'woocommerce_composite_container_cart_item_data_value' filter.
					 *
					 * @since  3.14.0
					 *
					 * @param  string  $child_item_description
					 * @param  array   $child_cart_item
					 * @param  string  $child_cart_item_key
					 */
					$child_item_description = apply_filters( 'woocommerce_composite_container_cart_item_data_value', $child_item_description, $child_cart_item, $child_cart_item_key );
				}

				if ( $child_item_description ) {
					$data[] = array(
						'key'   => $child_item_title,
						'value' => $child_item_description,
					);
				}
			}
		}

		if ( $formatted ) {

			$formatted_data = '';

			if ( ! empty( $data ) ) {

				ob_start();

				wc_get_template(
					'cart/composite-container-item-data.php',
					array(
						'data' => $data,
					),
					false,
					Module::instance()->plugin_path() . '/templates/'
				);

				$formatted_data = ob_get_clean();
			}

			$data = $formatted_data;
		}

		return $data;
	}

	/*
	|--------------------------------------------------------------------------
	| Orders.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Modify the subtotal of order items depending on their pricing setup.
	 *
	 * @param  string         $subtotal
	 * @param  WC_Order_Item  $item
	 * @param  WC_Order       $order
	 * @return string
	 */
	public function order_item_subtotal( $subtotal, $item, $order ) {

		// If it's a composited item...
		if ( $parent_item = wc_cp_get_composited_order_item_container( $item, $order ) ) {

			$item_priced_individually = $item->get_meta( '_component_priced_individually', true );
			$item_price_hidden        = $item->get_meta( '_component_subtotal_hidden', true );

			// Back-compat.
			if ( ! in_array( $item_priced_individually, array( 'yes', 'no' ) ) ) {
				$item_priced_individually = isset( $parent_item['per_product_pricing'] ) ? $parent_item['per_product_pricing'] : get_post_meta( $parent_item['product_id'], '_bto_per_product_pricing', true );
			}

			$hide_subtotal = ( 'no' === $item_priced_individually && $item->get_subtotal( 'edit' ) == 0 ) || 'yes' === $item_price_hidden;

			if ( Module::instance()->compatibility->is_pip( 'invoice' ) && $item->get_subtotal( 'edit' ) > 0 ) {
				$hide_subtotal = false;
			}

			if ( $hide_subtotal ) {

				$subtotal = '';

			} elseif ( false === Module::instance()->compatibility->is_pip( 'invoice' ) ) {

				/**
				 * Controls whether to include composited order item subtotals in the container order item subtotal.
				 *
				 * @param  boolean   $add
				 * @param  array     $container_order_item
				 * @param  WC_Order  $order
				 */
				if ( apply_filters( 'woocommerce_add_composited_order_item_subtotals', true, $parent_item, $order ) ) {
					$subtotal = '<span class="component_table_item_subtotal">' . sprintf( _x( '%1$s: %2$s', 'component subtotal', 'wpdrift-woocommerce-modules' ), __( 'Subtotal', 'wpdrift-woocommerce-modules' ), $subtotal ) . '</span>';
				}
			}

			// If it's a parent item...
		} elseif ( wc_cp_is_composite_container_order_item( $item ) ) {

			if ( ! isset( $item->child_subtotals_added ) ) {

				/** Documented right above. Look up. See? */
				$aggregate_subtotals = apply_filters( 'woocommerce_add_composited_order_item_subtotals', true, $item, $order ) && false === Module::instance()->compatibility->is_pip( 'invoice' );
				$children            = wc_cp_get_composited_order_items( $item, $order, false, true );

				if ( $aggregate_subtotals ) {

					if ( ! empty( $children ) ) {

						// Create a clone to ensure the original item will not be modified.
						$cloned_item = clone $item;

						foreach ( $children as $child ) {
							$cloned_item->set_subtotal( $cloned_item->get_subtotal( 'edit' ) + $child->get_subtotal( 'edit' ) );
							$cloned_item->set_subtotal_tax( $cloned_item->get_subtotal_tax( 'edit' ) + $child->get_subtotal_tax( 'edit' ) );
						}

						$cloned_item->child_subtotals_added = 'yes';

						$subtotal = $order->get_formatted_line_subtotal( $cloned_item );
					}
				} elseif ( sizeof( $children ) && $item->get_subtotal( 'edit' ) == 0 ) {
					$subtotal = '';
				}
			}
		}

		return $subtotal;
	}

	/**
	 * Adds component title preambles to order-details template.
	 *
	 * @param  string  $content
	 * @param  array   $order_item
	 * @return string
	 */
	public function order_item_component_name( $content, $order_item ) {

		if ( false !== $this->order_item_order && wc_cp_is_composited_order_item( $order_item, $this->order_item_order ) ) {

			$component_id    = $order_item['composite_item'];
			$composite_data  = maybe_unserialize( $order_item['composite_data'] );
			$component_title = $composite_data[ $component_id ]['title'];

			if ( did_action( 'woocommerce_view_order' ) || did_action( 'woocommerce_thankyou' ) || did_action( 'before_woocommerce_pay' ) || did_action( 'woocommerce_account_view-subscription_endpoint' ) ) {

				/**
				 * Filter 'woocommerce_composited_order_item_quantity_html'.
				 *
				 * @param  WC_Order_Item  $order_item
				 */
				$item_quantity = apply_filters( 'woocommerce_composited_order_item_quantity_html', '<strong class="composited_product_quantity">' . sprintf( _x( ' &times; %s', 'qty string', 'wpdrift-woocommerce-modules' ), $order_item['qty'] ) . '</strong>', $order_item );

				$this->enqueue_composited_table_item_js();

			} else {

				$item_quantity = '';
			}

			$product_title = $content . $item_quantity;
			$item_data     = array(
				'key'   => $component_title,
				'value' => $product_title,
			);

			ob_start();

			wc_get_template( 'component-item.php', array( 'component_data' => $item_data ), '', Module::instance()->plugin_path() . '/templates/' );

			/**
			 * Filter 'woocommerce_composited_order_item_name'.
			 *
			 * @param  strong         $order_item_name
			 * @param  strong         $original_name
			 * @param  WC_Order_Item  $order_item
			 * @param  WC_Order       $order
			 * @param  int            $qty
			 */
			$content = apply_filters( 'woocommerce_composited_order_item_name', ob_get_clean(), $content, $order_item, $this->order_item_order, $item_quantity );
		}

		return $content;
	}

	/**
	 * Delete composited item quantity from order-details template. Quantity is inserted into the product name by 'order_item_component_name'.
	 *
	 * @param  string  $content
	 * @param  array   $order_item
	 * @return string
	 */
	public function order_item_component_quantity( $content, $order_item ) {

		if ( false !== $this->order_item_order && wc_cp_is_composited_order_item( $order_item, $this->order_item_order ) ) {
			$this->order_item_order = false;
			$content                = '';
		}

		return $content;
	}

	/**
	 * Add 'component_table_item' class to child items in order templates.
	 *
	 * @param  string  $classname
	 * @param  array   $order_item
	 * @return string
	 */
	public function order_item_class( $classname, $order_item, $order ) {

		if ( wc_cp_is_composited_order_item( $order_item, $order ) ) {
			$classname             .= ' component_table_item';
			$this->order_item_order = $order;
		} elseif ( wc_cp_is_composite_container_order_item( $order_item ) ) {
			$classname .= ' component_container_table_item';
		}

		return $classname;
	}

	/**
	 * Filters the reported number of order items - counts only composite containers.
	 *
	 * @param  int       $count
	 * @param  string    $type
	 * @param  WC_Order  $order
	 * @return int
	 */
	public function order_item_count( $count, $type, $order ) {

		$subtract = 0;

		if ( function_exists( 'is_account_page' ) && is_account_page() ) {
			foreach ( $order->get_items() as $item ) {
				if ( wc_cp_is_composited_order_item( $item, $order ) ) {
					$subtract += $item->get_quantity();
				}
			}
		}

		return $count - $subtract;
	}

	/**
	 * Sets the 'order_item_order' prop.
	 *
	 * @param  WC_Order  $order
	 */
	public function set_order_item_order( $order ) {
		$this->order_item_order = $order;
	}

	/**
	 * Indent composited items in emails.
	 *
	 * @param  string  $css
	 * @return string
	 */
	public function email_styles( $css ) {
		$css .= ' .component_table_item td:first-of-type { padding-left: 2.5em !important; } .component_table_item td { border-top: none; font-size: 0.875em; } .component_table_item td dl.component, .component_table_item td dl.component dt, .component_table_item td dl.component dd { margin: 0; padding: 0; } .component_table_item td dl.component dt { font-weight: bold; } .component_table_item td dl.component dd p { margin-bottom: 0 !important; } #body_content table tr.component_table_item td ul.wc-item-meta { font-size: inherit; } ';
		return $css;
	}

	/*
	|--------------------------------------------------------------------------
	| Other.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Inserts bundle contents after main wishlist bundle item is displayed.
	 *
	 * @param  array  $item
	 * @param  array  $wishlist
	 * @return void
	 */
	public function wishlist_after_list_item_name( $item, $wishlist ) {

		if ( ! empty( $item['composite_data'] ) ) {
			echo '<dl>';
			foreach ( $item['composite_data'] as $composited_item => $composited_item_data ) {

				$composited_product = wc_get_product( $composited_item_data['product_id'] );

				if ( ! $composited_product ) {
					continue;
				}

				echo '<dt class="component_title_meta wishlist_component_title_meta">' . $composited_item_data['title'] . ':</dt>';
				echo '<dd class="component_option_meta wishlist_component_option_meta">' . $composited_product->get_title() . ' <strong class="component_quantity_meta wishlist_component_quantity_meta product-quantity">&times; ' . $composited_item_data['quantity'] . '</strong></dd>';

				if ( ! empty( $composited_item_data['attributes'] ) ) {

					$attributes = '';

					foreach ( $composited_item_data['attributes'] as $attribute_name => $attribute_value ) {

						$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $attribute_name ) ) );

						// If this is a term slug, get the term's nice name.
						if ( taxonomy_exists( $taxonomy ) ) {

							$term = get_term_by( 'slug', $attribute_value, $taxonomy );

							if ( ! is_wp_error( $term ) && $term && $term->name ) {
								$attribute_value = $term->name;
							}

							$label = wc_attribute_label( $taxonomy );

							// If this is a custom option slug, get the options name.
						} else {

							$product_attributes = $composited_product->get_attributes();
							$attribute_value    = apply_filters( 'woocommerce_variation_option_name', $attribute_value );

							if ( isset( $product_attributes[ str_replace( 'attribute_', '', $attribute_name ) ] ) ) {
								$label = wc_attribute_label( $product_attributes[ str_replace( 'attribute_', '', $attribute_name ) ]['name'] );
							} else {
								$label = $attribute_name;
							}
						}

						$attributes = $attributes . $label . ': ' . $attribute_value . ', ';
					}
					echo '<dd class="component_attribute_meta wishlist_component_attribute_meta">' . rtrim( $attributes, ', ' ) . '</dd>';
				}
			}
			echo '</dl>';
			echo '<p class="component_notice wishlist_component_notice">' . __( '*', 'wpdrift-woocommerce-modules' ) . '&nbsp;&nbsp;<em>' . __( 'Accurate pricing info available in cart.', 'wpdrift-woocommerce-modules' ) . '</em></p>';
		}
	}

	/**
	 * Modifies wishlist bundle item price - the precise sum cannot be displayed reliably unless the item is added to the cart.
	 *
	 * @param  double  $price
	 * @param  array   $item
	 * @param  array   $wishlist
	 * @return string  $price
	 */
	public function wishlist_list_item_price( $price, $item, $wishlist ) {

		if ( ! empty( $item['composite_data'] ) ) {
			$price = __( '*', 'wpdrift-woocommerce-modules' );
		}

		return $price;

	}

	/**
	 * Enhance price filter widget meta query to include results based on max '_wc_sw_max_price' meta.
	 *
	 * @param  array     $meta_query
	 * @param  WC_Query  $wc_query
	 * @return array
	 */
	public function price_filter_query_params( $meta_query, $wc_query ) {

		if ( isset( $meta_query['price_filter'] ) && isset( $meta_query['price_filter']['price_filter'] ) && ! isset( $meta_query['price_filter']['sw_price_filter'] ) ) {

			$min = isset( $_GET['min_price'] ) ? floatval( $_GET['min_price'] ) : 0;
			$max = isset( $_GET['max_price'] ) ? floatval( $_GET['max_price'] ) : 9999999999;

			$price_meta_query = $meta_query['price_filter'];
			$price_meta_query = array(
				'sw_price_filter' => true,
				'price_filter'    => true,
				'relation'        => 'OR',
				$price_meta_query,
				array(
					'relation' => 'AND',
					array(
						'key'     => '_price',
						'compare' => '<=',
						'type'    => 'DECIMAL',
						'value'   => $max,
					),
					array(
						'key'     => '_wc_sw_max_price',
						'compare' => '>=',
						'type'    => 'DECIMAL',
						'value'   => $min,
					),
				),
			);

			$meta_query['price_filter'] = $price_meta_query;
		}

		return $meta_query;
	}

	/**
	 * Add to cart link.
	 * @param [type] $link    [description]
	 * @param [type] $product [description]
	 * @param [type] $args    [description]
	 */
	public function add_to_cart_link( $link, $product, $args ) {
		if ( $product->is_type( 'composite' ) ) {
			return sprintf(
				'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			);
		}

		return $link;
	}

	/**
	 * [wc_cp_get_composite_config description]
	 * @return [type] [description]
	 */
	public function wc_cp_get_composite_config() {
		global $product;

		if ( ! is_singular( 'product' ) ) {
			return [];
		}

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( get_the_ID() );
		}

		if ( ! ( $product instanceof ProductComposite ) ) {
			return [];
		}

		if ( doing_action( 'woocommerce_single_product_summary' ) ) {
			if ( 'after_summary' === $product->get_add_to_cart_form_location() ) {
			}
		}

		$navigation_style           = $product->get_composite_layout_style();
		$navigation_style_variation = $product->get_composite_layout_style_variation();
		$components                 = $product->get_components();
		$prepared_components        = [];
		foreach ( $components as $coponent_id => $component ) {
			$prepared_components[] = [
				'id'              => $component->get_id(),
				'title'           => $component->get_title(),
				'description'     => wp_strip_all_tags( $component->get_description() ),
				'options'         => $component->view->get_options_data(),
				'options_style'   => $component->get_options_style(),
				'multiple'        => $component->is_multiple(),
				'selected_option' => $component->view->get_selected_option(),
				'product_id'      => $component->get_default_option(),
				'quantity'        => $component->get_quantity( 'min' ),
				'quantity_min'    => $component->get_quantity( 'min' ),
				'quantity_max'    => $component->get_quantity( 'max' ),
				'discount'        => $component->get_discount(),
				'optional'        => $component->is_optional() ? 'yes' : 'no',
				'static'          => $component->is_static() ? 'yes' : 'no',
				'title'           => $component->get_title(),
				'composite_id'    => $product->get_id(),
				'type'            => $product->get_type(),
			];
		}

		if ( empty( $components ) ) {
			return [];
		}

		$image_src = '';

		if ( $product->get_image_id() ) {
			$image_src = wp_get_attachment_image_src( $product->get_image_id(), 'medium' );
		}

		$product_data = [
			'title'        => $product->get_title(),
			'description'  => $product->get_short_description(),
			'min_quantity' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
			'max_quantity' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
			'layout_style' => $product->get_composite_layout_style(),
			'image_src'    => $image_src ? $image_src[0] : wc_placeholder_img_src(),
		];

		return array(
			'product_id'       => $product->get_id(),
			'navigation_style' => $navigation_style,
			'classes'          => implode( ' ', apply_filters( 'woocommerce_composite_form_classes', array( $navigation_style, $navigation_style_variation ), $product ) ),
			'components'       => $prepared_components,
			'product'          => $product,
			'product_data'     => $product_data,
			'price_data'       => $product->get_composite_price_data(),
		);
	}

	/**
	 * [blocks_product_grid_item_html description]
	 * @param  [type] $html    [description]
	 * @param  [type] $data    [description]
	 * @param  [type] $product [description]
	 * @return [type]          [description]
	 */
	public function blocks_product_grid_item_html( $html, $data, $product ) {
		if ( $product->is_type( 'composite' ) ) {
			return "<li class=\"wc-block-grid__product product_type_composite\">
				<a href=\"{$data->permalink}\" class=\"wc-block-grid__product-link\">
					{$data->image}
					{$data->title}
				</a>
				{$data->badge}
				{$data->price}
				{$data->rating}
				{$data->button}
			</li>";
		}

		return $html;
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Show composited product data in the front-end.
	 * Used on first product page load to display content for component defaults.
	 *
	 * @deprecated 4.0.0
	 *
	 * @param  mixed                 $product_id
	 * @param  mixed                 $component_id
	 * @param  ProductComposite  $container_id
	 * @return string
	 */
	public function show_composited_product( $product_id, $component_id, $composite ) {
		_deprecated_function( __METHOD__ . '()', '4.0.0', 'Product::get_option_data()' );
		if ( ! $product_id ) {
			$data = Product::get_placeholder_product_data( 'no-product' );
		} else {
			$component_option = $composite->get_component_option( $component_id, $product_id );
			if ( ! $component_option || ! $component_option->is_purchasable() ) {
				$data = Product::get_placeholder_product_data( 'invalid-product', array( 'is_static' => $composite->is_component_static( $component_id ) ) );
			} else {
				$data->get_product_data();
			}
		}
		return $data['product_html'];
	}
}
