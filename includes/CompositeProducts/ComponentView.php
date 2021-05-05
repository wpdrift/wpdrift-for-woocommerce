<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use WC_Data_Store;

/**
 * ComponentView class.
 */
class ComponentView {

	/**
	 * A reference to the component whose state is being maintained here.
	 *
	 * @var Component
	 */
	private $component;

	/**
	 * The current component options query instance.
	 *
	 * @var Query
	 */
	private $query = null;

	/**
	 * Constructor.
	 *
	 * @param  Component  $component
	 */
	public function __construct( $component ) {
		$this->component = $component;
	}

	/**
	 * Component getter.
	 *
	 * @return Component
	 */
	public function get_component() {
		return $this->component;
	}

	/**
	 * True if the component view has been set.
	 *
	 * @return boolean
	 */
	public function is_set() {
		return isset( $this->query );
	}

	/**
	 * Get the query object that was used to build the component options view of a component.
	 * Should be called after {@see get_options} has been used to initialize the component view.
	 *
	 * @return Query
	 */
	public function get_options_query() {
		return $this->is_set() ? $this->query : false;
	}

	/**
	 * Initializes the query object required by this view.
	 *
	 * @since  3.12.0
	 *
	 * @return void
	 */
	public function initialize() {
		$this->get_options();
	}

	/**
	 * Get component options to display. Fetched using a WP Query wrapper to allow advanced component options filtering / ordering / pagination.
	 *
	 * @param  array  $args
	 * @return array
	 */
	public function get_options( $args = array() ) {

		$options = array();

		if ( $this->is_set() ) {

			$options = $this->query->get_component_options();

		} else {

			$options_style = $this->component->get_options_style();

			// Only do paged component options when supported.
			if ( false === Component::options_style_supports( $options_style, 'pagination' ) ) {
				$per_page = false;
			} else {
				$per_page = $this->component->get_results_per_page();
			}

			$defaults = array(
				'load_page'       => $this->component->paginate_options() ? 'selected' : 1,
				'per_page'        => $per_page,
				'selected_option' => $this->get_selected_option(),
				'orderby'         => $this->component->get_default_sorting_order(),
				'query_type'      => 'product_ids',
			);

			// Component option ids have already been queried without any pages / filters / sorting when the component was initialized.
			// This time, we can speed up our paged / filtered / sorted query by using the stored ids of the first "raw" query.

			$data                 = $this->component->get_data();
			$data['assigned_ids'] = $this->component->get_options();

			// At this point, we can also filter the IDs when requesting options that match specific scenarios.
			if ( ! empty( $args['options_in_scenarios'] ) ) {
				$data['assigned_ids'] = $this->get_options_in_scenarios( $data['assigned_ids'], $args['options_in_scenarios'] );
			}

			/**
			 * Filter args passed to Query.
			 *
			 * @param  array                 $query_args
			 * @param  array                 $passed_args
			 * @param  string                $component_id
			 * @param  ProductComposite  $product
			 */
			$current_args = apply_filters( 'woocommerce_composite_component_options_query_args_current', wp_parse_args( $args, $defaults ), $args, $this->component->get_id(), $this->component->get_composite() );

			// Pass through query to apply filters / ordering.
			$this->query = new Query( $data, $current_args );

			$options = $this->query->get_component_options();
		}

		return $options;
	}

	/**
	 * Filter option IDs matching specific scenario IDs.
	 *
	 * @param  array  $options
	 * @param  array  $scenarios
	 * @return array
	 */
	private function get_options_in_scenarios( $options, $scenarios ) {

		if ( in_array( '0', $scenarios ) ) {
			return $options;
		}

		$component_id         = $this->component->get_id();
		$options_map          = $this->component->get_composite()->scenarios()->get_map( array( $component_id => $options ), $scenarios );
		$options_in_scenarios = array();

		if ( ! empty( $options_map[ $component_id ] ) ) {
			foreach ( $options_map[ $component_id ] as $product_id => $product_in_scenarios ) {
				if ( sizeof( array_intersect( $product_in_scenarios, $scenarios ) ) > 0 && in_array( $product_id, $options ) ) {
					$options_in_scenarios[] = $product_id;
				}
			}
		}

		return $options_in_scenarios;
	}

	/**
	 * Get component options data for use by JS.
	 *
	 * @param  array  $args
	 * @return array
	 */
	public function get_options_data( $args = array() ) {

		$data = array();

		$component_options          = $this->get_options( $args );
		$selected_option_id         = $this->get_selected_option();
		$is_selected_option_in_view = true;

		if ( $selected_option_id && ! in_array( $selected_option_id, $component_options ) ) {
			$component_options[]        = $selected_option_id;
			$is_selected_option_in_view = false;
		}

		if ( ! empty( $component_options ) ) {

			// Preload product data to reduce queries.
			$data_store = WC_Data_Store::load( 'product-composite' );
			$data_store->preload_component_options_data( $component_options );

			foreach ( $component_options as $product_id ) {

				$component_option = $this->component->get_option( $product_id );

				if ( ! $component_option ) {
					continue;
				}

				$is_selected  = absint( $product_id ) === absint( $selected_option_id );
				$title        = $component_option->get_product()->get_title();
				$product_data = '';
				$price_html   = '';

				if ( false === $this->component->hide_component_option_prices() && 'absolute' === $this->component->get_price_display_format() ) {
					$options_style = $this->component->get_options_style();
					// $price_html    = 'dropdowns' === $options_style ? $component_option->get_price_string() : $component_option->get_price_html();
					$price_html = $component_option->get_price_html();
				}

				/**
				 * Filter 'woocommerce_composite_component_option_title'.
				 *
				 * @since  3.12.0
				 *
				 * @param  boolean        $preload_details
				 * @param  Product  $component_option
				 */
				$title = apply_filters( 'woocommerce_composite_component_option_title', $title, $component_option );

				if ( has_post_thumbnail( $product_id ) ) {
					$thumbnail_html = get_the_post_thumbnail( $product_id, $component_option->get_image_size() );
				} else {
					$thumbnail_html = apply_filters( 'woocommerce_composite_component_option_image_placeholder', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'wpdrift-woocommerce-modules' ) ), $product_id, $this->component->get_id(), $this->component->get_composite_id() );
				}

				/**
				 * Filter 'woocommerce_composite_component_option_details_preload'.
				 *
				 * @since  3.12.0
				 *
				 * @param  boolean        $preload_details
				 * @param  Product  $component_option
				 */
				$preload_details = apply_filters( 'woocommerce_composite_component_option_details_preload', sizeof( $component_options ) < 24 && $component_option->get_product()->is_type( 'simple' ), $component_option );

				// Load the details of simple products right away.
				if ( $is_selected || $preload_details ) {

					if ( $component_option->is_purchasable() ) {
						$product_data = $component_option->get_product_data();
					} else {
						$product_data = Product::get_placeholder_product_data( 'invalid-product', array( 'is_static' => $this->component->is_static() ) );
					}
				}

				/**
				 * Filter 'woocommerce_composite_component_option_data'.
				 *
				 * @param  array          $component_option_data
				 * @param  Product  $component_option
				 */
				$data[] = apply_filters(
					'woocommerce_composite_component_option_data',
					array(
						'option_id'             => strval( $product_id ),
						'option_title'          => $title,
						'option_price_html'     => $price_html,
						'option_thumbnail_html' => $thumbnail_html,
						'option_product_data'   => $product_data,
						'option_price_data'     => $component_option->get_price_data(),
						'is_selected'           => $is_selected,
						'is_configurable'       => $component_option->is_configurable(),
						'has_addons'            => $component_option->has_addons(),
						'has_required_addons'   => $component_option->has_addons( true ),
						'is_in_view'            => false === $is_selected || $is_selected_option_in_view,
					),
					$component_option
				);
			}
		}

		return $data;
	}

	/**
	 * Get the currently selected option (product ID) in a component view.
	 *
	 * @return int
	 */
	public function get_selected_option() {

		$data = $this->component->get_data();

		if ( empty( $data ) ) {
			return '';
		}

		$selected_option = false;

		// If the component view has been set/changed, grab the selected option from there.
		if ( $this->is_set() ) {
			$query_args = $this->query->get_query_args();
			if ( ! empty( $query_args ) ) {
				$selected_option = $query_args['selected_option'];
			}
		}

		// Otherwise, return the default component option.
		if ( false === $selected_option ) {
			$selected_option = $this->component->get_default_option( true );
		}

		return $selected_option;
	}

	/**
	 * Are component options paged?
	 *
	 * @return boolean
	 */
	public function has_pages() {
		return $this->is_set() ? $this->query->has_pages() : false;
	}

	/**
	 * Get the currently viewed page, if applicable.
	 *
	 * @return int|false
	 */
	public function get_page() {
		return $this->is_set() ? $this->query->get_current_page() : false;
	}

	/**
	 * Get the total number of pages.
	 *
	 * @return int|false
	 */
	public function get_pages() {
		return $this->is_set() ? $this->query->get_pages_num() : false;
	}

	/**
	 * Get pagination data.
	 *
	 * @return int|false
	 */
	public function get_pagination_data() {
		return array(
			'page'  => $this->has_pages() ? $this->get_page() : 1,
			'pages' => $this->has_pages() ? $this->get_pages() : 1,
		);
	}
}
