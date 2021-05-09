<?php
/**
 * Wocommerce_Modules Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package Wocommerce_Modules\Functions
 * @version 1.0.0
 */

namespace WPdrift;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Checks if the provided content or the current single page or post has a Woocommerce shortcode.
 *
 * @param string|null       $content   Content to check. If not provided, it uses the current post content.
 * @param string|array|null $tag Check specifically for one or more shortcodes. If not provided, checks for any Woocommerce shortcode.
 *
 * @return bool
 */
function has_woocommerce_shortcode( $content = null, $tag = null ) {
	global $post;

	$has_woocommerce_shortcode = false;

	if ( null === $content && is_singular() && is_a( $post, 'WP_Post' ) ) {
		$content = $post->post_content;
	}

	if ( ! empty( $content ) ) {
		$woocommerce_shortcodes = [ 'products', 'featured_products', 'sale_products', 'best_selling_products', 'recent_products', 'top_rated_products' ];
		/**
		 * Filters a list of all shortcodes associated with WooCommerce.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $woocommerce_shortcodes
		 */
		$woocommerce_shortcodes = array_unique( apply_filters( 'woocommerce_shortcodes', $woocommerce_shortcodes ) );

		if ( null !== $tag ) {
			if ( ! is_array( $tag ) ) {
				$tag = [ $tag ];
			}
			$woocommerce_shortcodes = array_intersect( $woocommerce_shortcodes, $tag );
		}

		foreach ( $woocommerce_shortcodes as $shortcode ) {
			if ( has_shortcode( $content, $shortcode ) ) {
				$has_woocommerce_shortcode = true;
				break;
			}
		}
	}

	/**
	 * Filter the result of has_woocommerce_shortcode()
	 *
	 * @since 1.0.0
	 *
	 * @param bool $has_woocommerce_shortcode
	 */
	return apply_filters( 'has_woocommerce_shortcode', $has_woocommerce_shortcode );
}

/**
 * Is_woocommerce_page - Returns true if on a page which uses WooCommerce templates and shortcodes.
 *
 * @return bool
 */
function is_woocommerce_page() {
	return apply_filters( 'is_woocommerce_page', is_woocommerce() || has_woocommerce_shortcode() );
}
