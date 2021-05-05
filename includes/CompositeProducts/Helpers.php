<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Helpers class.
 */
class Helpers {

	/**
	 * General-purpose runtime key/value cache.
	 *
	 * @var array
	 */
	private static $cache = array();

	/**
	 * Simple runtime cache getter.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public static function cache_get( $key ) {
		$value = null;
		if ( isset( self::$cache[ $key ] ) ) {
			$value = self::$cache[ $key ];
		}
		return $value;
	}

	/**
	 * Simple runtime cache setter.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public static function cache_set( $key, $value ) {
		self::$cache[ $key ] = $value;
	}

	/**
	 * Simple runtime cache unsetter.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public static function cache_delete( $key ) {
		if ( isset( self::$cache[ $key ] ) ) {
			unset( self::$cache[ $key ] );
		}
	}

	/**
	 * True when processing a FE request.
	 *
	 * @return boolean
	 */
	public static function is_front_end() {
		$is_fe = ( ! is_admin() ) || ( defined( 'DOING_AJAX' ) && DOING_AJAX );
		return $is_fe;
	}

	/**
	 * Filters the 'woocommerce_price_num_decimals' option to use the internal WC rounding precision.
	 */
	public static function extend_price_display_precision() {
		add_filter( 'option_woocommerce_price_num_decimals', array( 'WPdrift\CompositeProducts\Compatibility\Core\Compatibility', 'wc_get_rounding_precision' ) );
	}

	/**
	 * Reset applied filters to the 'woocommerce_price_num_decimals' option.
	 */
	public static function reset_price_display_precision() {
		remove_filter( 'option_woocommerce_price_num_decimals', array( 'WPdrift\CompositeProducts\Compatibility\Core\Compatibility', 'wc_get_rounding_precision' ) );
	}

	/**
	 * Loads variation IDs for a given variable product.
	 *
	 * @param  WC_Product_Variable|int  $product
	 * @return array
	 */
	public static function get_product_variations( $product ) {

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( ! $product || ! $product->is_type( 'variable' ) ) {
			return false;
		}

		return $product->get_children();
	}

	/**
	 * Loads variation descriptions and ids for a given variable product.
	 *
	 * @param  WC_Product_Variable|int  $product
	 * @param  string                   $format
	 * @return array
	 */
	public static function get_product_variation_descriptions( $product, $format = 'flat' ) {

		$variation_descriptions = array();
		$variations             = self::get_product_variations( $product );

		if ( empty( $variations ) ) {
			return $variation_descriptions;
		}

		foreach ( $variations as $variation_id ) {

			$variation_description = self::get_product_variation_title( $variation_id, $format );

			if ( ! $variation_description ) {
				continue;
			}

			$variation_descriptions[ $variation_id ] = $variation_description;
		}

		return $variation_descriptions;
	}

	/**
	 * Return a formatted variation title.
	 *
	 * @param  WC_Product_Variation|int  $variation
	 * @param  string                    $format
	 *
	 * @return string
	 */
	public static function get_product_variation_title( $variation, $format = 'flat' ) {

		if ( ! is_object( $variation ) ) {
			$variation = wc_get_product( $variation );
		}

		if ( ! $variation ) {
			return false;
		}

		if ( 'core' === $format || true === $format ) {

			$title = $variation->get_formatted_name();

		} else {

			$description = wc_get_formatted_variation( $variation, true );

			$title = $variation->get_title();
			$sku   = $variation->get_sku();
			$id    = $variation->get_id();

			if ( $sku ) {
				$identifier = $sku;
			} else {
				$identifier = '#' . $id;
			}

			$title = self::format_product_title( $title, $identifier, $description );
		}

		return $title;
	}

	/**
	 * Return a formatted product title.
	 *
	 * @param  WC_Product|int  $product
	 * @param  string          $title
	 * @param  string          $meta
	 * @return string
	 */
	public static function get_product_title( $product, $title = '', $meta = '' ) {

		if ( ! is_object( $product ) ) {
			$product = wc_get_product( $product );
		}

		if ( ! $product ) {
			return false;
		}

		$title = $title ? $title : $product->get_title();
		$sku   = $product->get_sku();
		$id    = $product->get_id();

		if ( $sku ) {
			$identifier = $sku;
		} else {
			$identifier = '#' . $id;
		}

		return self::format_product_title( $title, $identifier, $meta );
	}

	/**
	 * Format a product title.
	 *
	 * @param  string  $title
	 * @param  string  $identifier
	 * @param  string  $meta
	 * @param  string  $paren
	 * @return string
	 */
	public static function format_product_title( $title, $identifier = '', $meta = '', $paren = true ) {

		if ( $identifier && $meta ) {
			if ( $paren ) {
				$title = sprintf( _x( '%1$s (%2$s) &ndash; %3$s', 'product title followed by sku in parenthesis and meta', 'wpdrift-woocommerce-modules' ), $title, $identifier, $meta );
			} else {
				$title = sprintf( _x( '%1$s &ndash; %2$s &ndash; %3$s', 'product title followed by sku and meta', 'wpdrift-woocommerce-modules' ), $title, $identifier, $meta );
			}
		} elseif ( $identifier ) {
			if ( $paren ) {
				$title = sprintf( _x( '%1$s (%2$s)', 'product title followed by sku in parenthesis', 'wpdrift-woocommerce-modules' ), $title, $identifier );
			} else {
				$title = sprintf( _x( '%1$s &ndash; %2$s', 'product title followed by sku', 'wpdrift-woocommerce-modules' ), $title, $identifier );
			}
		} elseif ( $meta ) {
			if ( $paren ) {
				$title = sprintf( _x( '%1$s (%2$s)', 'product title followed by meta in parenthesis', 'wpdrift-woocommerce-modules' ), $title, $meta );
			} else {
				$title = sprintf( _x( '%1$s &ndash; %2$s', 'product title followed by meta', 'wpdrift-woocommerce-modules' ), $title, $meta );
			}
		}

		return $title;
	}

	/**
	 * Format prices without html content.
	 *
	 * @param  mixed  $price
	 * @param  array  $args
	 * @return string
	 */
	public static function format_raw_price( $price, $args = array() ) {

		$return          = '';
		$num_decimals    = wc_cp_price_num_decimals();
		$currency        = isset( $args['currency'] ) ? $args['currency'] : '';
		$currency_symbol = get_woocommerce_currency_symbol( $currency );
		$decimal_sep     = wc_cp_price_decimal_sep();
		$thousands_sep   = wc_cp_price_thousand_sep();

		$price = apply_filters( 'raw_woocommerce_price', floatval( $price ) );
		$price = apply_filters( 'formatted_woocommerce_price', number_format( $price, $num_decimals, $decimal_sep, $thousands_sep ), $price, $num_decimals, $decimal_sep, $thousands_sep );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $num_decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$return = sprintf( get_woocommerce_price_format(), $currency_symbol, $price );

		return $return;
	}

	/**
	 * Version of 'in_array' operating on the values of an input array.
	 *
	 * @since  3.9.0
	 *
	 * @param  array   $array
	 * @param  mixed   $key
	 * @param  mixed   $value
	 * @return boolean
	 */
	public static function in_array_key( $array, $key, $value ) {
		if ( ! empty( $array ) && is_array( $array ) && ! empty( $array[ $key ] ) && is_array( $array[ $key ] ) && in_array( $value, $array[ $key ] ) ) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Recursive version of 'urlencode' for multidimensional assosciative arrays.
	 *
	 * @since  3.14.0
	 *
	 * @param  function  $array
	 * @param  array     $escaped_array
	 * @return array
	 */
	public static function urlencode_recursive( $array ) {

		$escaped_array = array();

		foreach ( $array as $key => $value ) {

			if ( is_array( $value ) ) {
				$data = self::urlencode_recursive( $value );
			} else {
				$data = urlencode( $value );
			}

			$escaped_array[ urlencode( $key ) ] = $data;
		}

		return $escaped_array;
	}
}
