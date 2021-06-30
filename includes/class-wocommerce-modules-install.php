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
	 * The single instance of the class.
	 * @var [type]
	 */
	protected static $_instance = null;

	/**
	 * Main Wocommerce_Modules_Install instance.
	 * Ensures only one instance of Wocommerce_Modules_Install is loaded or can be loaded.
	 *
	 * @return [type] [description]
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Floating_Video_Shortcodes constructor.
	 */
	public function __construct() {
		$this->hooks();
	}

	/**
	 * Init hooks.
	 * @return [type] [description]
	 */
	private function hooks() {
		// Show row meta on the plugin screen.
		add_filter( 'plugin_row_meta', [ $this, 'plugin_row_meta' ], 10, 2 );
	}

	/**
	 * Show row meta on the plugin screen.
	 *
	 * @param   mixed  $links
	 * @param   mixed  $file
	 * @return  array
	 */
	public function plugin_row_meta( $links, $file ) {
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
