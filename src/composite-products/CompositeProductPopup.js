/**
 * External dependencies
 */
import $ from "jquery";
import classnames from "classnames";
import ReactModal from "react-modal";

/**
 * WordPress dependencies
 */
import {Component} from "@wordpress/element";
import {withDispatch, withSelect} from "@wordpress/data";
import {compose} from "@wordpress/compose";
import {__} from "@wordpress/i18n";
import {Button, TextControl, Placeholder, Spinner} from "@wordpress/components";

/**
 * Internal dependencies
 */
import {wc_price_format} from "./utility";
import ThumbnailsControl from "./components/thumbnails-control";
import RadioControl from "./components/radio-control";
import CheckboxControl from "./components/checkbox-control";
import ProductHeader from "./components/ProductHeader";

class CompositeModal extends Component {
	constructor(props) {
		super(props);
		this.state = {
			components: [],
			addingToCart: false
		};

		this.addToCart = this.addToCart.bind(this);
	}

	addToCart() {
		const {
			productId,
			closePopup,
			quantity,
			updateAddedToCart,
			button
		} = this.props;
		const config = this.parseConfiguration();
		const self = this;

		self.setState({addingToCart: true});

		$.ajax({
			method: "POST",
			url: wpdriftSettings.root + "wpdrift/v1/add_to_cart",
			data: {
				product_id: productId,
				quantity,
				config
			},
			success: function(response) {
				if (response === false) {
					location.reload();
				} else {
					updateAddedToCart(productId);
					$(document.body).trigger("added_to_cart", [
						"",
						"",
						$(button)
					]);
					closePopup();
				}
			},
			complete: function() {
				$(".widget_shopping_cart_content").empty();
				$(document.body).trigger("wc_fragment_refresh");
				self.setState({
					addingToCart: false
				});
			}
		});
	}

	get_formatted_price_suffix(totals) {
		const suffix = "";
		return suffix;
	}

	get_price_html() {
		const {totals} = this.props;
		const total_string = wc_composite_params.i18n_total
			? '<span class="total">' +
			  wc_composite_params.i18n_total +
			  "</span>"
			: "";
		let price_html = "";
		let formatted_price = wc_price_format(totals.price, true);
		let formatted_regular_price = wc_price_format(
			totals.regular_price,
			true
		);
		let formatted_suffix = this.get_formatted_price_suffix(totals);

		if (totals.regular_price > totals.price) {
			formatted_price = wc_composite_params.i18n_strikeout_price_string
				.replace("%f", formatted_regular_price)
				.replace("%t", formatted_price);
		}

		price_html = wc_composite_params.i18n_price_format
			.replace("%t", total_string)
			.replace("%p", formatted_price)
			.replace("%s", formatted_suffix);
		price_html = '<p class="price">' + price_html + "</p>";

		return {__html: price_html};
	}

	parseConfiguration() {
		const {components} = this.props;
		return components.reduce((acc, cp) => {
			const {
				selected_option,
				quantity,
				quantity_min,
				quantity_max,
				discount,
				optional,
				title,
				composite_id,
				type
			} = cp;

			if (selected_option === "") {
				return acc;
			}

			const items = acc[cp.id] === undefined ? [] : acc[cp.id];

			if (Array.isArray(selected_option)) {
				const selectedItems = selected_option.reduce(function(
					acc,
					option
				) {
					return [
						...acc,
						{
							product_id: option,
							quantity,
							quantity_min,
							quantity_max,
							discount,
							optional,
							static: cp.static,
							title,
							composite_id,
							type
						}
					];
				},
				[]);

				acc[cp.id] = [...items, ...selectedItems];
			} else {
				acc[cp.id] = [
					...items,
					{
						product_id: selected_option,
						quantity,
						quantity_min,
						quantity_max,
						discount,
						optional,
						static: cp.static,
						title,
						composite_id,
						type
					}
				];
			}

			return acc;
		}, {});
	}

	renderControl(args) {
		const {
			productId,
			updateComponent,
			calculateComponentSubtotals
		} = this.props;
		let options = args["options"].reduce(function(acc, option) {
			const imageSrc =
				option.option_product_data !== undefined
					? option.option_product_data.image_data.image_src
					: "";
			return [
				...acc,
				{
					label: option.option_title,
					value: option.option_id,
					image: imageSrc,
					priceHtmml: {__html: option.option_price_html},
					data: {
						productData: option.option_product_data,
						priceData: option.option_price_data
					}
				}
			];
		}, []);

		const props = {
			key: args.id,
			id: args.id,
			label: args.title,
			help: args.description,
			selected: args.selected_option,
			options,
			multiple: args.multiple,
			onChange: option => {
				updateComponent(productId, args.id, option);
				calculateComponentSubtotals(productId, args.id, 1);
			}
		};

		if (args.options_style === "thumbnails") {
			return <ThumbnailsControl {...props} />;
		}

		if (args.multiple) {
			return <CheckboxControl {...props} />;
		}

		return <RadioControl {...props} />;
	}

	render() {
		const {
			productId,
			displayPopup,
			closePopup,
			components,
			productData,
			productTitle,
			quantity,
			updateProductQuantity,
			removeAddedToCart
		} = this.props;
		const {addingToCart} = this.state;
		const hasComponents = Array.isArray(components) && components.length;

		const modalBody = (
			<>
				{!hasComponents && (
					<Placeholder>
						<Spinner />
					</Placeholder>
				)}

				{!!hasComponents && (
					<div className="components-modal__body">
						{components.map(args => this.renderControl(args))}
					</div>
				)}
			</>
		);

		const modalBottom = (
			<div className="components-modal__bottom">
				{displayPopup && (
					<Button
						className={classnames("button")}
						onClick={() => {
							closePopup();
							removeAddedToCart(productId);
						}}
					>
						{__("Close")}
					</Button>
				)}

				<Button
					className={classnames("button", "button--add", {
						loading: addingToCart
					})}
					onClick={this.addToCart}
				>
					{__("Add to cart")}
				</Button>
			</div>
		);

		const renderedComponents = (
			<>
				<div className="components-modal__content components-modal__content--single">
					<ProductHeader
						productId={productId}
						productTitle={productTitle}
						productData={productData}
						quantity={quantity}
						updateProductQuantity={updateProductQuantity}
						priceHtml={this.get_price_html()}
					/>

					{modalBody}

					{modalBottom}
				</div>
			</>
		);

		const renderedComponentstTwoColumns = (
			<>
				<div className="components-modal__content components-modal__content--splitted">
					<ProductHeader
						productId={productId}
						productTitle={productTitle}
						productData={productData}
						quantity={quantity}
						updateProductQuantity={updateProductQuantity}
						priceHtml={this.get_price_html()}
					/>

					<div className="components-modal__wrap">
						<div className="components-modal__row">
							<div className="components-modal__col">
								<div className="components-modal__product-details">
									<h2>{productTitle}</h2>

									<div className="composite-product__thumbnail">
										<img
											src={productData.image_src}
											alt={productTitle}
										/>
									</div>

									<div className="composite-product__wrap-totals">
										<div
											className="composite-product__totals"
											dangerouslySetInnerHTML={this.get_price_html()}
										/>
										<TextControl
											className="composite-product__quantity"
											value={quantity}
											onChange={quantity =>
												updateProductQuantity(
													productId,
													quantity
												)
											}
											min={productData.min_quantity}
											max={
												0 < productData.max_quantity
													? productData.max_quantity
													: ""
											}
											type="number"
										/>
									</div>

									<p className="composite-product__description">
										{productData.description}
									</p>
								</div>
							</div>
							<div className="components-modal__col">
								{modalBody}
							</div>
						</div>
					</div>

					{modalBottom}
				</div>
			</>
		);

		const modalContent =
			"single" == productData.layout_style
				? renderedComponents
				: renderedComponentstTwoColumns;

		return (
			<ReactModal
				isOpen={displayPopup}
				appElement={document.getElementById("page")}
				style={{
					overlay: {
						position: "fixed",
						top: 0,
						left: 0,
						right: 0,
						bottom: 0,
						backgroundColor: "rgba(0, 0, 0, 0.1)",
						zIndex: 999
					},
					content: {
						// position: 'absolute',
						top: "0px",
						left: "0px",
						right: "0px",
						bottom: "0px",
						// minWidth: '500px',
						// maxWidth: '700px',
						// maxHeight: 'calc(100% - 15px - 15px)',
						// transform: 'translate(-50%, -50%)',
						border: "0",
						background: "transparent",
						overflow: "initial",
						// WebkitOverflowScrolling: 'touch',
						// borderRadius: '4px',
						margin: "0px auto",
						padding: "0px",
						outline: "none"
					}
				}}
			>
				{modalContent}
			</ReactModal>
		);
	}
}

const CompositeProductPopup = props => <CompositeModal {...props} />;

const applyWithSelect = withSelect((select, ownProps) => {
	const {
		displayPopup,
		getPopupProductId,
		getProductTitle,
		getComponents,
		getTotals,
		getPriceData,
		getProductQuantity,
		getProductData,
		addedToCart
	} = select("composite-products");

	const productId = getPopupProductId();
	const components = getComponents(productId) || [];

	return {
		displayPopup: displayPopup(),
		productId,
		productTitle: getProductTitle(productId),
		components,
		priceData: getPriceData(productId),
		totals: getTotals(productId),
		quantity: getProductQuantity(productId),
		productData: getProductData(productId),
		addedToCart: addedToCart(productId)
	};
});

const applyWithDispatch = withDispatch((dispatch, ownProps) => {
	const {
		closePopup,
		updateComponent,
		updateProductQuantity,
		updateAddedToCart,
		removeAddedToCart,
		calculateSubtotals,
		calculateComponentSubtotals
	} = dispatch("composite-products");

	return {
		closePopup,
		updateComponent,
		updateProductQuantity(productId, quantity) {
			updateProductQuantity(productId, quantity);
			calculateSubtotals(productId);
		},
		calculateComponentSubtotals,
		updateAddedToCart,
		removeAddedToCart
	};
});

export default compose(
	applyWithSelect,
	applyWithDispatch
)(CompositeProductPopup);
