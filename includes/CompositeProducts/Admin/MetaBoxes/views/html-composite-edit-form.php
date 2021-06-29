<?php
/**
 * Admin edit-Component meta box html
 *
 * @version 4.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="composite_components">
<?php

foreach ( $components as $component ) {

	$component_data = array(
		'component_id' => $component->get_id(),
		'composite_id' => $component->get_composite_id(),
	);

	?>
		<div class="composite_component" data-component_data="<?php echo esc_attr( json_encode( $component_data ) ); ?>">
		<?php

		/*
		 * Component title.
		 */

		wc_get_template(
			'single-product/component-title.php',
			array(
				'title'   => $component->get_title(),
				'toggled' => false,
				'tag'     => 'h4',
			),
			'',
			Module::instance()->plugin_path() . '/templates/'
		);

		/*
		 * Component options.
		 */

		$chosen_option_id = $component->get_default_option( true );
		$chosen_option    = $chosen_option_id ? $component->get_option( $chosen_option_id ) : false;

		?>
			<div class="component_option_select_wrapper">
				<select id="component_option_select_<?php echo esc_attr( $component->get_id() ); ?>" class="component_option_select wc-product-search" style="width: 75%;" name="wccp_component_selection[<?php echo esc_attr( $component->get_id() ); ?>]" data-allow_clear="true" data-action="woocommerce_json_search_products_in_component" data-include="<?php echo esc_attr( json_encode( $component_data ) ); ?>" data-limit="100" data-component_optional="<?php echo $component->is_optional() ? 'yes' : 'no'; ?>" data-placeholder="<?php echo __( 'Search for a product&hellip;', 'wpdrift-woocommerce-modules' ); ?>">
				<?php
				if ( $chosen_option ) {
					echo '<option value="' . esc_attr( $chosen_option_id ) . '" selected="selected">' . esc_html( Helpers::get_product_title( $chosen_option->get_product() ) ) . '</option>';
				}
				?>
				</select>
			</div>
			<?php

			/*
			* Component selection details.
			*/

			?>
			<div class="component_option_selection_details_wrapper <?php echo $chosen_option ? 'component_option_selection_type_' . esc_attr( $chosen_option->get_product()->get_type() ) : ''; ?>">
			<?php
			if ( $chosen_option ) {

				$chosen_option_data = $chosen_option->get_product_data();

				if ( ! empty( $chosen_option_data ) ) {
					echo wp_kses_post( $chosen_option_data['product_html'] );
				}
			}
			?>
			</div>
		</div>
		<?php
}
?>
</div>
