<?php
/**
 * Admin Component meta box html
 *
 * @version 4.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<div class="bto_group wc-metabox <?php echo esc_attr( $toggle ); ?>" rel="<?php echo isset( $data['position'] ) ? esc_attr( $data['position'] ) : esc_attr( $id ); ?>">
	<h3 class="bto_group_handle">
		<strong class="group_name">
			<?php
			if ( isset( $data['title'] ) && ! empty( $data['component_id'] ) ) {
				echo esc_html( $data['title'] );
			}
			?>
		</strong>
		<div class="handle">
			<input type="hidden" name="bto_data[<?php echo esc_attr( $id ); ?>][position]" class="group_position" value="<?php echo isset( $data['position'] ) ? esc_attr( $data['position'] ) : esc_attr( $id ); ?>" />

			<?php if ( ! empty( $data['component_id'] ) ) : ?>
				<input type="hidden" name="bto_data[<?php echo esc_attr( $id ); ?>][group_id]" class="group_id" value="<?php echo esc_attr( $data['component_id'] ); ?>" />
			<?php endif; ?>

			<div class="handle-item toggle-item" aria-label="<?php _e( 'Click to toggle', 'wpdrift-woocommerce-modules' ); ?>"></div>
			<div class="handle-item sort-item" aria-label="<?php esc_attr_e( 'Drag and drop to set order', 'wpdrift-woocommerce-modules' ); ?>"></div>
			<a class="remove_row delete" href="#"><?php _e( 'Remove', 'wpdrift-woocommerce-modules' ); ?></a>
		</div>
	</h3>
	<div class="bto_group_data wc-metabox-content">
		<ul class="subsubsub">
		<?php
		/*--------------------------------*/
		/*  Tab menu items.               */
		/*--------------------------------*/

		$tab_loop = 0;

		foreach ( $tabs as $tab_id => $tab_values ) {
			?>
			<li>
				<a href="#" data-tab="<?php echo esc_attr( $tab_id ); ?>" class="<?php echo 0 === $tab_loop ? 'current' : ''; ?>">
					<?php echo esc_html( $tab_values['title'] ); ?>
				</a>
			</li>
			<?php
			$tab_loop++;
		}
		?>
		</ul>
		<?php

		/*--------------------------------*/
		/*  Tab contents.                 */
		/*--------------------------------*/

		$tab_loop = 0;

		foreach ( $tabs as $tab_id => $tab_values ) {

			?>
			<div class="tab_group tab_group_<?php echo esc_attr( $tab_id ); ?> <?php echo $tab_loop > 0 ? 'tab_group_hidden' : ''; ?>">
				<?php

				/**
				 * Action 'woocommerce_composite_component_admin_{$tab_id}_html':
				 *
				 * @param  string  $component_id
				 * @param  array   $component_data
				 * @param  string  $composite_id
				*
				* Action 'woocommerce_composite_component_admin_config_html':
				*
				* @hooked Admin::component_config_title()        - 10
				* @hooked Admin::component_config_description()  - 15
				* @hooked Admin::component_config_options()      - 20
				* @hooked Admin::component_config_quantity_min() - 25
				* @hooked Admin::component_config_quantity_max() - 33
				* @hooked Admin::component_config_discount()     - 35
				* @hooked Admin::component_config_optional()     - 40
				*
				*
				* Action 'woocommerce_composite_component_admin_advanced_html':
				*
				* @hooked Admin::component_config_default_option()           -   5
				* @hooked Admin::component_sort_filter_show_orderby()        -  10
				* @hooked Admin::component_sort_filter_show_filters()        -  15
				* @hooked Admin::component_layout_hide_product_title()       -  20
				* @hooked Admin::component_layout_hide_product_description() -  25
				* @hooked Admin::component_layout_hide_product_thumbnail()   -  30
				* @hooked Admin::component_id_marker()                       - 100
				*
				*/
				do_action( 'woocommerce_composite_component_admin_' . $tab_id . '_html', $id, $data, $composite_id );

				?>
			</div>
			<?php

			$tab_loop++;
		}
		?>
	</div>
</div>
