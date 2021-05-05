<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WC_Data_Store;

/**
 * Query class.
 */
class Query {

	/**
	 * Queried results.
	 * @var array
	 */
	private $results;

	/**
	 * Constructor.
	 *
	 * @param  array  $component_data
	 * @param  array  $query_args
	 */
	public function __construct( $component_data, $query_args = array() ) {

		/**
		 * Action 'woocommerce_composite_component_query_start'.
		 *
		 * @param  array  $component_data
		 * @param  array  $query_args
		 */
		do_action( 'woocommerce_composite_component_query_start', $component_data, $query_args );

		$this->query( $component_data, $query_args );

		/**
		 * Action 'woocommerce_composite_component_query_end'.
		 *
		 * @param  array  $component_data
		 * @param  array  $query_args
		 */
		do_action( 'woocommerce_composite_component_query_end', $component_data, $query_args );
	}

	/**
	 * Get queried component option IDs.
	 *
	 * @return array
	 */
	public function get_component_options() {
		return ! empty( $this->results['component_options'] ) ? $this->results['component_options'] : array();
	}

	/**
	 * Query args getter.
	 *
	 * @return array
	 */
	public function get_query_args() {
		return ! empty( $this->results['query_args'] ) ? $this->results['query_args'] : array();
	}

	/**
	 * True if the query was paged and there is more than one page to show.
	 *
	 * @return boolean
	 */
	public function has_pages() {
		return isset( $this->results['pages'] ) ? $this->results['pages'] > 1 : false;
	}

	/**
	 * Get the page number of the query.
	 *
	 * @return int
	 */
	public function get_current_page() {
		return ! empty( $this->results['current_page'] ) ? $this->results['current_page'] : array();
	}

	/**
	 * Get the total number of pages.
	 *
	 * @return int
	 */
	public function get_pages_num() {
		return isset( $this->results['pages'] ) ? $this->results['pages'] : 1;
	}

	/**
	 * Runs the query.
	 *
	 * @param  array  $component_data
	 * @param  array  $query_args
	 */
	private function query( $component_data, $query_args ) {
		$data_store    = WC_Data_Store::load( 'product-composite' );
		$this->results = $data_store->query_component_options( $component_data, $query_args );
	}
}
