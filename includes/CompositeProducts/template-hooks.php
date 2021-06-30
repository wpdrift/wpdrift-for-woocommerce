<?php
namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*----------------------------------*/
/*  Single product template hooks.  */
/*----------------------------------*/

// Single product form content: Displayed in the Summary.
// add_action( 'woocommerce_composite_add_to_cart', __NAMESPACE__ . '\wc_cp_add_to_cart' );
add_action( 'woocommerce_before_single_product_summary', __NAMESPACE__ . '\wc_cp_wrap_start_product_summary', 1 );

// Single product form content: Displayed after the Summary.
add_action( 'woocommerce_after_single_product_summary', __NAMESPACE__ . '\wc_cp_add_to_cart_after_summary', -1000 );
add_action( 'woocommerce_after_single_product_summary', __NAMESPACE__ . '\wc_cp_wrap_end_product_summary', 1 );

// Single product add-to-cart button template for composite products.
add_action( 'woocommerce_composite_add_to_cart_button', __NAMESPACE__ . '\wc_cp_add_to_cart_button' );

// After components: Status Messages.
add_action( 'woocommerce_composite_after_components', __NAMESPACE__ . '\wc_cp_status', 10, 2 );

// After components: Display no-JS notice regardless of layout.
add_action( 'woocommerce_composite_after_components', __NAMESPACE__ . '\wc_cp_no_js_msg', 15, 2 );

/*----------------------------------------*/
/*  Single product summary widget hooks.  */
/*----------------------------------------*/

// Validation message for fixed-display widgets.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_message_fixed', 0, 3 );

// Selections wrapper start.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_details_wrapper_start', 5, 3 );

// Content.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_content', 10, 3 );

// Selections wrapper end.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_details_wrapper_end', 15, 3 );

// UI wrapper start.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_ui_wrapper_start', 15, 3 );

// Price.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_price', 20, 2 );

// Validation message for default-display widgets.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_message_default', 30, 3 );

// Availability.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_availability', 40, 2 );

// Button.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_button', 50, 2 );

// UI wrapper end.
add_action( 'woocommerce_composite_summary_widget_content', __NAMESPACE__ . '\wc_cp_summary_widget_ui_wrapper_end', 100, 3 );

/*---------------------------*/
/*  Stacked layout hooks.    */
/*---------------------------*/

/*
 * Component options:
 */

// Current selection in single-page mode.
add_action( 'woocommerce_composite_component_selections_single', __NAMESPACE__ . '\wc_cp_component_selection', 50, 2 );

/*-----------------------------*/
/*  Progressive layout hooks.  */
/*-----------------------------*/

/*
 * Component options:
 */

// Current selections block wrapper in progressive mode -- end.
add_action( 'woocommerce_composite_component_selections_progressive', __NAMESPACE__ . '\wc_cp_component_options_progressive_end', 45, 2 );

// Current selection in single-page mode.
add_action( 'woocommerce_composite_component_selections_progressive', __NAMESPACE__ . '\wc_cp_component_selection', 50, 2 );

// Component notices container.
add_action( 'woocommerce_composite_component_selections_progressive', __NAMESPACE__ . '\wc_cp_component_selection_message_progressive', 60, 2 );

/*-------------------------------------------*/
/*  Stepped and Componentized layout hooks.  */
/*-------------------------------------------*/

/*
 * Before components:
 */

// Auto-scroll target at top of page when transitioning to a new component.

/*
 * Note:
 *
 * When component options loaded via ajax are appended instead of paginated (@see WC_Component::paginate_options),
 * the selected product details are relocated below the selected product thumbnail row.
 *
 * In this case, when transitioning back to a component with relocated selected product details, the relocated container will be moved back to the original position
 * and the viewport will auto-scroll to the target defined here.
 *
 * Alternatively, the 'woocommerce_composite_front_end_params' filter ('relocated_content_reset_on_return' key) can be used to prevent resetting the position of the relocated container.
 * In this case, the viewport will always auto-scroll to the relocated container.
 */
add_action( 'woocommerce_composite_before_components_paged', __NAMESPACE__ . '\wc_cp_component_transition_scroll_target', 10, 2 );

// Component blocker div (blocks input during transitions).
add_action( 'woocommerce_composite_before_components_paged', __NAMESPACE__ . '\wc_cp_component_blocker', 10, 2 );

/*
 * Component options:
 */

// Component details scroll target.
add_action( 'woocommerce_composite_component_selections_paged', __NAMESPACE__ . '\wc_cp_component_selection_scroll_target_paged_top', -20, 2 );

// Component notices container (thumbnails).
add_action( 'woocommerce_composite_component_selections_paged', __NAMESPACE__ . '\wc_cp_component_selection_message_paged_top', -10, 2 );

// Component options: Current selection details in paged mode - before thumbnails.
add_action( 'woocommerce_composite_component_selections_paged', __NAMESPACE__ . '\wc_cp_component_selection_paged_top', 0, 2 );

// Component options: Current selection in paged mode - after dropdown.
add_action( 'woocommerce_composite_component_selections_paged', __NAMESPACE__ . '\wc_cp_component_selection_paged_bottom', 50, 2 );

// Component notices container (dropdowns and radios).
add_action( 'woocommerce_composite_component_selections_paged', __NAMESPACE__ . '\wc_cp_component_selection_message_paged_bottom', 60, 2 );

/*--------------------------------------*/
/*  Composited product template hooks.  */
/*--------------------------------------*/

// Composited product title.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_title', 5 );

// Composited product details wrapper open.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_wrapper_open', 10 );

// Composited product thumbnail.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_thumbnail', 20 );

// Composited product details wrapper close.
add_action( 'woocommerce_composited_product_single', __NAMESPACE__ . '\wc_cp_composited_product_wrapper_close', 100 );

// Composited product - Excerpt.
add_action( 'woocommerce_composited_product_details', __NAMESPACE__ . '\wc_cp_composited_product_excerpt', 10, 3 );

// Composited Simple product - Price.
add_action( 'woocommerce_composited_product_add_to_cart', __NAMESPACE__ . '\wc_cp_composited_product_price', 8, 3 );
