<?php
namespace WPdrift\CompositeProducts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*---------------------------------------------------------*/
/*                                                         */
/*  Composite products single product template functions.  */
/*                                                         */
/*---------------------------------------------------------*/

/**
 * Add-to-cart template for composite products. Form location: After summary.
 *
 * @since  1.0.0
 */
function wc_cp_add_to_cart_after_summary() {

	global $product;

	if ( is_composite_product() ) {
		if ( 'after_summary' === $product->get_add_to_cart_form_location() ) {
			$classes = implode( ' ', apply_filters( 'woocommerce_composite_form_wrapper_classes', array( 'summary-add-to-cart-form', 'summary-add-to-cart-form-composite' ), $product ) );
			?>
			<div class="<?php echo esc_attr( $classes ); ?>">
				<?php do_action( 'woocommerce_composite_add_to_cart' ); ?>
			</div>
			<?php
		}
	}
}

/**
 * [wc_cp_wrap_start_product_summary description]
 * @return [type] [description]
 */
function wc_cp_wrap_start_product_summary() {
	global $product;

	if ( is_composite_product() ) {
		if ( 'default' === $product->get_add_to_cart_form_location() ) {
			?>
			<div>
				<div class="summary__wrapper">
			<?php
		}
	}

}

/**
 * [wc_cp_wrap_end_product_summary description]
 * @return [type] [description]
 */
function wc_cp_wrap_end_product_summary() {
	global $product;

	if ( is_composite_product() ) {
		if ( 'default' === $product->get_add_to_cart_form_location() ) {
			?>
				</div>
				<?php wc_cp_components(); ?>
			</div>
			<?php
		} else {
			wc_cp_components();
		}
	}
}

/**
 * [wc_cp_components description]
 * @return [type] [description]
 */
function wc_cp_components() {
	echo '<div class="composite-components" id="composite-components"></div>';
}

/**
 * Add-to-cart template for composite products. Form location: Default.
 */
function wc_cp_add_to_cart() {

	global $product;

	if ( doing_action( 'woocommerce_single_product_summary' ) ) {
		if ( 'after_summary' === $product->get_add_to_cart_form_location() ) {
			return;
		}
	}

	// Enqueue scripts.
	wp_enqueue_script( 'wc-add-to-cart-composite' );

	// Enqueue styles.
	wp_enqueue_style( 'wc-composite-single-css' );

	// Load NYP scripts.
	if ( function_exists( 'WC_Name_Your_Price' ) ) {
		WC_Name_Your_Price()->display->nyp_scripts();
	}

	// Enqueue Bundle styles.
	if ( class_exists( 'WC_Bundles' ) ) {
		wp_enqueue_style( 'wc-bundle-css' );
	}

	$navigation_style           = $product->get_composite_layout_style();
	$navigation_style_variation = $product->get_composite_layout_style_variation();
	$components                 = $product->get_components();
	$prepared_components        = [];
	foreach ( $components as $coponent_id => $component ) {
		$prepared_components[] = [
			'id'              => $component->get_id(),
			'title'           => $component->get_title(),
			'description'     => wp_strip_all_tags( $component->get_description() ),
			'options'         => $component->view->get_options_data(),
			'options_style'   => $component->get_options_style(),
			'multiple'        => $component->is_multiple(),
			'selected_option' => $component->view->get_selected_option(),
			'product_id'      => $component->get_default_option(),
			'quantity'        => $component->get_quantity( 'min' ),
			'quantity_min'    => $component->get_quantity( 'min' ),
			'quantity_max'    => $component->get_quantity( 'max' ),
			'discount'        => $component->get_discount(),
			'optional'        => $component->is_optional() ? 'yes' : 'no',
			'static'          => $component->is_static() ? 'yes' : 'no',
			'title'           => $component->get_title(),
			'composite_id'    => $product->get_id(),
			'type'            => $product->get_type(),
		];
	}

	if ( ! empty( $components ) ) {
		$image_src = '';

		if ( $product->get_image_id() ) {
			$image_src = wp_get_attachment_image_src( $product->get_image_id(), 'medium' );
		}

		$product_data = [
			'title'        => $product->get_title(),
			'description'  => $product->get_short_description(),
			'min_quantity' => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
			'max_quantity' => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
			'layout_style' => $product->get_composite_layout_style(),
			'image_src'    => $image_src ? $image_src[0] : wc_placeholder_img_src(),
		];

		wc_get_template(
			'single-product/add-to-cart/composite.php',
			array(
				'navigation_style' => $navigation_style,
				'classes'          => implode( ' ', apply_filters( 'woocommerce_composite_form_classes', array( $navigation_style, $navigation_style_variation ), $product ) ),
				'components'       => $prepared_components,
				'product'          => $product,
				'product_data'     => $product_data,
				'price_data'       => $product->get_composite_price_data(),
			),
			'',
			Module::instance()->plugin_path() . '/templates/'
		);
	}
}

/**
 * Add-to-cart button and quantity template for composite products.
 */
function wc_cp_add_to_cart_button() {

	if ( isset( $_GET['update-composite'] ) ) {
		$cart_id = wc_clean( $_GET['update-composite'] );
		echo '<input type="hidden" name="update-composite" value="' . esc_attr( $cart_id ) . '" />';
	}

	wc_get_template( 'single-product/add-to-cart/composite-quantity-input.php', array(), false, Module::instance()->plugin_path() . '/templates/' );
	wc_get_template( 'single-product/add-to-cart/composite-button.php', array(), false, Module::instance()->plugin_path() . '/templates/' );
}


/*-----------------------------------------------------------------------------*/
/*                                                                             */
/*  Composite products single product summary widget functions.                */
/*                                                                             */
/*-----------------------------------------------------------------------------*/

/**
 * Summary widget content.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 * @param  array                 $widget_options
 */
function wc_cp_summary_widget_content( $components, $composite, $widget_options ) {

	?>
	<div class="widget_composite_summary_elements" data-summary_columns="<?php echo esc_attr( $widget_options['columns'] ); ?>">
	<?php
		wc_get_template(
			'single-product/composite-summary-content.php',
			array(
				'summary_columns'  => $widget_options['columns'],
				'summary_elements' => count( $components ),
				'components'       => $components,
				'product'          => $composite,
			),
			'',
			Module::instance()->plugin_path() . '/templates/'
		);
	?>
	</div>
	<?php
}

/**
 * Summary widget wrapper start.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 * @param  array                 $widget_options
 */
function wc_cp_summary_widget_details_wrapper_start( $components, $composite, $widget_options ) {

	if ( 'fixed' === $widget_options['display'] ) {
		?>
		<div class="widget_composite_summary_details_wrapper">
			<span role="button" class="summary_carousel_button prev disabled inactive"><button class="aria_button" aria-label="<?php echo __( 'View previous steps', 'wpdrift-woocommerce-modules' ); ?>"></button></span>
			<div class="widget_composite_summary_elements_wrapper">
			<?php
	}
}

/**
 * Summary widget wrapper end.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 * @param  array                 $widget_options
 */
function wc_cp_summary_widget_details_wrapper_end( $components, $composite, $widget_options ) {

	if ( 'fixed' === $widget_options['display'] ) {
		?>
			</div>
			<span role="button" class="summary_carousel_button next disabled inactive"><button class="aria_button" aria-label="<?php echo __( 'View next steps', 'wpdrift-woocommerce-modules' ); ?>"></button></span>
		</div>
		<?php
	}
}

/**
 * Summary widget UI wrapper start.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 * @param  array                 $widget_options
 */
function wc_cp_summary_widget_ui_wrapper_start( $components, $composite, $widget_options ) {

	if ( 'fixed' === $widget_options['display'] ) {
		?>
		<div class="widget_composite_summary_ui_wrapper">
		<?php
	}
}

/**
 * Summary widget UI wrapper end.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 * @param  array                 $widget_options
 */
function wc_cp_summary_widget_ui_wrapper_end( $components, $composite, $widget_options ) {

	if ( 'fixed' === $widget_options['display'] ) {
		?>
		</div>
		<?php
	}
}

/**
 * Summary widget price. Empty element to be populated by the script.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 */
function wc_cp_summary_widget_price( $components, $composite ) {

	?>
	<div class="widget_composite_summary_price">
		<div class="composite_price"></div>
	</div>
	<?php
}

/**
 * Summary widget product availability.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 */
function wc_cp_summary_widget_availability( $components, $product ) {

	?>
	<div class="widget_composite_summary_availability">
		<div class="composite_availability">
		<?php
			echo wc_get_stock_html( $product );
		?>
		</div>
	</div>
	<?php
}

/**
 * Summary widget add-to-cart button.
 *
 * @since  1.0.0
 *
 * @param  array                 $components
 * @param  ProductComposite  $composite
 */
function wc_cp_summary_widget_button( $components, $composite ) {

	?>
	<div class="widget_composite_summary_button">
		<div class="composite_button">
			<?php do_action( 'woocommerce_composite_add_to_cart_button' ); ?>
		</div>
	</div>
	<?php
}

/*-----------------------------------------------------------------------------*/
/*                                                                             */
/*  Composite products single product template functions - Component Options.  */
/*                                                                             */
/*-----------------------------------------------------------------------------*/

/**
 * Show current selection scroll target in paged modes.
 *
 * @since  1.0.0
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 */
function wc_cp_component_selection_scroll_target_paged_top( $component_id, $product ) {

	$options_style = $product->get_component( $component_id )->get_options_style();

	if ( 'thumbnails' === $options_style ) {
		?>
		<div class="scroll_show_component_details"></div>
		<?php
	}
}

/**
 * Component selection notices container displayed in the 'component_selections' container (paged layout, thumbnails).
 *
 * @since  1.0.0
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 */
function wc_cp_component_selection_message_paged_top( $component_id, $product ) {

	$options_style = $product->get_component( $component_id )->get_options_style();

	if ( 'thumbnails' === $options_style ) {
		$classes = array( 'top' );
		wc_cp_component_message( $classes );
	}
}

/**
 * Show current selection details in paged modes -- added before component options when viewed as thumbnails.
 *
 * @since  1.0.0
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 */
function wc_cp_component_selection_paged_top( $component_id, $product ) {

	$options_style = $product->get_component( $component_id )->get_options_style();

	if ( 'thumbnails' === $options_style ) {
		wc_cp_component_selection( $component_id, $product );
	}
}

/**
 * In progressive mode, wrap component options & sorting/filtering controls in a blockable div.
 *
 * @since  1.0.0
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 */
function wc_cp_component_options_progressive_end( $component_id, $product ) {

	?>
	</div>
	<?php
}

/**
 * Show current selection details in non-paged modes.
 *
 * @since  1.0.0
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 * @return void
 */
function wc_cp_component_selection( $component_id, $product ) {

	$selected_option  = $product->get_component( $component_id )->view->get_selected_option();
	$navigation_style = $product->get_composite_layout_style();

	?>
	<div class="component_content" data-product_id="<?php echo esc_attr( $component_id ); ?>">
		<div class="component_summary cp_clearfix">
		<?php

			/**
			 * Action 'woocommerce_composite_component_before_summary_content_{$navigation_style}'.
			 *
			 * @since  1.0.0
			 *
			 * @param  string                $component_id
			 * @param  ProductComposite  $product
			 */
			do_action( 'woocommerce_composite_component_before_summary_content_' . $navigation_style, $component_id, $product );

			// View container.
		?>
			<div class="product content summary_content <?php echo $selected_option ? 'populated' : ''; ?>"></div>
		</div>
	</div>
	<?php
}

/**
 * Show current selection details in paged modes -- added after component options when viewed as drop-downs/radios.
 *
 * @since  1.0.0
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 */
function wc_cp_component_selection_paged_bottom( $component_id, $product ) {

	$options_style = $product->get_component( $component_id )->get_options_style();

	if ( 'dropdowns' === $options_style || 'radios' === $options_style ) {
		wc_cp_component_selection( $component_id, $product );
	}
}

/**
 * Component selection notices container displayed in the 'component_selections' container (paged layout, dropdowns/radios).
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 */
function wc_cp_component_selection_message_paged_bottom( $component_id, $product ) {

	$options_style = $product->get_component( $component_id )->get_options_style();

	if ( 'thumbnails' !== $options_style ) {
		$classes = array( 'bottom' );
		wc_cp_component_message( $classes );
	}
}

/*----------------------------------------------------------------------------------*/
/*                                                                                  */
/*  Composite products single product template functions - Composite.               */
/*                                                                                  */
/*----------------------------------------------------------------------------------*/

/**
 * Loading status message.
 */
function wc_cp_status() {
	?>
	<div class="composite_status">
		<div class="wrapper"></div>
	</div>
	<?php
}

/**
 * Component selection notices container displayed in the component_selections container (progressive layout).
 *
 * @param  string                $component_id
 * @param  ProductComposite  $product
 */
function wc_cp_component_selection_message_progressive( $component_id, $product ) {

	$classes = array( 'bottom' );
	wc_cp_component_message( $classes );
}

/**
 * Component selection notices container displayed in progressive/paged layouts.
 *
 * @param  array  $classes
 */
function wc_cp_component_message( $classes ) {

	?>
	<div class="component_message <?php echo esc_attr( implode( ' ', $classes ) ); ?>" style="display:none"></div>
	<?php
}

/**
 * When changing between components in paged mode, the viewport will scroll to this div if it's not visible.
 * Adding the 'scroll_bottom' class to the element will scroll the bottom of the viewport to the target.
 *
 * @param  array                 $components
 * @param  ProductComposite  $product
 */
function wc_cp_component_transition_scroll_target( $components, $product ) {

	?>
	<div class="scroll_show_component"></div>
	<?php
}

/**
 * Div for blocking form content during transitions.
 *
 * @param  array                 $components
 * @param  ProductComposite  $product
 */
function wc_cp_component_blocker( $components, $product ) {

	?>
	<div class="form_input_blocker"></div>
	<?php
}

/**
 * When selecting the final step in paged mode, the viewport will scroll to this div.
 * Adding the 'scroll_bottom' class to the element will scroll the bottom of the viewport to the target.
 *
 * @param  array                 $components
 * @param  ProductComposite  $product
 */
function wc_cp_final_step_scroll_target( $components, $product ) {

	$navigation_style = $product->get_composite_layout_style();

	if ( 'paged' === $navigation_style ) {

		?>
		<div class="scroll_final_step"></div>
		<?php
	}
}

/**
 * No js notice.
 *
 * @param  array                 $components
 * @param  ProductComposite  $product
 */
function wc_cp_no_js_msg( $components, $product ) {

	?>
	<p class="cp-no-js-msg">
		<span id="cp-no-js-msg">
			<script type="text/javascript">
				var el = document.getElementById( 'cp-no-js-msg' );
				el.innerHTML = "<?php _e( 'Loading...', 'wpdrift-woocommerce-modules' ); ?>";
			</script>
		</span>
		<noscript>
			<?php _e( 'JavaScript must be supported by your browser and needs to be enabled in order to view this page.', 'wpdrift-woocommerce-modules' ); ?>
		</noscript>
	</p>
	<?php
}

/*--------------------------------------------------------*/
/*                                                        */
/*  Component selection template functions.               */
/*                                                        */
/*--------------------------------------------------------*/

/**
 * Composited product title template.
 *
 * @param  Product  $component_option
 */
function wc_cp_composited_product_title( $component_option ) {

	$component = $component_option->get_component();
	$is_hidden = $component->hide_selected_option_title();

	?>
	<div class="composited_product_title_wrapper" data-show_title="<?php echo ! $is_hidden ? 'yes' : 'no'; ?>" tabindex="-1"></div>
	<?php
}

/**
 * Composited product wrapper open.
 *
 * @since  1.0.0
 *
 * @param  Product  $component_option
 */
function wc_cp_composited_product_wrapper_open( $component_option ) {
	echo '<div class="composited_product_details_wrapper">';
}

/**
 * Composited product thumbnail template.
 *
 * @param  Product  $component_option
 */
function wc_cp_composited_product_thumbnail( $component_option ) {

	$component  = $component_option->get_component();
	$product_id = $component_option->get_product_id();

	if ( ! $component->hide_selected_option_thumbnail() ) {

		/**
		 * 'woocommerce_bundled_product_gallery_classes' filter.
		 *
		 * @param  array          $classes
		 * @param  Product  $component_option
		 */
		$gallery_classes = apply_filters( 'woocommerce_composited_product_gallery_classes', array( 'composited_product_images', 'images' ), $component_option );

		wc_get_template(
			'composited-product/image.php',
			array(
				'product_id'      => $product_id,
				'gallery_classes' => $gallery_classes,
				'image_size'      => $component_option->get_selection_thumbnail_size(),
				'image_rel'       => current_theme_supports( 'wc-product-gallery-lightbox' ) ? 'photoSwipe' : 'prettyPhoto',
				'component'       => $component,
			),
			'',
			Module::instance()->plugin_path() . '/templates/'
		);
	}
}

/**
 * Composited product details wrapper close.
 *
 * @since  1.0.0
 *
 * @param  Product  $component_option
 */
function wc_cp_composited_product_wrapper_close( $component_option ) {
	echo '</div>';
}

/**
 * Composited product excerpt.
 *
 * @param  WC_Product            $product
 * @param  string                $component_id
 * @param  ProductComposite  $composite
 */
function wc_cp_composited_product_excerpt( $product, $component_id, $composite ) {

	$product_id = $product->get_id();
	$component  = $composite->get_component( $component_id );

	if ( ! $component->hide_selected_option_description() ) {
		wc_get_template(
			'composited-product/excerpt.php',
			array(
				'product_description' => $product->get_short_description(),
				'product_id'          => $product_id,
				'component_id'        => $component_id,
				'composite'           => $composite,
			),
			'',
			Module::instance()->plugin_path() . '/templates/'
		);
	}
}

/**
 * Composited simple product price.
 *
 * @param  WC_Product            $product
 * @param  string                $component_id
 * @param  ProductComposite  $composite
 */
function wc_cp_composited_product_price( $product, $component_id, $composite ) {

	if ( 'simple' === $product->get_type() ) {

		$product_id         = $product->get_id();
		$component          = $composite->get_component( $component_id );
		$composited_product = $component->get_option( $product_id );

		if ( $composited_product->is_priced_individually() && false === $component->hide_selected_option_price() && '' !== $product->get_price() ) {
			wc_get_template(
				'composited-product/price.php',
				array(
					'product' => $product,
				),
				'',
				Module::instance()->plugin_path() . '/templates/'
			);
		}
	}
}

/**
 * Composited single variation details.
 *
 * @since  1.0.0
 *
 * @param  WC_Product_Variable   $product
 * @param  string                $component_id
 * @param  ProductComposite  $composite
 */
function wc_cp_composited_single_variation( $product, $component_id, $composite ) {
	?>
	<div class="woocommerce-variation single_variation"></div>
	<?php
}

/**
 * Variation attribute options for composited products.
 *
 * @since  1.0.0
 *
 * @param  array  $args
 */
function wc_cp_composited_single_variation_attribute_options( $args ) {

	$options        = $args['options'];
	$attribute_name = $args['attribute'];
	$product        = $args['product'];
	$component      = $args['component'];

	$attribute_keys = array_keys( $args['attributes'] );
	$component_id   = $component->get_id();
	$selected       = isset( $_REQUEST[ 'wccp_attribute_' . sanitize_title( $attribute_name ) ][ $component_id ] ) ? wc_clean( wp_unslash( $_REQUEST[ 'wccp_attribute_' . sanitize_title( $attribute_name ) ][ $component_id ] ) ) : $product->get_variation_default_attribute( $attribute_name );
	$html           = '';

	ob_start();

	wc_dropdown_variation_attribute_options(
		array(
			'options'   => $options,
			'attribute' => $attribute_name,
			'name'      => 'wccp_attribute_' . sanitize_title( $attribute_name ) . '[' . $component_id . ']',
			'product'   => $product,
			'selected'  => $selected,
		)
	);

	$attribute_options = ob_get_clean();

	$html .= $attribute_options;

	if ( end( $attribute_keys ) === $attribute_name ) {
		$html .= wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<div class="reset_variations_wrapper"><a class="reset_variations" href="#">' . esc_html__( 'Clear', 'wpdrift-woocommerce-modules' ) . '</a></div>' ) );
	}

	return $html;
}

/**
 * [wc_cp_get_formatted_cart_item_data description]
 * @param  [type] $cart_item [description]
 * @return [type]            [description]
 */
function wc_cp_get_formatted_cart_item_data( $cart_item ) {
	if ( wc_cp_is_composite_container_cart_item( $cart_item ) ) {
		$child_cart_items = wc_cp_get_composited_cart_items( $cart_item );
		if ( empty( $child_cart_items ) ) {
			return '';
		}

		$data                    = array();
		$child_item_descriptions = array();

		foreach ( $child_cart_items as $child_cart_item_key => $child_cart_item ) {
			$component_id           = $child_cart_item['composite_item'];
			$component              = $cart_item['data']->get_component( $component_id );
			$child_item_description = '';

			if ( $component ) {
				$child_item_title       = $component->get_title();
				$child_item_description = Product::get_title_string( $child_cart_item['data']->get_name(), $child_cart_item['quantity'] );
				$child_item_description = apply_filters( 'woocommerce_composite_container_cart_item_data_value', $child_item_description, $child_cart_item, $child_cart_item_key );
			}

			if ( $child_item_description ) {
				$data[] = array(
					'key'   => $child_item_title,
					'value' => $child_item_description,
				);
			}
		}

		if ( empty( $data ) ) {
			return '';
		}

		foreach ( $data as $item ) {
			$child_item_descriptions[] = esc_html( $item['key'] ) . ': ' . wp_kses_post( $item['value'] );
		}

		return '<div class="product-short-description">' . implode( ', ', $child_item_descriptions ) . '</div>';
	}

	return '';
}
