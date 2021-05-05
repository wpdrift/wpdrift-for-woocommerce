<?php
namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*---------------*/
/*  Cart         */
/*---------------*/

/**
 * Given a composited cart item, find and return its container cart item - the Composite - or its cart id when the $return_id arg is true.
 *
 * @param  array    $maybe_composited_cart_item
 * @param  array    $cart_contents
 * @param  boolean  $return_id
 * @return mixed
 */
function wc_cp_get_composited_cart_item_container( $maybe_composited_cart_item, $cart_contents = false, $return_id = false ) {

	if ( ! $cart_contents ) {
		$cart_contents = WC()->cart->cart_contents;
	}

	$container = false;

	if ( wc_cp_maybe_is_composited_cart_item( $maybe_composited_cart_item ) ) {

		$composited_by = $maybe_composited_cart_item[ 'composite_parent' ];

		if ( isset( $cart_contents[ $composited_by ] ) ) {
			$container = $return_id ? $composited_by : $cart_contents[ $composited_by ];
		}
	}

	return $container;
}

/**
 * Given a composite container cart item, find and return its child cart items - or their cart ids when the $return_ids arg is true.
 * Includes a deep mode argument to allow filtering the result of the internal cart item comparison.
 *
 * @param  array    $maybe_composite_container_cart_item
 * @param  array    $cart_contents
 * @param  boolean  $return_ids
 * @param  boolean  $deep_mode
 * @return mixed
 */
function wc_cp_get_composited_cart_items( $maybe_composite_container_cart_item, $cart_contents = false, $return_ids = false, $deep_mode = false ) {

	if ( ! $cart_contents ) {
		$cart_contents = WC()->cart->cart_contents;
	}

	$composited_cart_items = array();

	if ( wc_cp_is_composite_container_cart_item( $maybe_composite_container_cart_item ) ) {

		$composited_items = $maybe_composite_container_cart_item[ 'composite_children' ];

		if ( ! empty( $cart_contents ) && ! empty( $composited_items ) && is_array( $composited_items ) ) {

			if ( $deep_mode ) {

					// First, find the container cart item key.
					$maybe_composite_container_cart_item_key = '';

					foreach ( $cart_contents as $search_item_key => $search_item ) {
						if ( wc_cp_is_composite_container_cart_item( $search_item ) && $search_item[ 'composite_data' ] === $maybe_composite_container_cart_item[ 'composite_data' ] ) {
							$maybe_composite_container_cart_item_key = $search_item_key;
							break;
						}
					}

					// Then, search all cart items and pass the result through the 'woocommerce_cart_item_is_child_of_composite' filter.
					if ( $maybe_composite_container_cart_item_key ) {
						foreach ( $cart_contents as $search_item_key => $search_item ) {
							/**
							 * Filter to allow sub-grouped cart items to be recognized as composite children.
							 *
							 * @param   boolean  $is_child
							 * @param   string   $checked_cart_item_key
							 * @param   array    $checked_cart_item_data
							 * @param   string   $cart_item_key
							 * @param   array    $cart_item_data
							 */
							if ( apply_filters( 'woocommerce_cart_item_is_child_of_composite', in_array( $search_item_key, $maybe_composite_container_cart_item[ 'composite_children' ] ), $search_item_key, $search_item, $maybe_composite_container_cart_item_key, $maybe_composite_container_cart_item ) ) {
								$composited_cart_items[ $search_item_key ] = $search_item;
							}
						}
					}

			} else {

				foreach ( $composited_items as $composited_cart_item_key ) {
					if ( isset( $cart_contents[ $composited_cart_item_key ] ) ) {
						$composited_cart_items[ $composited_cart_item_key ] = $cart_contents[ $composited_cart_item_key ];
					}
				}
			}
		}
	}

	return $return_ids ? array_keys( $composited_cart_items ) : $composited_cart_items;
}

/**
 * True if a cart item is part of a composite.
 * Instead of relying solely on cart item data, the function also checks that the alleged parent item actually exists.
 *
 * @param  array  $maybe_composited_cart_item
 * @param  array  $cart_contents
 * @return boolean
 */
function wc_cp_is_composited_cart_item( $maybe_composited_cart_item, $cart_contents = false ) {

	$is_composited = false;

	if ( wc_cp_get_composited_cart_item_container( $maybe_composited_cart_item, $cart_contents ) ) {
		$is_composited = true;
	}

	return $is_composited;
}

/**
 * True if a cart item appears to be part of a composite.
 * The result is purely based on cart item data - the function does not check that a valid parent item actually exists.
 *
 * @param  array  $maybe_composited_cart_item
 * @return boolean
 */
function wc_cp_maybe_is_composited_cart_item( $maybe_composited_cart_item ) {

	$is_composited = false;

	if ( ! empty( $maybe_composited_cart_item[ 'composite_parent' ] ) && ! empty( $maybe_composited_cart_item[ 'composite_item' ] ) && ! empty( $maybe_composited_cart_item[ 'composite_data' ] ) ) {
		$is_composited = true;
	}

	return $is_composited;
}

/**
 * True if a cart item appears to be a composite container item.
 *
 * @param  array  $cart_item
 * @return boolean
 */
function wc_cp_is_composite_container_cart_item( $maybe_composite_container_cart_item ) {

	$is_composite = false;

	if ( isset( $maybe_composite_container_cart_item[ 'composite_children' ] ) && ! empty( $maybe_composite_container_cart_item[ 'composite_data' ] ) ) {
		$is_composite = true;
	}

	return $is_composite;
}

/*---------------*/
/*  Orders       */
/*---------------*/

/**
 * Given a composited order item, find and return its container order item - the Composite - or its order item id when the $return_id arg is true.
 *
 * @param  array     $maybe_composited_order_item
 * @param  WC_Order  $order
 * @param  boolean   $return_id
 * @return mixed
 */
function wc_cp_get_composited_order_item_container( $maybe_composited_order_item, $order = false, $return_id = false ) {

	$result = false;

	if ( wc_cp_maybe_is_composited_order_item( $maybe_composited_order_item ) ) {

		$container = Helpers::cache_get( 'order_item_container_' . $maybe_composited_order_item->get_id() );

		if ( null === $container ) {

			if ( false === $order ) {
				if ( is_callable( array( $maybe_composited_order_item, 'get_order' ) ) ) {

					$order_id = $maybe_composited_order_item->get_order_id();
					$order    = Helpers::cache_get( 'order_' . $order_id );

					if ( null === $order ) {
						$order = $maybe_composited_order_item->get_order();
						Helpers::cache_set( 'order_' . $order_id, $order );
					}

				} else {
					$msg = 'get_order() is not callable on the supplied $order_item. No $order object given.';
					_doing_it_wrong( __FUNCTION__ . '()', $msg, '3.10.0' );
				}
			}

			$order_items = is_object( $order ) ? $order->get_items( 'line_item' ) : $order;

			if ( ! empty( $order_items ) ) {
				foreach ( $order_items as $order_item_id => $order_item ) {

					$is_container = false;

					if ( isset( $order_item[ 'composite_cart_key' ] ) ) {
						$is_container = $maybe_composited_order_item[ 'composite_parent' ] === $order_item[ 'composite_cart_key' ];
					} else {
						$is_container = isset( $order_item[ 'composite_data' ] ) && $order_item[ 'composite_data' ] === $maybe_composited_order_item[ 'composite_data' ] && ! isset( $order_item[ 'composite_parent' ] );
					}

					if ( $is_container ) {
						Helpers::cache_set( 'order_item_container_' . $maybe_composited_order_item->get_id(), $order_item );
						$container = $order_item;
						break;
					}
				}
			}
		}

		if ( $container && is_callable( array( $container, 'get_id' ) ) ) {
			$result = $return_id ? $container->get_id() : $container;
		}
	}

	return $result;
}

/**
 * Given a composite container order item, find and return its child order items - or their order item ids when the $return_ids arg is true.
 * Includes a deep mode argument to allow filtering the result of the internal order item comparison.
 *
 * @param  array     $item
 * @param  WC_Order  $order
 * @param  boolean   $return_ids
 * @param  boolean   $deep_mode
 * @return mixed
 */
function wc_cp_get_composited_order_items( $maybe_composite_container_order_item, $order = false, $return_ids = false, $deep_mode = false ) {

	$composited_order_items = array();

	if ( wc_cp_is_composite_container_order_item( $maybe_composite_container_order_item ) ) {

		$composited_cart_keys = maybe_unserialize( $maybe_composite_container_order_item[ 'composite_children' ] );

		if ( ! empty( $composited_cart_keys ) && is_array( $composited_cart_keys ) ) {

			if ( false === $order ) {
				if ( is_callable( array( $maybe_composite_container_order_item, 'get_order' ) ) ) {

					$order_id = $maybe_composite_container_order_item->get_order_id();
					$order    = Helpers::cache_get( 'order_' . $order_id );

					if ( null === $order ) {
						$order = $maybe_composite_container_order_item->get_order();
						Helpers::cache_set( 'order_' . $order_id, $order );
					}

				} else {
					$msg = 'get_order() is not callable on the supplied $order_item. No $order object given.';
					_doing_it_wrong( __FUNCTION__ . '()', $msg, '3.10.0' );
				}
			}

			$order_items = is_object( $order ) ? $order->get_items( 'line_item' ) : $order;

			foreach ( $order_items as $order_item_id => $order_item ) {

				$is_child = false;

				if ( isset( $order_item[ 'composite_cart_key' ] ) ) {
					$is_child = in_array( $order_item[ 'composite_cart_key' ], $composited_cart_keys ) ? true : false;
				} else {
					$is_child = isset( $order_item[ 'composite_data' ] ) && $order_item[ 'composite_data' ] == $maybe_composite_container_order_item[ 'composite_data' ] && isset( $order_item[ 'composite_parent' ] ) ? true : false;
				}

				if ( $deep_mode ) {
					/**
					 * Filter to allow sub-grouped order items to be recognized as composite container order item children.
					 *
					 * @param   boolean   $is_child
					 * @param   array     $checked_order_item
					 * @param   string    $container_order_item
					 * @param   WC_Order  $order
					 */
					$is_child = apply_filters( 'woocommerce_order_item_is_child_of_composite', $is_child, $order_item, $maybe_composite_container_order_item, $order );
				}

				if ( $is_child ) {
					$composited_order_items[ $order_item_id ] = $order_item;
				}
			}
		}
	}

	return $return_ids ? array_keys( $composited_order_items ) : $composited_order_items;
}

/**
 * True if an order item is part of a composite.
 * Instead of relying solely on the existence of item meta, the function also checks that the alleged parent item actually exists.
 *
 * @param  array     $maybe_composited_order_item
 * @param  WC_Order  $order
 * @return boolean
 */
function wc_cp_is_composited_order_item( $maybe_composited_order_item, $order = false ) {

	$is_composited = false;

	if ( wc_cp_get_composited_order_item_container( $maybe_composited_order_item, $order ) ) {
		$is_composited = true;
	}

	return $is_composited;
}

/**
 * True if an order item appears to be part of a composite.
 * The result is purely based on item meta - the function does not check that a valid parent item actually exists.
 *
 * @param  array  $maybe_composited_order_item
 * @return boolean
 */
function wc_cp_maybe_is_composited_order_item( $maybe_composited_order_item ) {

	$is_composited = false;

	if ( ! empty( $maybe_composited_order_item[ 'composite_parent' ] ) ) {
		$is_composited = true;
	}

	return $is_composited;
}

/**
 * True if an order item appears to be a composite container item.
 *
 * @param  array  $maybe_composited_container_order_item
 * @return boolean
 */
function wc_cp_is_composite_container_order_item( $maybe_composited_container_order_item ) {

	$is_composite = false;

	if ( isset( $maybe_composited_container_order_item[ 'composite_children' ] ) ) {
		$is_composite = true;
	}

	return $is_composite;
}

/*--------------------------*/
/*  Conditional functions.  */
/*--------------------------*/

/**
 * True if the current product page is a composite product.
 *
 * @return boolean
 */
function is_composite_product() {

	global $product;

	return function_exists( 'is_product' ) && is_product() && ! empty( $product ) && is_callable( array( $product, 'is_type' ) ) && $product->is_type( 'composite' );
}

/*----------------------------*/
/*  Helper functions.         */
/*----------------------------*/

/**
 * get_option( 'woocommerce_calc_taxes' ) cache.
 *
 * @return string
 */
function wc_cp_calc_taxes() {
	$wc_calc_taxes = Helpers::cache_get( 'wc_calc_taxes' );
	if ( null === $wc_calc_taxes ) {
		$wc_calc_taxes = get_option( 'woocommerce_calc_taxes' );
		Helpers::cache_set( 'wc_calc_taxes', $wc_calc_taxes );
	}
	return $wc_calc_taxes;
}

/**
 * get_option( 'woocommerce_prices_include_tax' ) cache.
 *
 * @return string
 */
function wc_cp_prices_include_tax() {
	$wc_prices_include_tax = Helpers::cache_get( 'wc_prices_include_tax' );
	if ( null === $wc_prices_include_tax ) {
		$wc_prices_include_tax = get_option( 'woocommerce_prices_include_tax' );
		Helpers::cache_set( 'wc_prices_include_tax', $wc_prices_include_tax );
	}
	return $wc_prices_include_tax;
}

/**
 * get_option( 'woocommerce_tax_display_shop' ) cache.
 *
 * @return string
 */
function wc_cp_tax_display_shop() {
	$wc_tax_display_shop = Helpers::cache_get( 'wc_tax_display_shop' );
	if ( null === $wc_tax_display_shop ) {
		$wc_tax_display_shop = get_option( 'woocommerce_tax_display_shop' );
		Helpers::cache_set( 'wc_tax_display_shop', $wc_tax_display_shop );
	}
	return $wc_tax_display_shop;
}

/**
 * get_option( 'woocommerce_price_decimal_sep' ) cache.
 *
 * @return string
 */
function wc_cp_price_decimal_sep() {
	$wc_price_decimal_sep = Helpers::cache_get( 'wc_price_decimal_sep' );
	if ( null === $wc_price_decimal_sep ) {
		$wc_price_decimal_sep = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ), ENT_QUOTES );
		Helpers::cache_set( 'wc_price_decimal_sep', apply_filters( 'wc_get_price_decimal_separator', $wc_price_decimal_sep ) );
	}
	return $wc_price_decimal_sep;
}

/**
 * get_option( 'woocommerce_price_thousand_sep' ) cache.
 *
 * @return string
 */
function wc_cp_price_thousand_sep() {
	$wc_price_thousand_sep = Helpers::cache_get( 'wc_price_thousand_sep' );
	if ( null === $wc_price_thousand_sep ) {
		$wc_price_thousand_sep = wp_specialchars_decode( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ), ENT_QUOTES );
		Helpers::cache_set( 'wc_price_thousand_sep', apply_filters( 'wc_get_price_thousand_separator', $wc_price_thousand_sep ) );
	}
	return $wc_price_thousand_sep;
}

/**
 * get_option( 'woocommerce_price_num_decimals' ) cache.
 *
 * @return string
 */
function wc_cp_price_num_decimals() {
	$wc_price_num_decimals = Helpers::cache_get( 'wc_price_num_decimals' );
	if ( null === $wc_price_num_decimals ) {
		$wc_price_num_decimals = absint( get_option( 'woocommerce_price_num_decimals', 2 ) );
		Helpers::cache_set( 'wc_price_num_decimals', apply_filters( 'wc_get_price_decimals', $wc_price_num_decimals ) );
	}
	return $wc_price_num_decimals;
}
