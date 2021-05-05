<?php
namespace WPdrift\CompositeProducts\API;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use WP_Error;
use WC_Customer;
use WC_Cart;
use WPdrift\CompositeProducts\Cart;

class CompositeCartController extends WP_REST_Controller {

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		$version   = '1';
		$namespace = 'wpdrift/v' . $version;
		$base      = 'add_to_cart';
		register_rest_route(
			$namespace,
			'/' . $base,
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create_item' ),
					'permission_callback' => array( $this, 'create_item_permissions_check' ),
				),
			)
		);
	}

	/**
	 * Create one item from the collection.
	 */
	public function create_item( $request ) {
		if ( defined( 'WC_ABSPATH' ) ) {
			// WC 3.6+ - Cart and notice functions are not included during a REST request.
			include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
			include_once WC_ABSPATH . 'includes/wc-notice-functions.php';
		}

		if ( null === WC()->session ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );

			//Prefix session class with global namespace if not already namespaced
			if ( false === strpos( $session_class, '' ) ) {
				$session_class = '' . $session_class;
			}

			WC()->session = new $session_class();
			WC()->session->init();
		}

		if ( null === WC()->customer ) {
			WC()->customer = new WC_Customer( get_current_user_id(), true );
		}

		if ( null === WC()->cart ) {
			WC()->cart = new WC_Cart();

			// We need to force a refresh of the cart contents from session here (cart contents are normally refreshed on wp_loaded, which has already happened by this point).
			WC()->cart->get_cart();
		}

		// $cc = array(
		// 	'1580014903' => [
		// 		[
		// 			'product_id'   => '40',
		// 			'quantity'     => 1,
		// 			'quantity_min' => 1,
		// 			'quantity_max' => 1,
		// 			'discount'     => 10,
		// 			'optional'     => 'no',
		// 			'static'       => 'no',
		// 			'title'        => 'CPU',
		// 			'composite_id' => 1848,
		// 			'type'         => 'composite',
		// 		],
		// 		[
		// 			'product_id'   => '41',
		// 			'quantity'     => 1,
		// 			'quantity_min' => 1,
		// 			'quantity_max' => 1,
		// 			'discount'     => 10,
		// 			'optional'     => 'no',
		// 			'static'       => 'no',
		// 			'title'        => 'CPU',
		// 			'composite_id' => 1848,
		// 			'type'         => 'composite',
		// 		],
		// 	],
		// 	'1580876486' => [
		// 		[
		// 			'product_id'   => '40',
		// 			'quantity'     => 1,
		// 			'quantity_min' => 1,
		// 			'quantity_max' => 1,
		// 			'discount'     => '',
		// 			'optional'     => 'yes',
		// 			'static'       => 'no',
		// 			'title'        => 'Radio',
		// 			'composite_id' => 1848,
		// 			'type'         => 'composite',
		// 		],
		// 	],
		// );

		$cart = Cart::instance();

		// $product_id = 1848;
		// $composite = wc_get_product( $product_id );
		// $validated = $cart->validate_composite_configuration( $product_id, 1, $cc );
		// wp_send_json( $validated );

		$added_to_cart = $cart->add_composite_to_cart( $request['product_id'], $request['quantity'], $request['config'] );
		wp_send_json( $added_to_cart );
	}

	/**
	 * Check if a given request has access to create items.
	 */
	public function create_item_permissions_check( $request ) {
		return true;
	}
}
