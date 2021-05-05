<?php
namespace WPdrift\CompositeProducts\API;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WP_REST_Controller;
use WP_REST_Server;
use WP_REST_Response;
use WC_Product_Factory;

class CompositeProductsController extends WP_REST_Controller {

	/**
	 * Here initialize our namespace and resource name.
	 */
	public function __construct() {
		$this->namespace     = 'wpdrift/composite/v1';
		$this->resource_name = 'products';
	}

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {
		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => array(),
				),
			)
		);

		register_rest_route(
			$this->namespace,
			'/' . $this->resource_name . '/(?P<id>[\d]+)',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_item' ),
					'permission_callback' => array( $this, 'get_item_permissions_check' ),
				),
			)
		);

	}

	/**
	 * Check if a given request has access to get items.
	 */
	public function get_items_permissions_check( $request ) {
		return true;
	}

	/**
	 * Get a collection of items
	 */
	public function get_items( $request ) {
		$data = array();
		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Check if a given request has access to get item.
	 */
	public function get_item_permissions_check( $request ) {
		return true;
	}

	/**
	 * Get an item.
	 */
	public function get_item( $request ) {
		$id      = (int) $request['id'];
		$product = wc_get_product( $id );

		if ( empty( $product ) ) {
			return rest_ensure_response( array() );
		}

		$response = $this->prepare_item_for_response( $product, $request );

		// Return all of our post response data.
		return $response;
	}

	/**
	 * Matches the post data to the schema we want.
	 */
	public function prepare_item_for_response( $product, $request ) {
		$product_data               = array();
		$product_data['price_data'] = $product->get_composite_price_data();

		return rest_ensure_response( $product_data );
	}

}
