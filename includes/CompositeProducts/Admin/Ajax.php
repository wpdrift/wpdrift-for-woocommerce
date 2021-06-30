<?php

namespace WPdrift\CompositeProducts\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WC_AJAX;
use WPdrift\CompositeProducts\ProductComposite;
use WPdrift\CompositeProducts\Admin\MetaBoxes\ProductData;


/**
 * Ajax class.
 */
class Ajax {

	/**
	 * Hook in.
	 */
	public static function init() {

		/*
		 * Notices.
		 */

		// Dismiss notices.
		add_action( 'wp_ajax_woocommerce_dismiss_composite_notice', array( __CLASS__, 'dismiss_notice' ) );

		/*
		 * Edit-Product screens.
		 */

		// Save composite config.
		add_action( 'wp_ajax_woocommerce_bto_composite_save', array( __CLASS__, 'ajax_composite_save' ) );

		// Add component.
		add_action( 'wp_ajax_woocommerce_add_composite_component', array( __CLASS__, 'ajax_add_component' ) );

		// Search products and variations.
		add_action( 'wp_ajax_woocommerce_json_search_component_options', array( __CLASS__, 'search_component_options' ) );
		add_action( 'wp_ajax_woocommerce_json_search_products_in_categories', array( __CLASS__, 'search_products_in_categories' ) );
		add_action( 'wp_ajax_woocommerce_json_search_products_and_variations_in_component', array( __CLASS__, 'search_products_and_variations_in_component' ) );

		// Fetch the categories of a product.
		add_action( 'wp_ajax_woocommerce_get_product_categories', array( __CLASS__, 'get_product_categories' ) );

		/*
		 * Edit-Order screens.
		 */

		// Ajax handler used to fetch form content for populating "Configure/Edit" composite order item modals.
		add_action( 'wp_ajax_woocommerce_configure_composite_order_item', array( __CLASS__, 'ajax_composite_order_item_form' ) );

		// Ajax handler for editing composites in manual/editable orders.
		add_action( 'wp_ajax_woocommerce_edit_composite_in_order', array( __CLASS__, 'ajax_edit_composite_in_order' ) );

		// Search products.
		add_action( 'wp_ajax_woocommerce_json_search_products_in_component', array( __CLASS__, 'search_products_in_component' ) );

		// Show selection details.
		add_action( 'wp_ajax_woocommerce_get_composited_product_data', array( __CLASS__, 'ajax_show_composited_product' ) );
	}

	/*
	|--------------------------------------------------------------------------
	| Notices.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Dismisses notices.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function dismiss_notice() {

		$failure = array(
			'result' => 'failure',
		);

		if ( ! check_ajax_referer( 'wc_cp_dismiss_notice_nonce', 'security', false ) ) {
			wp_send_json( $failure );
		}

		if ( empty( $_POST['notice'] ) ) {
			wp_send_json( $failure );
		}

		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			wp_send_json( $failure );
		}

		$dismissed = AdminNotices::dismiss_notice( wc_clean( $_POST['notice'] ) );

		if ( ! $dismissed ) {
			wp_send_json( $failure );
		}

		$response = array(
			'result' => 'success',
		);

		wp_send_json( $response );
	}

	/*
	|--------------------------------------------------------------------------
	| Edit-Product.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Fetches the categories of a product.
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function get_product_categories() {

		check_ajax_referer( 'wc_bto_get_product_categories', 'security' );

		if ( empty( $_POST['product_id'] ) ) {
			die();
		}

		$product = wc_get_product( absint( $_POST['product_id'] ) );

		if ( ! $product ) {
			die();
		}

		wp_send_json(
			array(
				'result'       => 'success',
				'category_ids' => $product->get_category_ids(),
			)
		);
	}

	/**
	 * Handles saving composite config via ajax.
	 *
	 * @return void
	 */
	public static function ajax_composite_save() {

		check_ajax_referer( 'wc_bto_save_composite', 'security' );

		parse_str( $_POST['data'], $posted_composite_data );

		$post_id = absint( $_POST['post_id'] );

		ProductData::save_configuration( $post_id, $posted_composite_data );

		wc_delete_product_transients( $post_id );

		wp_send_json( ProductData::$ajax_notices );
	}

	/**
	 * Handles adding components via ajax.
	 *
	 * @return void
	 */
	public static function ajax_add_component() {

		check_ajax_referer( 'wc_bto_add_component', 'security' );

		$id      = intval( $_POST['id'] );
		$post_id = intval( $_POST['post_id'] );

		$component_data = array( 'composite_id' => $post_id );

		/**
		 * Action 'woocommerce_composite_component_admin_html'.
		 *
		 * @param  int     $id
		 * @param  array   $component_data
		 * @param  int     $post_id
		 * @param  string  $state
		 *
		 * @hooked {@see component_admin_html} - 10
		 */
		do_action( 'woocommerce_composite_component_admin_html', $id, $component_data, $post_id, 'open' );

		die();
	}

	/**
	 * Search for products and variations in component.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function search_products_in_categories() {

		$include_category_ids = ! empty( $_GET['include'] ) ? array_map( 'absint', explode( ',', $_GET['include'] ) ) : array();

		if ( empty( $include_category_ids ) ) {
			wp_die();
		}

		$include_category_slugs = get_terms(
			'product_cat',
			array(
				'include' => $include_category_ids,
				'fields'  => 'id=>slug',
			)
		);

		if ( empty( $include_category_slugs ) ) {
			wp_die();
		}

		$product_ids = wc_get_products(
			array(
				'category' => array_values( $include_category_slugs ),
				'return'   => 'ids',
				'limit'    => -1,
			)
		);

		$_GET['include'] = $product_ids;
		add_filter( 'woocommerce_json_search_found_products', array( __CLASS__, 'component_options_search_results' ) );
		WC_AJAX::json_search_products();
	}

	/**
	 * Search for products and variations in component.
	 *
	 * @return void
	 */
	public static function search_products_and_variations_in_component() {
		self::search_products_in_component( array( 'include_variations' => true ) );
	}

	/**
	 * Search for products and variations in component.
	 *
	 * @param  array  $args
	 * @return void
	 */
	public static function search_products_in_component( $args = array() ) {

		$args = wp_parse_args(
			$args,
			array(
				'include_variations' => false,
			)
		);

		if ( ! empty( $_GET['include'] ) ) {

			$include      = $_GET['include'];
			$composite_id = isset( $include['composite_id'] ) ? absint( $include['composite_id'] ) : false;
			$component_id = isset( $include['component_id'] ) ? absint( $include['component_id'] ) : false;
			$composite    = $composite_id && $component_id ? wc_get_product( $composite_id ) : false;

			if ( ! $composite ) {
				wp_die();
			}

			$component         = $composite->get_component( $component_id );
			$component_options = $component ? Component::query_component_options( $component->get_data() ) : array();

			if ( empty( $component_options ) ) {
				wp_die();
			}

			if ( $args['include_variations'] ) {

				$_GET['include_parent_ids'] = $composite->get_data_store()->get_expanded_component_options( $component_options, 'mapped' );
				$_GET['include']            = $composite->get_data_store()->get_expanded_component_options( $component_options, 'merged' );

			} else {

				$_GET['include'] = $component_options;
			}
		}

		WC_AJAX::json_search_products( '', $args['include_variations'] );
	}

	/**
	 * Ajax search for Component Options: Show results for supported product types only.
	 */
	public static function search_component_options() {
		add_filter( 'woocommerce_json_search_found_products', array( __CLASS__, 'component_options_search_results' ) );
		WC_AJAX::json_search_products( '', false );
	}

	/**
	 * Include only supported product types in Component Options search results.
	 *
	 * @param  array  $search_results
	 * @return array
	 */
	public static function component_options_search_results( $search_results ) {

		if ( ! empty( $search_results ) ) {

			$search_results_filtered = array();

			foreach ( $search_results as $product_id => $product_title ) {

				$product = wc_get_product( $product_id );

				if ( is_object( $product ) && in_array( $product->get_type(), ProductComposite::get_supported_component_option_types() ) ) {
					$search_results_filtered[ $product_id ] = $product_title;
				}
			}

			$search_results = $search_results_filtered;
		}

		return $search_results;
	}

	/*
	|--------------------------------------------------------------------------
	| Edit-Order.
	|--------------------------------------------------------------------------
	*/

	/**
	 * True when displaying content in an edit-composite order item modal.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function is_composite_edit_request() {
		return doing_action( 'wp_ajax_woocommerce_configure_composite_order_item' ) || doing_action( 'wp_ajax_woocommerce_get_composited_product_data' );
	}

	/**
	 * Form content used to populate "Configure/Edit" composite order item modals.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function ajax_composite_order_item_form() {
		global $product;

		$failure = array(
			'result' => 'failure',
		);

		if ( ! check_ajax_referer( 'wc_bto_edit_composite', 'security', false ) ) {
			wp_send_json( $failure );
		}

		if ( empty( $_POST['order_id'] ) || empty( $_POST['item_id'] ) ) {
			wp_send_json( $failure );
		}

		$order   = wc_get_order( wc_clean( $_POST['order_id'] ) );
		$item_id = absint( wc_clean( $_POST['item_id'] ) );

		if ( ! ( $order instanceof WC_Order ) ) {
			wp_send_json( $failure );
		}

		$item = $order->get_item( $item_id );

		if ( ! ( $item instanceof WC_Order_Item ) ) {
			wp_send_json( $failure );
		}

		$product = $item->get_product();

		if ( empty( $product ) ) {
			wp_send_json( $failure );
		}

		// Filter component config.
		add_filter( 'woocommerce_composite_component_data', array( __CLASS__, 'filter_component_data' ), 10 );

		$components = $product->get_components();

		// Initialize form state based on the actual configuration of the bundle.
		$configuration = Order::get_current_composite_configuration( $item, $order );

		if ( ! empty( $configuration ) ) {
			$_REQUEST = array_merge( $_REQUEST, Module::instance()->cart->rebuild_posted_composite_form_data( $configuration ) );
		}

		// Force 'single' layout.
		$product->set_layout( 'single' );

		ob_start();
		include( 'meta-boxes/views/html-composite-edit-form.php' );
		$html = ob_get_clean();

		$response = array(
			'result' => 'success',
			'html'   => $html,
		);

		wp_send_json( $response );
	}

	/**
	 * Validates edited/configured composites and returns updated order items.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function ajax_edit_composite_in_order() {

		$failure = array(
			'result' => 'failure',
		);

		if ( ! current_user_can( 'edit_shop_orders' ) ) {
			wp_send_json( $failure );
		}

		if ( ! check_ajax_referer( 'wc_bto_edit_composite', 'security', false ) ) {
			wp_send_json( $failure );
		}

		if ( empty( $_POST['order_id'] ) || empty( $_POST['item_id'] ) ) {
			wp_send_json( $failure );
		}

		$order   = wc_get_order( wc_clean( $_POST['order_id'] ) );
		$item_id = absint( wc_clean( $_POST['item_id'] ) );

		if ( ! ( $order instanceof WC_Order ) ) {
			wp_send_json( $failure );
		}

		$item = $order->get_item( $item_id );

		if ( ! ( $item instanceof WC_Order_Item ) ) {
			wp_send_json( $failure );
		}

		$product = $item->get_product();

		if ( ! ( $product instanceof ProductComposite ) ) {
			wp_send_json( $failure );
		}

		if ( ! empty( $_POST['fields'] ) ) {
			parse_str( $_POST['fields'], $posted_form_fields );
			$_POST = array_merge( $_POST, $posted_form_fields );
		}

		$posted_configuration  = Module::instance()->cart->get_posted_composite_configuration( $product );
		$current_configuration = Order::get_current_composite_configuration( $item, $order );

		// Compare posted against current configuration.
		if ( $posted_configuration !== $current_configuration ) {

			$added_to_order = Module::instance()->order->add_composite_to_order(
				$product,
				$order,
				$item->get_quantity(),
				array(
					'configuration' => $posted_configuration,
				)
			);

			// Invalid configuration?
			if ( is_wp_error( $added_to_order ) ) {

				$message = __( 'The submitted configuration is invalid.', 'wpdrift-woocommerce-modules' );
				$data    = $added_to_order->get_error_data();

				$notice = isset( $data['notices'] ) ? html_entity_decode( current( $data['notices'] ) ) : '';

				if ( $notice ) {
					$message = sprintf( _x( '%1$s %2$s', 'edit composite in order: formatted validation message', 'wpdrift-woocommerce-modules' ), $message, $notice );
				}

				$response = array(
					'result' => 'failure',
					'error'  => $message,
				);

				wp_send_json( $response );

				// Remove old items.
			} else {

				if ( has_action( 'woocommerce_editing_bundle_in_order' ) ) {

					$new_container_item = $order->get_item( $added_to_order );

					/**
					 * 'woocommerce_editing_composite_in_order' action.
					 *
					 * @since  1.0.0
					 *
					 * @param  WC_Order_Item_Product  $new_item
					 * @param  WC_Order_Item_Product  $old_item
					 */
					do_action( 'woocommerce_editing_composite_in_order', $new_container_item, $item, $order );
				}

				$items_to_remove = array( $item ) + wc_cp_get_composited_order_items( $item, $order, false, true );

				foreach ( $items_to_remove as $remove_item ) {
					$order->remove_item( $remove_item->get_id() );
					$remove_item->delete();
				}

				if ( isset( $_POST['country'], $_POST['state'], $_POST['postcode'], $_POST['city'] ) ) {

					$calculate_tax_args = array(
						'country'  => strtoupper( wc_clean( $_POST['country'] ) ),
						'state'    => strtoupper( wc_clean( $_POST['state'] ) ),
						'postcode' => strtoupper( wc_clean( $_POST['postcode'] ) ),
						'city'     => strtoupper( wc_clean( $_POST['city'] ) ),
					);

					$order->calculate_taxes( $calculate_tax_args );
					$order->calculate_totals( false );

				} else {
					$order->save();
				}
			}
		}

		ob_start();
		include( WC_ABSPATH . 'includes/admin/meta-boxes/views/html-order-items.php' );
		$html = ob_get_clean();

		$response = array(
			'result' => 'success',
			'html'   => $html,
		);

		wp_send_json( $response );
	}

	/**
	 * Fetches selection data.
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public static function ajax_show_composited_product() {

		// Filter component config.
		add_filter( 'woocommerce_composite_component_data', array( __CLASS__, 'filter_component_data' ), 10 );

		return AJAX::show_composited_product_ajax();
	}

	/**
	 * Filter component data in edit-order context.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $component_data
	 * @return array
	 */
	public static function filter_component_data( $component_data ) {

		// Disable Add-Ons.
		$component_data['disable_addons'] = true;

		// Disable Sorting/Filtering.
		$component_data['show_orderby'] = 'no';
		$component_data['show_filters'] = 'no';

		// Selection title/image visibility.
		$component_data['hide_product_title']     = 'yes';
		$component_data['hide_product_price']     = 'yes';
		$component_data['hide_product_thumbnail'] = 'no';

		// Force 'dropdowns' style.
		$component_data['selection_mode'] = 'dropdowns';

		// Hide prices.
		$component_data['display_prices'] = 'hidden';

		return $component_data;
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Add variations to component product search results.
	 *
	 * @deprecated  1.0.0
	 *
	 * @param  array  $search_results
	 * @return array
	 */
	public static function add_variations_to_component_search_results( $search_results ) {

		$search_results_incl_variations = array();

		if ( ! empty( $search_results ) ) {

			$search_result_objects = array_map( 'wc_get_product', array_keys( $search_results ) );

			foreach ( $search_result_objects as $product ) {
				if ( $product ) {

					$product_id                                    = $product->get_id();
					$search_results_incl_variations[ $product_id ] = Helpers::get_product_title( $product, '', $product->is_type( 'variable' ) ? __( 'Any Variation', 'wpdrift-woocommerce-modules' ) : '' );

					if ( $product->is_type( 'variable' ) ) {

						$child_ids     = $product->get_children();
						$child_objects = array_map( 'wc_get_product', $child_ids );

						if ( ! empty( $child_objects ) ) {
							foreach ( $child_objects as $child ) {
								if ( $child ) {
									$child_id                                    = $child->get_id();
									$search_results_incl_variations[ $child_id ] = rawurldecode( Helpers::get_product_variation_title( $child, 'flat' ) );
								}
							}
						}
					}
				}
			}
		}

		return $search_results_incl_variations;
	}
}
