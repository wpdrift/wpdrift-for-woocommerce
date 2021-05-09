<?php
namespace WPdrift;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Install class.
 */
class Wocommerce_Modules_Install {
	/**
	 * Init hooks.
	 * @return [type] [description]
	 */
	public static function init() {
		// Show row meta on the plugin screen.
		add_filter( 'plugin_row_meta', array( __CLASS__, 'plugin_row_meta' ), 10, 2 );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param   mixed  $links
	 * @param   mixed  $file
	 * @return  array
	 */
	public static function plugin_row_meta( $links, $file ) {

		if ( woocommerce_modules()->plugin_basename() === $file ) {
			$row_meta = array(
				'docs'    => '<a href="https://wpdrift.com/wpdrift-for-woocommerce/">' . __( 'Documentation', 'wpdrift-woocommerce-modules' ) . '</a>',
				'support' => '<a href="' . esc_url( WPDRIFT_WOOCOMMERCE_MODULES_SUPPORT_URL ) . '">' . __( 'Support', 'wpdrift-woocommerce-modules' ) . '</a>',
			);

			return array_merge( $links, $row_meta );
		}

		return $links;
	}
}
