/**
 * WordPress dependencies
 */
import {render} from "@wordpress/element";
import {dispatch} from "@wordpress/data";

/**
 * Internal dependencies
 */
import "./api";
import CompositeProduct from "./composite-products/CompositeProduct";

import "./sass/composite-add-to-cart.scss";

const wpDrift = wpDrift || {};

(function($) {
	"use strict";

	wpDrift.Composite = wpDrift.Composite || {};

	wpDrift.Composite.Product = {
		start: function() {
			const element = <CompositeProduct />;
			const appRoot = document.getElementById("composite-components");
			if (appRoot) {
				render(element, appRoot);
			}

			const productId = wc_composite_params.composite_config.product_id;
			if (productId !== undefined) {
				this.setupData();
				dispatch("composite-products").displayInlineComponents(
					productId
				);
				dispatch("composite-products").calculateSubtotals(productId);
			}
		},

		setupData: function() {
			const productId = wc_composite_params.composite_config.product_id;
			const productData =
				wc_composite_params.composite_config.product_data;
			const productPriceData =
				wc_composite_params.composite_config.price_data;
			const productComponents =
				wc_composite_params.composite_config.components;
			dispatch("composite-products").updateProductData(
				productId,
				productData
			);
			dispatch("composite-products").updatePriceData(
				productId,
				productPriceData
			);
			dispatch("composite-products").updatedcomponents(
				productId,
				productComponents
			);
		}
	};

	$(document).ready(function() {
		wpDrift.Composite.Product.start();
	});
})(jQuery);
