<?php

namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CompositePopup class.
 */
class CompositePopup {
	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public static function init() {
		add_action( 'wp_footer', array( __CLASS__, 'output_root_dom_node' ) );
	}

	/**
	 * [output_root_dom_node description]
	 * @return [type] [description]
	 */
	public static function output_root_dom_node() {
		if ( ! self::enable_popup() ) {
			return;
		}

		echo '<div id="composite-product-popup"></div>';
	}

	/**
	 * Ennable popup conditionally.
	 * @return [type] [description]
	 */
	public static function enable_popup() {
		global $post;

		if ( is_product() || is_shop() || is_product_category() || is_product_tag() ) {
			return true;
		}

		if ( is_a( $post, 'WP_Post' ) ) {
			foreach ( [ 'products', 'featured_products', 'sale_products', 'best_selling_products', 'recent_products', 'top_rated_products' ] as $shortcode_tag ) {
				if ( has_shortcode( $post->post_content, $shortcode_tag ) ) {
					return true;
				}
			}
		}

		return false;
	}
}
