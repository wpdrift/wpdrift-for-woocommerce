/**
 * WordPress dependencies
 */
import {render} from "@wordpress/element";
import {dispatch} from "@wordpress/data";

/**
 * Internal dependencies
 */
import "./api";
import CompositeProductPopup from "./composite-products/CompositeProductPopup";

let wpDrift = wpDrift || {};

(function($) {
	"use strict";

	wpDrift.Composite = wpDrift.Composite || {};
	wpDrift.Composite.Popup = {
		start: function(el) {
			const appRoot = document.getElementById("composite-product-popup");
			if (appRoot) {
				const productId = $(el).data("product_id");
				if (typeof productId !== "undefined") {
					this.setupData(productId);
					render(<CompositeProductPopup button={el} />, appRoot);
				}
			}
		},

		setupData: function(productId) {
			const productData = productsData[productId];
			const productPriceData = productData["price_data"];
			const productComponents = productData["components"];

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
			dispatch("composite-products").calculateSubtotals(productId);
			dispatch("composite-products").displayPopup(productId);
		}
	};

	$(document).on(
		"click",
		".product_type_composite.add_to_cart_button, .product_type_composite .wp-block-button__link.add_to_cart_button",
		function(e) {
			e.preventDefault();
			wpDrift.Composite.Popup.start(this);
		}
	);
})(jQuery);
