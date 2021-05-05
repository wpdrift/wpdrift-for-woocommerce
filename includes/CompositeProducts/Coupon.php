<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Coupon class.
 */
class Coupon {

	/*
	 * Initilize.
	 */
	public static function init() {

		// Coupons - inherit children coupon validity from parent.
		add_filter( 'woocommerce_coupon_is_valid_for_product', array( __CLASS__, 'coupon_validity' ), 10, 4 );
	}

	/**
	 * Inherit coupon validity from parent:
	 *
	 * - Coupon is invalid for child item if parent is excluded.
	 * - Coupon is valid for child item if valid for parent, unless child item is excluded.
	 *
	 * @param  bool        $valid
	 * @param  WC_Product  $product
	 * @param  WC_Coupon   $coupon
	 * @param  array       $item
	 * @return bool
	 */
	public static function coupon_validity( $valid, $product, $coupon, $item ) {

		if ( is_a( $item, 'WC_Order_Item_Product' ) ) {

			if ( $container_cart_item = wc_cp_get_composited_order_item_container( $item ) ) {

				$composite    = $container_cart_item->get_product();
				$composite_id = $container_cart_item[ 'product_id' ];
			}

		} elseif ( ! empty( WC()->cart ) ) {

			if ( $container_cart_item = wc_cp_get_composited_cart_item_container( $item ) ) {

				$composite    = $container_cart_item[ 'data' ];
				$composite_id = $composite->get_id();
			}
		}

		if ( ! isset( $composite, $composite_id ) || empty( $container_cart_item ) ) {
			return $valid;
		}

		/**
		 * Filter to disable coupon validity inheritance from container.
		 *
		 * @param  boolean     $inherit
		 * @param  WC_Product  $product
		 * @param  WC_Coupon   $coupon
		 * @param  array       $component_cart_item_data
		 * @param  array       $container_cart_item_data
		 */
		if ( apply_filters( 'woocommerce_composite_inherit_coupon_validity', true, $product, $coupon, $item, $container_cart_item ) ) {

			$product_id = $product->get_id();
			$parent_id  = $product->get_parent_id();

			$excluded_product_ids        = $coupon->get_excluded_product_ids();
			$excluded_product_categories = $coupon->get_excluded_product_categories();
			$excludes_sale_items         = $coupon->get_exclude_sale_items();

			if ( $valid ) {

				$parent_excluded = false;

				// Parent ID excluded from the discount.
				if ( sizeof( $excluded_product_ids ) > 0 ) {
					if ( in_array( $composite_id, $excluded_product_ids ) ) {
						$parent_excluded = true;
					}
				}

				// Parent category excluded from the discount.
				if ( sizeof( $excluded_product_categories ) > 0 ) {

					$product_cats = wc_get_product_cat_ids( $composite_id );

					if ( sizeof( array_intersect( $product_cats, $excluded_product_categories ) ) > 0 ) {
						$parent_excluded = true;
					}
				}

				// Sale Items excluded from discount and parent on sale.
				if ( $excludes_sale_items ) {

					$product_ids_on_sale = wc_get_product_ids_on_sale();

					if ( in_array( $composite_id, $product_ids_on_sale, true ) ) {
						$parent_excluded = true;
					}
				}

				if ( $parent_excluded ) {
					$valid = false;
				}

			} else {

				$composited_product_excluded = false;

				// Composited product ID excluded from the discount.
				if ( sizeof( $excluded_product_ids ) > 0 ) {
					if ( in_array( $product_id, $excluded_product_ids ) || ( $parent_id && in_array( $parent_id, $excluded_product_ids ) ) ) {
						$composited_product_excluded = true;
					}
				}

				// Composited product category excluded from the discount.
				if ( sizeof( $excluded_product_categories ) > 0 ) {

					$product_cats = $parent_id ? wc_get_product_cat_ids( $parent_id ) : wc_get_product_cat_ids( $product_id );

					if ( sizeof( array_intersect( $product_cats, $excluded_product_categories ) ) > 0 ) {
						$composited_product_excluded = true;
					}
				}

				// Composited product on sale and sale items excluded from discount.
				if ( $excludes_sale_items ) {

					$product_ids_on_sale = wc_get_product_ids_on_sale();

					if ( in_array( $product_id, $product_ids_on_sale ) || ( $parent_id && in_array( $parent_id, $product_ids_on_sale ) ) ) {
						$composited_product_excluded = true;
					}
				}

				if ( ! $composited_product_excluded && $coupon->is_valid_for_product( $composite, $container_cart_item ) ) {
					$valid = true;
				}
			}
		}


		return $valid;
	}
}
