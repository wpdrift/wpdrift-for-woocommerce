// import { Button, Dropdown } from '@wordpress/components';

import { useState } from '@wordpress/element';
import FieldOption from './FieldOption';

const Field = () => {
    const [ isChecked, setChecked ] = useState( true );

    const optionProps = {
        option_id: 42,
        option_title: 'Vneck Tshirt',
        option_product_data: {
            image_data: {
                image_src: "http://localhost/wp/woocommerce/wp-content/uploads/2019/10/vneck-tee-324x324.jpg"
            },
        },
        is_selected: false,
    };

    return (
        <div>
            <FieldOption 
                heading="User"
        		label="Is author"
        		help="Is the user a author or not?"
        		checked={ isChecked }
        		onChange={ setChecked }
            />
        </div>
    );
};

export default Field;
