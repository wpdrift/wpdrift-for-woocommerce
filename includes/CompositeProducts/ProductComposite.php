<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use stdClass;
use WC_Product;
use WPdrift\CompositeProducts\Module;

/**
 * ProductComposite class
 */
class ProductComposite extends WC_Product {

	/**
	 * Array of composite-type extended product data fields used in CRUD and runtime operations.
	 * @var array
	 */
	private $extended_data = array(
		'add_to_cart_form_location' => 'default',  // "Form Location" option.
		'shop_price_calc'           => 'defaults', // "Catalog Price" option.
		'layout'                    => 'default',  // "Composite Layout" option.
		'editable_in_cart'          => false,      // "Edit in cart" option.
		'sold_individually_context' => 'product',  // Provides context when the "Sold Individually" option is set to 'yes': 'product' or 'configuration'.
		'min_raw_price'             => '',         // Min raw price of the composite based on raw prices, as stored in the DB.
		'max_raw_price'             => '',          // Max raw price of the composite based on raw prices, as stored in the DB.
	);

	/**
	 * Raw meta where all component data is saved.
	 * A shamefully simple way to store/manage data that just works, but can't be used for any complex operations on the DB side.
	 * @var array
	 */
	private $composite_meta = array();

	/**
	 * Indicates whether composite meta have been changed.
	 * @var array
	 */
	private $composite_meta_save_pending = false;

	/**
	 * Raw meta where all scenario data is saved.
	 * A shamefully simple way to store/manage data that just works, but can't be used for any complex operations on the DB side.
	 * @var array
	 */
	private $scenario_meta = array();

	/**
	 * Configurations with lowest/highest composite prices.
	 * Used in 'get_composite_price', 'get_composite_regular_price', 'get_composite_price_including_tax' and 'get_composite_price_excluding_tax methods'.
	 * @var array
	 */
	private $permutations = array();

	/**
	 * Array of composite price data for consumption by the front-end script.
	 * @var array
	 */
	private $composite_price_data = array();

	/**
	 * Array of cached composite prices.
	 * @var array
	 */
	private $composite_price_cache = array();

	/**
	 * Storage of 'contains' keys, most set during sync.
	 * @var array
	 */
	private $contains = array();

	/**
	 * Indicates whether the product has been synced with component data.
	 * @var boolean
	 */
	private $is_synced = false;

	/**
	 * Constructor.
	 *
	 * @param  mixed $composite
	 */
	public function __construct( $composite ) {
		// Initialize private properties.
		$this->load_defaults();

		// Define/load type-specific data.
		$this->load_extended_data();

		// Load product data.
		parent::__construct( $composite );
	}

	/**
	 * Get internal type.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_type() {
		return 'composite';
	}

	/**
	 * Load property and runtime cache defaults to trigger a re-sync.
	 *
	 * @since 1.0.0
	 */
	public function load_defaults() {
		$this->permutations = array(
			'min'    => array(),
			'max'    => array(),
			'status' => '',
		);

		$this->contains = array(
			'priced_individually'  => null,
			'shipped_individually' => null,
			'priced_indefinitely'  => false,
			'optional'             => false,
			'mandatory'            => false,
			'discounted'           => false,
		);

		$this->is_synced             = false;
		$this->composite_price_data  = array();
		$this->composite_price_cache = array();
	}

	/**
	 * Define type-specific data.
	 *
	 * @since  1.0.0
	 */
	private function load_extended_data() {
		// Back-compat.
		$this->product_type = 'composite';

		// Define type-specific fields and let WC use our data store to read the data.
		$this->data = array_merge( $this->data, $this->extended_data );
	}

	/**
	 * Sync composite props with component objects.
	 *
	 * @since  1.0.0
	 *
	 * @param  bool  $force
	 * @return bool
	 */
	public function sync( $force = false ) {
		if ( $this->is_synced && false === $force ) {
			return false;
		}

		$components = $this->get_components();

		if ( ! empty( $components ) ) {

			// Initialize 'contains' data.
			foreach ( $components as $component_id => $component ) {

				if ( $component->is_optional() ) {
					$this->contains['priced_indefinitely'] = true;
					$this->contains['optional']            = true;
				} else {
					$this->contains['mandatory'] = true;
				}

				if ( $component->is_priced_individually() && $component->get_discount() ) {
					$this->contains['discounted'] = true;
				}

				$quantity_min = $component->get_quantity( 'min' );
				$quantity_max = $component->get_quantity( 'max' );

				if ( $quantity_min !== $quantity_max ) {
					$this->contains['priced_indefinitely'] = true;
				}
			}
		}

		// Set synced flag.
		$this->is_synced = true;

		/*
		 * Sync min/max raw prices.
		 */
		if ( $this->sync_raw_prices() ) {
			$this->data_store->save_raw_prices( $this );
		}

		/**
		 * 'woocommerce_composite_synced' action.
		 *
		 * @param  ProductComposite  $this
		 */
		do_action( 'woocommerce_composite_synced', $this );

		return true;
	}

	/**
	 * Sync product bundle raw price meta.
	 *
	 * @since  1.0.0
	 *
	 * @return bool
	 */
	private function sync_raw_prices() {
		// Initialize min/max raw prices.
		$min_raw_price = $max_raw_price = $this->get_price( 'sync' );

		$components = $this->get_components();

		if ( ! empty( $components ) ) {
			foreach ( $components as $component_id => $component ) {
				// Infinite max quantity.
				if ( '' === $component->get_quantity( 'max' ) ) {
					$max_raw_price = INF;
				}
			}
		}

		// Price calculations.
		if ( $this->contains( 'priced_individually' ) && 'hidden' !== $this->get_shop_price_calc() ) {

			$price_data = $this->data_store->read_price_data( $this );

			$this->permutations['status'] = $price_data['status'];

			if ( 'completed' === $price_data['status'] ) {

				/*
				 * Store cheapest/most expensive permutation.
				 */

				if ( ! empty( $price_data ) ) {
					$this->permutations['min'] = $price_data['min'];
					$this->permutations['max'] = $price_data['max'];
				}

				/*
				 * Calculate min/max raw prices from permutations.
				 */

				// Min raw price.
				foreach ( $components as $component_id => $component ) {

					if ( empty( $this->permutations['min'][ $component_id ] ) ) {
						continue;
					}

					$min_component_raw_price_option = $component->get_option( $this->permutations['min'][ $component_id ] );

					if ( $min_component_raw_price_option ) {
						$min_component_raw_price = $min_component_raw_price_option->min_price;
						$quantity_min            = $component->is_optional() ? 0 : $component->get_quantity( 'min' );
						$min_raw_price          += $quantity_min * $min_component_raw_price;
					}
				}

				// Max raw price.

				// Infinite.
				if ( empty( $this->permutations['max'] ) ) {

					$max_raw_price = INF;

					// Finite.
				} elseif ( INF !== $max_raw_price ) {

					foreach ( $components as $component_id => $component ) {

						if ( empty( $this->permutations['max'][ $component_id ] ) ) {
							continue;
						}

						$max_component_raw_price_option = $component->get_option( $this->permutations['max'][ $component_id ] );

						if ( $max_component_raw_price_option ) {
							$max_component_raw_price = $max_component_raw_price_option->max_price;
							$quantity_max            = $component->get_quantity( 'max' );
							$max_raw_price          += $quantity_max * $max_component_raw_price;
						}
					}
				}
			}
		}

		// Filter raw prices.
		$min_raw_price = apply_filters( 'woocommerce_min_composite_price', $min_raw_price, $this );
		$max_raw_price = apply_filters( 'woocommerce_max_composite_price', $max_raw_price, $this );

		// Filter min/max price index.
		$this->permutations['min'] = apply_filters( 'woocommerce_min_composite_price_index', $this->permutations['min'], $this );
		$this->permutations['max'] = apply_filters( 'woocommerce_max_composite_price_index', $this->permutations['max'], $this );

		$raw_price_meta_changed = false;

		if ( $this->get_min_raw_price( 'sync' ) !== $min_raw_price || $this->get_max_raw_price( 'sync' ) !== $max_raw_price ) {
			$raw_price_meta_changed = true;
		}

		$this->set_min_raw_price( $min_raw_price );
		$this->set_max_raw_price( $max_raw_price );

		if ( $raw_price_meta_changed ) {
			return true;
		}

		return false;
	}

	/**
	 * Stores composite pricing strategy data that is passed to JS.
	 *
	 * @return void
	 */
	public function load_price_data() {
		$this->composite_price_data['is_purchasable']   = $this->is_purchasable() ? 'yes' : 'no';
		$this->composite_price_data['has_price_range']  = $this->contains( 'priced_individually' ) && $this->get_composite_price( 'min', true ) !== $this->get_composite_price( 'max', true ) ? 'yes' : 'no';
		$this->composite_price_data['show_free_string'] = ( $this->contains( 'priced_individually' ) ? apply_filters( 'woocommerce_composite_show_free_string', false, $this ) : true ) ? 'yes' : 'no';

		$this->composite_price_data['is_priced_individually'] = array();

		$components = $this->get_components();

		if ( ! empty( $components ) ) {
			foreach ( $components as $component_id => $component ) {
				$this->composite_price_data['is_priced_individually'][ $component_id ] = $component->is_priced_individually() ? 'yes' : 'no';
			}
		}

		$this->composite_price_data['prices']           = new stdClass;
		$this->composite_price_data['regular_prices']   = new stdClass;
		$this->composite_price_data['price_tax_ratios'] = new stdClass;
		$this->composite_price_data['addons_prices']    = new stdClass;
		$this->composite_price_data['quantities']       = new stdClass;

		$this->composite_price_data['base_price']            = $this->get_price();
		$this->composite_price_data['base_regular_price']    = $this->get_regular_price();
		$this->composite_price_data['base_price_tax_ratios'] = Products::get_tax_ratios( $this );
	}

	/**
	 * Calculates composite prices.
	 *
	 * @since  1.0.0
	 *
	 * @param  array  $args
	 * @return mixed
	 */
	public function calculate_price( $args ) {
		$min_or_max = isset( $args['min_or_max'] ) && in_array( $args['min_or_max'], array( 'min', 'max' ) ) ? $args['min_or_max'] : 'min';
		$qty        = isset( $args['qty'] ) ? absint( $args['qty'] ) : 1;
		$price_prop = isset( $args['prop'] ) && in_array( $args['prop'], array( 'price', 'regular_price' ) ) ? $args['prop'] : 'price';
		$price_calc = isset( $args['calc'] ) && in_array( $args['calc'], array( 'incl_tax', 'excl_tax', 'display', '' ) ) ? $args['calc'] : '';
		$strict     = isset( $args['strict'] ) && $args['strict'] && 'regular_price' === $price_prop;

		if ( $this->contains( 'priced_individually' ) ) {

			$this->sync();

			$cache_key = md5(
				json_encode(
					apply_filters(
						'woocommerce_composite_prices_hash',
						array(
							'prop'       => $price_prop,
							'min_or_max' => $min_or_max,
							'calc'       => $price_calc,
							'qty'        => $qty,
							'strict'     => $strict,
						),
						$this
					)
				)
			);

			if ( isset( $this->composite_price_cache[ $cache_key ] ) ) {
				$price = $this->composite_price_cache[ $cache_key ];
			} else {

				$raw_price_fn = 'get_' . $min_or_max . '_raw_price';

				if ( '' === $this->$raw_price_fn() || INF === $this->$raw_price_fn() || empty( $this->permutations[ $min_or_max ] ) ) {
					$price = '';
				} else {

					$price_fn = 'get_' . $price_prop;
					$price    = wc_format_decimal(
						Products::get_product_price(
							$this,
							array(
								'price' => $this->$price_fn(),
								'qty'   => $qty,
								'calc'  => $price_calc,
							)
						),
						wc_cp_price_num_decimals()
					);

					foreach ( $this->permutations[ $min_or_max ] as $component_id => $product_id ) {

						if ( ! $product_id ) {
							continue;
						}

						$component = $this->get_component( $component_id );
						$item_qty  = 'min' === $min_or_max && $component->is_optional() ? 0 : $qty * $component->get_quantity( $min_or_max );

						if ( $item_qty ) {

							$composited_product = $this->get_component_option( $component_id, $product_id );

							if ( ! $composited_product ) {
								continue;
							}

							if ( false === $composited_product->is_purchasable() ) {
								continue;
							}

							$price += wc_format_decimal(
								$composited_product->calculate_price(
									array(
										'min_or_max' => $min_or_max,
										'qty'        => $item_qty,
										'strict'     => $strict,
										'calc'       => $price_calc,
										'prop'       => $price_prop,
									)
								),
								wc_cp_price_num_decimals()
							);
						}
					}
				}

				$this->composite_price_cache[ $cache_key ] = $price;
			}
		} else {

			$price_fn = 'get_' . $price_prop;
			$price    = Products::get_product_price(
				$this,
				array(
					'price' => $this->$price_fn(),
					'qty'   => $qty,
					'calc'  => $price_calc,
				)
			);
		}

		return $price;
	}

	/**
	 * Get min/max composite price.
	 *
	 * @param  string  $min_or_max
	 * @param  boolean $display
	 * @return mixed
	 */
	public function get_composite_price( $min_or_max = 'min', $display = false ) {
		return $this->calculate_price(
			array(
				'min_or_max' => $min_or_max,
				'calc'       => $display ? 'display' : '',
				'prop'       => 'price',
			)
		);
	}

	/**
	 * Get min/max composite regular price.
	 *
	 * @param  string   $min_or_max
	 * @param  boolean  $display
	 * @return mixed
	 */
	public function get_composite_regular_price( $min_or_max = 'min', $display = false ) {
		return $this->calculate_price(
			array(
				'min_or_max' => $min_or_max,
				'calc'       => $display ? 'display' : '',
				'prop'       => 'regular_price',
				'strict'     => true,
			)
		);
	}

	/**
	 * Get min/max composite price including tax.
	 *
	 * @return mixed
	 */
	public function get_composite_price_including_tax( $min_or_max = 'min', $qty = 1 ) {
		return $this->calculate_price(
			array(
				'min_or_max' => $min_or_max,
				'qty'        => $qty,
				'calc'       => 'incl_tax',
				'prop'       => 'price',
			)
		);
	}

	/**
	 * Get min/max composite price excluding tax.
	 *
	 * @return double
	 */
	public function get_composite_price_excluding_tax( $min_or_max = 'min', $qty = 1 ) {
		return $this->calculate_price(
			array(
				'min_or_max' => $min_or_max,
				'qty'        => $qty,
				'calc'       => 'excl_tax',
				'prop'       => 'price',
			)
		);
	}

	/**
	 * Get min/max regular composite price including tax.
	 *
	 * @since  1.0.0
	 *
	 * @param  string   $min_or_max
	 * @param  integer  $qty
	 * @return mixed
	 */
	public function get_composite_regular_price_including_tax( $min_or_max = 'min', $qty = 1 ) {
		return $this->calculate_price(
			array(
				'min_or_max' => $min_or_max,
				'qty'        => $qty,
				'calc'       => 'incl_tax',
				'prop'       => 'regular_price',
				'strict'     => true,
			)
		);
	}

	/**
	 * Get min/max regular composite price excluding tax.
	 *
	 * @since  1.0.0
	 *
	 * @param  string   $min_or_max
	 * @param  integer  $qty
	 * @return mixed
	 */
	public function get_composite_regular_price_excluding_tax( $min_or_max = 'min', $qty = 1 ) {
		return $this->calculate_price(
			array(
				'min_or_max' => $min_or_max,
				'qty'        => $qty,
				'calc'       => 'excl_tax',
				'prop'       => 'regular_price',
				'strict'     => true,
			)
		);
	}

	/**
	 * Returns range style html price string without min and max.
	 *
	 * @param  mixed  $price
	 * @return string
	 */
	public function get_price_html( $price = '' ) {

		$this->sync();

		$components = $this->get_components();

		if ( $this->contains( 'priced_individually' ) && ! empty( $components ) ) {

			// Get the price.
			if ( 'hidden' === $this->get_shop_price_calc() || '' === $this->get_composite_price( 'min' ) ) {

				$price = apply_filters( 'woocommerce_composite_empty_price_html', '', $this );

			} else {

				$has_indefinite_max_price = 'defaults' === $this->get_shop_price_calc() || $this->contains( 'priced_indefinitely' ) || INF === $this->get_max_raw_price();

				/**
				 * 'woocommerce_composite_force_old_style_price_html' filter.
				 *
				 * Used to suppress the range-style display of bundle price html strings.
				 *
				 * @param  boolean            $suppress_range_price_html
				 * @param  WC_Product_Bundle  $this
				 */
				$suppress_range_price_html = $has_indefinite_max_price || apply_filters( 'woocommerce_composite_force_old_style_price_html', false, $this );

				$price_min = $this->get_composite_price( 'min', true );
				$price_max = $this->get_composite_price( 'max', true );

				if ( $suppress_range_price_html ) {

					$price = wc_price( $price_min );

					$regular_price_min = $this->get_composite_regular_price( 'min', true );

					if ( $regular_price_min !== $price_min ) {

						$regular_price = wc_price( $regular_price_min );

						if ( $price_min !== $price_max ) {
							$price = sprintf( _x( '%1$s%2$s', 'Price range: from', 'wpdrift-woocommerce-modules' ), wc_get_price_html_from_text(), wc_format_sale_price( $regular_price, $price ) . $this->get_price_suffix() );
						} else {
							$price = wc_format_sale_price( $regular_price, $price ) . $this->get_price_suffix();
						}

						$price = apply_filters( 'woocommerce_composite_sale_price_html', $price, $this );

					} elseif ( 0.0 === $price_min && 0.0 === $price_max ) {

						$free_string = apply_filters( 'woocommerce_composite_show_free_string', false, $this ) ? __( 'Free!', 'wpdrift-woocommerce-modules' ) : $price;
						$price       = apply_filters( 'woocommerce_composite_free_price_html', $free_string, $this );

					} else {

						if ( $price_min !== $price_max ) {
							$price = sprintf( _x( '%1$s%2$s', 'Price range: from', 'wpdrift-woocommerce-modules' ), wc_get_price_html_from_text(), $price . $this->get_price_suffix() );
						} else {
							$price = $price . $this->get_price_suffix();
						}

						$price = apply_filters( 'woocommerce_composite_price_html', $price, $this );
					}
				} else {

					$is_range = false;

					if ( $price_min !== $price_max ) {
						$price    = wc_format_price_range( $price_min, $price_max );
						$is_range = true;
					} else {
						$price = wc_price( $price_min );
					}

					$regular_price_min = $this->get_composite_regular_price( 'min', true );
					$regular_price_max = $this->get_composite_regular_price( 'max', true );

					if ( $regular_price_min !== $price_min || $regular_price_max > $price_max ) {

						if ( $regular_price_min !== $regular_price_max ) {
							$regular_price = wc_format_price_range( min( $regular_price_min, $regular_price_max ), max( $regular_price_min, $regular_price_max ) );
						} else {
							$regular_price = wc_price( $regular_price_min );
						}

						$price = apply_filters( 'woocommerce_composite_sale_price_html', wc_format_sale_price( $regular_price, $price ) . $this->get_price_suffix( 'range' ), $this );

					} elseif ( 0.0 === $price_min && 0.0 === $price_max ) {

						$free_string = apply_filters( 'woocommerce_composite_show_free_string', false, $this ) ? __( 'Free!', 'wpdrift-woocommerce-modules' ) : $price;
						$price       = apply_filters( 'woocommerce_composite_free_price_html', $free_string, $this );

					} else {
						$price = apply_filters( 'woocommerce_composite_price_html', $price . $this->get_price_suffix( 'range' ), $this );
					}
				}
			}

			return apply_filters( 'woocommerce_get_price_html', $price, $this );

		} else {

			return parent::get_price_html();
		}
	}

	/**
	 * Price suffix for Composite products.
	 *
	 * @return string
	 */
	public function get_price_suffix( $price = '', $qty = 1 ) {

		if ( ! $this->contains( 'priced_individually' ) ) {
			return parent::get_price_suffix();
		}

		$suffix      = get_option( 'woocommerce_price_display_suffix' );
		$suffix_html = '';

		if ( $suffix && wc_tax_enabled() ) {

			if ( 'range' === $price && strstr( $suffix, '{' ) ) {
				$suffix = false;
				$price  = '';
			}

			if ( $suffix ) {

				$replacements = array(
					'{price_including_tax}' => wc_price( $this->get_composite_price_including_tax( 'min', $qty ) ),
					'{price_excluding_tax}' => wc_price( $this->get_composite_price_excluding_tax( 'min', $qty ) ),
				);

				$suffix_html = str_replace( array_keys( $replacements ), array_values( $replacements ), ' <small class="woocommerce-price-suffix">' . wp_kses_post( $suffix ) . '</small>' );
			}
		}

		/**
		 * 'woocommerce_get_price_suffix' filter.
		 *
		 * @param  string             $suffix_html
		 * @param  WC_Product_Bundle  $this
		 * @param  mixed              $price
		 * @param  int                $qty
		 */
		return apply_filters( 'woocommerce_get_price_suffix', $suffix_html, $this, $price, $qty );
	}

	/**
	 * Gets price data array. Contains localized strings and price data passed to JS.
	 *
	 * @return array
	 */
	public function get_composite_price_data() {

		if ( empty( $this->composite_price_data ) ) {
			$this->load_price_data();
		}

		return $this->composite_price_data;
	}

	/**
	 * Wrapper for get_permalink that adds composite configuration data to the URL.
	 *
	 * @return string
	 */
	public function get_permalink() {

		$permalink     = get_permalink( $this->get_id() );
		$fn_args_count = func_num_args();

		if ( 1 === $fn_args_count ) {

			$cart_item = func_get_arg( 0 );

			if ( isset( $cart_item['composite_data'] ) && is_array( $cart_item['composite_data'] ) ) {

				$config_data      = isset( $cart_item['composite_data'] ) ? $cart_item['composite_data'] : array();
				$args             = Module::instance()->cart->rebuild_posted_composite_form_data( $config_data );
				$args['quantity'] = $cart_item['quantity'];

				/**
				 * 'woocommerce_composite_cart_permalink_args' filter.
				 *
				 * @param  array                 $args
				 * @param  array                 $cart_item
				 * @param  ProductComposite  $this
				 */
				$args = apply_filters( 'woocommerce_composite_cart_permalink_args', $args, $cart_item, $this );

				$escaped_array = Helpers::urlencode_recursive( $args );

				if ( ! empty( $args ) ) {
					$permalink = add_query_arg( $escaped_array, $permalink );
				}
			}
		}

		return $permalink;
	}

	/**
	 * Generate component slugs based on component titles. Used to generate routes.
	 *
	 * @return array
	 */
	public function get_component_slugs() {

		$slugs = Helpers::cache_get( 'component_slugs_' . $this->get_id() );

		if ( is_array( $slugs ) ) {
			return $slugs;
		}

		$slugs      = array();
		$components = $this->get_components();

		if ( ! empty( $components ) ) {
			foreach ( $components as $component_id => $component ) {

				$sanitized_title = sanitize_title( $component->get_title() );
				$component_slug  = $sanitized_title;
				$loop            = 0;

				while ( in_array( $component_slug, $slugs ) ) {
					$loop++;
					$component_slug = $sanitized_title . '-' . $loop;
				}

				$slugs[ $component_id ] = $component_slug;
			}

			$review_slug     = 'componentized' === $this->get_composite_layout_style_variation() ? __( 'configuration', 'wpdrift-woocommerce-modules' ) : __( 'review', 'wpdrift-woocommerce-modules' );
			$slugs['review'] = sanitize_title( $review_slug );
		}

		Helpers::cache_set( 'component_slugs_' . $this->get_id(), $slugs );

		return $slugs;
	}

	/**
	 * Get the add to cart button text.
	 *
	 * @return  string
	 */
	public function add_to_cart_text() {

		$text = $this->is_purchasable() && $this->is_in_stock() ? __( 'Select options', 'wpdrift-woocommerce-modules' ) : __( 'Read More', 'wpdrift-woocommerce-modules' );

		return apply_filters( 'woocommerce_product_add_to_cart_text', $text, $this );
	}

	/**
	 * Get the add to cart button text for the single page.
	 *
	 * @return string
	 */
	public function single_add_to_cart_text() {

		$text = __( 'Add to cart', 'wpdrift-woocommerce-modules' );

		if ( isset( $_GET['update-composite'] ) ) {
			$updating_cart_key = wc_clean( $_GET['update-composite'] );

			if ( isset( WC()->cart->cart_contents[ $updating_cart_key ] ) ) {
				$text = __( 'Update Cart', 'wpdrift-woocommerce-modules' );
			}
		}

		return apply_filters( 'woocommerce_product_single_add_to_cart_text', $text, $this );
	}

	/**
	 * Get composite-specific add to cart form settings.
	 *
	 * @return  string
	 */
	public function add_to_cart_form_settings() {

		$image_data               = array();
		$pagination_data          = array();
		$placeholder_option       = array();
		$product_price_visibility = array();
		$subtotal_visibility      = array();
		$price_display_format     = array();

		$components = $this->get_components();

		if ( ! empty( $components ) ) {
			foreach ( $components as $component_id => $component ) {
				$image_data[ $component_id ]               = $component->get_image_data();
				$pagination_data[ $component_id ]          = $component->get_pagination_data();
				$placeholder_option[ $component_id ]       = $component->show_placeholder_option() ? 'yes' : 'no';
				$product_price_visibility[ $component_id ] = $component->hide_selected_option_price() ? 'no' : 'yes';
				$subtotal_visibility[ $component_id ]      = $component->is_subtotal_visible() ? 'yes' : 'no';
				$price_display_format[ $component_id ]     = $component->get_price_display_format();
			}
		}

		$settings = array(
			// Apply a sequential configuration process when using the 'componentized' layout.
			// When set to 'yes', a component can be configured only if all previous components have been configured.
			'sequential_componentized_progress'      => apply_filters( 'woocommerce_composite_sequential_comp_progress', 'no', $this ), /* yes | no */
			// Hide or disable the add-to-cart button if the composite has any components pending user input.
			'button_behaviour'                       => apply_filters( 'woocommerce_composite_button_behaviour', 'new', $this ), /* new | old */
			'layout'                                 => $this->get_composite_layout_style(),
			'layout_variation'                       => $this->get_composite_layout_style_variation(),
			'update_browser_history'                 => $this->get_composite_layout_style() !== 'single' ? 'yes' : 'no',
			'show_placeholder_option'                => $placeholder_option,
			'slugs'                                  => $this->get_component_slugs(),
			'image_data'                             => $image_data,
			'pagination_data'                        => $pagination_data,
			'selected_product_price_visibility_data' => $product_price_visibility,
			'subtotal_visibility_data'               => $subtotal_visibility,
			'price_display_format_data'              => $price_display_format,
			'summary_carousel_autoscroll'            => 'yes',
			'summary_carousel_scroll_coeff'          => 0.5,
			'component_qty_restore'                  => apply_filters( 'woocommerce_composite_component_qty_restore', 'yes', $this ), /* yes | no */
		);

		/**
		 * Filter composite-level JS app settings.
		 *
		 * @param  array                 $settings
		 * @param  ProductComposite  $product
		 */
		return apply_filters( 'woocommerce_composite_add_to_cart_form_settings', $settings, $this );
	}

	/**
	 * Container of scenarios-related functionality - @see ScenariosManager.
	 *
	 * @param  string  $context
	 * @return ScenariosManager
	 */
	public function scenarios( $context = 'view' ) {

		$prop = 'scenarios_manager_' . $context;

		if ( ! isset( $this->$prop ) ) {
			$this->$prop = new ScenariosManager( $this, $context );
		}

		return $this->$prop;
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Getters
	|--------------------------------------------------------------------------
	|
	| Methods for getting data from the product object.
	*/

	/**
	 * Returns the base active price of the composite.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context
	 * @return mixed
	 */
	public function get_price( $context = 'view' ) {
		$value = $this->get_prop( 'price', $context );
		return in_array( $context, array( 'view', 'sync' ) ) && $this->contains( 'priced_individually' ) ? (float) $value : $value;
	}

	/**
	 * Returns the base regular price of the composite.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $context
	 * @return mixed
	 */
	public function get_regular_price( $context = 'view' ) {
		$value = $this->get_prop( 'regular_price', $context );
		return in_array( $context, array( 'view', 'sync' ) ) && $this->contains( 'priced_individually' ) ? (float) $value : $value;
	}

	/**
	 * Returns the base sale price of the composite.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return mixed
	 */
	public function get_sale_price( $context = 'view' ) {
		$value = $this->get_prop( 'sale_price', $context );
		return in_array( $context, array( 'view', 'sync' ) ) && $this->contains( 'priced_individually' ) && '' !== $value ? (float) $value : $value;
	}

	/**
	 * Catalog Price getter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function get_shop_price_calc( $context = 'any' ) {

		$value = $this->get_prop( 'shop_price_calc', $context );

		// Back compat with 'hide_price_html'.
		if ( has_filter( 'woocommerce_composite_hide_price_html' ) ) {
			_deprecated_function( 'The "woocommerce_composite_hide_price_html" filter', '1.0.0' );
			$is_hidden = apply_filters( 'woocommerce_composite_hide_price_html', 'hidden' === $value, $this );
			$value     = $is_hidden ? 'hidden' : $value;
		}

		return $value;
	}

	/**
	 * Form Location getter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function get_add_to_cart_form_location( $context = 'view' ) {
		return $this->get_prop( 'add_to_cart_form_location', $context );
	}

	/**
	 * Layout getter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function get_layout( $context = 'any' ) {
		return $this->get_prop( 'layout', $context );
	}

	/**
	 * Editable-in-cart getter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function get_editable_in_cart( $context = 'any' ) {
		return $this->get_prop( 'editable_in_cart', $context );
	}

	/**
	 * "Sold Individually" option context.
	 * Returns 'product' or 'configuration'.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function get_sold_individually_context( $context = 'any' ) {
		return $this->get_prop( 'sold_individually_context', $context );
	}

	/**
	 * Minimum raw composite price getter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function get_min_raw_price( $context = 'view' ) {
		if ( 'sync' !== $context ) {
			$this->sync();
		}
		$value = $this->get_prop( 'min_raw_price', $context );
		return in_array( $context, array( 'view', 'sync' ) ) && $this->contains( 'priced_individually' ) && '' !== $value ? (float) $value : $value;
	}

	/**
	 * Maximum raw composite price getter.
	 * INF is 9999999999.0 in 'edit' (DB) context.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 * @return string
	 */
	public function get_max_raw_price( $context = 'view' ) {
		if ( 'sync' !== $context ) {
			$this->sync();
		}
		$value = $this->get_prop( 'max_raw_price', $context );
		$value = 'edit' !== $context && $this->contains( 'priced_individually' ) && '' !== $value && INF !== $value ? (float) $value : $value;
		$value = 'edit' === $context && INF === $value ? 9999999999.0 : $value;
		return $value;
	}

	/**
	 * Get data for all Components, indexed by component ID.
	 *
	 * @return array
	 */
	public function get_composite_data( $context = 'any' ) {

		$components = $this->get_components();

		if ( empty( $components ) ) {
			return false;
		}

		$composite_data = array();

		foreach ( $components as $component_id => $component ) {

			if ( 'rest' === $context ) {

				$thumbnail_id  = '';
				$thumbnail_src = '';

				if ( ! empty( $component['thumbnail_id'] ) ) {

					$thumbnail_id = absint( $component['thumbnail_id'] );
					$image        = wp_get_attachment_image_src( $thumbnail_id, 'full' );

					if ( $image ) {
						$thumbnail_src = $image[0];
					}
				}

				$composite_data[ $component_id ] = array(
					'id'                       => (string) $component->get_id(),
					'title'                    => $component->get_title(),
					'description'              => $component->get_description(),
					'query_type'               => isset( $component['query_type'] ) ? $component['query_type'] : 'product_ids',
					'query_ids'                => 'category_ids' === $component['query_type'] ? (array) $component['assigned_category_ids'] : (array) $component['assigned_ids'],
					'default_option_id'        => $component->get_default_option(),
					'thumbnail_id'             => $thumbnail_id,
					'thumbnail_src'            => $thumbnail_src,
					'quantity_min'             => $component->get_quantity( 'min' ),
					'quantity_max'             => $component->get_quantity( 'max' ),
					'priced_individually'      => $component->is_priced_individually(),
					'shipped_individually'     => $component->is_shipped_individually(),
					'optional'                 => $component->is_optional(),
					'discount'                 => $component->get_discount(),
					'options_style'            => $component->get_options_style(),
					'pagination_style'         => $component->get_pagination_style(),
					'display_prices'           => $component->get_price_display_format(),
					'show_sorting_options'     => $component->show_sorting_options(),
					'show_filtering_options'   => $component->show_filtering_options(),
					'attribute_filter_ids'     => $component->get_attribute_filters(),
					'product_title_visible'    => false === $component->hide_selected_option_title(),
					'product_descr_visible'    => false === $component->hide_selected_option_description(),
					'product_price_visible'    => false === $component->hide_selected_option_price(),
					'product_thumb_visible'    => false === $component->hide_selected_option_thumbnail(),
					'subtotal_visible_product' => $component->is_subtotal_visible( 'product' ),
					'subtotal_visible_cart'    => $component->is_subtotal_visible( 'cart' ),
					'subtotal_visible_orders'  => $component->is_subtotal_visible( 'orders' ),
				);

			} else {
				$composite_data[ $component_id ] = $component->get_data();
			}
		}

		return $composite_data;
	}

	/**
	 * Get raw scenario data, indexed by scenario ID.
	 *
	 * @return array
	 */
	public function get_scenario_data( $context = 'view' ) {

		$scenario_meta = $this->scenario_meta;

		if ( empty( $scenario_meta ) ) {
			$scenario_meta = array();
		}

		if ( 'rest' === $context ) {

			$rest_api_scenario_meta = array();

			if ( ! empty( $scenario_meta ) ) {
				foreach ( $scenario_meta as $id => $data ) {

					$configuration = array();
					$actions       = array();

					if ( ! empty( $data['component_data'] ) && is_array( $data['component_data'] ) ) {
						foreach ( $data['component_data'] as $component_id => $component_data ) {
							$configuration[] = array(
								'component_id'      => strval( $component_id ),
								'component_options' => $component_data,
								'options_modifier'  => isset( $data['modifier'][ $component_id ] ) ? $data['modifier'][ $component_id ] : 'in',
							);
						}
					}

					if ( ! empty( $data['scenario_actions'] ) && is_array( $data['scenario_actions'] ) ) {
						foreach ( $data['scenario_actions'] as $action_id => $action_data ) {
							$actions[] = array(
								'action_id'   => strval( $action_id ),
								'is_active'   => isset( $action_data['is_active'] ) && 'yes' === $action_data['is_active'],
								'action_data' => array_diff_key( $action_data, array( 'is_active' => 1 ) ),
							);
						}
					}

					$rest_api_scenario_meta[ $id ] = array(
						'id'            => (string) $id,
						'name'          => esc_html( $data['title'] ),
						'description'   => esc_html( $data['description'] ),
						'configuration' => $configuration,
						'actions'       => $actions,
					);
				}
			}

			$scenario_meta = $rest_api_scenario_meta;
		}

		/**
		 * Filter raw scenario metadata.
		 *
		 * @param  array                 $scenario_meta
		 * @param  ProductComposite  $product
		 */
		return 'view' === $context ? apply_filters( 'woocommerce_composite_scenario_meta', $scenario_meta, $this ) : $scenario_meta;
	}

	/*
	|--------------------------------------------------------------------------
	| CRUD Setters
	|--------------------------------------------------------------------------
	|
	| Functions for setting product data. These should not update anything in the
	| database itself and should only change what is stored in the class
	| object.
	*/

	/**
	 * Shop price calc setter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $value
	 */
	public function set_shop_price_calc( $value ) {
		$value = in_array( $value, array_keys( self::get_shop_price_calc_options() ) ) ? $value : 'defaults';
		$this->set_prop( 'shop_price_calc', $value );
	}

	/**
	 * Form Location setter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $value
	 */
	public function set_add_to_cart_form_location( $value ) {
		$value = in_array( $value, array_keys( self::get_add_to_cart_form_location_options() ) ) ? $value : 'default';
		return $this->set_prop( 'add_to_cart_form_location', $value );
	}

	/**
	 * Layout setter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $layout
	 */
	public function set_layout( $layout ) {
		$layout = self::get_layout_option( $layout );
		$this->set_prop( 'layout', $layout );
	}

	/**
	 * Edtiable-in-cart setter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $editable_in_cart
	 */
	public function set_editable_in_cart( $editable_in_cart ) {

		$editable_in_cart = wc_string_to_bool( $editable_in_cart );
		$this->set_prop( 'editable_in_cart', $editable_in_cart );

		if ( $editable_in_cart ) {
			if ( ! in_array( 'edit_in_cart', $this->supports ) ) {
				$this->supports[] = 'edit_in_cart';
			}
		} else {
			foreach ( $this->supports as $key => $value ) {
				if ( 'edit_in_cart' === $value ) {
					unset( $this->supports[ $key ] );
				}
			}
		}
	}

	/**
	 * Sold-individually context setter.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $context
	 */
	public function set_sold_individually_context( $context ) {
		$context = in_array( $context, array( 'product', 'configuration' ) ) ? $context : 'product';
		$this->set_prop( 'sold_individually_context', $context );
	}

	/**
	 * Minimum raw composite price setter.
	 *
	 * @since  1.0.0
	 *
	 * @param  mixed  $value
	 */
	public function set_min_raw_price( $value ) {
		$value = wc_format_decimal( $value );
		$this->set_prop( 'min_raw_price', $value );
	}

	/**
	 * Maximum raw composite price setter.
	 * Convert 9999999999.0 to INF.
	 *
	 * @since  1.0.0
	 *
	 * @param  mixed  $value
	 */
	public function set_max_raw_price( $value ) {
		$value = INF !== $value ? wc_format_decimal( $value ) : INF;
		$value = 9999999999.0 === (float) $value ? INF : $value;
		$this->set_prop( 'max_raw_price', $value );
	}

	/**
	 * Set raw components data using internal schema.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function set_composite_data( $data ) {

		$validated_data = array();

		if ( is_array( $data ) ) {
			foreach ( $data as $key => $values ) {

				if ( empty( $values['title'] ) ) {
					continue;
				}

				if ( empty( $values['query_type'] ) ) {
					$values['query_type'] = 'product_ids';
				}

				if ( ( 'product_ids' === $values['query_type'] && empty( $values['assigned_ids'] ) ) || ( $values['query_type'] === 'category_ids' && empty( $values['assigned_category_ids'] ) ) ) {
					continue;
				}

				$validated_data[ strval( absint( $key ) ) ] = $values;

				if ( $this->get_object_read() ) {
					$this->composite_meta_save_pending = true;
				}
			}
		}

		$this->composite_meta = $validated_data;
		$this->load_defaults();
	}

	/**
	 * Set raw scenario data using internal schema.
	 *
	 * @internal
	 *
	 * @since 1.0.0
	 */
	public function set_scenario_data( $data ) {

		$validated_data = array();

		if ( is_array( $data ) ) {
			foreach ( $data as $key => $values ) {

				if ( empty( $values['title'] ) ) {
					continue;
				}

				$validated_data[ $key ] = $values;
			}
		}

		$this->scenario_meta = $validated_data;
	}

	/*
	|--------------------------------------------------------------------------
	| Other CRUD Methods
	|--------------------------------------------------------------------------
	*/

	/**
	 * Ensure properties are set correctly before saving.
	 *
	 * @since 1.0.0
	 */
	public function validate_props() {
		parent::validate_props();
		$this->validate_scenarios();
	}

	/**
	 * Ensure scenarios are consistent with components before saving.
	 *
	 * @since 1.0.0
	 */
	protected function validate_scenarios() {

		$data = $this->get_scenario_data( 'edit' );

		$components             = $this->get_components();
		$component_options      = array();
		$component_options_data = array();

		if ( ! empty( $data ) ) {

			foreach ( $components as $component_id => $component ) {
				$component_options[ $component_id ]      = $component->get_options();
				$component_options_data[ $component_id ] = $this->get_data_store()->get_expanded_component_options( $component_options[ $component_id ], 'all' );
			}

			foreach ( $data as $scenario_id => $scenario_data ) {

				if ( empty( $scenario_data['component_data'] ) ) {
					$data[ $scenario_id ]['component_data'] = array();
				}

				if ( empty( $scenario_data['scenario_actions'] ) ) {
					$data[ $scenario_id ]['scenario_actions'] = array();
				}

				if ( empty( $data[ $scenario_id ]['scenario_actions']['compat_group'] ) ) {
					$data[ $scenario_id ]['scenario_actions']['compat_group'] = array();
				}

				if ( empty( $data[ $scenario_id ]['scenario_actions']['compat_group']['is_active'] ) || 'yes' !== $data[ $scenario_id ]['scenario_actions']['compat_group']['is_active'] ) {
					$data[ $scenario_id ]['scenario_actions']['compat_group']['is_active'] = 'no';
				}

				if ( empty( $data[ $scenario_id ]['scenario_actions']['conditional_components'] ) ) {
					$data[ $scenario_id ]['scenario_actions']['conditional_components'] = array();
				}

				if ( empty( $data[ $scenario_id ]['scenario_actions']['conditional_components']['is_active'] ) || 'yes' !== $data[ $scenario_id ]['scenario_actions']['conditional_components']['is_active'] ) {
					$data[ $scenario_id ]['scenario_actions']['conditional_components']['is_active'] = 'no';
				}

				/*
				 * Validate scenario configuration.
				 */

				$all_masked = true;

				foreach ( $components as $component_id => $component ) {

					if ( empty( $scenario_data['modifier'][ $component_id ] ) ) {
						$data[ $scenario_id ]['modifier'][ $component_id ] = 'in';
					}

					if ( 'masked' !== $data[ $scenario_id ]['modifier'][ $component_id ] ) {
						$all_masked = false;
					}

					if ( 'exclude' === $data[ $scenario_id ]['modifier'][ $component_id ] ) {
						$data[ $scenario_id ]['modifier'][ $component_id ] = 'not-in';
					}

					if ( 'not-in' === $data[ $scenario_id ]['modifier'][ $component_id ] ) {
						if ( empty( $data[ $scenario_id ]['component_data'][ $component_id ] ) || Helpers::in_array_key( $data[ $scenario_id ]['component_data'], $component_id, 0 ) ) {
							$data[ $scenario_id ]['modifier'][ $component_id ] = 'in';
						}
					}

					if ( empty( $data[ $scenario_id ]['component_data'][ $component_id ] ) || Helpers::in_array_key( $data[ $scenario_id ]['component_data'], $component_id, 0 ) ) {
						$data[ $scenario_id ]['component_data'][ $component_id ] = array( 0 );
						continue;
					}

					$validated_configuration = array();

					if ( Helpers::in_array_key( $scenario_data['component_data'], $component_id, -1 ) ) {
						$validated_configuration[] = -1;
					}

					foreach ( $scenario_data['component_data'][ $component_id ] as $id_in_scenario ) {

						if ( (int) $id_in_scenario === -1 || (int) $id_in_scenario === 0 ) {
							continue;
						}

						$parent_id = isset( $component_options_data[ $component_id ]['mapped'][ $id_in_scenario ] ) ? $component_options_data[ $component_id ]['mapped'][ $id_in_scenario ] : false;

						if ( $parent_id ) {

							if ( ! in_array( $parent_id, $scenario_data['component_data'][ $component_id ] ) ) {
								$validated_configuration[] = $id_in_scenario;
							}
						} elseif ( in_array( $id_in_scenario, $component_options[ $component_id ] ) ) {
							$validated_configuration[] = $id_in_scenario;
						}
					}
				}

				if ( $all_masked ) {
					unset( $data[ $scenario_id ] );
				}
			}
		}

		$this->set_scenario_data( $data );
	}

	/**
	 * Alias for 'set_props'.
	 *
	 * @since 1.0.0
	 */
	public function set( $properties ) {
		return $this->set_props( $properties );
	}

	/**
	 * Override 'save' to invalidate component runtime cache.
	 *
	 * @since 1.0.0
	 */
	public function save() {

		parent::save();

		// Save composite props that depend on components.
		$this->sync( true );

		if ( $this->composite_meta_save_pending ) {
			$this->composite_meta_save_pending = false;
			foreach ( $this->get_component_ids() as $component_id ) {
				Helpers::cache_delete( 'wc_cp_component_' . $component_id . '_' . $this->get_id() );
			}
		}

		return $this->get_id();
	}

	/*
	|--------------------------------------------------------------------------
	| Conditionals
	|--------------------------------------------------------------------------
	*/

	/**
	 * Getter of composite 'contains' properties.
	 *
	 * @since  1.0.0
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function contains( $key ) {

		if ( 'priced_individually' === $key ) {

			if ( is_null( $this->contains[ $key ] ) ) {

				$this->contains['priced_individually'] = false;

				// Any components priced individually?
				$components = $this->get_components();

				if ( ! empty( $components ) ) {

					foreach ( $components as $component ) {
						if ( $component->is_priced_individually() ) {
							$this->contains['priced_individually'] = true;
						}
					}
				}
			}
		} elseif ( 'shipped_individually' === $key ) {

			if ( is_null( $this->contains[ $key ] ) ) {

				$this->contains['shipped_individually'] = false;

				// Any components shipped individually?
				$components = $this->get_components();

				if ( ! empty( $components ) ) {

					foreach ( $components as $component ) {
						if ( $component->is_shipped_individually() ) {
							$this->contains['shipped_individually'] = true;
						}
					}
				}
			}
		} else {
			$this->sync();
		}

		return isset( $this->contains[ $key ] ) ? $this->contains[ $key ] : null;
	}

	/**
	 * Indicates whether the catalog price of this product is being calculated in the background or has maybe failed to complete.
	 * Values: 'pending' | 'completed' | 'failed' | ''
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get_shop_price_calc_status( $context = 'view' ) {

		$status = '';

		if ( 'hidden' === $this->get_shop_price_calc() ) {
			return $status;
		}

		if ( 'view' === $context ) {
			$this->sync();
			$status = $this->permutations['status'];
		} else {
			$price_data = $this->get_meta( '_bto_price_data', true );
			if ( $price_data && isset( $price_data['status'] ) ) {
				$status = $price_data['status'];
			}
		}

		return $status;
	}

	/**
	 * True if the composite is in sync with its contents.
	 *
	 * @return boolean
	 */
	public function is_synced() {
		return $this->is_synced;
	}

	/**
	 * True if a one of the composited products has a component discount, or if there is a base sale price defined.
	 *
	 * @param  string  $context
	 * @return boolean
	 */
	public function is_on_sale( $context = 'view' ) {

		if ( 'update-price' !== $context && $this->contains( 'priced_individually' ) ) {
			$this->sync();
			$composite_on_sale = parent::is_on_sale( $context ) || ( $this->contains( 'discounted' ) && $this->get_composite_regular_price() > 0 );
		} else {
			$composite_on_sale = parent::is_on_sale( $context );
		}

		/**
		 * Filter composite on sale status.
		 *
		 * @param   boolean               $composite_on_sale
		 * @param   ProductComposite  $this
		 */
		return 'view' === $context ? apply_filters( 'woocommerce_product_is_on_sale', $composite_on_sale, $this ) : $composite_on_sale;
	}

	/**
	 * Override purchasable method to account for empty price meta being allowed when individually-priced components exist.
	 *
	 * @return boolean
	 */
	public function is_purchasable() {

		$purchasable = true;

		// Products must exist of course.
		if ( ! $this->exists() ) {
			$purchasable = false;

			// When priced statically a price needs to be set.
		} elseif ( ! $this->contains( 'priced_individually' ) && '' === $this->get_price() ) {
			$purchasable = false;

			// Check the product is published.
		} elseif ( 'publish' !== $this->get_status() && ! current_user_can( 'edit_post', $this->get_id() ) ) {
			$purchasable = false;
		}

		/**
		 * Filter composite purchasable status.
		 *
		 * @param   boolean               $is_purchasable
		 * @param   ProductComposite  $product
		 */
		return apply_filters( 'woocommerce_is_purchasable', $purchasable, $this );
	}

	/**
	 * True if the composite is editable in cart.
	 *
	 * @return boolean
	 */
	public function is_editable_in_cart() {
		return $this->supports( 'edit_in_cart' );
	}

	/*
	|--------------------------------------------------------------------------
	| Layout.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Composite base layout.
	 *
	 * @return string
	 */
	public function get_composite_layout_style() {

		if ( isset( $this->base_layout ) ) {
			return $this->base_layout;
		}

		$composite_layout = $this->get_layout();
		$layout           = explode( '-', $composite_layout, 2 );

		$this->base_layout = $layout[0];

		return $this->base_layout;
	}

	/**
	 * Composite base layout variation.
	 *
	 * @return string
	 */
	public function get_composite_layout_style_variation() {

		if ( isset( $this->base_layout_variation ) ) {
			return $this->base_layout_variation;
		}

		$composite_layout = $this->get_layout();

		$layout = explode( '-', $composite_layout, 2 );

		if ( ! empty( $layout[1] ) ) {
			$this->base_layout_variation = $layout[1];
		} else {
			$this->base_layout_variation = 'standard';
		}

		return $this->base_layout_variation;
	}

	/*
	|--------------------------------------------------------------------------
	| Scenarios.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Build scenario data arrays for specific components, adapted to the data present in the current component options queries.
	 * Make sure this is always called after component options queries have run, otherwise component options queries will be populated with results for the initial composite state.
	 *
	 * @param  array    $component_ids
	 * @param  boolean  $use_current_query
	 * @return array
	 */
	public function get_current_scenario_data( $component_ids = array() ) {

		$component_options_subset = array();

		foreach ( $this->get_components() as $component_id => $component ) {

			if ( empty( $component_ids ) || in_array( $component_id, $component_ids ) ) {

				$current_component_options = $this->get_current_component_options( $component_id );
				$default_option            = $this->get_current_component_selection( $component_id );

				if ( $default_option && ! in_array( $default_option, $current_component_options ) ) {
					$current_component_options[] = $default_option;
				}

				$component_options_subset[ $component_id ] = $current_component_options;
			}
		}

		$scenario_data = $this->scenarios()->get_data( $component_options_subset );

		/**
		 * Filter generated scenario data.
		 *
		 * @since  1.0.0
		 *
		 * @param  array                 $scenario_data
		 * @param  array                 $component_options_subset
		 * @param  ProductComposite  $product
		 */
		return apply_filters( 'woocommerce_composite_current_scenario_data', $scenario_data, $component_options_subset, $this );
	}

	/*
	|--------------------------------------------------------------------------
	| Component methods: Instantiation and data.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get component raw meta array by component id.
	 * All component data is currently lumped in a single meta field, which should hopefully change at some point.
	 *
	 * @param  string  $component_id
	 * @return array
	 */
	public function get_component_meta( $component_id ) {

		if ( ! isset( $this->composite_meta[ $component_id ] ) ) {
			return false;
		}

		return $this->composite_meta[ $component_id ];
	}

	/**
	 * Component object getter.
	 *
	 * @param  string  $component_id
	 * @return Component
	 */
	public function get_component( $component_id ) {

		$component = false;

		if ( $this->has_component( $component_id ) ) {

			$component = Helpers::cache_get( 'wc_cp_component_' . $component_id . '_' . $this->get_id() );

			if ( $this->composite_meta_save_pending || defined( 'DEBUG_RUNTIME_CACHE' ) || null === $component ) {
				$component = new Component( $component_id, $this );
				Helpers::cache_set( 'wc_cp_component_' . $component_id . '_' . $this->get_id(), $component );
			}
		}

		return $component;
	}

	/**
	 * Checks if a specific component ID exists.
	 *
	 * @param  string  $component_id
	 * @return boolean
	 */
	public function has_component( $component_id ) {

		$has_component = false;
		$component_ids = $this->get_component_ids();

		if ( in_array( $component_id, $component_ids ) ) {
			$has_component = true;
		}

		return $has_component;
	}

	/**
	 * Get all component ids.
	 *
	 * @return array
	 */
	public function get_component_ids() {
		return ! empty( $this->composite_meta ) ? array_keys( $this->composite_meta ) : array();
	}

	/**
	 * Gets all components.
	 *
	 * @return array
	 */
	public function get_components() {

		$components    = array();
		$component_ids = $this->get_component_ids();

		foreach ( $component_ids as $component_id ) {
			if ( $component = $this->get_component( $component_id ) ) {
				$components[ $component_id ] = $component;
			}
		}

		return $components;
	}

	/**
	 * Get component data array by component id.
	 *
	 * @param  string  $component_id
	 * @return array
	 */
	public function get_component_data( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_data() : false;
	}


	/*
	|--------------------------------------------------------------------------
	| Component methods: Options and properties.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get all component options (product IDs) available in a component.
	 *
	 * @param  string  $component_id
	 * @return array|null
	 */
	public function get_component_options( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_options() : null;
	}

	/**
	 * Get composited product.
	 *
	 * @param  string  $component_id
	 * @param  int     $product_id
	 * @return Product|null
	 */
	public function get_component_option( $component_id, $product_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_option( $product_id ) : null;
	}

	/**
	 * Grab component discount by component id.
	 *
	 * @param  string  $component_id
	 * @return string|null
	 */
	public function get_component_discount( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_discount() : null;
	}

	/**
	 * True if a component has only one option and is not optional.
	 *
	 * @param  string  $component_id
	 * @return boolean|null
	 */
	public function is_component_static( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->is_static() : null;
	}

	/**
	 * True if a component is optional.
	 *
	 * @param  string  $component_id
	 * @return boolean|null
	 */
	public function is_component_optional( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->is_optional() : null;
	}

	/**
	 * Get the default method to sort the options of a component.
	 *
	 * @param  int  $component_id
	 * @return string|null
	 */
	public function get_component_default_sorting_order( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_default_sorting_order() : null;
	}

	/**
	 * Get component sorting options, if enabled.
	 *
	 * @param  int  $component_id
	 * @return array|null
	 */
	public function get_component_sorting_options( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_sorting_options() : null;
	}

	/**
	 * Get component filtering options, if enabled.
	 *
	 * @param  int  $component_id
	 * @return array|null
	 */
	public function get_component_filtering_options( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_filtering_options() : null;
	}

	/*
	|--------------------------------------------------------------------------
	| Component methods: Templating.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Component options selection style.
	 *
	 * @param  string  $component_id
	 * @return string|null
	 */
	public function get_component_options_style( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_options_style() : null;
	}

	/**
	 * Thumbnail loop columns count.
	 *
	 * @param  string  $component_id
	 * @return int|null
	 */
	public function get_component_columns( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_columns() : null;
	}

	/**
	 * Thumbnail loop results per page.
	 *
	 * @param  string  $component_id
	 * @return int|null
	 */
	public function get_component_results_per_page( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_results_per_page() : null;
	}

	/**
	 * Controls whether component options loaded via ajax will be appended or paginated.
	 * When incompatible component options are set to be hidden, pagination cannot be used since results are filtered via js on the client side.
	 *
	 * @param  string  $component_id
	 * @return boolean
	 */
	public function paginate_component_options( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->paginate_options() : null;
	}

	/**
	 * Controls whether disabled component options will be hidden instead of greyed-out.
	 *
	 * @param  string  $component_id
	 * @return boolean|null
	 */
	public function hide_disabled_component_options( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->hide_disabled_options() : null;
	}

	/**
	 * Create an array of classes to use in the component layout templates.
	 *
	 * @param  string  $component_id
	 * @return array|null
	 */
	public function get_component_classes( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->get_classes() : null;
	}

	/*
	|--------------------------------------------------------------------------
	| Component methods: View state.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Get the current query object that was used to build the component options view of a component.
	 * Should be called after 'ComponentView::get_options()' has been used to set its view state.
	 *
	 * @param  int  $component_id
	 * @return Query|null|false
	 */
	public function get_current_component_options_query( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->view->get_options_query() : null;
	}

	/**
	 * Get component options to display. Fetched using a WP Query wrapper to allow advanced component options filtering / ordering / pagination.
	 *
	 * @param  string $component_id
	 * @param  array  $args
	 * @return array|null
	 */
	public function get_current_component_options( $component_id, $args = array() ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->view->get_options( $args ) : null;
	}

	/**
	 * Get the currently selected option (product id) for a component.
	 *
	 * @since  1.0.0
	 *
	 * @param  string $component_id
	 * @return int
	 */
	public function get_current_component_selection( $component_id ) {
		return $this->has_component( $component_id ) ? $this->get_component( $component_id )->view->get_selected_option() : null;
	}

	/*
	|--------------------------------------------------------------------------
	| Static methods.
	|--------------------------------------------------------------------------
	*/

	/**
	 * Supported types for use as Component Options.
	 *
	 * @return array
	 */
	public static function get_supported_component_option_types( $expanded = false ) {

		$supported_types = array( 'simple' );

		if ( $expanded ) {
			$supported_types = array_merge( $supported_types, array( 'variation' ) );
		}

		return apply_filters( 'woocommerce_composite_products_supported_types', $supported_types, $expanded );
	}

	/**
	 * Get "Catalog Price" options.
	 *
	 * @return array
	 */
	public static function get_shop_price_calc_options() {

		$shop_price_calc_options = array(
			'defaults' => array(
				'title'       => __( 'Use defaults', 'wpdrift-woocommerce-modules' ),
				'description' => __( 'Displays the price of the default configuration. Requires <strong>Default Option</strong> to be set in all non-optional Components.', 'wpdrift-woocommerce-modules' ),
			),
			'min_max'  => array(
				'title'       => __( 'Calculate from/to', 'wpdrift-woocommerce-modules' ),
				'description' => __( 'Builds a price string based on the configuration with the lowest/highest price.', 'wpdrift-woocommerce-modules' ),
			),
			'hidden'   => array(
				'title'       => __( 'Hide', 'wpdrift-woocommerce-modules' ),
				'description' => __( 'Hides the catalog price.', 'wpdrift-woocommerce-modules' ),
			),
		);

		return $shop_price_calc_options;
	}

	/**
	 * Get "Form Location" options.
	 *
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public static function get_add_to_cart_form_location_options() {

		$options = array(
			'default'       => array(
				'title'       => __( 'Default', 'wpdrift-woocommerce-modules' ),
				'description' => __( 'The add-to-cart form is displayed inside the single-product summary.', 'wpdrift-woocommerce-modules' ),
			),
			'after_summary' => array(
				'title'       => __( 'After summary', 'wpdrift-woocommerce-modules' ),
				'description' => __( 'The add-to-cart form is displayed after the single-product summary. Usually allocates the entire page width for displaying form content. Note that some themes may not support this option.', 'wpdrift-woocommerce-modules' ),
			),
		);

		return apply_filters( 'woocommerce_composite_add_to_cart_form_location_options', $options );
	}

	/**
	 * Get "Layout" options.
	 *
	 * @return array
	 */
	public static function get_layout_options() {

		$sanitized_custom_layouts = array();

		$base_layouts = array(
			'single'      => array(
				'title'       => __( 'Stacked', 'wpdrift-woocommerce-modules' ),
				'description' => __( 'Components are stacked.', 'wpdrift-woocommerce-modules' ),
				'image_src'   => Module::instance()->plugin_url() . '/assets/images/single.png',
			),
			'progressive' => array(
				'title'       => __( 'Stacked with featured image', 'wpdrift-woocommerce-modules' ),
				'description' => __( 'Components are stacked on the right with featured image on left.', 'wpdrift-woocommerce-modules' ),
				'image_src'   => Module::instance()->plugin_url() . '/assets/images/progressive.png',
			),
		);

		$custom_layouts = array();

		/**
		 * Filter layout variations array to add custom layout variations.
		 *
		 * @param  array  $custom_layouts
		 */
		$custom_layouts = apply_filters( 'woocommerce_composite_product_layout_variations', $custom_layouts );

		foreach ( $custom_layouts as $layout_id => $layout_data ) {

			$sanitized_layout_id = esc_attr( sanitize_title( $layout_id ) );

			if ( array_key_exists( $sanitized_layout_id, $base_layouts ) ) {
				continue;
			}

			$sanitized_layout_id_parts = explode( '-', $sanitized_layout_id, 2 );

			if ( ! empty( $sanitized_layout_id_parts[0] ) && array_key_exists( $sanitized_layout_id_parts[0], $base_layouts ) ) {
				$sanitized_custom_layouts[ $sanitized_layout_id ] = $layout_data;
			}
		}

		return array_merge( $base_layouts, $sanitized_custom_layouts );
	}

	/**
	 * Get composite layout descriptions.
	 *
	 * @param  string  $layout_id
	 * @return string
	 */
	public static function get_layout_description( $layout_id ) {

		$layout_descriptions = wp_list_pluck( self::get_layout_options(), 'description' );

		return isset( $layout_descriptions[ $layout_id ] ) ? $layout_descriptions[ $layout_id ] : '';
	}

	/**
	 * Get selected layout option.
	 *
	 * @param  string  $layout
	 * @return string
	 */
	public static function get_layout_option( $layout ) {

		if ( ! $layout ) {
			return 'single';
		}

		$layouts         = self::get_layout_options();
		$layout_id_parts = explode( '-', $layout, 2 );

		if ( array_key_exists( $layout, $layouts ) ) {
			return $layout;
		} elseif ( array_key_exists( $layout_id_parts[0], $layouts ) ) {
			return $layout_id_parts[0];
		}

		return 'single';
	}
}
