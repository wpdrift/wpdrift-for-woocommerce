<?php
/**
 * Plugin Name:          WPdrift for WooCommerce
 * Plugin URI:           https://wpdrift.com/wpdrift-for-woocommerce/
 * Description:          A collection of modules for your WooCommerce website.
 * Author:               WPdrift
 * Author URI:           https://wpdrift.com/
 * Version:              1.0.0
 * Requires at least:    5.2
 * Tested up to:         5.7.1
 * WC requires at least: 3.6
 * WC tested up to:      5.2.2
 * Text Domain:          wpdrift-woocommerce-modules
 * Domain Path:          /languages
 *
 * @package              WPdrift\Wocommerce_Modules
 */

namespace WPdrift;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define constants.
define( 'WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_FILE', __FILE__ );
define( 'WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_DIR', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

// Require the main Wocommerce_Modules class.
require_once dirname( __FILE__ ) . '/includes/class-wocommerce-modules.php';

// Main instance of WPdrift\Wocommerce_Modules.
function woocommerce_modules() {
	return Wocommerce_Modules::instance();
}

// Start the plugin.
woocommerce_modules()->init();
