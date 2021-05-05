/**
 * External dependencies
 */
import { isEmpty } from 'lodash';
import classnames from 'classnames';

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
} from '@wordpress/components';

function ThumbnailsControl( {
	instanceId,
	label,
	className,
	selected,
	help,
	onChange,
	options = [],
	multiple
} ) {
	const id = `inspector-thumbnail-control-${instanceId}`
	const onChangeValue = ( event ) => onChange( event.target.value )
	const inputType = multiple ? 'checkbox' : 'radio'

	return ! isEmpty( options ) && (
		<BaseControl label={ label } id={ id } help={ help } className={ classnames( className, 'components-thumbnail-control' ) }>
			{ options.map( ( option, index ) => {
				const checked = multiple ? selected.includes(option.value) : option.value === selected

				return (
					<div
						key={ `${ id }-${ index }` }
						className={ classnames( 'components-thumbnail-control__option', { 'components-thumbnail-control__option--selected': checked } ) }
					>
						<div className="components-thumbnail-control__wrap">
							<input
								id={ `${ id }-${ index }` }
								className="components-thumbnail-control__input"
								type={ inputType }
								name={ id }
								value={ option.value }
								onChange={ onChangeValue }
								checked={ checked }
								aria-describedby={ !! help ? `${ id }__help` : undefined }
							/>

							{ checked ? (
								<div className="components-thumbnail-control__checked">
									<Dashicon icon="yes" size="18" role="presentation" />
								</div>
							) : null }

							<label htmlFor={ `${ id }-${ index }` }>
								<img className="components-thumbnail-control__image" src={option.image} alt={option.label} />
								<span className="components-thumbnail-control__price" dangerouslySetInnerHTML={option.priceHtmml} />
								<span className="components-thumbnail-control__title">{ option.label }</span>
							</label>
						</div>
					</div>
				)
			} )}
		</BaseControl>
	)
}

export default withInstanceId( ThumbnailsControl );
