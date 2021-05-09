<?php
/**
 * File containing the class Wocommerce_Modules.
 *
 * @package WPdrift\Wocommerce_Modules
 */

namespace WPdrift;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Handles core plugin hooks and action setup.
 * @var [type]
 */
final class Wocommerce_Modules {

	/**
	 * The single instance of the class.
	 *
	 * @var Wocommerce_Modules
	 * @since 1.0.0
	 */
	protected static $_instance = null;

	/**
	 * Main Wocommerce_Modules instance.
	 *
	 * Ensures only one instance of Wocommerce_Modules is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see woocommerce_modules()
	 * @return Wocommerce_Modules - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Autoload classes.
		require WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_DIR . '/vendor/autoload.php';
		require WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_DIR . '/includes/wocommerce-modules-core-functions.php';
	}

	/**
	 * Initialize.
	 * @return [type] [description]
	 */
	public function init() {
		// Load translations hook.
		add_action( 'init', array( $this, 'load_translation' ) );
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );
		add_filter( 'woocommerce_locate_template', array( $this, 'locate_template' ), 10, 3 );
		add_filter( 'wc_get_template_part', array( $this, 'get_template_part' ), 10, 3 );
	}

	/**
	 * Plugin base path name getter.
	 *
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function plugin_basename() {
		return plugin_basename( WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_FILE );
	}

	/**
	 * Load textdomain.
	 */
	public function load_translation() {
		load_plugin_textdomain( 'wpdrift-woocommerce-modules', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Setup plugin once all other plugins are loaded.
	 *
	 * @return void
	 */
	public function on_plugins_loaded() {
		$this->load_plugin_textdomain();

		if ( ! $this->check_dependencies() ) {
			add_action( 'admin_init', array( $this, 'deactivate_self' ) );
			add_action( 'admin_notices', array( $this, 'render_dependencies_notice' ) );
			return;
		}

		if ( ! $this->check_build() ) {
			add_action( 'admin_notices', array( $this, 'render_build_notice' ) );
			return;
		}

		// Classes.
		CompositeProducts\Module::instance();
	}

	/**
	 * Load Localisation files.
	 */
	protected function load_plugin_textdomain() {
		load_plugin_textdomain( 'wpdrift-woocommerce-modules', false, basename( dirname( __DIR__ ) ) . '/languages' );
	}

	/**
	 * Returns true if all dependencies for the wpdrift-woocommerce-modules plugin are loaded.
	 * @return [type] [description]
	 */
	protected function check_dependencies() {
		$woocommerce_minimum_met = class_exists( 'WooCommerce' ) && version_compare( WC_VERSION, '3.6', '>=' );
		if ( ! $woocommerce_minimum_met ) {
			return false;
		}

		$wordpress_version = get_bloginfo( 'version' );
		return version_compare( $wordpress_version, '5.2.0', '>=' );
	}

	/**
	 * Returns true if build file exists.
	 *
	 * @return bool
	 */
	protected function check_build() {
		return file_exists( plugin_dir_path( __DIR__ ) . '/build/index.js' );
	}

	/**
	 * Deactivates this plugin.
	 */
	public function deactivate_self() {
		deactivate_plugins( plugin_basename( WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_BASENAME ) );
		unset( $_GET['activate'] );
	}

	/**
	 * Notify users of the plugin requirements.
	 */
	public function render_dependencies_notice() {
		// The notice varies by WordPress version.
		$wordpress_version    = get_bloginfo( 'version' );
		$has_valid_wp_version = version_compare( $wordpress_version, '5.2.0', '>=' );

		if ( $has_valid_wp_version ) {
			$message = sprintf(
				/* translators: URL of WooCommerce plugin */
				__( 'The WPdrift for WooCommerce plugin requires <a href="%s">WooCommerce</a> 3.6 or greater to be installed and active.', 'wpdrift-woocommerce-modules' ),
				'https://wordpress.org/plugins/woocommerce/'
			);
		} else {
			$message = sprintf(
				/* translators: 1: URL of WordPress.org, 2: URL of WooCommerce plugin */
				__( 'The WPdrift for WooCommerce plugin requires both <a href="%1$s">WordPress</a> 5.2 or greater and <a href="%2$s">WooCommerce</a> 3.6 or greater to be installed and active.', 'wpdrift-woocommerce-modules' ),
				'https://wordpress.org/',
				'https://wordpress.org/plugins/woocommerce/'
			);
		}
		printf( '<div class="error"><p>%s</p></div>', $message ); /* WPCS: xss ok. */
	}

	/**
	 * Notify users that the plugin needs to be built.
	 */
	public function render_build_notice() {
		$message_one = __( 'You have installed a development version of WPdrift for WooCommerce which requires files to be built. From the plugin directory, run <code>npm install</code> to install dependencies, <code>npm run build</code> to build the files.', 'wpdrift-woocommerce-modules' );
		$message_two = sprintf(
			/* translators: 1: URL of GitHub Repository build page */
			__( 'Or you can download a pre-built version of the plugin by visiting <a href="%1$s">the releases page in the repository</a>.', 'wpdrift-woocommerce-modules' ),
			'https://github.com/wpdrift/wpdrift-woocommerce-modules/releases'
		);
		printf( '<div class="error"><p>%s %s</p></div>', $message_one, $message_two ); /* WPCS: xss ok. */
	}

	/**
	 * Get and return template.
	 * @param  [type] $template [description]
	 * @return [type]           [description]
	 */
	public function get_template_part( $template, $slug, $name ) {
		$template = '';
		if ( $name ) {
			$template = WC_TEMPLATE_DEBUG_MODE ? '' : locate_template(
				array(
					"{$slug}-{$name}.php",
					WC()->template_path() . "{$slug}-{$name}.php",
				)
			);
		}

		if ( ! $template ) {
			if ( file_exists( WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_DIR . "/templates/{$slug}-{$name}.php" ) ) {
				$template = WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_DIR . "/templates/{$slug}-{$name}.php";
			}

			if ( ! $template ) {
				$fallback = WC()->plugin_path() . "/templates/{$slug}-{$name}.php";
				$template = file_exists( $fallback ) ? $fallback : '';
			}
		}

		if ( ! $template ) {
			// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php.
			$template = WC_TEMPLATE_DEBUG_MODE ? '' : locate_template(
				array(
					"{$slug}.php",
					WC()->template_path() . "{$slug}.php",
				)
			);
		}

		return $template;
	}

	/**
	 * Locate template.
	 * @param  [type] $template      [description]
	 * @param  [type] $template_name [description]
	 * @param  [type] $template_path [description]
	 * @return [type]                [description]
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		global $woocommerce;
		$_template = $template;
		if ( ! $template_path ) {
			$template_path = $woocommerce->template_url;
		}

		$plugin_path = WPDRIFT_WOOCOMMERCE_MODULES_PLUGIN_DIR . '/templates/';

		// Look within passed path within the theme - this is priority
		$template = locate_template(
			array(
				trailingslashit( $template_path ) . $template_name,
				$template_name,
			)
		);

		if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
			$template = $plugin_path . $template_name;
		}

		if ( ! $template ) {
			$template = $_template;
		}

		return $template;
	}
}
