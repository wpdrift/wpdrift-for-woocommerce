import numeral from "numeral";

/**
 * Converts numbers to formatted price strings. Respects WC price format settings.
 */
export function wc_price_format(price, plain) {
	plain = typeof(plain) === 'undefined' ? false : plain;
	return wc_woocommerce_number_format(wc_number_format(price), plain);
}

/**
 * Formats price strings according to WC settings.
 */
export function wc_woocommerce_number_format(price, plain) {
	var remove = wc_composite_params.currency_format_decimal_sep,
		position = wc_composite_params.currency_position,
		symbol = wc_composite_params.currency_symbol,
		trim_zeros = wc_composite_params.currency_format_trim_zeros,
		decimals = wc_composite_params.currency_format_num_decimals,
		format = '0a';

	plain = typeof(plain) === 'undefined' ? false : plain;

	if (trim_zeros == 'yes' && decimals > 0) {
		for (var i = 0; i < decimals; i++) {
			remove = remove + '0';
		}

		price = price.replace(remove, '');

	}

	if ( decimals > 0 ) {
		var zeros = '';
		for (var i = 0; i < decimals; i++) {
			zeros = zeros + '0';
		}

		format = '0.' + zeros + 'a';
	}

	var formatted_price = numeral(price).format(format),
		formatted_symbol = plain ? symbol : '<span class="woocommerce-Price-currencySymbol">' + symbol + '</span>';

	formatted_price = String(formatted_price);

	if ('left' === position) {
		formatted_price = formatted_symbol + formatted_price;
	} else if ('right' === position) {
		formatted_price = formatted_price + formatted_symbol;
	} else if ('left_space' === position) {
		formatted_price = formatted_symbol + ' ' + formatted_price;
	} else if ('right_space' === position) {
		formatted_price = formatted_price + ' ' + formatted_symbol;
	}

	formatted_price = plain ? formatted_price : '<span class="woocommerce-Price-amount amount">' + formatted_price + '</span>';

	return formatted_price;
}

/**
 * Formats price values according to WC settings.
 */
export function wc_number_format(number) {
	var decimals = wc_composite_params.currency_format_num_decimals;
	var decimal_sep = wc_composite_params.currency_format_decimal_sep;
	var thousands_sep = wc_composite_params.currency_format_thousand_sep;

	var n = number,
		c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
	var d = typeof(decimal_sep) === 'undefined' ? ',' : decimal_sep;
	var t = typeof(thousands_sep) === 'undefined' ? '.' : thousands_sep,
		s = n < 0 ? '-' : '';
	var i = parseInt(n = Math.abs(+n || 0).toFixed(c), 10) + '',
		j = (j = i.length) > 3 ? j % 3 : 0;

	return s + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
}
