<?php
/**
 * Admin Add Scenario markup
 *
 * @version 4.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$render_on_toggle = 'closed' === $toggle && ! empty( $scenario_data[ 'scenario_id' ] );

?>
<div class="bto_scenario wc-metabox <?php echo $toggle; ?>" rel="<?php echo isset( $scenario_data[ 'position' ] ) ? esc_attr( $scenario_data[ 'position' ] ) : ''; ?>">
	<h3 class="bto_scenario_handle">
		<strong class="scenario_name">
			<?php
				if ( ! empty( $scenario_data[ 'title' ] ) ) {
					echo esc_html( $scenario_data[ 'title' ] );
				}
			?>
		</strong>
		<div class="handle">

			<input type="hidden" name="bto_scenario_data[<?php echo $id; ?>][position]" class="scenario_position" value="<?php echo isset( $scenario_data[ 'position' ] ) ? esc_attr( $scenario_data[ 'position' ] ) : $id; ?>"/>

			<?php
				if ( ! empty( $scenario_data[ 'scenario_id' ] ) ) {
					?><input type="hidden" name="bto_scenario_data[<?php echo $id; ?>][scenario_id]" class="scenario_id" value="<?php echo $scenario_data[ 'scenario_id' ]; ?>"/><?php
				}
			?>
			<span class="handle-loading"></span>
			<div class="handle-item toggle-item" aria-label="<?php _e( 'Click to toggle', 'wpdrift-woocommerce-modules' ); ?>"></div>
			<div class="handle-item sort-item" aria-label="<?php esc_attr_e( 'Drag and drop to set order', 'wpdrift-woocommerce-modules' ); ?>"></div>
			<a href="#" class="remove_row delete"><?php echo __( 'Remove', 'wpdrift-woocommerce-modules' ); ?></a>
		</div>
	</h3><?php

	ob_start();
	include( 'html-scenario-contents.php' );
	$scenario_content = ob_get_clean();

	?><div class="bto_scenario_data wc-metabox-content" data-scenario_content="<?php echo $render_on_toggle ? htmlspecialchars( $scenario_content ) : ''; ?>"><?php
		echo $render_on_toggle ? '' : $scenario_content;
	?></div>
</div>
