<?php
/**
 * Composited Invalid Product template
 *
 * Override this template by copying it to 'yourtheme/woocommerce/composited-product/invalid-product.php'.
 *
 * On occasion, this template file may need to be updated and you (the theme developer) will need to copy the new files to your theme to maintain compatibility.
 * We try to do this as little as possible, but it does happen.
 * When this occurs the version of the template file will be bumped and the readme will list any important changes.
 *
 * @version 4.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?><div class="component_data woocommerce-error"><?php

	if ( $is_static ) {
		$html = __( 'This item cannot be purchased at the moment.', 'wpdrift-woocommerce-modules' );
	} else {
		$link = '<a class="clear_component_options button" href="#" role="button">' . __( 'Clear selection', 'wpdrift-woocommerce-modules' ) . '</a>';
		$html = sprintf( __( 'The selected item cannot be purchased at the moment. %s', 'wpdrift-woocommerce-modules' ), $link );
	}

	echo $html;

?></div>

