/**
 * External dependencies
 */
import $ from "jquery";
import classnames from "classnames";

/**
 * WordPress dependencies
 */
import {Component} from "@wordpress/element";
import {withDispatch, withSelect} from "@wordpress/data";
import {compose} from "@wordpress/compose";
import {__} from "@wordpress/i18n";
import {Button, Placeholder, Spinner} from "@wordpress/components";
import {Icon, check} from "@wordpress/icons";

/**
 * Internal dependencies
 */
import {wc_price_format} from "./utility";
import ThumbnailsControl from "./components/thumbnails-control";
import RadioControl from "./components/radio-control";
import CheckboxControl from "./components/checkbox-control";
import ProductHeader from "./components/ProductHeader";

class Composite extends Component {
	constructor(props) {
		super(props);
		this.state = {
			components: [],
			addingToCart: false,
			addedToCart: false
		};

		this.addToCart = this.addToCart.bind(this);
	}

	addToCart() {
		const {productId, quantity, updateAddedToCart} = this.props;
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
				}
			},
			complete: function() {
				$(".widget_shopping_cart_content").empty();
				$(document.body).trigger("wc_fragment_refresh");
				self.setState({
					addingToCart: false,
					addedToCart: true
				});

				setTimeout(function() {
					self.setState({
						addedToCart: false
					});
				}, 1000);

				updateAddedToCart(productId);
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
		price_html = '<span class"price">' + price_html + "</span>";

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
			const productData = option.option_product_data;
			if (productData.product_type === "invalid-product") {
				return acc;
			}

			let imageSrc = "";
			if (productData !== undefined) {
				imageSrc =
					productData.image_data !== undefined
						? productData.image_data.image_src
						: "";
			}

			return [
				...acc,
				{
					label: option.option_title,
					value: option.option_id,
					image: imageSrc,
					priceHtmml: {__html: option.option_price_html},
					data: {
						productData,
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
			components,
			productData,
			productTitle,
			quantity,
			updateProductQuantity
		} = this.props;
		const {addingToCart, addedToCart} = this.state;
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
				<Button
					className={classnames("button", "button--add", {
						loading: addingToCart
					})}
					onClick={this.addToCart}
				>
					{addedToCart ? (
						<Icon icon={check} size={16} />
					) : (
						__("Add to cart")
					)}
				</Button>
			</div>
		);

		const renderedComponents = (
			<>
				<div className="components-modal__content">
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

		return <div className="inline-components">{renderedComponents}</div>;
	}
}

const CompositeProduct = props => <Composite {...props} />;

const applyWithSelect = withSelect((select, ownProps) => {
	const {
		getSelectedProductId,
		getProductTitle,
		getComponents,
		getTotals,
		getPriceData,
		getProductQuantity,
		getProductData
	} = select("composite-products");

	const productId = getSelectedProductId();
	const components = getComponents(productId) || [];
	return {
		productId,
		productTitle: getProductTitle(productId),
		components,
		priceData: getPriceData(productId),
		totals: getTotals(productId),
		quantity: getProductQuantity(productId),
		productData: getProductData(productId)
	};
});

const applyWithDispatch = withDispatch((dispatch, ownProps) => {
	const {
		updateComponent,
		updateProductQuantity,
		updateAddedToCart,
		calculateSubtotals,
		calculateComponentSubtotals
	} = dispatch("composite-products");

	return {
		updateComponent,
		updateProductQuantity(productId, quantity) {
			updateProductQuantity(productId, quantity);
			calculateSubtotals(productId);
		},
		calculateComponentSubtotals,
		updateAddedToCart
	};
});

export default compose(
	applyWithSelect,
	applyWithDispatch
)(CompositeProduct);
