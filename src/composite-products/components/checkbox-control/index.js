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
	BaseControl,
	Dashicon
} from '@wordpress/components'

function CheckboxControl({
    instanceId,
	label,
	className,
	selected,
	help,
	onChange,
	options = [],
}) {
	const id = `inspector-checkbox-control-${instanceId}`
	const onChangeValue = (event) => onChange(event.target.value)

	return (
		!isEmpty(options) && (
			<BaseControl
				label={label}
				id={id}
				help={help}
				className={classnames(
					className,
					'components-checkbox-control'
				)}
			>
				{options.map((option, index) => (
					<div
						key={`${id}-${index}`}
						className={ classnames( 'components-checkbox-control__option', { 'components-checkbox-control__option--selected': selected.includes(option.value) } ) }
					>
						<input
							id={`${id}-${index}`}
							className="components-checkbox-control__input"
							type="checkbox"
							name={id}
							value={option.value}
							onChange={onChangeValue}
							checked={selected.includes(option.value)}
							aria-describedby={
								!!help ? `${id}__help` : undefined
							}
						/>

						<label htmlFor={`${id}-${index}`}>
							<div
								className={ classnames( 'components-checkbox-control__checkbox', { 'components-checkbox-control__checkbox-checked': selected.includes(option.value) } ) }
							>
								{ selected.includes(option.value) ? (
									<Dashicon icon="yes" size="18" role="presentation" />
								) : null }
							</div>
							<span className="components-checkbox-control__title">{ option.label }</span>
							<span className="components-checkbox-control__price" dangerouslySetInnerHTML={option.priceHtmml} />
						</label>
					</div>
				))}
			</BaseControl>
		)
	)
}

export default withInstanceId( CheckboxControl );
