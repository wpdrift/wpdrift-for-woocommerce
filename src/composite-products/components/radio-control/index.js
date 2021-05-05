/**
 * External dependencies
 */
import { isEmpty } from 'lodash'
import classnames from 'classnames'

/**
 * WordPress dependencies
 */
import { withInstanceId } from '@wordpress/compose'

/**
 * WordPress dependencies
 */
import {
	BaseControl
} from '@wordpress/components'

function RadioControl({
    instanceId,
	label,
	className,
	selected,
	help,
	onChange,
	options = [],
}) {
	const id = `inspector-radio-control-${instanceId}`
	const onChangeValue = (event) => onChange(event.target.value)

	return (
		!isEmpty(options) && (
			<BaseControl
				label={label}
				id={id}
				help={help}
				className={classnames(
					className,
					'components-radio-control'
				)}
			>
				{options.map((option, index) => (
					<div
						key={`${id}-${index}`}
						className={ classnames( 'components-radio-control__option', { 'components-radio-control__option--selected': (option.value === selected) } ) }
					>
						<input
							id={`${id}-${index}`}
							className="components-radio-control__input"
							type="radio"
							name={id}
							value={option.value}
							onChange={onChangeValue}
							checked={option.value === selected}
							aria-describedby={
								!!help ? `${id}__help` : undefined
							}
						/>
						<label htmlFor={`${id}-${index}`}>
							<span className="components-radio-control__title">{ option.label }</span>
							<span className="components-radio-control__price" dangerouslySetInnerHTML={option.priceHtmml} />
						</label>
					</div>
				))}
			</BaseControl>
		)
	)
}

export default withInstanceId( RadioControl );
