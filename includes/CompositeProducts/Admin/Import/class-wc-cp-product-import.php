<?php
/**
 * Product_Import class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Composite Products
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WooCommerce core Product Importer support.
 *
 * @class    Product_Import
 * @version  1.0.0
 */
class Product_Import {

	/**
	 * Working importer instance.
	 *
	 * @var WC_Product_CSV_Importer
	 */
	private static $importer = null;

	/**
	 * Hook in.
	 */
	public static function init() {

		// Map custom column titles.
		add_filter( 'woocommerce_csv_product_import_mapping_options', array( __CLASS__, 'map_columns' ) );
		add_filter( 'woocommerce_csv_product_import_mapping_default_columns', array( __CLASS__, 'add_columns_to_mapping_screen' ) );

		// Fix formatting callback.
		add_filter( 'woocommerce_product_importer_formatting_callbacks', array( __CLASS__, 'formatting_callbacks' ), 10, 2 );

		// Parse components.
		add_filter( 'woocommerce_product_importer_parsed_data', array( __CLASS__, 'parse_components' ), 10, 2 );

		// Set composite-type props.
		add_filter( 'woocommerce_product_import_pre_insert_product_object', array( __CLASS__, 'set_composite_props' ), 10, 2 );
	}

	/**
	 * Register the 'Custom Column' column in the importer.
	 *
	 * @param  array  $options
	 * @return array  $options
	 */
	public static function map_columns( $options ) {
		$options['wc_cp_components']                = __( 'Composite Components (JSON-encoded)', 'wpdrift-woocommerce-modules' );
		$options['wc_cp_layout']                    = __( 'Composite Layout', 'wpdrift-woocommerce-modules' );
		$options['wc_cp_editable_in_cart']          = __( 'Composite Cart Editing', 'wpdrift-woocommerce-modules' );
		$options['wc_cp_sold_individually_context'] = __( 'Composite Sold Individually', 'wpdrift-woocommerce-modules' );
		$options['wc_cp_shop_price_calc']           = __( 'Composite Catalog Price', 'wpdrift-woocommerce-modules' );
		$options['wc_cp_add_to_cart_form_location'] = __( 'Composite Form Location', 'wpdrift-woocommerce-modules' );

		return $options;
	}

	/**
	 * Add automatic mapping support for custom columns.
	 *
	 * @param  array  $columns
	 * @return array  $columns
	 */
	public static function add_columns_to_mapping_screen( $columns ) {
		$columns[ __( 'Composite Components (JSON-encoded)', 'wpdrift-woocommerce-modules' ) ] = 'wc_cp_components';
		$columns[ __( 'Composite Layout', 'wpdrift-woocommerce-modules' ) ]                    = 'wc_cp_layout';
		$columns[ __( 'Composite Cart Editing', 'wpdrift-woocommerce-modules' ) ]              = 'wc_cp_editable_in_cart';
		$columns[ __( 'Composite Sold Individually', 'wpdrift-woocommerce-modules' ) ]         = 'wc_cp_sold_individually_context';
		$columns[ __( 'Composite Catalog Price', 'wpdrift-woocommerce-modules' ) ]             = 'wc_cp_shop_price_calc';
		$columns[ __( 'Composite Form Location', 'wpdrift-woocommerce-modules' ) ]             = 'wc_cp_add_to_cart_form_location';

		// Always add English mappings.
		$columns['Composite Components (JSON-encoded)'] = 'wc_cp_components';
		$columns['Composite Layout']                    = 'wc_cp_layout';
		$columns['Composite Cart Editing']              = 'wc_cp_editable_in_cart';
		$columns['Composite Sold Individually']         = 'wc_cp_sold_individually_context';
		$columns['Composite Catalog Price']             = 'wc_cp_shop_price_calc';
		$columns['Composite Form Location']             = 'wc_cp_add_to_cart_form_location';

		return $columns;
	}

	/**
	 * Prevent JSON-encoded fields from being kses-posted.
	 *
	 * @param  array                    $callbacks
	 * @param  WC_Product_CSV_Importer  $importer
	 * @return array
	 */
	public static function formatting_callbacks( $callbacks, $importer ) {
		$mapped_keys    = $importer->get_mapped_keys();
		$components_key = '';

		foreach ( $mapped_keys as $key => $value ) {
			if ( 'wc_cp_components' === $value ) {
				$components_key = $key;
			}
		}

		$callbacks[ $components_key ] = 'strval';

		return $callbacks;
	}

	/**
	 * Decode component data and parse relative IDs.
	 *
	 * @param  array                    $parsed_data
	 * @param  WC_Product_CSV_Importer  $importer
	 * @return array
	 */
	public static function parse_components( $parsed_data, $importer ) {

		self::$importer = $importer;

		if ( ! empty( $parsed_data['wc_cp_components'] ) ) {

			$components_rest_data = json_decode( $parsed_data['wc_cp_components'], true );

			unset( $parsed_data['wc_cp_components'] );

			if ( is_array( $components_rest_data ) ) {

				$parsed_data['wc_cp_components'] = array();

				foreach ( $components_rest_data as $component_rest_data ) {

					$parsed_component_data = $component_rest_data;

					// Parse query data.
					if ( ! empty( $component_rest_data['query_ids'] ) ) {
						if ( isset( $component_rest_data['query_type'] ) && 'category_ids' === $component_rest_data['query_type'] ) {
							$parsed_component_data['query_ids'] = $importer->parse_categories_field( $component_rest_data['query_ids'] );
						} else {
							$parsed_component_data['query_ids'] = $importer->parse_relative_comma_field( $component_rest_data['query_ids'] );
						}
					}

					// Parse default option.
					if ( ! empty( $component_rest_data['default_option_id'] ) ) {
						$parsed_component_data['default_option_id'] = $importer->parse_relative_field( $component_rest_data['default_option_id'] );
					}

					// Parse attribute filter labels.
					if ( ! empty( $component_rest_data['attribute_filters'] ) ) {

						$parsed_component_data['attribute_filter_ids'] = array();

						foreach ( $component_rest_data['attribute_filters'] as $attribute_label ) {
							$parsed_component_data['attribute_filter_ids'][] = $importer->get_attribute_taxonomy_id( $attribute_label );
						}
					}

					// Sanitize.
					$parsed_data['wc_cp_components'][] = REST_API::sanitize_rest_api_component_data( $parsed_component_data );
				}
			}
		}

		return $parsed_data;
	}

	/**
	 * Set composite-type props.
	 *
	 * @param  array  $parsed_data
	 * @return array
	 */
	public static function set_composite_props( $product, $data ) {

		if ( ( $product instanceof WC_Product ) && $product->is_type( 'composite' ) ) {

			$props = array();

			if ( isset( $data['wc_cp_layout'] ) ) {
				$props['layout'] = strval( $data['wc_cp_layout'] );
			}

			if ( isset( $data['wc_cp_sold_individually_context'] ) ) {
				$props['sold_individually_context'] = strval( $data['wc_cp_sold_individually_context'] );
			}

			if ( isset( $data['wc_cp_shop_price_calc'] ) ) {
				$props['shop_price_calc'] = strval( $data['wc_cp_shop_price_calc'] );
			}

			if ( isset( $data['wc_cp_editable_in_cart'] ) ) {
				$props['editable_in_cart'] = 1 === intval( $data['wc_cp_editable_in_cart'] ) ? 'yes' : 'no';
			}

			if ( isset( $data['wc_cp_add_to_cart_form_location'] ) ) {
				$props['add_to_cart_form_location'] = strval( $data['wc_cp_add_to_cart_form_location'] );
			}

			$product->set_props( $props );

			try {

				if ( isset( $data['wc_cp_components'] ) ) {

					$composite_data = array();

					if ( ! empty( $data['wc_cp_components'] ) ) {

						$timestamp = current_time( 'timestamp' );
						$loop      = 0;

						foreach ( $data['wc_cp_components'] as $component_data ) {

							if ( empty( $component_data['id'] ) ) {
								$component_id = strval( $timestamp + $loop );
								$loop++;
							} else {
								$component_id = $component_data['id'];
							}

							// Convert schema.
							$component_data = REST_API::convert_rest_api_component_data( $component_data );

							// Validate data.
							$composite_data[ $component_id ] = REST_API::validate_internal_component_data( $component_data, 'import' );

							$thumbnail_id  = ! empty( $component_data['thumbnail_id'] ) ? $component_data['thumbnail_id'] : '';
							$thumbnail_src = ! empty( $component_data['thumbnail_src'] ) ? $component_data['thumbnail_src'] : '';

							// Parse component thumbnail.
							if ( ! $thumbnail_id && $thumbnail_src ) {
								if ( is_object( self::$importer ) && is_callable( array( self::$importer, 'get_attachment_id_from_url' ) ) ) {
									try {
										$thumbnail_id = self::$importer->get_attachment_id_from_url( $thumbnail_src, $product->get_id() );
									} catch ( Exception $e ) {
										$thumbnail_id = '';
									}
								}
							}

							if ( $thumbnail_id || $thumbnail_src ) {
								$composite_data[ $component_id ]['thumbnail_id'] = Component::set_thumbnail( $thumbnail_id, $thumbnail_src, $product );
							}
						}
					}

					if ( ! empty( $composite_data ) ) {
						$product->set_composite_data( $composite_data );
					}
				}
			} catch ( WC_REST_Exception $e ) {
				throw $e;
			}
		}

		return $product;
	}
}

Product_Import::init();
