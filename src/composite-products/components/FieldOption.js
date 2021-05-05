import { BaseControl, Dashicon } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { useInstanceId } from '@wordpress/compose';

function FieldOption( { label, className, heading, checked, help, onChange, ...props } ) {
    const { option_title, option_product_data } = props;
    const [ isChecked, setChecked ] = useState( true );

    // const instanceId = useInstanceId( FieldOption );
    const instanceId = '123';
	const id = `inspector-checkbox-control-${ instanceId }`;
	const onChangeValue = ( event ) => onChange( event.target.checked );

	return (
		<BaseControl label={ heading } id={ id } help={ help } className={ className }>
			<span className="components-checkbox-control__input-container">
				<input
					id={ id }
					className="components-checkbox-control__input"
					type="checkbox"
					value="1"
					onChange={ onChangeValue }
					checked={ checked }
					aria-describedby={ !! help ? id + '__help' : undefined }
					{ ...props }
				/>
				{ checked ? <Dashicon icon="yes" className="components-checkbox-control__checked" role="presentation" /> : null }
			</span>
			<label className="components-checkbox-control__label" htmlFor={ id }>
				{ label }
			</label>
		</BaseControl>
	);

    return (
        <div className="wd-field__option">
            <img className="wd-field__option-image" src={option_product_data.image_data.image_src} alt={option_title} />
            <span className="wd-field__option-title">{option_title}</span>
            <Dashicon icon="yes" className="components-checkbox-control__checked" role="presentation" />
        </div>
    );
};

export default FieldOption;
