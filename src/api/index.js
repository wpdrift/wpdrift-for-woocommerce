/**
 * WordPress dependencies
 */
import {
	registerStore
} from '@wordpress/data'

import apiFetch from '@wordpress/api-fetch'
import {
	addQueryArgs
} from '@wordpress/url'
import { __ } from '@wordpress/i18n'

const DEFAULT_STATE = {
	displayPopup: false,
	displayInlineComponents: false,
	products: [],
	productSelected: null,
	popupProductId: null,
	productData: {},
	components: {},
	quantity: {},
	priceData: {},
	componentTotals: {},
	totals: {
		price: 0.0,
		regular_price: 0.0,
		price_incl_tax: 0.0,
		price_excl_tax: 0.0
	},
	addedToCart: {}
}

const actions = {
	displayPopup(productId) {
		return {
			type: 'DISPLAY_POPUP',
			productId
		}
	},

	displayInlineComponents(productId) {
		return {
			type: 'DISPLAY_INLINE_COMPONENTS',
			productId
		}
	},

	closePopup() {
		return {
			type: 'CLOSE_POPUP'
		}
	},

	fetchFromAPI(path) {
		return {
			type: 'FETCH_FROM_API',
			path
		}
	},

	updatedcomponents(productId, components) {
		return {
			type: 'UPDATE_COMPONENTS',
			productId,
			components
		}
	},

	updatePriceData(productId, priceData) {
		return {
			type: 'UPDATE_PRICE_DATA',
			productId,
			priceData
		}
	},

	updateComponent(productId, id, option) {
		return {
			type: 'UPDATE_COMPONENT',
			productId,
			id,
			option
		}
	},

	updateProductQuantity(productId, quantity) {
		return {
			type: 'UPDATE_PRODUCT_QUANTITY',
			productId,
			quantity
		}
	},

	updateAddedToCart(productId) {
		return {
			type: 'UPDATE_ADDED_TO_CART',
			productId
		}
	},

	removeAddedToCart(productId) {
		return {
			type: 'REMOVE_ADDED_TO_CART',
			productId
		}
	},

	calculateSubtotals(productId) {
		return {
			type: 'CALCULATE_SUBTOTALS',
			productId
		}
	},

	calculateComponentSubtotals(productId, componentId, quantity) {
		return {
			type: 'CALCULATE_COMPONENT_SUBTOTALS',
			productId,
			componentId,
			quantity
		}
	},

	updateProductData(productId, productData) {
		return {
			type: 'UPDATE_PRODUCT_DATA',
			productId,
			productData
		}
	}
};

registerStore('composite-products', {
	reducer(state = DEFAULT_STATE, action) {
		const {
			components,
			quantity
		} = state
		switch (action.type) {
			case 'DISPLAY_POPUP':
				return {
					...state,
					displayPopup: true,
					popupProductId: action.productId
				}

			case 'CLOSE_POPUP':
				return {
					...state,
					displayPopup: false
				}

			case 'DISPLAY_INLINE_COMPONENTS':
				return {
					...state,
					displayInlineComponents: true,
					productSelected: action.productId
				}

			case 'UPDATE_COMPONENTS':
				return {
					...state,
					components: {
						...state.components,
						[action.productId]: action.components
					}
				}

			case 'UPDATE_COMPONENT':
				const updatedcomponents = components[action.productId].map(component => {
					if (component.id === action.id) {
						if (component.multiple) {
							let selectedOptions = component.selected_option
							if (!Array.isArray(selectedOptions)) {
								selectedOptions = selectedOptions ? [ selectedOptions ] : []
							}

							if (selectedOptions.includes(action.option)) {
								const updatedOptions = selectedOptions.reduce(function (acc, option) {
									if (action.option === option) {
										return acc
									}
								    return [ ...acc, option ]
								 }, [])

								 return {
	 								...component,
	 								selected_option: updatedOptions
	 							}
 							} else {
								const updatedOptions = [ ...selectedOptions, action.option ]
								return {
									...component,
									selected_option: updatedOptions
								}
 							}

						}
						const selectedOption = ((component.optional === 'yes') && (action.option === component.selected_option)) ? "" : action.option
						return {
							...component,
							selected_option: selectedOption
						}
					} else {
						return component
					}
				})

				return {
					...state,
					components: {
						...state.components,
						[action.productId]: updatedcomponents
					}
				}

			case 'CALCULATE_SUBTOTALS':
				const qty = typeof( quantity[action.productId] ) === 'undefined' ? 1 : parseInt( quantity[action.productId], 10 )

				const updatedComponentTotals = components[action.productId].reduce(function (acc, cp) {
					const componentQty = cp.quantity * qty;
					let totals = {
						price:          0.0,
						regular_price:  0.0,
						price_incl_tax: 0.0,
						price_excl_tax: 0.0
					}

					if (Array.isArray(cp.selected_option)) {
						cp.selected_option.forEach(function(optionId){
							const selectedOption = cp.options.find( ({ option_id }) => option_id === optionId )
							const priceData = selectedOption.option_price_data

							totals.price          += componentQty * priceData.price
							totals.regular_price  += componentQty * priceData.regular_price
							totals.price_incl_tax += componentQty * priceData.price
							totals.price_excl_tax += componentQty * priceData.price
						})
					} else {
						const selectedOption = cp.options.find( ({ option_id }) => option_id === cp.selected_option )
						const priceData = (selectedOption === undefined) ? totals : selectedOption.option_price_data

						totals.price          = componentQty * priceData.price
						totals.regular_price  = componentQty * priceData.regular_price
						totals.price_incl_tax = componentQty * priceData.price
						totals.price_excl_tax = componentQty * priceData.price
					}

					acc[cp.id] = totals
					return acc
				}, {})

				return {
					...state,
					componentTotals: updatedComponentTotals
				}

			case 'CALCULATE_COMPONENT_SUBTOTALS':
				let totals = {
					price:          0.0,
					regular_price:  0.0,
					price_incl_tax: 0.0,
					price_excl_tax: 0.0
				}

				const selectedQty = typeof( quantity[action.productId] ) === 'undefined' ? 1 : parseInt( quantity[action.productId], 10 )
				const selectedComponents = components[action.productId]
				const selectedComponent = selectedComponents.find( ({ id }) => id === action.componentId )

				if (Array.isArray(selectedComponent.selected_option)) {
					selectedComponent.selected_option.forEach(function(optionId){
						const selectedOption = selectedComponent.options.find( ({ option_id }) => option_id === optionId )
						const priceData = selectedOption.option_price_data
						const actualQty = selectedComponent.quantity * selectedQty;

						totals.price          += actualQty * priceData.price
						totals.regular_price  += actualQty * priceData.regular_price
						totals.price_incl_tax += actualQty * priceData.price
						totals.price_excl_tax += actualQty * priceData.price
					})
				} else {
					const selectedOption = selectedComponent.options.find( ({ option_id }) => option_id === selectedComponent.selected_option )
					const priceData = selectedOption.option_price_data
					const actualQty = selectedComponent.quantity * selectedQty;

					totals.price          = actualQty * priceData.price
					totals.regular_price  = actualQty * priceData.regular_price
					totals.price_incl_tax = actualQty * priceData.price
					totals.price_excl_tax = actualQty * priceData.price
				}

				return {
					...state,
					componentTotals: {
						...state.componentTotals,
						[action.componentId]: totals
					}
				}

			case 'UPDATE_PRICE_DATA':
				return {
					...state,
					priceData: {
						...state.priceData,
						[action.productId]: action.priceData
					}
				}

			case 'UPDATE_PRODUCT_QUANTITY':
				return {
					...state,
					quantity: {
						...state.quantity,
						[action.productId]: action.quantity
					}
				}

			case 'UPDATE_ADDED_TO_CART':
				return {
					...state,
					addedToCart: {
						...state.addedToCart,
						[action.productId]: true
					}
				}

			case 'REMOVE_ADDED_TO_CART':
				return {
					...state,
					addedToCart: {
						...state.addedToCart,
						[action.productId]: false
					}
				}

			case 'UPDATE_PRODUCT_DATA':
				return {
					...state,
					productData: {
						...state.productData,
						[action.productId]: action.productData
					}
				}
		}

		return state;
	},

	actions,

	selectors: {
		displayPopup(state) {
			const {
				displayPopup
			} = state

			return displayPopup
		},
		displayInlineComponents(state) {
			const {
				displayInlineComponents
			} = state

			return displayInlineComponents
		},
		getComponents(state, productId) {
			const {
				components
			} = state

			return components[productId]
		},
		getSelectedProductId(state) {
			const {
				productSelected
			} = state

			return productSelected
		},
		getPopupProductId(state) {
			const {
				popupProductId
			} = state

			return popupProductId
		},
		getTotals(state, productId) {
			const { components, componentTotals, quantity, priceData } = state
			const qty = typeof( quantity[productId] ) === 'undefined' ? 1 : parseInt( quantity[productId], 10 )
			const basePriceData = priceData[productId]

			let totals = {
				price:          0.0,
				regular_price:  0.0,
				price_incl_tax: 0.0,
				price_excl_tax: 0.0
			}

			if (basePriceData !== undefined) {
				totals.price = basePriceData.base_price * qty
				totals.regular_price = basePriceData.base_regular_price * qty
			}

			const comps = components[productId]
			if (comps !== undefined) {
				comps.forEach(function(component){
					const component_totals = componentTotals[component.id];
					if ((component_totals !== undefined) && (component.selected_option !== "")) {
						totals.price          += component_totals.price;
						totals.regular_price  += component_totals.regular_price;
						totals.price_incl_tax += component_totals.price_incl_tax;
						totals.price_excl_tax += component_totals.price_excl_tax;
					}
				})
			}

			return totals
		},
		getPriceData(state, productId) {
			const {
				priceData
			} = state

			return priceData[productId]
		},
		getProductQuantity(state, productId) {
			const {
				quantity
			} = state

			return quantity[productId] || 1
		},
		addedToCart(state, productId) {
			const {
				addedToCart
			} = state

			return addedToCart[productId] || false
		},
		getProductData(state, productId) {
			const {
				productData
			} = state

			if(productData[productId] === undefined) {
				return {
					min_quantity: 1,
					max_quantity: ''
				}
			}

			return productData[productId]
		},
		getProductTitle(state, productId) {
			const {
				productData
			} = state

			if(productData[productId] === undefined) {
				return __('No title')
			}

			return productData[productId].title
		}
	},

	controls: {
		FETCH_FROM_API(action) {
			return apiFetch({
				path: action.path
			})
		}
	}
})
