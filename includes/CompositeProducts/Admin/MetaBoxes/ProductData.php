<?php

namespace WPdrift\CompositeProducts\Admin\MetaBoxes;

/**
 * ProductData class
 *
 * @author   SomewhereWarm <info@somewherewarm.gr>
 * @package  WooCommerce Composite Products
 * @since    3.7.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use stdClass;
use WC_Product_Factory;
use WC_Product_Simple;
use WC_Data_Store;
use WPdrift\CompositeProducts\ProductComposite;
use WPdrift\CompositeProducts\ScenariosManager;
use WPdrift\CompositeProducts\Component;
use WPdrift\CompositeProducts\Helpers;
use WPdrift\CompositeProducts\Admin\AdminNotices;
use WPdrift\CompositeProducts\Compatibility\Core\Compatibility;



/**
 * Product Data tabs/panels for the Composite type.
 *
 * @class    ProductData
 * @version  4.0.6
 */
class ProductData {

	/**
	 * Notices to send via ajax when saving a Composite config.
	 * @var array
	 */
	public static $ajax_notices = array();

	/**
	 * Hook in.
	 */
	public static function init() {

		// Creates the admin Components and Scenarios panel tabs.
		add_action( 'woocommerce_product_data_tabs', array( __CLASS__, 'composite_product_data_tabs' ) );

		// Creates the admin Components and Scenarios panels.
		add_action( 'woocommerce_product_data_panels', array( __CLASS__, 'composite_data_panel' ) );
		add_action( 'woocommerce_product_options_stock', array( __CLASS__, 'composite_stock_info' ) );

		// Allows the selection of the 'composite product' type.
		add_filter( 'product_type_options', array( __CLASS__, 'add_composite_type_options' ) );

		// Processes and saves type-specific data.
		add_action( 'woocommerce_admin_process_product_object', array( __CLASS__, 'process_composite_data' ) );

		// Add a notice if calculating min/max catalog price in the background.
		add_action( 'admin_notices', array( __CLASS__, 'maybe_add_catalog_price_notice' ), 0 );

		/*----------------------------------*/
		/*  Composite writepanel options.   */
		/*----------------------------------*/

		add_action( 'woocommerce_composite_admin_options_html', array( __CLASS__, 'composite_options' ), 10, 2 );
		add_action( 'woocommerce_composite_admin_html', array( __CLASS__, 'composite_component_options' ), 15, 2 );

		/*---------------------------------*/
		/*  Component meta boxes.          */
		/*---------------------------------*/

		add_action( 'woocommerce_composite_component_admin_html', array( __CLASS__, 'component_admin_html' ), 10, 4 );

		// Basic component config options.
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_pre' ), 0, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_title' ), 10, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_description' ), 15, 3 );
		// add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_image' ), 15, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_post' ), 20, 3 );

		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_pre' ), 20, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_options' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_optional' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_default_option' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_options_style' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_options_multiple' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_pagination_style' ), 25, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_post' ), 30, 3 );

		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_pre' ), 30, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_quantity_min' ), 35, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_quantity_max' ), 40, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_post' ), 45, 3 );

		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_pre' ), 45, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_shipped_individually' ), 50, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_priced_individually' ), 50, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_discount' ), 50, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_config_display_prices' ), 50, 3 );
		add_action( 'woocommerce_composite_component_admin_config_html', array( __CLASS__, 'component_options_group_post' ), 55, 3 );

		// Advanced component configuration.
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_pre_sa' ), -10, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_select_action_options' ), 0, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_post' ), 0, 3 );

		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_pre' ), 0, 3 );
		// add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_selection_details_options' ), 10, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_post' ), 10, 3 );

		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_pre' ), 10, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_subtotal_visibility_options' ), 10, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_post' ), 15, 3 );

		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_pre' ), 15, 3 );
		// add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_sort_filter_show_orderby' ), 20, 3 );
		// add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_sort_filter_show_filters' ), 20, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_id_marker' ), 100, 3 );
		add_action( 'woocommerce_composite_component_admin_advanced_html', array( __CLASS__, 'component_options_group_post' ), 100, 3 );

		/*-----------------------------*/
		/*  Scenario meta boxes html.  */
		/*-----------------------------*/

		add_action( 'woocommerce_composite_scenario_admin_html', array( __CLASS__, 'scenario_admin_html' ), 10, 5 );

		// Scenario options.
		add_action( 'woocommerce_composite_scenario_admin_info_html', array( __CLASS__, 'scenario_info' ), 10, 4 );
		add_action( 'woocommerce_composite_scenario_admin_config_html', array( __CLASS__, 'scenario_config' ), 10, 4 );

		// "Dependency Group" action.
		add_action( 'woocommerce_composite_scenario_admin_actions_html', array( __CLASS__, 'scenario_action_compat_group' ), 10, 4 );
		// "Hide Components" action.
		add_action( 'woocommerce_composite_scenario_admin_actions_html', array( __CLASS__, 'scenario_action_hide_components' ), 15, 4 );

		/*--------------------------------*/
		/*  "Sold Individually" Options.  */
		/*--------------------------------*/

		add_action( 'woocommerce_product_options_sold_individually', array( __CLASS__, 'sold_individually_options' ) );
	}

	/**
	 * Renders additional "Sold Individually" options.
	 *
	 * @return void
	 */
	public static function sold_individually_options() {

		global $composite_product_object;

		$sold_individually         = $composite_product_object->get_sold_individually( 'edit' );
		$sold_individually_context = $composite_product_object->get_sold_individually_context( 'edit' );

		$value = 'no';

		if ( $sold_individually ) {
			if ( ! in_array( $sold_individually_context, array( 'configuration', 'product' ) ) ) {
				$value = 'product';
			} else {
				$value = $sold_individually_context;
			}
		}

		// Extend "Sold Individually" options to account for different configurations.
		woocommerce_wp_select(
			array(
				'id'            => '_bto_sold_individually',
				'wrapper_class' => 'show_if_composite',
				'label'         => __( 'Sold individually', 'wpdrift-woocommerce-modules' ),
				'options'       => array(
					'no'            => __( 'No', 'wpdrift-woocommerce-modules' ),
					'product'       => __( 'Yes', 'wpdrift-woocommerce-modules' ),
					'configuration' => __( 'Matching configurations only', 'wpdrift-woocommerce-modules' ),
				),
				'value'         => $value,
				'description'   => __( 'Allow only one of this item (or only one of each unique configuration of this item) to be bought in a single order.', 'wpdrift-woocommerce-modules' ),
				'desc_tip'      => 'true',
			)
		);
	}

	/**
	 * Composite general options.
	 *
	 * @since  3.14.0
	 *
	 * @param  ProductComposite  $composite_product_object
	 * @return void
	 */
	public static function composite_options( $composite_product_object ) {
		self::composite_layout( $composite_product_object );
		self::composite_form_location( $composite_product_object );
		self::composite_shop_price_calc( $composite_product_object );
		// self::composite_edit_in_cart( $composite_product_object );
	}

	/**
	 * Renders the composite writepanel Layout Options section before the Components section.
	 *
	 * @param  ProductComposite  $composite_product_object
	 * @return void
	 */
	public static function composite_layout( $composite_product_object ) {

		?><div class="bundle_group bto_clearfix">
			<div class="bto_layouts bto_clearfix form-field components_panel_field">
				<label class="bundle_group_label">
					<?php _e( 'Layout', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<ul class="bto_clearfix bto_layouts_list">
					<?php
					$layouts         = ProductComposite::get_layout_options();
					$selected_layout = $composite_product_object->get_layout( 'edit' );
					$loop            = 0;

					foreach ( $layouts as $layout_id => $layout_data ) {

						if ( ! isset( $layout_data['title'] ) || ! isset( $layout_data['description'] ) || ! isset( $layout_data['image_src'] ) ) {
							continue;
						}

						echo $loop % 2 === 0 ? '<li>' : '';

						?>
						<label class="bto_layout_label <?php echo $selected_layout == $layout_id ? 'selected' : ''; ?>">
							<img class="layout_img" src="<?php echo $layout_data['image_src']; ?>" />
							<input <?php echo $selected_layout == $layout_id ? 'checked="checked"' : ''; ?> name="bto_style" type="radio" value="<?php echo $layout_id; ?>" />
							<?php echo wc_help_tip( '<strong>' . $layout_data['title'] . '</strong> &ndash; ' . $layout_data['description'] ); ?>
						</label>
						<?php

						echo $loop % 2 === 1 ? '</li>' : '';

						$loop++;
					}

					?>
				</ul>
			</div>
		</div>
		<?php
	}

	/**
	 * Displays the "Form location" option.
	 *
	 * @since  3.14.0
	 *
	 * @param  ProductComposite  $composite_product_object
	 * @return void
	 */
	public static function composite_form_location( $composite_product_object ) {

		$options  = ProductComposite::get_add_to_cart_form_location_options();
		$help_tip = '';
		$loop     = 0;

		foreach ( $options as $option_key => $option ) {

			$help_tip .= '<strong>' . $option['title'] . '</strong> &ndash; ' . $option['description'];

			if ( $loop < sizeof( $options ) - 1 ) {
				$help_tip .= '</br></br>';
			}

			$loop++;
		}

		woocommerce_wp_select(
			array(
				'id'            => '_bto_add_to_cart_form_location',
				'wrapper_class' => 'components_panel_field',
				'label'         => __( 'Form Location', 'wpdrift-woocommerce-modules' ),
				'options'       => array_combine( array_keys( $options ), wp_list_pluck( $options, 'title' ) ),
				'value'         => $composite_product_object->get_add_to_cart_form_location( 'edit' ),
				'description'   => $help_tip,
				'desc_tip'      => 'true',
			)
		);
	}

	/**
	 * Displays the "Catalog Price" option.
	 *
	 * @param  ProductComposite  $composite_product_object
	 * @return void
	 */
	public static function composite_shop_price_calc( $composite_product_object ) {

		$shop_price_calc_options = ProductComposite::get_shop_price_calc_options();
		$help_tip                = '';
		$loop                    = 0;

		foreach ( $shop_price_calc_options as $option_key => $option ) {

			$help_tip .= '<strong>' . $option['title'] . '</strong> &ndash; ' . $option['description'];

			if ( $loop < sizeof( $shop_price_calc_options ) - 1 ) {
				$help_tip .= '</br></br>';
			}

			$loop++;
		}

		woocommerce_wp_select(
			array(
				'id'            => '_bto_shop_price_calc',
				'wrapper_class' => 'components_panel_field',
				'value'         => $composite_product_object->get_shop_price_calc( 'edit' ),
				'label'         => __( 'Catalog Price', 'wpdrift-woocommerce-modules' ),
				'description'   => $help_tip,
				'options'       => array_combine( array_keys( $shop_price_calc_options ), wp_list_pluck( $shop_price_calc_options, 'title' ) ),
				'desc_tip'      => true,
			)
		);
	}

	/**
	 * Displays the "Edit in Cart" option.
	 *
	 * @param  ProductComposite  $composite_product_object
	 * @return void
	 */
	public static function composite_edit_in_cart( $composite_product_object ) {

		woocommerce_wp_checkbox(
			array(
				'id'            => '_bto_edit_in_cart',
				'wrapper_class' => 'components_panel_field',
				'label'         => __( 'Edit in Cart', 'wpdrift-woocommerce-modules' ),
				'value'         => $composite_product_object->get_editable_in_cart( 'edit' ) ? 'yes' : 'no',
				'description'   => __( 'Enable this option to allow changing the configuration of this Composite in the cart.', 'wpdrift-woocommerce-modules' ),
				'desc_tip'      => true,
			)
		);
	}

	/**
	 * Renders the composite writepanel Layout Options section before the Components section.
	 *
	 * @param  array $composite_data
	 * @param  int   $composite_id
	 * @return void
	 */
	public static function composite_component_options( $composite_data, $composite_id ) {

		global $composite_product_object;

		$selected_layout = $composite_product_object->get_layout( 'edit' );

		?>
		<div class="hr-section hr-section-components"><?php echo __( 'Components', 'wpdrift-woocommerce-modules' ); ?></div>
		<div class="options_group config_group bto_clearfix <?php echo empty( $composite_data ) ? 'options_group--boarding' : ''; ?> <?php echo 'layout-' . $selected_layout; ?>">
			<p class="toolbar">
				<span class="bulk_toggle_wrapper">
					<a href="#" class="close_all"><?php _e( 'Close all', 'wpdrift-woocommerce-modules' ); ?></a>
					<a href="#" class="expand_all"><?php _e( 'Expand all', 'wpdrift-woocommerce-modules' ); ?></a>
				</span>
			</p>

			<div id="bto_config_group_inner">

				<div class="bto_groups wc-metaboxes ui-sortable" data-count="">
				<?php

				if ( ! empty( $composite_data ) ) {

					$i = 0;

					foreach ( $composite_data as $group_id => $data ) {

						/**
						 * Action 'woocommerce_composite_component_admin_html'.
						 *
						 * @param  int     $i
						 * @param  array   $data
						 * @param  string  $composite_id
						 * @param  string  $state
						 *
						 * @hooked {@see component_admin_html} - 10
						 */
						do_action( 'woocommerce_composite_component_admin_html', $i, $data, $composite_id, 'closed' );

						$i++;
					}
				} else {

					?>
						<div class="bto_boarding__components">
							<div class="bto_boarding__components__message">
								<h3><?php _e( 'Components', 'wpdrift-woocommerce-modules' ); ?></h3>
								<p><?php _e( 'Components are the <a href="https://docs.woocommerce.com/document/composite-products/composite-products-configuration/" target="_blank">building blocks</a> of every Composite Product.', 'wpdrift-woocommerce-modules' ); ?>
								<br/><?php _e( 'Ready to start building?', 'wpdrift-woocommerce-modules' ); ?>
								</p>
							</div>
						</div>
						<?php
				}

				?>
				</div>
			</div>

			<p class="bto_action_button_wrapper bto_action_button_wrapper--add_component">
				<button type="button" class="button add_bto_group"><?php _e( 'Add Component', 'wpdrift-woocommerce-modules' ); ?></button>
			</p>
		</div>
		<?php
	}

	/**
	 * Add a component id watermark in the 'Advanced Configuration' tab.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_id_marker( $id, $data, $product_id ) {

		if ( ! empty( $data['component_id'] ) ) {

			?>
			<span class="group_id">
				<?php echo '#' . esc_html( $data['component_id'] ); ?>
			</span>
			<?php
		}
	}

	/**
	 * Handles getting component meta box tabs - @see 'component_admin_html'.
	 *
	 * @return array
	 */
	public static function get_component_tabs() {

		/**
		 * Filter the tab sections that appear in every Component metabox.
		 *
		 * @param  array  $tabs
		 */
		return apply_filters(
			'woocommerce_composite_component_admin_html_tabs',
			array(
				'config'   => array(
					'title' => __( 'Basic Settings', 'wpdrift-woocommerce-modules' ),
				),
				'advanced' => array(
					'title' => __( 'Advanced Settings', 'wpdrift-woocommerce-modules' ),
				),
			)
		);
	}

	/**
	 * Load component meta box in 'woocommerce_composite_component_admin_html'.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $composite_id
	 * @param  string $toggle
	 * @return void
	 */
	public static function component_admin_html( $id, $data, $composite_id, $toggle = 'closed' ) {

		$tabs = self::get_component_tabs();

		include( 'views/html-component.php' );
	}

	/**
	 * Load component meta box in 'woocommerce_composite_component_admin_html'.
	 *
	 * @param  int    $id
	 * @param  array  $scenario_data
	 * @param  array  $composite_data
	 * @param  int    $composite_id
	 * @param  string $toggle
	 * @return void
	 */
	public static function scenario_admin_html( $id, $scenario_data, $composite_data, $composite_id, $toggle = 'closed' ) {

		include( 'views/html-scenario.php' );
	}

	/**
	 * Add "Activate Configuration" scenario action.
	 *
	 * @param  int    $id
	 * @param  array  $scenario_data
	 * @param  array  $composite_data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function scenario_action_compat_group( $id, $scenario_data, $composite_data, $product_id ) {

		$defines_compat_group = isset( $scenario_data['scenario_actions']['compat_group']['is_active'] ) ? $scenario_data['scenario_actions']['compat_group']['is_active'] : 'yes';

		?>
		<div class="scenario_action_compat_group" >
			<div class="form-field">
				<label for="scenario_action_compat_group_<?php echo $id; ?>">
					<?php echo __( 'Activate Configuration', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $defines_compat_group === 'yes' ? ' checked="checked"' : '' ); ?> name="bto_scenario_data[<?php echo $id; ?>][scenario_actions][compat_group][is_active]" <?php echo ( $defines_compat_group === 'yes' ? ' value="1"' : '' ); ?> />
																  <?php
																	echo wc_help_tip( __( 'Activates the combination of selections specified in the Configuration section. Different configurations will be unavailable, unless activated by another Scenario.', 'wpdrift-woocommerce-modules' ) );
																	?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add "Hide Components" scenario action.
	 *
	 * @param  int    $id
	 * @param  array  $scenario_data
	 * @param  array  $composite_data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function scenario_action_hide_components( $id, $scenario_data, $composite_data, $product_id ) {

		$hide_components   = isset( $scenario_data['scenario_actions']['conditional_components']['is_active'] ) ? $scenario_data['scenario_actions']['conditional_components']['is_active'] : 'no';
		$hidden_components = ! empty( $scenario_data['scenario_actions']['conditional_components']['hidden_components'] ) ? $scenario_data['scenario_actions']['conditional_components']['hidden_components'] : array();

		?>
		<div class="scenario_action_conditional_components_group" >
			<div class="form-field toggle_conditional_components">
				<label for="scenario_action_conditional_components_<?php echo $id; ?>">
					<?php echo __( 'Hide Components', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox" <?php echo ( $hide_components === 'yes' ? ' checked="checked"' : '' ); ?> name="bto_scenario_data[<?php echo $id; ?>][scenario_actions][conditional_components][is_active]" <?php echo ( $hide_components === 'yes' ? ' value="1"' : '' ); ?> />
																   <?php
																	echo wc_help_tip( __( 'Enable this option to conditionally hide one or more Components based on the matching conditions specified in the Configuration section.', 'wpdrift-woocommerce-modules' ) );
																	?>
			</div>
			<div class="form-field action_components" <?php echo ( $hide_components === 'no' ? ' style="display:none;"' : '' ); ?> >
				<select id="bto_conditional_components_ids_<?php echo $id; ?>" name="bto_scenario_data[<?php echo $id; ?>][scenario_actions][conditional_components][hidden_components][]" style="width: 75%;" class="wc-enhanced-select-lazy conditional_components_ids" multiple="multiple" data-placeholder="<?php echo __( 'Select components&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
																	  <?php

																		foreach ( $composite_data as $component_id => $component_data ) {

																			$component_title = $component_data['title'];

																			$option_selected = in_array( $component_id, $hidden_components ) ? 'selected="selected"' : '';
																			echo '<option ' . $option_selected . 'value="' . $component_id . '">' . esc_html( $component_title ) . '</option>';
																		}

																		?>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Add scenario title and description options.
	 *
	 * @param  int    $id
	 * @param  array  $scenario_data
	 * @param  array  $composite_data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function scenario_info( $id, $scenario_data, $composite_data, $product_id ) {

		$title       = isset( $scenario_data['title'] ) ? $scenario_data['title'] : '';
		$description = isset( $scenario_data['description'] ) ? $scenario_data['description'] : '';

		?>
		<div class="scenario_title">
			<div class="form-field">
				<label>
					<?php echo __( 'Scenario Name', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="text" class="scenario_title component_text_input" name="bto_scenario_data[<?php echo $id; ?>][title]" value="<?php echo esc_attr( $title ); ?>"/>
			</div>
		</div>
		<div class="scenario_description">
			<div class="form-field">
				<label>
					<?php echo __( 'Scenario Description', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<textarea class="scenario_description" name="bto_scenario_data[<?php echo $id; ?>][description]" id="scenario_description_<?php echo $id; ?>" placeholder="" rows="2" cols="20"><?php echo esc_textarea( $description ); ?></textarea>
			</div>
		</div>
		<?php
	}

	/**
	 * Add scenario config options.
	 *
	 * @param  int    $id
	 * @param  array  $scenario_data
	 * @param  array  $composite_data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function scenario_config( $id, $scenario_data, $composite_data, $product_id ) {

		global $composite_product_object_data;

		if ( empty( $composite_product_object_data ) ) {
			$composite_product_object_data = array();
		}

		?>
		<div class="scenario_config_group">
		<?php

		foreach ( $composite_data as $component_id => $component_data ) {

			$modifier = 'masked';

			if ( isset( $scenario_data['modifier'][ $component_id ] ) ) {

				$modifier = $scenario_data['modifier'][ $component_id ];

			} elseif ( ! doing_action( 'wp_ajax_woocommerce_add_composite_scenario' ) ) {

				$exclude = isset( $scenario_data['exclude'][ $component_id ] ) ? $scenario_data['exclude'][ $component_id ] : 'no';

				if ( 'no' === $exclude ) {
					$modifier = 'in';
				} elseif ( 'yes' === $exclude ) {
					$modifier = 'not-in';
				}
			}

			$component_title = esc_html( $component_data['title'] );

			?>
				<div class="bto_scenario_selector">
					<div class="form-field">
						<label>
						<?php
						echo  $component_title;
						?>
						</label>
						<div class="bto_scenario_match_component_wrapper">
							<input type="checkbox" class="bto_scenario_match_component checkbox"<?php echo ( $modifier !== 'masked' ? ' checked="checked"' : '' ); ?> name="bto_scenario_data[<?php echo $id; ?>][match_component][<?php echo $component_id; ?>]" <?php echo ( $modifier !== 'masked' ? ' value="1"' : '' ); ?> />
																										   <?php
																											echo wc_help_tip( sprintf( __( 'Include/exclude options from <strong>%s</strong> in this Scenario.', 'wpdrift-woocommerce-modules' ), $component_title ) );
																											?>
						</div>
					</div>
					<div class="form-field bto_scenario_matching_conditions_wrapper" <?php echo ( $modifier === 'masked' ? ' style="display:none;"' : '' ); ?> >
						<div class="bto_scenario_modifier_wrapper">
							<select class="bto_scenario_modifier wc-enhanced-select-lazy" style="width: 75%" name="bto_scenario_data[<?php echo $id; ?>][modifier][<?php echo $component_id; ?>]">
								<option <?php selected( $modifier, 'in', true ); ?> value="in"><?php echo __( 'is', 'wpdrift-woocommerce-modules' ); ?></option>
								<option <?php selected( $modifier, 'not-in', true ); ?> value="not-in"><?php echo __( 'is not', 'wpdrift-woocommerce-modules' ); ?></option>
							</select>
						</div>
						<div class="bto_scenario_selector_inner">
								<?php

								$component_options_cache_key = 'component_' . $component_id . '_options';
								$component_options           = Helpers::cache_get( $component_options_cache_key );

								if ( null === $component_options ) {
										$component_options = Component::query_component_options( $component_data );
										Helpers::cache_set( $component_options_cache_key, $component_options );
								}

									$composite_data_store    = WC_Data_Store::load( 'product-composite' );
									$component_options_count = count( $composite_data_store->get_expanded_component_options( $component_options, 'merged' ) );
									$component_options_data  = array();

									$ajax_threshold                 = apply_filters( 'woocommerce_composite_scenario_admin_products_ajax_threshold', 30, $component_id, $product_id );
									$global_ajax_threshold          = isset( $composite_product_object_data['component_options_ajax_threshold'] ) ? $composite_product_object_data['component_options_ajax_threshold'] : false;
									$global_ajax_threshold_exceeded = $global_ajax_threshold && isset( $composite_product_object_data['component_options_count'] ) && $composite_product_object_data['component_options_count'] >= $global_ajax_threshold;
									$use_ajax                       = $global_ajax_threshold_exceeded || $component_options_count >= $ajax_threshold;

								if ( false === $use_ajax ) {

									foreach ( $component_options as $component_option_id ) {

										$component_option = self::get_component_option( $component_option_id );

										if ( false === $component_option ) {
											continue;
										}

										$component_options_data[ $component_option_id ] = $component_option;
									}
								}

								if ( false === $use_ajax ) {

									$scenario_options    = array();
									$scenario_selections = array();

									if ( $component_data['optional'] === 'yes' ) {

										if ( isset( $scenario_data['component_data'] ) && Helpers::in_array_key( $scenario_data['component_data'], $component_id, -1 ) ) {
											$scenario_selections[] = -1;
										}

										$scenario_options[-1] = _x( 'No selection', 'optional component property controlled in scenarios', 'wpdrift-woocommerce-modules' );
									}

									if ( isset( $scenario_data['component_data'] ) && Helpers::in_array_key( $scenario_data['component_data'], $component_id, 0 ) ) {
										$scenario_selections[] = 0;
									}

									$scenario_options[0] = __( 'Any Product or Variation', 'wpdrift-woocommerce-modules' );

									foreach ( $component_options_data as $option_id => $option_data ) {

										$title         = $option_data->get_title();
										$product_type  = $option_data->get_type();
										$product_title = 'variable' === $product_type ? Helpers::get_product_title( $option_data, '', __( 'Any Variation', 'wpdrift-woocommerce-modules' ) ) : Helpers::get_product_title( $option_data );

										if ( isset( $scenario_data['component_data'] ) && Helpers::in_array_key( $scenario_data['component_data'], $component_id, $option_id ) ) {
											$scenario_selections[] = $option_id;
										}

										$scenario_options[ $option_id ] = $product_title;

										if ( $product_type === 'variable' ) {

											$component_option_variations_cache_key = 'component_option_variations_' . $option_id;
											$component_option_variations           = Helpers::cache_get( $component_option_variations_cache_key );

											if ( null === $component_option_variations ) {
												$component_option_variations = Helpers::get_product_variation_descriptions( $option_data, 'flat' );
												Helpers::cache_set( $component_option_variations_cache_key, $component_option_variations );
											}

											if ( ! empty( $component_option_variations ) ) {

												foreach ( $component_option_variations as $variation_id => $description ) {

													if ( isset( $scenario_data['component_data'] ) && Helpers::in_array_key( $scenario_data['component_data'], $component_id, $variation_id ) ) {
														$scenario_selections[] = $variation_id;
													}

													$scenario_options[ $variation_id ] = $description;
												}
											}
										}
									}

									$no_selection = _x( 'No selection', 'optional component property controlled in scenarios', 'wpdrift-woocommerce-modules' );
									$optional_tip = $component_data['optional'] === 'yes' ? sprintf( __( '<br/><br/><strong>Advanced Tip</strong> &ndash; The <strong>%1$s</strong> option refers to a state where none of the available products is selected. You can use it in combination with product references to create selection dependencies, or even to make <strong>%2$s</strong> conditionally <strong>Optional</strong>.', 'wpdrift-woocommerce-modules' ), $no_selection, $component_title ) : '';
									$select_tip   = sprintf( __( 'Select products and variations from <strong>%1$s</strong>.<br/><br/><strong>Tip</strong> &ndash; Choose <strong>Any Product or Variation</strong> to add all <strong>%1$s</strong> products and variations in this Scenario.%2$s', 'wpdrift-woocommerce-modules' ), $component_title, $optional_tip );

									?>
								<select id="bto_scenario_ids_<?php echo $id; ?>_<?php echo $component_id; ?>" name="bto_scenario_data[<?php echo $id; ?>][component_data][<?php echo $component_id; ?>][]" style="width: 75%;" class="wc-enhanced-select-lazy bto_scenario_ids" multiple="multiple" data-placeholder="<?php echo __( 'Select products &amp; variations&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
									<?php

									foreach ( $scenario_options as $scenario_option_id => $scenario_option_description ) {
										$option_selected = in_array( $scenario_option_id, $scenario_selections ) ? 'selected="selected"' : '';
										echo '<option ' . $option_selected . 'value="' . $scenario_option_id . '">' . $scenario_option_description . '</option>';
									}

									?>
								</select>
								<span class="bto_scenario_select tips" data-tip="<?php echo $select_tip; ?>"></span>
																				<?php

								} else {

									$selections_in_scenario = array();

									if ( ! empty( $scenario_data['component_data'] ) ) {

										foreach ( $scenario_data['component_data'][ $component_id ] as $product_id_in_scenario ) {

											if ( $product_id_in_scenario == -1 ) {
												if ( $component_data['optional'] === 'yes' ) {
													$selections_in_scenario[ $product_id_in_scenario ] = _x( 'No selection', 'optional component property controlled in scenarios', 'wpdrift-woocommerce-modules' );
												}
											} elseif ( $product_id_in_scenario == 0 ) {
												$selections_in_scenario[ $product_id_in_scenario ] = __( 'Any Product or Variation', 'wpdrift-woocommerce-modules' );
											} else {

												$product_in_scenario = self::get_component_option( $product_id_in_scenario, true );

												if ( false === $product_in_scenario ) {
													continue;
												}

												if ( ! in_array( Compatibility::get_product_id( $product_in_scenario ), $component_options ) ) {
													continue;
												}

												if ( $product_in_scenario->get_type() === 'variation' ) {
													$selections_in_scenario[ $product_id_in_scenario ] = Helpers::get_product_variation_title( $product_in_scenario );
												} elseif ( $product_in_scenario->get_type() === 'variable' ) {
													$selections_in_scenario[ $product_id_in_scenario ] = Helpers::get_product_title( $product_in_scenario, '', __( 'Any Variation', 'wpdrift-woocommerce-modules' ) );
												} else {
													$selections_in_scenario[ $product_id_in_scenario ] = Helpers::get_product_title( $product_in_scenario );
												}
											}
										}
									}

									$no_selection = _x( 'No selection', 'optional component property controlled in scenarios', 'wpdrift-woocommerce-modules' );
									$optional_tip = $component_data['optional'] === 'yes' ? sprintf( __( '<br/><br/><strong>Advanced Tip</strong> &ndash; The <strong>%1$s</strong> option refers to a state where none of the available products is selected. You can use it in combination with product references to create selection dependencies, or even to make <strong>%2$s</strong> conditionally <strong>Optional</strong>.', 'wpdrift-woocommerce-modules' ), $no_selection, $component_title ) : '';
									$search_tip   = sprintf( __( 'Search for products and variations from <strong>%1$s</strong>.<br/><br/><strong>Tip</strong> &ndash; Choose <strong>Any Product or Variation</strong> to add all <strong>%1$s</strong> products and variations in this Scenario.%2$s', 'wpdrift-woocommerce-modules' ), $component_title, $optional_tip );

									?>
								<select id="bto_scenario_ids_<?php echo $id; ?>_<?php echo $component_id; ?>" name="bto_scenario_data[<?php echo $id; ?>][component_data][<?php echo $component_id; ?>][]" class="wc-component-options-search-lazy" multiple="multiple" style="width: 75%;" data-include="
																		<?php
																		echo esc_attr(
																			json_encode(
																				array(
																					'composite_id' => $product_id,
																					'component_id' => $component_id,
																				)
																			)
																		);
																		?>
																		" data-limit="100" data-component_optional="<?php echo $component_data['optional']; ?>" data-action="woocommerce_json_search_products_and_variations_in_component" data-placeholder="<?php echo  __( 'Search for products &amp; variations&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
									<?php

									if ( ! empty( $selections_in_scenario ) ) {

										foreach ( $selections_in_scenario as $selection_id_in_scenario => $selection_in_scenario ) {
											echo '<option value="' . $selection_id_in_scenario . '" selected="selected">' . $selection_in_scenario . '</option>';
										}
									}

									?>
								</select>
								<span class="bto_scenario_search tips" data-tip="<?php echo $search_tip; ?>"></span>
															<?php
								}

								?>
						</div>
					</div>
				</div>
				<?php
		}

		?>
		</div>
		<?php
	}

	/**
	 * Select action options.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_select_action_options( $id, $data, $product_id ) {

		$select_action_options = Component::get_select_action_options();
		$select_action         = isset( $data['select_action'] ) && in_array( $data['select_action'], wp_list_pluck( $select_action_options, 'id' ) ) ? $data['select_action'] : 'view';
		$help_tip              = '';

		?>
		<div class="component_select_action">
			<div class="form-field">
				<label>
					<?php _e( 'Option Select Action', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<select class="wc-enhanced-select-lazy" style="width: 75%" name="bto_data[<?php echo $id; ?>][select_action]">
																									 <?php

																										foreach ( $select_action_options as $option_key => $option ) {

																											echo '<option ' . selected( $select_action, $option['id'], false ) . ' value="' . $option['id'] . '">' . $option['title'] . '</option>';

																											$help_tip .= '<strong>' . $option['title'] . '</strong> &ndash; ' . $option['description'];

																											if ( $option_key < sizeof( $select_action_options ) - 1 ) {
																												$help_tip .= '</br></br>';
																											}
																										}

																										?>
				</select>
				<?php echo wc_help_tip( $help_tip ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component selection details layout options.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_selection_details_options( $id, $data, $product_id ) {

		$hide_product_title       = isset( $data['hide_product_title'] ) && 'yes' === $data['hide_product_title'] ? 'yes' : 'no';
		$hide_product_description = isset( $data['hide_product_description'] ) && 'yes' === $data['hide_product_description'] ? 'yes' : 'no';
		$hide_product_thumbnail   = isset( $data['hide_product_thumbnail'] ) && 'yes' === $data['hide_product_thumbnail'] ? 'yes' : 'no';
		$hide_product_price       = isset( $data['hide_product_price'] ) && 'yes' === $data['hide_product_price'] ? 'yes' : 'no';

		?>
		<div class="component_selection_details">
			<div class="form-field">
				<label for="component_selection_details_<?php echo $id; ?>">
					<?php echo __( 'Selection Details Visibility', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<div class="component_selection_details_option">
					<input type="checkbox" class="checkbox"<?php echo ( 'no' === $hide_product_title ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_product_title]" <?php echo ( 'no' === $hide_product_title ? 'value="1"' : '' ); ?>/>
					<span class="labelspan"><?php echo __( 'Title', 'wpdrift-woocommerce-modules' ); ?>
					<?php echo wc_help_tip( __( 'Show/hide the title of the selected option.', 'wpdrift-woocommerce-modules' ) ); ?>
				</div>
				<div class="component_selection_details_option">
					<input type="checkbox" class="checkbox"<?php echo ( 'no' === $hide_product_description ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_product_description]" <?php echo ( 'no' === $hide_product_description ? 'value="1"' : '' ); ?>/>
					<span class="labelspan"><?php echo __( 'Description', 'wpdrift-woocommerce-modules' ); ?>
					<?php echo wc_help_tip( __( 'Show/hide the description of the selected option.', 'wpdrift-woocommerce-modules' ) ); ?>
				</div>
				<div class="component_selection_details_option">
					<input type="checkbox" class="checkbox"<?php echo ( 'no' === $hide_product_thumbnail ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_product_thumbnail]" <?php echo ( 'no' === $hide_product_thumbnail ? 'value="1"' : '' ); ?>/>
					<span class="labelspan"><?php echo __( 'Thumbnail', 'wpdrift-woocommerce-modules' ); ?>
					<?php echo wc_help_tip( __( 'Show/hide the thumbnail of the selected option.', 'wpdrift-woocommerce-modules' ) ); ?>
				</div>
				<div class="component_selection_details_option">
					<input type="checkbox" class="checkbox"<?php echo ( 'no' === $hide_product_price ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_product_price]" <?php echo ( 'no' === $hide_product_price ? 'value="1"' : '' ); ?>/>
					<span class="labelspan"><?php echo __( 'Price', 'wpdrift-woocommerce-modules' ); ?>
					<?php echo wc_help_tip( __( 'Show/hide the price of the selected option.', 'wpdrift-woocommerce-modules' ) ); ?>
				</div>
				<?php

				/**
				 * Action 'woocommerce_composite_component_admin_config_filter_options':
				 * Add your own custom filter config options here.
				 *
				 * @param  string  $component_id
				 * @param  array   $component_data
				 * @param  string  $composite_id
				 */
				do_action( 'woocommerce_composite_component_admin_advanced_selection_details_options', $id, $data, $product_id );

				?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component subtotal visibility options.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_subtotal_visibility_options( $id, $data, $product_id ) {

		$hide_in_product = isset( $data['hide_subtotal_product'] ) && 'yes' === $data['hide_subtotal_product'] ? 'yes' : 'no';
		$hide_in_cart    = isset( $data['hide_subtotal_cart'] ) && 'yes' === $data['hide_subtotal_cart'] ? 'yes' : 'no';
		$hide_in_orders  = isset( $data['hide_subtotal_orders'] ) && 'yes' === $data['hide_subtotal_orders'] ? 'yes' : 'no';

		?>
		<div class="component_subtotal_visibility">
			<div class="form-field">
				<label for="component_subtotal_visibility_<?php echo $id; ?>">
					<?php echo __( 'Subtotal Visibility', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<div class="component_subtotal_visibility_option">
					<input type="checkbox" class="checkbox"<?php echo ( 'no' === $hide_in_product ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_subtotal_product]" <?php echo ( 'no' === $hide_in_product ? 'value="1"' : '' ); ?>/>
					<span class="labelspan"><?php echo __( 'Single-product summary', 'wpdrift-woocommerce-modules' ); ?>
					<?php echo wc_help_tip( __( 'Controls the visibility of the Component subtotal in the single-product Summary section.', 'wpdrift-woocommerce-modules' ) ); ?>
				</div>
				<div class="component_subtotal_visibility_option">
					<input type="checkbox" class="checkbox"<?php echo ( 'no' === $hide_in_cart ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_subtotal_cart]" <?php echo ( 'no' === $hide_in_cart ? 'value="1"' : '' ); ?>/>
					<span class="labelspan"><?php echo __( 'Cart/checkout', 'wpdrift-woocommerce-modules' ); ?>
					<?php echo wc_help_tip( __( 'Controls the visibility of the Component subtotal in cart/checkout templates.', 'wpdrift-woocommerce-modules' ) ); ?>
				</div>
				<div class="component_subtotal_visibility_option">
					<input type="checkbox" class="checkbox"<?php echo ( 'no' === $hide_in_orders ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_subtotal_orders]" <?php echo ( 'no' === $hide_in_orders ? 'value="1"' : '' ); ?>/>
					<span class="labelspan"><?php echo __( 'Order details', 'wpdrift-woocommerce-modules' ); ?>
					<?php echo wc_help_tip( __( 'Controls the visibility of the Component subtotal in order details &amp; e-mail templates.', 'wpdrift-woocommerce-modules' ) ); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component 'show orderby' option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_sort_filter_show_orderby( $id, $data, $product_id ) {

		$show_orderby = isset( $data['show_orderby'] ) ? $data['show_orderby'] : 'no';

		?>
		<div class="component_show_orderby group_show_orderby" >
			<div class="form-field">
				<label for="group_show_orderby_<?php echo $id; ?>">
					<?php echo __( 'Options Sorting', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $show_orderby === 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_orderby]" <?php echo ( $show_orderby === 'yes' ? 'value="1"' : '' ); ?>/>
				<?php echo wc_help_tip( __( 'Check this option to allow sorting the available Component Options by popularity, rating, newness or price.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component 'show filters' option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_sort_filter_show_filters( $id, $data, $product_id ) {

		$show_filters         = isset( $data['show_filters'] ) ? $data['show_filters'] : 'no';
		$selected_taxonomies  = isset( $data['attribute_filters'] ) ? $data['attribute_filters'] : array();
		$attribute_taxonomies = wc_get_attribute_taxonomies();

		?>
		<div class="component_show_filters group_show_filters" >
			<div class="form-field">
				<label for="group_show_filters_<?php echo $id; ?>">
					<?php echo __( 'Options Filtering', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $show_filters === 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][show_filters]" <?php echo ( $show_filters === 'yes' ? 'value="1"' : '' ); ?>/>
				<?php echo wc_help_tip( __( 'Check this option to configure and display layered attribute filters. Useful for narrowing down Component Options more easily.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php

		if ( $attribute_taxonomies ) {

			$options        = array();
			$sorted_options = array();

			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$options[ $tax->attribute_id ] = $tax->attribute_label;
				}
			}

			if ( ! empty( $selected_taxonomies ) ) {
				foreach ( $selected_taxonomies as $tax_id ) {
					if ( isset( $options[ $tax_id ] ) ) {
						$sorted_options[ $tax_id ] = $options[ $tax_id ];
					}
				}
			}

			foreach ( $options as $tax_id => $tax_label ) {
				if ( ! isset( $sorted_options[ $tax_id ] ) ) {
					$sorted_options[ $tax_id ] = $options[ $tax_id ];
				}
			}

			?>
			<div class="component_filters group_filters" >
				<div class="bto_attributes_selector bto_multiselect">
					<div class="form-field">
						<select id="bto_attribute_ids_<?php echo $id; ?>" name="bto_data[<?php echo $id; ?>][attribute_filters][]" style="width: 75%" class="multiselect wc-enhanced-select-lazy" multiple="multiple" data-sortable="yes" data-placeholder="<?php echo  __( 'Select product attributes&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
																 <?php

																	foreach ( $sorted_options as $attribute_taxonomy_id => $attribute_taxonomy_label ) {
																		echo '<option value="' . $attribute_taxonomy_id . '" ' . selected( in_array( $attribute_taxonomy_id, $selected_taxonomies ), true, false ) . '>' . $attribute_taxonomy_label . '</option>';
																	}

																	?>
						</select>
					</div>
				</div>
				<?php

				/**
				 * Action 'woocommerce_composite_component_admin_config_filter_options':
				 * Add your own custom filter config options here.
				 *
				 * @param  string  $component_id
				 * @param  array   $component_data
				 * @param  string  $composite_id
				 */
				do_action( 'woocommerce_composite_component_admin_config_filter_options', $id, $data, $product_id );

				?>
			</div>
			<?php
		}
	}

	/**
	 * Open component config group div.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_options_group_pre( $id, $data, $product_id ) {
		?>
		<div class="options_group options_group_component">
		<?php
	}

	/**
	 * Open component select-action config group div.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_options_group_pre_sa( $id, $data, $product_id ) {
		?>
		<div class="options_group options_group_component options_group_component--select_action">
		<?php
	}

	/**
	 * Close component config group div.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_options_group_post( $id, $data, $product_id ) {
		?>
		</div>
		<?php
	}

	/**
	 * Add component config title option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_title( $id, $data, $product_id ) {

		$title = isset( $data['title'] ) ? $data['title'] : '';

		?>
		<div class="component_title group_title">
			<div class="form-field">
				<label>
					<?php echo __( 'Component Name', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="text" class="group_title component_text_input" name="bto_data[<?php echo $id; ?>][title]" value="<?php echo esc_attr( $title ); ?>"/><?php echo wc_help_tip( __( 'Name or title of this Component.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config description option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_description( $id, $data, $product_id ) {

		$description = isset( $data['description'] ) ? $data['description'] : '';

		?>
		<div class="component_description group_description">
			<div class="form-field">
				<label>
					<?php echo __( 'Component Description', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<textarea class="group_description" name="bto_data[<?php echo $id; ?>][description]" id="group_description_<?php echo $id; ?>" placeholder="" rows="2" cols="20"><?php echo esc_textarea( $description ); ?></textarea><?php echo wc_help_tip( __( 'Optional short description of this Component.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component placeholder image.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_image( $id, $data, $product_id ) {

		$image_id = isset( $data['thumbnail_id'] ) ? $data['thumbnail_id'] : '';
		$image    = $image_id ? wp_get_attachment_thumb_url( $image_id ) : '';

		?>
		<div class="component_image group_image">
			<div class="form-field">
				<label>
					<?php echo __( 'Component Image', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<a href="#" class="upload_component_image_button <?php echo $image_id ? 'has_image' : ''; ?>"><span class="prompt"><?php echo __( 'Select image', 'wpdrift-woocommerce-modules' ); ?></span><img src="
																			<?php
																			if ( ! empty( $image ) ) {
																				echo esc_attr( $image );
																			} else {
																				echo esc_attr( wc_placeholder_img_src() );
																			}
																			?>
																			" /><input type="hidden" name="bto_data[<?php echo $id; ?>][thumbnail_id]" class="image" value="<?php echo $image_id; ?>" /></a>
				<?php echo wc_help_tip( __( 'Placeholder image to use in configuration summaries. Substituted by the image of the selected Component Option.', 'wpdrift-woocommerce-modules' ) ); ?>
				<a href="#" class="remove_component_image_button <?php echo $image_id ? 'has_image' : ''; ?>"><?php echo __( 'Remove image', 'wpdrift-woocommerce-modules' ); ?></a>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config multi select products option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_options( $id, $data, $product_id ) {

		$query_type          = isset( $data['query_type'] ) ? $data['query_type'] : 'product_ids';
		$product_categories  = (array) get_terms( 'product_cat', array( 'get' => 'all' ) );
		$selected_categories = isset( $data['assigned_category_ids'] ) ? $data['assigned_category_ids'] : array();

		$select_by = array(
			'product_ids'  => __( 'Select products', 'wpdrift-woocommerce-modules' ),
			'category_ids' => __( 'Select categories', 'wpdrift-woocommerce-modules' ),
		);

		/**
		 * Filter the default query types.
		 *
		 * @param  array  $select_by
		 */
		$select_by = apply_filters( 'woocommerce_composite_component_query_types', $select_by, $data, $product_id );

		?>
		<div class="component_query_type">
			<div class="form-field">
				<label>
					<?php echo __( 'Component Options', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<select class="component_query_type wc-enhanced-select-lazy" name="bto_data[<?php echo $id; ?>][query_type]" style="width: 75%;">
																									   <?php

																										foreach ( $select_by as $key => $description ) {
																											?>
						<option value="<?php echo $key; ?>" <?php selected( $query_type, $key, true ); ?>><?php echo $description; ?></option>
																											<?php
																										}

																										?>
				</select>
				<?php echo wc_help_tip( __( 'Product options offered in this Component. Add products individually, or select a category to include all associated products.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>

		<div class="component_selector bto_selector component_query_type_selector bto_multiselect component_query_type_product_ids">
			<div class="form-field">
			<?php

				$product_id_options = array();

			if ( ! empty( $data['assigned_ids'] ) ) {

				$component_options = $data['assigned_ids'];

				foreach ( $component_options as $component_option_id ) {

					$component_option = self::get_component_option( $component_option_id );

					if ( false === $component_option ) {
						continue;
					}

					if ( $product_title = Helpers::get_product_title( $component_option ) ) {
						$product_id_options[ $component_option_id ] = $product_title;
					}
				}
			}

			?>
				<select id="bto_ids_<?php echo $id; ?>" class="wc-product-search-lazy products_selector" name="bto_data[<?php echo $id; ?>][assigned_ids][]" multiple="multiple" style="width: 75%;" data-limit="100" data-action="woocommerce_json_search_component_options" data-placeholder="<?php echo  __( 'Search for a product&hellip;', 'wpdrift-woocommerce-modules' ); ?>" data-sortable="true">
				<?php

				if ( ! empty( $product_id_options ) ) {
					foreach ( $product_id_options as $product_id => $product_name ) {
						echo '<option value="' . $product_id . '" selected="selected">' . $product_name . '</option>';
					}
				}

				?>
				</select></div>
		</div>

		<div class="component_category_selector bto_category_selector component_query_type_selector bto_multiselect component_query_type_category_ids">
			<div class="form-field">

				<select id="bto_category_ids_<?php echo $id; ?>" class="multiselect wc-enhanced-select-lazy categories_selector" name="bto_data[<?php echo $id; ?>][assigned_category_ids][]" style="width: 75%" multiple="multiple" data-placeholder="<?php echo  __( 'Select categories&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
														<?php

														foreach ( $product_categories as $product_category ) {
															echo '<option value="' . $product_category->term_id . '" ' . selected( in_array( $product_category->term_id, $selected_categories ), true, false ) . '>' . $product_category->name . '</option>';
														}

														?>
				</select>
			</div>
		</div>
		<?php

		/**
		 * Action 'woocommerce_composite_component_admin_config_query_options'.
		 * Use this hook to display additional query type options associated with a custom query type added via {@see woocommerce_composite_component_query_types}.
		 *
		 * @param  $id          int
		 * @param  $data        array
		 * @param  $product_id  string
		 */
		do_action( 'woocommerce_composite_component_admin_config_query_options', $id, $data, $product_id );
	}

	/**
	 * Add component config default selection option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_default_option( $id, $data, $product_id ) {

		global $composite_product_object_data;

		if ( empty( $composite_product_object_data ) ) {
			$composite_product_object_data = array();
		}

		$component_id      = isset( $data['component_id'] ) ? $data['component_id'] : '';
		$query_type        = isset( $data['query_type'] ) ? $data['query_type'] : 'product_ids';
		$default_option_id = isset( $data['default_id'] ) ? $data['default_id'] : '';
		$product_ids       = isset( $data['assigned_ids'] ) ? $data['assigned_ids'] : array();
		$category_ids      = isset( $data['assigned_category_ids'] ) ? $data['assigned_category_ids'] : array();
		$tip               = __( 'The default, pre-selected Component Option. Must be populated when the <strong>Catalog Price</strong> display method is set to <strong>Use Defaults</strong>.', 'wpdrift-woocommerce-modules' );

		$default_option            = false;
		$default_option_valid      = false;
		$default_option_title      = '';
		$default_option_categories = array();

		if ( $default_option_id ) {
			$default_option       = self::get_component_option( $default_option_id );
			$default_option_title = Helpers::get_product_title( $default_option );
			$default_option_valid = $default_option && $default_option_title;

			if ( $default_option_valid ) {

				$default_option_categories = $default_option->get_category_ids();

				if ( 'category_ids' === $query_type ) {
					$default_option_valid = sizeof( array_intersect( $default_option_categories, $category_ids ) ) > 0;
				} else {
					$default_option_valid = in_array( $default_option_valid, $product_ids );
				}
			}
		}

		$selected_data = array(
			'default_option_id'           => $default_option_valid ? $default_option_id : 0,
			'default_option_category_ids' => $default_option_valid ? $default_option_categories : false,
		);

		?>
		<div class="component_default_selector default_selector_container" data-selected_data="<?php echo esc_attr( json_encode( $selected_data ) ); ?>">
			<div class="form-field">
				<label>
					<?php echo __( 'Default Option', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<div class="component_query_type_category_ids default_selector_wrapper">
					<select id="group_default_<?php echo $id; ?>" class="wc-product-search-lazy default_selector_categories" style="width: 75%;" name="bto_data[<?php echo $id; ?>][default_id_categories]" data-allow_clear="true" data-action="woocommerce_json_search_products_in_categories" data-limit="200" data-include="<?php echo esc_attr( implode( ',', $category_ids ) ); ?>" data-placeholder="<?php echo __( 'Search for a product&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
														 <?php

															if ( $default_option_valid ) {
																echo '<option value="' . $default_option_id . '" selected="selected">' . $default_option_title . '</option>';
															}

															?>
					</select>
					<?php echo wc_help_tip( $tip ) . self::add_error_tip(); ?>
				</div>
				<div class="component_query_type_product_ids default_selector_wrapper">
					<select id="group_default_<?php echo $id; ?>" class="wc-enhanced-select-lazy default_selector_products" style="width: 75%;" name="bto_data[<?php echo $id; ?>][default_id_products]" data-allow_clear="true" data-placeholder="<?php esc_attr_e( 'Choose a product&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
														 <?php

															if ( $default_option_valid ) {
																echo '<option value="' . $default_option_id . '" selected="selected">' . $default_option_title . '</option>';
															}

															?>
					</select>
					<?php echo wc_help_tip( $tip ) . self::add_error_tip(); ?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config min quantity option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_quantity_min( $id, $data, $product_id ) {

		$quantity_min = isset( $data['quantity_min'] ) ? $data['quantity_min'] : 1;

		?>
		<div class="group_quantity_min">
			<div class="form-field">
				<label for="group_quantity_min_<?php echo $id; ?>">
					<?php echo __( 'Min Quantity', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="number" class="group_quantity_min" name="bto_data[<?php echo $id; ?>][quantity_min]" id="group_quantity_min_<?php echo $id; ?>" value="<?php echo $quantity_min; ?>" placeholder="" step="1" min="0" />
				<?php echo wc_help_tip( __( 'Set a minimum quantity for the selected Component Option.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config max quantity option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_quantity_max( $id, $data, $product_id ) {

		$quantity_max = isset( $data['quantity_max'] ) ? $data['quantity_max'] : 1;

		?>
		<div class="group_quantity_max">
			<div class="form-field">
				<label for="group_quantity_max_<?php echo $id; ?>">
					<?php echo __( 'Max Quantity', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="number" class="group_quantity_max" name="bto_data[<?php echo $id; ?>][quantity_max]" id="group_quantity_max_<?php echo $id; ?>" value="<?php echo $quantity_max; ?>" placeholder="" step="1" min="0" />
				<?php echo wc_help_tip( __( 'Set a maximum quantity for the selected Component Option. Leave the field empty to allow an unlimited maximum quantity.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config optional option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_optional( $id, $data, $product_id ) {

		$optional = isset( $data['optional'] ) ? $data['optional'] : '';

		?>
		<div class="group_optional" >
			<div class="form-field">
				<label for="group_optional_<?php echo $id; ?>">
					<?php echo __( 'Optional', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox component_optional"<?php echo ( $optional === 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][optional]" <?php echo ( $optional === 'yes' ? ' value="1"' : '' ); ?> />
				<?php echo wc_help_tip( __( 'Controls whether a Component Option must be selected or not.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config Shipped Individually option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_shipped_individually( $id, $data, $product_id ) {

		$shipped_individually = isset( $data['shipped_individually'] ) ? $data['shipped_individually'] : '';

		?>
		<div class="group_shipped_individually">
			<div class="form-field">
				<label for="group_shipped_individually_<?php echo $id; ?>">
					<?php echo __( 'Shipped Individually', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $shipped_individually === 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][shipped_individually]" <?php echo ( $shipped_individually === 'yes' ? ' value="1"' : '' ); ?> />
				<?php echo wc_help_tip( __( 'Enable this option if the Component is <strong>not</strong> physically assembled or packaged within the Composite.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config Priced Individually option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_priced_individually( $id, $data, $product_id ) {

		$priced_individually = isset( $data['priced_individually'] ) ? $data['priced_individually'] : '';

		?>
		<div class="group_priced_individually">
			<div class="form-field">
				<label for="group_priced_individually_<?php echo $id; ?>">
					<?php echo __( 'Priced Individually', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( $priced_individually === 'yes' ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][priced_individually]" <?php echo ( $priced_individually === 'yes' ? ' value="1"' : '' ); ?> />
				<?php echo wc_help_tip( __( 'Enable this option if the included Component Options must maintain their individual prices.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * [component_config_options_multiple description]
	 * @param  [type] $id         [description]
	 * @param  [type] $data       [description]
	 * @param  [type] $product_id [description]
	 * @return [type]             [description]
	 */
	public static function component_config_options_multiple( $id, $data, $product_id ) {

		$multiple_options = isset( $data['multiple_options'] ) ? $data['multiple_options'] : '';
		?>
		<div class="group_multiple_options">
			<div class="form-field">
				<label for="group_multiple_options_<?php echo $id; ?>">
					<?php echo __( 'Multiple', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="checkbox" class="checkbox"<?php echo ( 'yes' === $multiple_options ? ' checked="checked"' : '' ); ?> name="bto_data[<?php echo $id; ?>][multiple_options]" <?php echo ( 'yes' === $multiple_options ? ' value="1"' : '' ); ?> />
				<?php echo wc_help_tip( __( 'Enable this option if the multiple items can be selected.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component "Option Prices" option.
	 *
	 * @since  3.12.0
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_display_prices( $id, $data, $product_id ) {

		$price_display_options = Component::get_price_display_options();
		$prices_display        = isset( $data['display_prices'] ) && in_array( $data['display_prices'], wp_list_pluck( $price_display_options, 'id' ) ) ? $data['display_prices'] : 'absolute';
		$help_tip              = '';

		?>
		<div class="component_display_prices">
			<div class="form-field">
				<label>
					<?php _e( 'Option Prices', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<select class="wc-enhanced-select-lazy" style="width: 75%" name="bto_data[<?php echo $id; ?>][display_prices]">
																									 <?php

																										foreach ( $price_display_options as $option_key => $option ) {

																											echo '<option ' . selected( $prices_display, $option['id'], false ) . ' value="' . $option['id'] . '">' . $option['title'] . '</option>';

																											$help_tip .= '<strong>' . $option['title'] . '</strong> &ndash; ' . $option['description'];

																											if ( $option_key < sizeof( $price_display_options ) - 1 ) {
																												$help_tip .= '</br></br>';
																											}
																										}

																										?>
				</select>
				<?php echo wc_help_tip( $help_tip ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component config discount option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_discount( $id, $data, $product_id ) {

		$discount = isset( $data['discount'] ) ? $data['discount'] : '';

		?>
		<div class="group_discount">
			<div class="form-field">
				<label for="group_discount_<?php echo $id; ?>">
					<?php echo __( 'Discount %', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<input type="text" class="group_discount input-text wc_input_decimal" name="bto_data[<?php echo $id; ?>][discount]" id="group_discount_<?php echo $id; ?>" value="<?php echo $discount; ?>" placeholder="" />
				<?php echo wc_help_tip( __( 'Discount to apply to the chosen Component Option.', 'wpdrift-woocommerce-modules' ) ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component "Options Style" option.
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_options_style( $id, $data, $product_id ) {

		?>
		<div class="component_options_style group_options_style">
			<div class="form-field">
				<label>
					<?php _e( 'Options Style', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<select class="options_style_selector wc-enhanced-select-lazy" name="bto_data[<?php echo $id; ?>][selection_mode]" style="width: 75%;">
					<?php
					   $option_style  = self::get_options_style( $data );
					   $option_styles = Component::get_options_styles();
					   $help_tip      = '';

					foreach ( Component::get_options_styles() as $style_key => $style ) {

						$supports             = new stdClass();
						$supports->pagination = Component::options_style_supports( $style['id'], 'pagination' ) ? 'yes' : 'no';

						echo '<option ' . selected( $option_style, $style['id'], false ) . ' value="' . $style['id'] . '" data-supports="' . esc_attr( json_encode( $supports ) ) . '">' . $style['title'] . '</option>';

						$help_tip .= '<strong>' . $style['title'] . '</strong> &ndash; ' . $style['description'];

						if ( $style_key < sizeof( $option_styles ) - 1 ) {
							$help_tip .= '</br></br>';
						}
					}
					?>
				</select>
				<?php echo wc_help_tip( $help_tip ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Add component "Pagination Style" option.
	 *
	 * @since  3.12.0
	 *
	 * @param  int    $id
	 * @param  array  $data
	 * @param  int    $product_id
	 * @return void
	 */
	public static function component_config_pagination_style( $id, $data, $product_id ) {

		$pagination_style_options = Component::get_pagination_style_options();
		$pagination_style         = isset( $data['pagination_style'] ) && in_array( $data['pagination_style'], wp_list_pluck( $pagination_style_options, 'id' ) ) ? $data['pagination_style'] : 'classic';
		$help_tip                 = '';

		?>
		<div class="component_pagination_style">
			<div class="form-field">
				<label>
					<?php _e( 'Options Pagination', 'wpdrift-woocommerce-modules' ); ?>
				</label>
				<select class="wc-enhanced-select-lazy" style="width: 75%" name="bto_data[<?php echo $id; ?>][pagination_style]">
																									 <?php

																										foreach ( $pagination_style_options as $option_key => $option ) {

																											echo '<option ' . selected( $pagination_style, $option['id'], false ) . ' value="' . $option['id'] . '">' . $option['title'] . '</option>';

																											$help_tip .= '<strong>' . $option['title'] . '</strong> &ndash; ' . $option['description'];

																											if ( $option_key < sizeof( $pagination_style_options ) - 1 ) {
																												$help_tip .= '</br></br>';
																											}
																										}

																										?>
				</select>
				<?php echo wc_help_tip( $help_tip ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Adds the Composite Product write panel tabs.
	 *
	 * @param  array  $tabs
	 * @return array
	 */
	public static function composite_product_data_tabs( $tabs ) {

		global $post, $product_object, $composite_product_object;

		/*
		 * Create a global composite-type object to use for populating fields.
		 */

		$post_id = $post->ID;

		if ( empty( $product_object ) || false === $product_object->is_type( 'composite' ) ) {
			$composite_product_object = $post_id ? new ProductComposite( $post_id ) : new ProductComposite();
		} else {
			$composite_product_object = $product_object;
		}

		$tabs['cp_components'] = array(
			'label'    => __( 'Components', 'wpdrift-woocommerce-modules' ),
			'target'   => 'bto_product_data',
			'class'    => array( 'show_if_composite', 'composite_product_options', 'bto_product_tab' ),
			'priority' => 48,
		);

		// $tabs['cp_scenarios'] = array(
		// 	'label'    => __( 'Scenarios', 'woocommerce-composite-products' ),
		// 	'target'   => 'bto_scenario_data',
		// 	'class'    => array( 'show_if_composite', 'composite_scenarios', 'bto_product_tab' ),
		// 	'priority' => 48,
		// );

		$tabs['inventory']['class'][] = 'show_if_composite';

		return $tabs;
	}

	/**
	 * Add Composited Products stock note.
	 *
	 * @return void
	 */
	public static function composite_stock_info() {
		?>
		<span class="composite_stock_msg show_if_composite">
				<?php echo wc_help_tip( __( 'By default, the sale of a product within a composite has the same effect on its stock as an individual sale. There are no separate inventory settings for composited items. However, managing stock at composite level can be very useful for allocating composite stock quota, or for keeping track of composited item sales.', 'wpdrift-woocommerce-modules' ) ); ?>
		</span>
		<?php
	}

	/**
	 * Sets global data used in component/scenario metaboxes.
	 *
	 * @param ProductComposite  $composite_product_object
	 */
	public static function set_global_object_data( $composite_product_object ) {

		global $composite_product_object_data;

		$composite_product_object_data = array();
		$merged_component_options      = array();

		$composite_data = $composite_product_object->get_composite_data( 'edit' );

		if ( ! empty( $composite_data ) ) {

			$composite_product_object_data['component_options_count'] = 0;

			foreach ( $composite_data as $component_id => $component_data ) {

				$component_options_cache_key = 'component_' . $component_id . '_options';
				$component_options           = Helpers::cache_get( $component_options_cache_key );

				if ( null === $component_options ) {
					$component_options = Component::query_component_options( $component_data );
					Helpers::cache_set( $component_options_cache_key, $component_options );
				}

				$merged_component_options = array_unique( array_merge( $merged_component_options, $component_options ) );
			}

			$composite_product_object_data['component_options_count']          = count( $merged_component_options );
			$composite_product_object_data['component_options_ajax_threshold'] = apply_filters( 'woocommerce_composite_admin_component_options_ajax_threshold', 200 );
		}
	}

	/**
	 * Components and Scenarios write panels.
	 *
	 * @return void
	 */
	public static function composite_data_panel() {

		global $composite_product_object;

		$composite_id   = $composite_product_object->get_id();
		$composite_data = $composite_product_object->get_composite_data( 'edit' );
		$scenarios_data = $composite_product_object->get_scenario_data( 'edit' );

		self::set_global_object_data( $composite_product_object );

		?>
		<div id="bto_product_data" class="bto_panel panel woocommerce_options_panel wc-metaboxes-wrapper">
			<div class="options_group_general">
				<?php
				/**
				 * Action 'woocommerce_composite_admin_options_html'.
				 *
				 * @since  3.14.0
				 *
				 * @param  ProductComposite  $composite_product_object
				 *
				 * @hooked {@see composite_options} - 10
				 */
				do_action( 'woocommerce_composite_admin_options_html', $composite_product_object );
				?>
			</div>
			<div class="options_group_components">
				<?php
				/**
				 * Action 'woocommerce_composite_admin_html'.
				 *
				 * @param  array   $composite_data
				 * @param  string  $composite_id
				 *
				 * @hooked {@see composite_component_options} - 10
				 */
				do_action( 'woocommerce_composite_admin_html', $composite_data, $composite_id );
				?>
			</div>

		</div>
		<div id="bto_scenario_data" class="bto_panel panel woocommerce_options_panel wc-metaboxes-wrapper">
			<div class="options_group scenarios_config_group bto_clearfix <?php echo empty( $scenarios_data ) ? 'options_group--boarding' : ''; ?>">

				<div id="bto_scenarios_inner">
				<?php

				if ( ! empty( $composite_data ) ) {

					?>
						<p class="toolbar">
							<span class="bulk_toggle_wrapper">
								<a href="#" class="close_all"><?php _e( 'Close all', 'wpdrift-woocommerce-modules' ); ?></a>
								<a href="#" class="expand_all"><?php _e( 'Expand all', 'wpdrift-woocommerce-modules' ); ?></a>
							</span>
						</p>

						<div class="bto_scenarios wc-metaboxes">
						<?php

						if ( ! empty( $scenarios_data ) ) {

							$i = 0;

							foreach ( $scenarios_data as $scenario_id => $scenario_data ) {

								$scenario_data['scenario_id'] = $scenario_id;

								/**
								 * Action 'woocommerce_composite_scenario_admin_html'.
								 *
								 * @param   int     $i
								 * @param   array   $scenario_data
								 * @param   array   $composite_data
								 * @param   string  $composite_id
								 * @param   string  $state
								 *
								 * @hooked  {@see scenario_admin_html} - 10
								 */
								do_action( 'woocommerce_composite_scenario_admin_html', $i, $scenario_data, $composite_data, $composite_id, 'closed' );

								$i++;
							}
						} else {

							?>
								<div class="bto_boarding__scenarios bto_scenarios__boarding--scenarios_empty">
									<div class="bto_boarding__scenarios__message">
										<h3><?php _e( 'Scenarios', 'wpdrift-woocommerce-modules' ); ?></h3>
										<p><?php _e( 'Use Scenarios to specify valid Composite configurations, and/or conditionally hide Components.', 'wpdrift-woocommerce-modules' ); ?>
										<br/><?php _e( 'Need assistance? <a href="https://docs.woocommerce.com/document/composite-products/composite-products-advanced-configuration/" target="_blank">Check out the documentation</a>, or <a href="https://woocommerce.com/my-account/marketplace-ticket-form/" target="_blank">get in touch with us</a>.', 'wpdrift-woocommerce-modules' ); ?>
										</p>
									</div>
								</div>
								<?php
						}

						?>
						</div>

						<p class="bto_action_button_wrapper bto_action_button_wrapper--add_scenario">
							<button type="button" class="button add_bto_scenario"><?php _e( 'Add Scenario', 'wpdrift-woocommerce-modules' ); ?></button>
						</p>
						<?php

				} else {

					?>
						<div class="bto_boarding__scenarios bto_scenarios__boarding--components_empty">
							<div class="bto_boarding__scenarios__message">
								<h3><?php _e( 'Scenarios', 'wpdrift-woocommerce-modules' ); ?></h3>
								<p><?php _e( 'First, <a href="https://docs.woocommerce.com/document/composite-products/composite-products-configuration/" target="_blank">create some Components</a> by navigating to the Components tab.', 'wpdrift-woocommerce-modules' ); ?>
								<br/><?php _e( 'Then, return here to <a href="https://docs.woocommerce.com/document/composite-products/composite-products-advanced-configuration/" target="_blank">define Scenarios</a>.', 'wpdrift-woocommerce-modules' ); ?>
								</p>
							</div>
						</div>
						<?php
				}

				?>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Product options for post-1.6.2 product data section.
	 *
	 * @param  array $options
	 * @return array
	 */
	public static function add_composite_type_options( $options ) {

		$options['virtual']['wrapper_class']      .= ' show_if_composite';
		$options['downloadable']['wrapper_class'] .= ' show_if_composite';

		return $options;
	}

	/**
	 * Process, verify and save bundle type product data.
	 *
	 * @param  WC_Product  $product
	 * @return void
	 */
	public static function process_composite_data( $product ) {

		if ( $product->is_type( 'composite' ) ) {

			$props = array(
				'sold_individually'         => false,
				'sold_individually_context' => 'product',
			);

			/*
			 * "Form location" option.
			 */

			if ( ! empty( $_POST['_bto_add_to_cart_form_location'] ) ) {

				$form_location = wc_clean( $_POST['_bto_add_to_cart_form_location'] );

				if ( in_array( $form_location, array_keys( ProductComposite::get_add_to_cart_form_location_options() ) ) ) {
					$props['add_to_cart_form_location'] = $form_location;
				}
			}

			/*
			 * Extended "Sold Individually" option.
			 */

			if ( ! empty( $_POST['_bto_sold_individually'] ) ) {

				$sold_individually_context = wc_clean( $_POST['_bto_sold_individually'] );

				if ( in_array( $sold_individually_context, array( 'product', 'configuration' ) ) ) {
					$props['sold_individually']         = true;
					$props['sold_individually_context'] = $sold_individually_context;
				}
			}

			/*
			 * Components and Scenarios tabs.
			 */

			if ( ! defined( 'UPDATING' ) ) {

				$props = array_merge( $props, self::process_posted_composite_configuration( $product ) );

				$product->set( $props );

			} else {
				self::add_notice( __( 'Your changes have not been saved &ndash; please wait for the <strong>WooCommerce Composite Products Data Update</strong> routine to complete before creating new composite products or making changes to existing ones.', 'wpdrift-woocommerce-modules' ), 'error' );
			}

			// Clear dismissible welcome notice.
			AdminNotices::remove_dismissible_notice( 'welcome' );
		}
	}

	/**
	 * Add a notice if calculating min/max catalog price in the background.
	 *
	 * @since  4.0.0
	 *
	 * @return void
	 */
	public static function maybe_add_catalog_price_notice() {

		global $post_id;

		// Get admin screen ID.
		$screen    = get_current_screen();
		$screen_id = $screen ? $screen->id : '';

		if ( 'product' !== $screen_id ) {
			return;
		}

		$product_type = WC_Product_Factory::get_product_type( $post_id );

		if ( 'composite' !== $product_type ) {
			return;
		}

		$product = wc_get_product( $post_id );

		if ( ! $product ) {
			return;
		}

		$shop_price_calc_notice = '';
		$shop_price_calc_status = $product->get_shop_price_calc_status( 'edit' );

		if ( 'pending' === $shop_price_calc_status ) {
			$shop_price_calc_notice = sprintf( __( 'The catalog price of "%s" is currently being calculated in the background. During this time, its price will be hidden.', 'wpdrift-woocommerce-modules' ), get_the_title( $post_id ) );
		} elseif ( 'failed' === $shop_price_calc_status ) {
			$shop_price_calc_notice = sprintf( __( 'The catalog price of "%s" could not be calculated within the default time limit. This may happen when adding Scenarios to Composite Products that contain many Components and a large number of product/variation options. For assistance, please check out the <a href="https://docs.woocommerce.com/document/composite-products/composite-products-configuration/#catalog-price" target="_blank">documentation</a>, or <a href="https://woocommerce.com/my-account/marketplace-ticket-form/" target="_blank">get in touch with us</a>.', 'wpdrift-woocommerce-modules' ), get_the_title( $post_id ) );
		}

		if ( $shop_price_calc_notice ) {
			AdminNotices::add_notice( $shop_price_calc_notice, 'warning', false );
		}
	}

	/**
	 * Save composite configuration: Components and Scenarios tab fields.
	 *
	 * @param  int    $composite_id
	 * @param  array  $posted_composite_data
	 * @return array
	 */
	public static function save_configuration( $composite_id, $posted_composite_data ) {

		$product = new ProductComposite( $composite_id );

		if ( $product ) {

			$props = self::process_posted_composite_configuration( $product, $posted_composite_data );

			$product->set( $props );
			$product->save();
		}
	}

	/**
	 * Save components and scenarios.
	 *
	 * @param  ProductComposite  $product
	 * @param  array                 $posted_composite_data
	 * @return array
	 */
	public static function process_posted_composite_configuration( $product, $posted_composite_data = array() ) {

		$composite_id = $product->get_id();
		$props        = array(
			'layout'           => 'single',
			'shop_price_calc'  => 'defaults',
			'editable_in_cart' => false,
			'composite_data'   => array(),
			'scenario_data'    => array(),
		);

		if ( empty( $posted_composite_data ) ) {
			$posted_composite_data = $_POST;
		}

		/*
		 * "Layout" option.
		 */

		if ( isset( $posted_composite_data['bto_style'] ) ) {
			$props['layout'] = wc_clean( $posted_composite_data['bto_style'] );
		}

		/*
		 * "Catalog Price" option.
		 */

		if ( isset( $posted_composite_data['_bto_shop_price_calc'] ) ) {
			$props['shop_price_calc'] = wc_clean( $posted_composite_data['_bto_shop_price_calc'] );
		}

		/*
		 * "Edit in cart" option.
		 */

		if ( ! empty( $posted_composite_data['_bto_edit_in_cart'] ) ) {
			$props['editable_in_cart'] = true;
		}

		/*
		 * Components and Scenarios.
		 */

		$untitled_component_exists           = false;
		$zero_product_item_exists            = false;
		$untitled_scenario_exists            = false;
		$undefined_matching_conditions_exist = false;
		$individually_priced_options_count   = 0;
		$composite_data                      = array();
		$component_options                   = array();

		if ( isset( $posted_composite_data['bto_data'] ) ) {

			/*--------------------------*/
			/*  Components.             */
			/*--------------------------*/

			$counter  = 0;
			$ordering = array();

			foreach ( $posted_composite_data['bto_data'] as $row_id => $post_data ) {

				$bto_ids     = isset( $post_data['assigned_ids'] ) ? $post_data['assigned_ids'] : '';
				$bto_cat_ids = isset( $post_data['assigned_category_ids'] ) ? $post_data['assigned_category_ids'] : '';

				$group_id = isset( $post_data['group_id'] ) ? wp_unslash( $post_data['group_id'] ) : ( current_time( 'timestamp' ) + $counter );
				$counter++;

				$composite_data[ $group_id ] = array();

				/*
				 * Save query type.
				 */

				if ( isset( $post_data['query_type'] ) && ! empty( $post_data['query_type'] ) ) {
					$composite_data[ $group_id ]['query_type'] = wp_unslash( $post_data['query_type'] );
				} else {
					$composite_data[ $group_id ]['query_type'] = 'product_ids';
				}

				if ( ! empty( $bto_ids ) ) {

					// Convert select2 v3/4 data.
					if ( is_array( $bto_ids ) ) {
						$bto_ids = array_map( 'intval', $post_data['assigned_ids'] );
					} else {
						$bto_ids = array_filter( array_map( 'intval', explode( ',', $post_data['assigned_ids'] ) ) );
					}

					foreach ( $bto_ids as $key => $id ) {

						$composited_product = wc_get_product( $id );

						if ( $composited_product && in_array( $composited_product->get_type(), ProductComposite::get_supported_component_option_types() ) ) {

							$error = apply_filters( 'woocommerce_composite_products_custom_type_save_error', false, $id );

							if ( $error ) {
								self::add_notice( $error, 'error' );
								continue;
							}

							// Save assigned IDs.
							$composite_data[ $group_id ]['assigned_ids'][] = $id;
						}
					}

					if ( ! empty( $composite_data[ $group_id ]['assigned_ids'] ) ) {
						$composite_data[ $group_id ]['assigned_ids'] = array_unique( $composite_data[ $group_id ]['assigned_ids'] );
					}
				}

				if ( ! empty( $bto_cat_ids ) ) {
					$bto_cat_ids = array_map( 'absint', $post_data['assigned_category_ids'] );
					$composite_data[ $group_id ]['assigned_category_ids'] = array_values( $bto_cat_ids );
				}

				// True if no products were added.
				if ( ( $composite_data[ $group_id ]['query_type'] === 'product_ids' && empty( $composite_data[ $group_id ]['assigned_ids'] ) ) || ( $composite_data[ $group_id ]['query_type'] === 'category_ids' && empty( $composite_data[ $group_id ]['assigned_category_ids'] ) ) ) {

					unset( $composite_data[ $group_id ] );
					$zero_product_item_exists = true;
					continue;
				}

				// Run query to get component option IDs.
				$component_options[ $group_id ] = Component::query_component_options( $composite_data[ $group_id ] );

				/*
				 * Save selection style.
				 */

				$component_options_style = 'dropdowns';

				if ( isset( $post_data['selection_mode'] ) ) {
					$component_options_style = wc_clean( $post_data['selection_mode'] );
				}

				$composite_data[ $group_id ]['selection_mode'] = $component_options_style;

				/*
				 * Save default option.
				 */

				$composite_data[ $group_id ]['default_id'] = '';

				if ( ! empty( $component_options[ $group_id ] ) ) {

					$default_id_key = 'product_ids' === $composite_data[ $group_id ]['query_type'] ? 'default_id_products' : 'default_id_categories';

					if ( ! empty( $post_data[ $default_id_key ] ) && in_array( $post_data[ $default_id_key ], $component_options[ $group_id ] ) ) {
						$composite_data[ $group_id ]['default_id'] = wc_clean( $post_data[ $default_id_key ] );
					}

					// Some extra work for mandatory components...
					if ( ! isset( $post_data['optional'] ) ) {

						if ( '' === $composite_data[ $group_id ]['default_id'] ) {
							/*
							 * A default must be set if:
							 * - There is only 1 component option.
							 * - "Catalog Price" is set to "Use defaults".
							 */
							if ( count( $component_options[ $group_id ] ) === 1 || 'defaults' === $props['shop_price_calc'] ) {
								$composite_data[ $group_id ]['default_id'] = $component_options[ $group_id ][0];
							}
						}
					}
				}

				/*
				 * Save title.
				 */

				if ( ! empty( $post_data['title'] ) ) {

					$composite_data[ $group_id ]['title'] = strip_tags( wp_unslash( $post_data['title'] ) );

				} else {

					$untitled_component_exists = true;

					$composite_data[ $group_id ]['title'] = __( 'Untitled Component', 'wpdrift-woocommerce-modules' );

					if ( isset( $posted_composite_data['post_status'] ) && $posted_composite_data['post_status'] === 'publish' ) {
						$props['status'] = 'draft';
					}
				}

				/*
				 * Save pagination style.
				 * ...and show an unpaginated selections style notice.
				 */

				if ( ! Component::options_style_supports( $component_options_style, 'pagination' ) ) {

					$unpaginated_options_count = count( $component_options[ $group_id ] );

					if ( $unpaginated_options_count > 30 ) {
						$dropdowns_prompt = sprintf( __( 'You have added %1$s Component Options to "%2$s". To reduce the load on your server, it is recommended to use the <strong>Product Thumbnails</strong> Options Style, which supports pagination.', 'wpdrift-woocommerce-modules' ), $unpaginated_options_count, strip_tags( wp_unslash( $post_data['title'] ) ) );
						self::add_notice( $dropdowns_prompt, 'warning' );
					}
				} else {

					if ( isset( $post_data['pagination_style'] ) && in_array( $post_data['pagination_style'], wp_list_pluck( Component::get_pagination_style_options(), 'id' ) ) && ! in_array( $props['layout'], array( 'single', 'progressive' ) ) ) {
						$composite_data[ $group_id ]['pagination_style'] = wc_clean( $post_data['pagination_style'] );
					} else {
						$composite_data[ $group_id ]['pagination_style'] = 'classic';
					}
				}

				/*
				 * Save description.
				 */

				if ( ! empty( $post_data['description'] ) ) {
					$composite_data[ $group_id ]['description'] = wp_kses_post( wp_unslash( $post_data['description'] ) );
				} else {
					$composite_data[ $group_id ]['description'] = '';
				}

				/*
				 * Save image.
				 */

				if ( ! empty( $post_data['thumbnail_id'] ) ) {
					$composite_data[ $group_id ]['thumbnail_id'] = wc_clean( $post_data['thumbnail_id'] );
				} else {
					$composite_data[ $group_id ]['thumbnail_id'] = '';
				}

				/*
				 * Save min quantity data.
				 */

				if ( isset( $post_data['quantity_min'] ) && is_numeric( $post_data['quantity_min'] ) ) {

					$quantity_min = absint( $post_data['quantity_min'] );

					if ( $quantity_min >= 0 ) {
						$composite_data[ $group_id ]['quantity_min'] = $quantity_min;
					} else {
						$composite_data[ $group_id ]['quantity_min'] = 1;

						$error = sprintf( __( 'The <strong>Min Quantity</strong> entered for "%s" was not valid and has been reset. Please enter a non-negative integer value.', 'wpdrift-woocommerce-modules' ), strip_tags( wp_unslash( $post_data['title'] ) ) );
						self::add_notice( $error, 'error' );
					}
				} else {
					// If its not there, it means the product was just added.
					$composite_data[ $group_id ]['quantity_min'] = 1;

					$error = sprintf( __( 'The <strong>Min Quantity</strong> entered for "%s" was not valid and has been reset. Please enter a non-negative integer value.', 'wpdrift-woocommerce-modules' ), strip_tags( wp_unslash( $post_data['title'] ) ) );
					self::add_notice( $error, 'error' );
				}

				$quantity_min = $composite_data[ $group_id ]['quantity_min'];

				/*
				 * Save max quantity data.
				 */

				if ( isset( $post_data['quantity_max'] ) && ( is_numeric( $post_data['quantity_max'] ) || $post_data['quantity_max'] === '' ) ) {

					$quantity_max = $post_data['quantity_max'] !== '' ? absint( $post_data['quantity_max'] ) : '';

					if ( $quantity_max === '' || ( $quantity_max > 0 && $quantity_max >= $quantity_min ) ) {
						$composite_data[ $group_id ]['quantity_max'] = $quantity_max;
					} else {
						$composite_data[ $group_id ]['quantity_max'] = 1;

						$error = sprintf( __( 'The <strong>Max Quantity</strong> you entered for "%s" was not valid and has been reset. Please enter a positive integer value greater than (or equal to) <strong>Min Quantity</strong>, or leave the field empty.', 'wpdrift-woocommerce-modules' ), strip_tags( wp_unslash( $post_data['title'] ) ) );
						self::add_notice( $error, 'error' );
					}
				} else {
					// If its not there, it means the product was just added.
					$composite_data[ $group_id ]['quantity_max'] = 1;

					$error = sprintf( __( 'The <strong>Max Quantity</strong> you entered for "%s" was not valid and has been reset. Please enter a positive integer value greater than (or equal to) <strong>Min Quantity</strong>, or leave the field empty.', 'wpdrift-woocommerce-modules' ), strip_tags( wp_unslash( $post_data['title'] ) ) );
					self::add_notice( $error, 'error' );
				}

				/*
				 * Save discount data.
				 */

				if ( isset( $post_data['discount'] ) ) {

					if ( is_numeric( $post_data['discount'] ) ) {

						$discount = wc_format_decimal( $post_data['discount'] );

						if ( $discount < 0 || $discount > 100 ) {

							$error = sprintf( __( 'The <strong>Discount</strong> value you entered for "%s" was not valid and has been reset. Please enter a positive number between 0-100.', 'wpdrift-woocommerce-modules' ), strip_tags( wp_unslash( $post_data['title'] ) ) );
							self::add_notice( $error, 'error' );

							$composite_data[ $group_id ]['discount'] = '';

						} else {
							$composite_data[ $group_id ]['discount'] = $discount;
						}
					} else {
						$composite_data[ $group_id ]['discount'] = '';
					}
				} else {
					$composite_data[ $group_id ]['discount'] = '';
				}

				/*
				 * Save priced-individually data.
				 */

				if ( isset( $post_data['priced_individually'] ) ) {
					$composite_data[ $group_id ]['priced_individually'] = 'yes';
					// Add up options.
					$individually_priced_options_count += count( $component_options[ $group_id ] );
				} else {
					$composite_data[ $group_id ]['priced_individually'] = 'no';
				}

				/*
				 * Save multiple-selections data.
				 */
				if ( isset( $post_data['multiple_options'] ) ) {
					$composite_data[ $group_id ]['multiple_options'] = 'yes';
				} else {
					$composite_data[ $group_id ]['multiple_options'] = 'no';
				}

				/*
				 * Save priced-individually data.
				 */

				if ( isset( $post_data['shipped_individually'] ) ) {
					$composite_data[ $group_id ]['shipped_individually'] = 'yes';
				} else {
					$composite_data[ $group_id ]['shipped_individually'] = 'no';
				}

				/*
				 * Save optional data.
				 */

				if ( isset( $post_data['optional'] ) ) {
					$composite_data[ $group_id ]['optional'] = 'yes';
				} else {
					$composite_data[ $group_id ]['optional'] = 'no';
				}

				/*
				 * Save price display format.
				 */

				if ( isset( $post_data['display_prices'] ) && in_array( $post_data['display_prices'], wp_list_pluck( Component::get_price_display_options(), 'id' ) ) ) {
					$composite_data[ $group_id ]['display_prices'] = wc_clean( $post_data['display_prices'] );
				} else {
					$composite_data[ $group_id ]['display_prices'] = 'absolute';
				}

				/*
				 * Save select action.
				 */

				if ( isset( $post_data['select_action'] ) && in_array( $post_data['select_action'], wp_list_pluck( Component::get_select_action_options(), 'id' ) ) && 'single' !== $props['layout'] ) {
					$composite_data[ $group_id ]['select_action'] = wc_clean( $post_data['select_action'] );
				} else {
					$composite_data[ $group_id ]['select_action'] = 'view';
				}

				/*
				 * Save product title visiblity data.
				 */

				if ( isset( $post_data['show_product_title'] ) ) {
					$composite_data[ $group_id ]['hide_product_title'] = 'no';
				} else {
					$composite_data[ $group_id ]['hide_product_title'] = 'yes';
				}

				/*
				 * Save product description visiblity data.
				 */

				if ( isset( $post_data['show_product_description'] ) ) {
					$composite_data[ $group_id ]['hide_product_description'] = 'no';
				} else {
					$composite_data[ $group_id ]['hide_product_description'] = 'yes';
				}

				/*
				 * Save product thumbnail visiblity data.
				 */

				if ( isset( $post_data['show_product_thumbnail'] ) ) {
					$composite_data[ $group_id ]['hide_product_thumbnail'] = 'no';
				} else {
					$composite_data[ $group_id ]['hide_product_thumbnail'] = 'yes';
				}

				/*
				 * Save product price visibility data.
				 */

				if ( isset( $post_data['show_product_price'] ) ) {
					$composite_data[ $group_id ]['hide_product_price'] = 'no';
				} else {
					$composite_data[ $group_id ]['hide_product_price'] = 'yes';
				}

				/*
				 * Save component subtotal visibility data.
				 */

				if ( isset( $post_data['show_subtotal_product'] ) ) {
					$composite_data[ $group_id ]['hide_subtotal_product'] = 'no';
				} else {
					$composite_data[ $group_id ]['hide_subtotal_product'] = 'yes';
				}

				/*
				 * Save component subtotal visibility data.
				 */

				if ( isset( $post_data['show_subtotal_cart'] ) ) {
					$composite_data[ $group_id ]['hide_subtotal_cart'] = 'no';
				} else {
					$composite_data[ $group_id ]['hide_subtotal_cart'] = 'yes';
				}

				/*
				 * Save component subtotal visibility data.
				 */

				if ( isset( $post_data['show_subtotal_orders'] ) ) {
					$composite_data[ $group_id ]['hide_subtotal_orders'] = 'no';
				} else {
					$composite_data[ $group_id ]['hide_subtotal_orders'] = 'yes';
				}

				/*
				 * Save show orderby data.
				 */

				if ( isset( $post_data['show_orderby'] ) ) {
					$composite_data[ $group_id ]['show_orderby'] = 'yes';
				} else {
					$composite_data[ $group_id ]['show_orderby'] = 'no';
				}

				/*
				 * Save show filters data.
				 */

				if ( isset( $post_data['show_filters'] ) ) {
					$composite_data[ $group_id ]['show_filters'] = 'yes';
				} else {
					$composite_data[ $group_id ]['show_filters'] = 'no';
				}

				/*
				 * Save filters.
				 */

				if ( ! empty( $post_data['attribute_filters'] ) ) {
					$attribute_filter_ids                             = array_map( 'absint', $post_data['attribute_filters'] );
					$composite_data[ $group_id ]['attribute_filters'] = array_values( $attribute_filter_ids );
				}

				/*
				 * Prepare position data.
				 */

				if ( isset( $post_data['position'] ) ) {
					$ordering[ (int) $post_data['position'] ] = $group_id;
				} else {
					$ordering[ count( $ordering ) ] = $group_id;
				}

				/**
				 * Filter the component data before saving. Add custom errors via 'add_notice()'.
				 *
				 * @param  array   $component_data
				 * @param  array   $post_data
				 * @param  string  $component_id
				 * @param  string  $composite_id
				 */
				$composite_data[ $group_id ] = apply_filters( 'woocommerce_composite_process_component_data', $composite_data[ $group_id ], $post_data, $group_id, $composite_id );
			}

			ksort( $ordering );
			$ordered_composite_data = array();
			$ordering_loop          = 0;

			foreach ( $ordering as $group_id ) {
				$ordered_composite_data[ $group_id ]             = $composite_data[ $group_id ];
				$ordered_composite_data[ $group_id ]['position'] = $ordering_loop;
				$ordering_loop++;
			}

			/*--------------------------*/
			/*  Scenarios.              */
			/*--------------------------*/

			// Convert posted data coming from select2 v3/4 ajax inputs.
			$compat_scenario_data = array();

			if ( isset( $posted_composite_data['bto_scenario_data'] ) ) {
				foreach ( $posted_composite_data['bto_scenario_data'] as $scenario_id => $scenario_post_data ) {

					$compat_scenario_data[ $scenario_id ] = $scenario_post_data;

					if ( isset( $scenario_post_data['component_data'] ) ) {
						foreach ( $scenario_post_data['component_data'] as $component_id => $products_in_scenario ) {

							if ( ! empty( $products_in_scenario ) ) {
								if ( is_array( $products_in_scenario ) ) {
									$compat_scenario_data[ $scenario_id ]['component_data'][ $component_id ] = array_unique( array_map( 'intval', $products_in_scenario ) );
								} else {
									$compat_scenario_data[ $scenario_id ]['component_data'][ $component_id ] = array_unique( array_map( 'intval', explode( ',', $products_in_scenario ) ) );
								}
							} else {
								$compat_scenario_data[ $scenario_id ]['component_data'][ $component_id ] = array();
							}
						}
					} else {
						$compat_scenario_data[ $scenario_id ]['component_data'] = array();
					}
				}

				$posted_composite_data['bto_scenario_data'] = $compat_scenario_data;
			}
			// End conversion.

			// Start processing.
			$current_scenario_data      = $product->get_scenario_data( 'edit' );
			$ordered_scenario_data      = array();
			$scenario_data              = array();
			$component_options_data     = array();
			$compat_group_actions_exist = false;

			if ( isset( $posted_composite_data['bto_scenario_data'] ) ) {

				$composite_data_store = WC_Data_Store::load( 'product-composite' );

				foreach ( $component_options as $component_id => $options ) {
					$component_options_data[ $component_id ] = $composite_data_store->get_expanded_component_options( $options, 'all' );
				}

				$counter           = 0;
				$scenario_ordering = array();

				foreach ( $posted_composite_data['bto_scenario_data'] as $scenario_post_data ) {

					$copied_from_current = false;

					// Scenario already saved in the past?
					if ( isset( $scenario_post_data['scenario_id'] ) ) {

						$scenario_id = wp_unslash( $scenario_post_data['scenario_id'] );

						// No fields posted?
						if ( ! isset( $scenario_post_data['dirty'] ) && isset( $current_scenario_data[ $scenario_id ] ) ) {

							$scenario_data[ $scenario_id ] = $current_scenario_data[ $scenario_id ];

							$copied_from_current = true;
						}
					} else {

						$scenario_id = current_time( 'timestamp' ) + $counter;

						$scenario_data[ $scenario_id ] = array();

						$counter++;
					}

					/*
					 * Prepare position data.
					 */

					if ( isset( $scenario_post_data['position'] ) ) {
						$scenario_ordering[ (int) $scenario_post_data['position'] ] = $scenario_id;
					} else {
						$scenario_ordering[ count( $scenario_ordering ) ] = $scenario_id;
					}

					if ( $copied_from_current ) {
						continue;
					}

					/*
					 * Save scenario title.
					 */

					if ( isset( $scenario_post_data['title'] ) && ! empty( $scenario_post_data['title'] ) ) {
						$scenario_data[ $scenario_id ]['title'] = strip_tags( wp_unslash( $scenario_post_data['title'] ) );
					} else {

						$untitled_scenario_exists = true;

						$scenario_data[ $scenario_id ]['title'] = __( 'Untitled Scenario', 'wpdrift-woocommerce-modules' );
					}

					/*
					 * Save scenario description.
					 */

					if ( isset( $scenario_post_data['description'] ) && ! empty( $scenario_post_data['description'] ) ) {
						$scenario_data[ $scenario_id ]['description'] = wp_kses_post( wp_unslash( $scenario_post_data['description'] ) );
					} else {
						$scenario_data[ $scenario_id ]['description'] = '';
					}

					/*
					 * Save scenario action(s).
					 */

					$scenario_data[ $scenario_id ]['scenario_actions'] = array();

					// "Dependency Group" action.
					if ( isset( $scenario_post_data['scenario_actions']['compat_group'] ) ) {
						if ( ! empty( $scenario_post_data['scenario_actions']['compat_group']['is_active'] ) ) {
							$scenario_data[ $scenario_id ]['scenario_actions']['compat_group']['is_active'] = 'yes';
							$compat_group_actions_exist = true;
						}
					} else {
						$scenario_data[ $scenario_id ]['scenario_actions']['compat_group']['is_active'] = 'no';
					}

					// "Hide Components" action.
					if ( isset( $scenario_post_data['scenario_actions']['conditional_components'] ) ) {
						if ( ! empty( $scenario_post_data['scenario_actions']['conditional_components']['is_active'] ) ) {
							$scenario_data[ $scenario_id ]['scenario_actions']['conditional_components']['is_active']         = 'yes';
							$scenario_data[ $scenario_id ]['scenario_actions']['conditional_components']['hidden_components'] = ! empty( $scenario_post_data['scenario_actions']['conditional_components']['hidden_components'] ) ? $scenario_post_data['scenario_actions']['conditional_components']['hidden_components'] : array();
						}
					} else {
						$scenario_data[ $scenario_id ]['scenario_actions']['conditional_components']['is_active'] = 'no';
					}

					/*
					 * Save component options in scenario.
					 */

					$scenario_data[ $scenario_id ]['component_data'] = array();

					$all_masked = true;

					foreach ( $ordered_composite_data as $group_id => $group_data ) {

						if ( empty( $scenario_post_data['match_component'][ $group_id ] ) ) {

							$scenario_data[ $scenario_id ]['modifier'][ $group_id ] = 'masked';

						} else {

							$all_masked = false;

							$scenario_data[ $scenario_id ]['modifier'][ $group_id ] = 'in';

							if ( isset( $scenario_post_data['modifier'][ $group_id ] ) && $scenario_post_data['modifier'][ $group_id ] === 'not-in' ) {

								if ( ! empty( $scenario_post_data['component_data'][ $group_id ] ) && ! Helpers::in_array_key( $scenario_post_data['component_data'], $group_id, 0 ) ) {
									$scenario_data[ $scenario_id ]['modifier'][ $group_id ] = 'not-in';
								}
							}
						}

						if ( ! empty( $scenario_post_data['component_data'][ $group_id ] ) ) {

							$scenario_data[ $scenario_id ]['component_data'][ $group_id ] = array();

							if ( isset( $scenario_post_data['component_data'] ) && Helpers::in_array_key( $scenario_post_data['component_data'], $group_id, 0 ) ) {
								$scenario_data[ $scenario_id ]['component_data'][ $group_id ][] = 0;
								continue;
							}

							if ( isset( $scenario_post_data['component_data'] ) && Helpers::in_array_key( $scenario_post_data['component_data'], $group_id, -1 ) ) {
								$scenario_data[ $scenario_id ]['component_data'][ $group_id ][] = -1;
							}

							foreach ( $scenario_post_data['component_data'][ $group_id ] as $id_in_scenario ) {

								if ( (int) $id_in_scenario === -1 || (int) $id_in_scenario === 0 ) {
									continue;
								}

								$parent_id = isset( $component_options_data[ $group_id ]['mapped'][ $id_in_scenario ] ) ? $component_options_data[ $group_id ]['mapped'][ $id_in_scenario ] : false;

								if ( $parent_id ) {

									if ( ! in_array( $parent_id, $scenario_post_data['component_data'][ $group_id ] ) ) {
										$scenario_data[ $scenario_id ]['component_data'][ $group_id ][] = $id_in_scenario;
									}
								} elseif ( in_array( $id_in_scenario, $component_options[ $group_id ] ) ) {
									$scenario_data[ $scenario_id ]['component_data'][ $group_id ][] = $id_in_scenario;
								}
							}
						} else {
							$scenario_data[ $scenario_id ]['component_data'][ $group_id ] = array( 0 );
						}
					}

					if ( $all_masked ) {

						unset( $scenario_data[ $scenario_id ] );

						$undefined_matching_conditions_exist = true;

						continue;
					}

					/**
					 * Filter the scenario data before saving. Add custom errors via 'add_notice()'.
					 *
					 * @param  array   $scenario_data
					 * @param  array   $post_data
					 * @param  string  $scenario_id
					 * @param  array   $composite_data
					 * @param  string  $composite_id
					 */
					$scenario_data[ $scenario_id ] = apply_filters( 'woocommerce_composite_process_scenario_data', $scenario_data[ $scenario_id ], $scenario_post_data, $scenario_id, $ordered_composite_data, $composite_id );
				}

				/*
				 * Re-order and save position data.
				 */

				ksort( $scenario_ordering );

				$ordering_loop = 0;

				foreach ( $scenario_ordering as $scenario_id ) {

					if ( ! isset( $scenario_data[ $scenario_id ] ) ) {
						continue;
					}

					$ordered_scenario_data[ $scenario_id ]             = $scenario_data[ $scenario_id ];
					$ordered_scenario_data[ $scenario_id ]['position'] = $ordering_loop;
					$ordering_loop++;
				}
			}

			/*
			 * Verify defaults.
			 */

			if ( ! empty( $ordered_scenario_data ) ) {

				// Stacked layout notice.
				if ( 'single' === $props['layout'] && $compat_group_actions_exist ) {
					$info = __( 'For a more streamlined user experience in applications that involve Scenarios and dependent Component Options, it is recommended to choose the <strong>Progressive</strong>, <strong>Stepped</strong> or <strong>Componentized</strong> layout.', 'wpdrift-woocommerce-modules' );
					self::add_notice( $info, 'info' );
				}

				$default_configuration = array();
				$optional_components   = array();

				foreach ( $ordered_composite_data as $component_id => $component_data ) {

					if ( '' !== $component_data['default_id'] ) {
						$default_configuration[ $component_id ] = array(
							'product_id'   => $component_data['default_id'],
							'variation_id' => 'any',
						);
					}

					if ( 'yes' === $component_data['optional'] ) {
						$optional_components[] = $component_id;
					}
				}

				$scenarios_manager = new ScenariosManager(
					array(
						'scenario_data'       => $ordered_scenario_data,
						'optional_components' => $optional_components,
					)
				);

				// Validate defaults.
				$validation_result = $scenarios_manager->validate_configuration( $default_configuration, array( 'validating_defaults' => true ) );

				if ( is_wp_error( $validation_result ) ) {

					$error_code = $validation_result->get_error_code();

					if ( in_array( $error_code, array( 'woocommerce_composite_configuration_selection_required', 'woocommerce_composite_configuration_selection_invalid' ) ) ) {

						$error_data = $validation_result->get_error_data( $error_code );

						if ( ! empty( $error_data['component_id'] ) ) {
							$error = sprintf( __( 'The <strong>Default Option</strong> chosen for &quot;%s&quot; was not found in any Scenario. Please double-check your preferences before saving, and always save any changes made to Component Options before choosing new defaults.', 'wpdrift-woocommerce-modules' ), strip_tags( $ordered_composite_data[ $error_data['component_id'] ]['title'] ) );
							self::add_notice( $error, 'error' );
						}
					} elseif ( 'woocommerce_composite_configuration_invalid' === $error_code ) {
						$error = __( 'The chosen combination of <strong>Default Options</strong> does not match with any Scenario. Please double-check your preferences before saving, and always save any changes made to Component Options before choosing new defaults.', 'wpdrift-woocommerce-modules' );
						self::add_notice( $error, 'error' );
					}
				}
			}

			/*
			 * Save config.
			 */

			$props['composite_data'] = $ordered_composite_data;
			$props['scenario_data']  = $ordered_scenario_data;
		}

		if ( ! isset( $posted_composite_data['bto_data'] ) || count( $composite_data ) == 0 ) {

			self::add_notice( __( 'Add at least one <strong>Component</strong> before saving. To add a Component, go to the <strong>Components</strong> tab and click <strong>Add Component</strong>.', 'wpdrift-woocommerce-modules' ), 'error' );

			if ( isset( $posted_composite_data['post_status'] ) && $posted_composite_data['post_status'] === 'publish' ) {
				$props['status'] = 'draft';
			}
		}

		if ( $untitled_component_exists ) {
			self::add_notice( __( 'Please give a valid <strong>Name</strong> to all Components before saving.', 'wpdrift-woocommerce-modules' ), 'error' );
		}

		if ( $zero_product_item_exists ) {
			self::add_notice( __( 'Add at least one valid <strong>Component Option</strong> to each Component. Component Options can be added by selecting products individually, or by choosing product categories.', 'wpdrift-woocommerce-modules' ), 'error' );
		}

		if ( $untitled_scenario_exists ) {
			self::add_notice( __( 'Please give a valid <strong>Name</strong> to all Scenarios before saving.', 'wpdrift-woocommerce-modules' ), 'error' );
		}

		if ( $undefined_matching_conditions_exist ) {
			self::add_notice( __( 'Some Scenarios could not be saved. Please define matching conditions for at least one Component in the <strong>Configuration</strong> section of each Scenario before saving.', 'wpdrift-woocommerce-modules' ), 'error' );
		}

		return $props;
	}

	/**
	 * Add custom save notices via filters.
	 *
	 * @param string  $content
	 * @param string  $type
	 */
	public static function add_notice( $content, $type ) {
		AdminNotices::add_notice( $content, $type, true );
		self::$ajax_notices[] = strip_tags( html_entity_decode( $content ) );
	}

	/**
	 * Get (cached) product by ID.
	 *
	 * @since  3.14.0
	 *
	 * @param  int   $product_id
	 * @param  bool  $expanded
	 * @return false|WC_Product
	 */
	protected static function get_component_option( $component_option_id, $expanded = false ) {

		$component_option_cache_key = 'component_option_' . $component_option_id;
		$component_option           = Helpers::cache_get( $component_option_cache_key );

		if ( null === $component_option ) {
			$component_option = wc_get_product( $component_option_id );
			Helpers::cache_set( $component_option_cache_key, $component_option );
		}

		if ( false === is_object( $component_option ) || false === in_array( $component_option->get_type(), ProductComposite::get_supported_component_option_types( $expanded ) ) ) {
			$component_option = false;
		}

		return $component_option;
	}

	/**
	 * Back-compat wrapper for getting the options style value from raw component data.
	 *
	 * @since  3.14.0
	 *
	 * @param  array  $data
	 * @return string
	 */
	protected static function get_options_style( $data ) {

		if ( ! empty( $data['selection_mode'] ) ) {
			$mode = $data['selection_mode'];
		} elseif ( ! empty( $data['composite_id'] ) ) {

			// Back-compat.
			$mode = get_post_meta( $data['composite_id'], '_bto_selection_mode', true );

			if ( empty( $mode ) ) {
				$mode = 'dropdowns';
			}
		}

		return $mode;
	}

	/**
	 * Adds an error tip.
	 *
	 * @since  3.14.0
	 *
	 * @param  string  $error
	 * @param  bool    $allow_html
	 * @return void
	 */
	protected static function add_error_tip( $error = '', $allow_html = false ) {

		if ( $allow_html ) {
			$error = wc_sanitize_tooltip( $error );
		} else {
			$error = esc_attr( $error );
		}

		return '<span class="woocommerce-help-tip wc-cp-error-tip" data-tip="' . $error . '"></span>';
	}

	/*
	|--------------------------------------------------------------------------
	| Deprecated methods.
	|--------------------------------------------------------------------------
	*/

	public static function form_location_option( $composite_product_object ) {
		_deprecated_function( __METHOD__ . '()', '3.14.0', __CLASS__ . '::composite_form_location()' );
		global $composite_product_object;
		return self::composite_form_location( $composite_product_object );
	}
}
