import {
	TextControl,
} from "@wordpress/components"

const ProductHeader = (props) => (
    <div className="components-modal__head">
        <div className="components-modal__head-section components-modal__head-section--start">
            <h2 className="composite-product__title">
                {props.productTitle}
            </h2>
        </div>
        <div className="components-modal__head-section components-modal__head-section--end">
            <TextControl
                className="composite-product__quantity"
                value={props.quantity}
                onChange={quantity =>
                    props.updateProductQuantity(props.productId, quantity)
                }
                min={props.productData.min_quantity}
                max={
                    0 < props.productData.max_quantity
                        ? props.productData.max_quantity
                        : ""
                }
                type="number"
            />
            <div
                className="composite-product__totals"
                dangerouslySetInnerHTML={props.priceHtml}
            />
        </div>
    </div>
)

export default ProductHeader
