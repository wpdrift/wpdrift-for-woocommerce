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

class CompositeComponentsController extends WP_REST_Controller {

	/**
	 * Register the routes for the objects of the controller.
	 */
	public function register_routes() {

		$version   = '1';
		$namespace = 'wpdrift/v' . $version;
		$base      = 'components';
		register_rest_route(
			$namespace,
			'/' . $base,
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_items' ),
					'permission_callback' => array( $this, 'get_items_permissions_check' ),
					'args'                => array(),
				),
			)
		);
	}

	/**
	 * Get a collection of items
	 */
	public function get_items( $request ) {
		$data       = array();
		$product    = wc_get_product( $request['product_id'] );
		$components = $product->get_components();

		foreach ( $components as $coponent_id => $component ) {
			$data[] = $this->prepare_response_for_collection(
				$this->prepare_item_for_response( $component, $request )
			);
		}

		return new WP_REST_Response( $data, 200 );
	}

	/**
	 * Check if a given request has access to get items
	 */
	public function get_items_permissions_check( $request ) {
		return true;
	}

	/**
	 * Prepare the item for the REST response
	 */
	public function prepare_item_for_response( $component, $request ) {
		$composite = wc_get_product( $request['product_id'] );

		return [
			'id'              => $component->get_id(),
			'title'           => $component->get_title(),
			'description'     => wp_strip_all_tags( $component->get_description() ),
			'options'         => $component->view->get_options_data(),
			'options_style'   => $component->get_options_style(),
			'selected_option' => $component->view->get_selected_option(),
			'product_id'      => $component->get_default_option(),
			'quantity'        => $component->get_quantity( 'min' ),
			'quantity_min'    => $component->get_quantity( 'min' ),
			'quantity_max'    => $component->get_quantity( 'max' ),
			'discount'        => $component->get_discount(),
			'optional'        => $component->is_optional() ? 'yes' : 'no',
			'static'          => $component->is_static() ? 'yes' : 'no',
			'title'           => $component->get_title(),
			'composite_id'    => $composite->get_id(),
			'type'            => WC_Product_Factory::get_product_type( $request['product_id'] ),
		];
	}

	/**
	 * Get the query params for collections
	 */
	public function get_collection_params() {

		return array(
			'page'     => array(
				'description'       => 'Current page of the collection.',
				'type'              => 'integer',
				'default'           => 1,
				'sanitize_callback' => 'absint',
			),
			'per_page' => array(
				'description'       => 'Maximum number of items to be returned in result set.',
				'type'              => 'integer',
				'default'           => 10,
				'sanitize_callback' => 'absint',
			),
			'search'   => array(
				'description'       => 'Limit results to those matching a string.',
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			),
		);
	}
}
