!function(t){var e={};function n(r){if(e[r])return e[r].exports;var o=e[r]={i:r,l:!1,exports:{}};return t[r].call(o.exports,o,o.exports,n),o.l=!0,o.exports}n.m=t,n.c=e,n.d=function(t,e,r){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:r})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var o in t)n.d(r,o,function(e){return t[e]}.bind(null,o));return r},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=49)}([function(t,e){!function(){t.exports=this.wp.element}()},function(t,e){!function(){t.exports=this.wp.components}()},function(t,e,n){var r;
/*!
  Copyright (c) 2017 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/!function(){"use strict";var n={}.hasOwnProperty;function o(){for(var t=[],e=0;e<arguments.length;e++){var r=arguments[e];if(r){var c=typeof r;if("string"===c||"number"===c)t.push(r);else if(Array.isArray(r)&&r.length){var a=o.apply(null,r);a&&t.push(a)}else if("object"===c)for(var i in r)n.call(r,i)&&r[i]&&t.push(i)}}return t.join(" ")}t.exports?(o.default=o,t.exports=o):void 0===(r=function(){return o}.apply(e,[]))||(t.exports=r)}()},function(t,e){!function(){t.exports=this.wp.data}()},function(t,e){t.exports=function(t,e,n){return e in t?Object.defineProperty(t,e,{value:n,enumerable:!0,configurable:!0,writable:!0}):t[e]=n,t}},function(t,e,n){var r=n(25),o=n(26),c=n(27);t.exports=function(t){return r(t)||o(t)||c()}},function(t,e){!function(){t.exports=this.wp.compose}()},function(t,e){!function(){t.exports=this.lodash}()},function(t,e){!function(){t.exports=this.wp.i18n}()},function(t,e){!function(){t.exports=this.jQuery}()},function(t,e){t.exports=function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}},function(t,e,n){"use strict";n.d(e,"a",(function(){return c}));var r=n(20),o=n.n(r);function c(t,e){return e=void 0!==e&&e,function(t,e){var n=wc_composite_params.currency_format_decimal_sep,r=wc_composite_params.currency_position,c=wc_composite_params.currency_symbol,a=wc_composite_params.currency_format_trim_zeros,i=wc_composite_params.currency_format_num_decimals,u="0a";if(e=void 0!==e&&e,"yes"==a&&i>0){for(var l=0;l<i;l++)n+="0";t=t.replace(n,"")}if(i>0){var s="";for(l=0;l<i;l++)s+="0";u="0."+s+"a"}var p=o()(t).format(u),d=e?c:'<span class="woocommerce-Price-currencySymbol">'+c+"</span>";p=String(p),"left"===r?p=d+p:"right"===r?p+=d:"left_space"===r?p=d+" "+p:"right_space"===r&&(p=p+" "+d);return p=e?p:'<span class="woocommerce-Price-amount amount">'+p+"</span>"}((n=t,r=wc_composite_params.currency_format_num_decimals,c=wc_composite_params.currency_format_decimal_sep,a=wc_composite_params.currency_format_thousand_sep,i=n,u=isNaN(r=Math.abs(r))?2:r,l=void 0===c?",":c,s=void 0===a?".":a,p=i<0?"-":"",d=parseInt(i=Math.abs(+i||0).toFixed(u),10)+"",m=(m=d.length)>3?m%3:0,p+(m?d.substr(0,m)+s:"")+d.substr(m).replace(/(\d{3})(?=\d)/g,"$1"+s)+(u?l+Math.abs(i-d).toFixed(u).slice(2):"")),e);var n,r,c,a,i,u,l,s,p,d,m}},function(t,e){!function(){t.exports=this.wp.blockEditor}()},function(t,e,n){"use strict";var r=n(0),o=n(1);e.a=function(t){return Object(r.createElement)("div",{className:"components-modal__head"},Object(r.createElement)("div",{className:"components-modal__head-section components-modal__head-section--start"},Object(r.createElement)("h2",{className:"composite-product__title"},t.productTitle)),Object(r.createElement)("div",{className:"components-modal__head-section components-modal__head-section--end"},Object(r.createElement)(o.TextControl,{className:"composite-product__quantity",value:t.quantity,onChange:function(e){return t.updateProductQuantity(t.productId,e)},min:t.productData.min_quantity,max:0<t.productData.max_quantity?t.productData.max_quantity:"",type:"number"}),Object(r.createElement)("div",{className:"composite-product__totals",dangerouslySetInnerHTML:t.priceHtml})))}},function(t,e){!function(){t.exports=this.wp.apiFetch}()},function(t,e){t.exports=function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}},function(t,e){function n(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}t.exports=function(t,e,r){return e&&n(t.prototype,e),r&&n(t,r),t}},function(t,e,n){var r=n(29),o=n(10);t.exports=function(t,e){return!e||"object"!==r(e)&&"function"!=typeof e?o(t):e}},function(t,e){function n(e){return t.exports=n=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},n(e)}t.exports=n},function(t,e,n){var r=n(30);t.exports=function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&r(t,e)}},function(t,e,n){var r,o;
/*! @preserve
 * numeral.js
 * version : 2.0.6
 * author : Adam Draper
 * license : MIT
 * http://adamwdraper.github.com/Numeral-js/
 */void 0===(o="function"==typeof(r=function(){var t,e,n,r,o,c={},a={},i={currentLocale:"en",zeroFormat:null,nullFormat:null,defaultFormat:"0,0",scalePercentBy100:!0},u={currentLocale:i.currentLocale,zeroFormat:i.zeroFormat,nullFormat:i.nullFormat,defaultFormat:i.defaultFormat,scalePercentBy100:i.scalePercentBy100};function l(t,e){this._input=t,this._value=e}return(t=function(n){var r,o,a,i;if(t.isNumeral(n))r=n.value();else if(0===n||void 0===n)r=0;else if(null===n||e.isNaN(n))r=null;else if("string"==typeof n)if(u.zeroFormat&&n===u.zeroFormat)r=0;else if(u.nullFormat&&n===u.nullFormat||!n.replace(/[^0-9]+/g,"").length)r=null;else{for(o in c)if((i="function"==typeof c[o].regexps.unformat?c[o].regexps.unformat():c[o].regexps.unformat)&&n.match(i)){a=c[o].unformat;break}r=(a=a||t._.stringToNumber)(n)}else r=Number(n)||null;return new l(n,r)}).version="2.0.6",t.isNumeral=function(t){return t instanceof l},t._=e={numberToFormat:function(e,n,r){var o,c,i,u,l,s,p,d,m=a[t.options.currentLocale],f=!1,_=!1,b="",h="",y=!1;if(e=e||0,i=Math.abs(e),t._.includes(n,"(")?(f=!0,n=n.replace(/[\(|\)]/g,"")):(t._.includes(n,"+")||t._.includes(n,"-"))&&(s=t._.includes(n,"+")?n.indexOf("+"):e<0?n.indexOf("-"):-1,n=n.replace(/[\+|\-]/g,"")),t._.includes(n,"a")&&(c=!!(c=n.match(/a(k|m|b|t)?/))&&c[1],t._.includes(n," a")&&(b=" "),n=n.replace(new RegExp(b+"a[kmbt]?"),""),i>=1e12&&!c||"t"===c?(b+=m.abbreviations.trillion,e/=1e12):i<1e12&&i>=1e9&&!c||"b"===c?(b+=m.abbreviations.billion,e/=1e9):i<1e9&&i>=1e6&&!c||"m"===c?(b+=m.abbreviations.million,e/=1e6):(i<1e6&&i>=1e3&&!c||"k"===c)&&(b+=m.abbreviations.thousand,e/=1e3)),t._.includes(n,"[.]")&&(_=!0,n=n.replace("[.]",".")),u=e.toString().split(".")[0],l=n.split(".")[1],p=n.indexOf(","),o=(n.split(".")[0].split(",")[0].match(/0/g)||[]).length,l?(t._.includes(l,"[")?(l=(l=l.replace("]","")).split("["),h=t._.toFixed(e,l[0].length+l[1].length,r,l[1].length)):h=t._.toFixed(e,l.length,r),u=h.split(".")[0],h=t._.includes(h,".")?m.delimiters.decimal+h.split(".")[1]:"",_&&0===Number(h.slice(1))&&(h="")):u=t._.toFixed(e,0,r),b&&!c&&Number(u)>=1e3&&b!==m.abbreviations.trillion)switch(u=String(Number(u)/1e3),b){case m.abbreviations.thousand:b=m.abbreviations.million;break;case m.abbreviations.million:b=m.abbreviations.billion;break;case m.abbreviations.billion:b=m.abbreviations.trillion}if(t._.includes(u,"-")&&(u=u.slice(1),y=!0),u.length<o)for(var v=o-u.length;v>0;v--)u="0"+u;return p>-1&&(u=u.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g,"$1"+m.delimiters.thousands)),0===n.indexOf(".")&&(u=""),d=u+h+(b||""),f?d=(f&&y?"(":"")+d+(f&&y?")":""):s>=0?d=0===s?(y?"-":"+")+d:d+(y?"-":"+"):y&&(d="-"+d),d},stringToNumber:function(t){var e,n,r,o=a[u.currentLocale],c=t,i={thousand:3,million:6,billion:9,trillion:12};if(u.zeroFormat&&t===u.zeroFormat)n=0;else if(u.nullFormat&&t===u.nullFormat||!t.replace(/[^0-9]+/g,"").length)n=null;else{for(e in n=1,"."!==o.delimiters.decimal&&(t=t.replace(/\./g,"").replace(o.delimiters.decimal,".")),i)if(r=new RegExp("[^a-zA-Z]"+o.abbreviations[e]+"(?:\\)|(\\"+o.currency.symbol+")?(?:\\))?)?$"),c.match(r)){n*=Math.pow(10,i[e]);break}n*=(t.split("-").length+Math.min(t.split("(").length-1,t.split(")").length-1))%2?1:-1,t=t.replace(/[^0-9\.]+/g,""),n*=Number(t)}return n},isNaN:function(t){return"number"==typeof t&&isNaN(t)},includes:function(t,e){return-1!==t.indexOf(e)},insert:function(t,e,n){return t.slice(0,n)+e+t.slice(n)},reduce:function(t,e){if(null===this)throw new TypeError("Array.prototype.reduce called on null or undefined");if("function"!=typeof e)throw new TypeError(e+" is not a function");var n,r=Object(t),o=r.length>>>0,c=0;if(3===arguments.length)n=arguments[2];else{for(;c<o&&!(c in r);)c++;if(c>=o)throw new TypeError("Reduce of empty array with no initial value");n=r[c++]}for(;c<o;c++)c in r&&(n=e(n,r[c],c,r));return n},multiplier:function(t){var e=t.toString().split(".");return e.length<2?1:Math.pow(10,e[1].length)},correctionFactor:function(){var t=Array.prototype.slice.call(arguments);return t.reduce((function(t,n){var r=e.multiplier(n);return t>r?t:r}),1)},toFixed:function(t,e,n,r){var o,c,a,i,u=t.toString().split("."),l=e-(r||0);return o=2===u.length?Math.min(Math.max(u[1].length,l),e):l,a=Math.pow(10,o),i=(n(t+"e+"+o)/a).toFixed(o),r>e-o&&(c=new RegExp("\\.?0{1,"+(r-(e-o))+"}$"),i=i.replace(c,"")),i}},t.options=u,t.formats=c,t.locales=a,t.locale=function(t){return t&&(u.currentLocale=t.toLowerCase()),u.currentLocale},t.localeData=function(t){if(!t)return a[u.currentLocale];if(t=t.toLowerCase(),!a[t])throw new Error("Unknown locale : "+t);return a[t]},t.reset=function(){for(var t in i)u[t]=i[t]},t.zeroFormat=function(t){u.zeroFormat="string"==typeof t?t:null},t.nullFormat=function(t){u.nullFormat="string"==typeof t?t:null},t.defaultFormat=function(t){u.defaultFormat="string"==typeof t?t:"0.0"},t.register=function(t,e,n){if(e=e.toLowerCase(),this[t+"s"][e])throw new TypeError(e+" "+t+" already registered.");return this[t+"s"][e]=n,n},t.validate=function(e,n){var r,o,c,a,i,u,l,s;if("string"!=typeof e&&(e+="",console.warn&&console.warn("Numeral.js: Value is not string. It has been co-erced to: ",e)),(e=e.trim()).match(/^\d+$/))return!0;if(""===e)return!1;try{l=t.localeData(n)}catch(e){l=t.localeData(t.locale())}return c=l.currency.symbol,i=l.abbreviations,r=l.delimiters.decimal,o="."===l.delimiters.thousands?"\\.":l.delimiters.thousands,!(null!==(s=e.match(/^[^\d]+/))&&(e=e.substr(1),s[0]!==c)||null!==(s=e.match(/[^\d]+$/))&&(e=e.slice(0,-1),s[0]!==i.thousand&&s[0]!==i.million&&s[0]!==i.billion&&s[0]!==i.trillion)||(u=new RegExp(o+"{2}"),e.match(/[^\d.,]/g)||(a=e.split(r)).length>2||(a.length<2?!a[0].match(/^\d+.*\d$/)||a[0].match(u):1===a[0].length?!a[0].match(/^\d+$/)||a[0].match(u)||!a[1].match(/^\d+$/):!a[0].match(/^\d+.*\d$/)||a[0].match(u)||!a[1].match(/^\d+$/))))},t.fn=l.prototype={clone:function(){return t(this)},format:function(e,n){var r,o,a,i=this._value,l=e||u.defaultFormat;if(n=n||Math.round,0===i&&null!==u.zeroFormat)o=u.zeroFormat;else if(null===i&&null!==u.nullFormat)o=u.nullFormat;else{for(r in c)if(l.match(c[r].regexps.format)){a=c[r].format;break}o=(a=a||t._.numberToFormat)(i,l,n)}return o},value:function(){return this._value},input:function(){return this._input},set:function(t){return this._value=Number(t),this},add:function(t){var n=e.correctionFactor.call(null,this._value,t);return this._value=e.reduce([this._value,t],(function(t,e,r,o){return t+Math.round(n*e)}),0)/n,this},subtract:function(t){var n=e.correctionFactor.call(null,this._value,t);return this._value=e.reduce([t],(function(t,e,r,o){return t-Math.round(n*e)}),Math.round(this._value*n))/n,this},multiply:function(t){return this._value=e.reduce([this._value,t],(function(t,n,r,o){var c=e.correctionFactor(t,n);return Math.round(t*c)*Math.round(n*c)/Math.round(c*c)}),1),this},divide:function(t){return this._value=e.reduce([this._value,t],(function(t,n,r,o){var c=e.correctionFactor(t,n);return Math.round(t*c)/Math.round(n*c)})),this},difference:function(e){return Math.abs(t(this._value).subtract(e).value())}},t.register("locale","en",{delimiters:{thousands:",",decimal:"."},abbreviations:{thousand:"k",million:"m",billion:"b",trillion:"t"},ordinal:function(t){var e=t%10;return 1==~~(t%100/10)?"th":1===e?"st":2===e?"nd":3===e?"rd":"th"},currency:{symbol:"$"}}),t.register("format","bps",{regexps:{format:/(BPS)/,unformat:/(BPS)/},format:function(e,n,r){var o,c=t._.includes(n," BPS")?" ":"";return e*=1e4,n=n.replace(/\s?BPS/,""),o=t._.numberToFormat(e,n,r),t._.includes(o,")")?((o=o.split("")).splice(-1,0,c+"BPS"),o=o.join("")):o=o+c+"BPS",o},unformat:function(e){return+(1e-4*t._.stringToNumber(e)).toFixed(15)}}),r={base:1024,suffixes:["B","KiB","MiB","GiB","TiB","PiB","EiB","ZiB","YiB"]},o="("+(o=(n={base:1e3,suffixes:["B","KB","MB","GB","TB","PB","EB","ZB","YB"]}).suffixes.concat(r.suffixes.filter((function(t){return n.suffixes.indexOf(t)<0}))).join("|")).replace("B","B(?!PS)")+")",t.register("format","bytes",{regexps:{format:/([0\s]i?b)/,unformat:new RegExp(o)},format:function(e,o,c){var a,i,u,l=t._.includes(o,"ib")?r:n,s=t._.includes(o," b")||t._.includes(o," ib")?" ":"";for(o=o.replace(/\s?i?b/,""),a=0;a<=l.suffixes.length;a++)if(i=Math.pow(l.base,a),u=Math.pow(l.base,a+1),null===e||0===e||e>=i&&e<u){s+=l.suffixes[a],i>0&&(e/=i);break}return t._.numberToFormat(e,o,c)+s},unformat:function(e){var o,c,a=t._.stringToNumber(e);if(a){for(o=n.suffixes.length-1;o>=0;o--){if(t._.includes(e,n.suffixes[o])){c=Math.pow(n.base,o);break}if(t._.includes(e,r.suffixes[o])){c=Math.pow(r.base,o);break}}a*=c||1}return a}}),t.register("format","currency",{regexps:{format:/(\$)/},format:function(e,n,r){var o,c,a=t.locales[t.options.currentLocale],i={before:n.match(/^([\+|\-|\(|\s|\$]*)/)[0],after:n.match(/([\+|\-|\)|\s|\$]*)$/)[0]};for(n=n.replace(/\s?\$\s?/,""),o=t._.numberToFormat(e,n,r),e>=0?(i.before=i.before.replace(/[\-\(]/,""),i.after=i.after.replace(/[\-\)]/,"")):e<0&&!t._.includes(i.before,"-")&&!t._.includes(i.before,"(")&&(i.before="-"+i.before),c=0;c<i.before.length;c++)switch(i.before[c]){case"$":o=t._.insert(o,a.currency.symbol,c);break;case" ":o=t._.insert(o," ",c+a.currency.symbol.length-1)}for(c=i.after.length-1;c>=0;c--)switch(i.after[c]){case"$":o=c===i.after.length-1?o+a.currency.symbol:t._.insert(o,a.currency.symbol,-(i.after.length-(1+c)));break;case" ":o=c===i.after.length-1?o+" ":t._.insert(o," ",-(i.after.length-(1+c)+a.currency.symbol.length-1))}return o}}),t.register("format","exponential",{regexps:{format:/(e\+|e-)/,unformat:/(e\+|e-)/},format:function(e,n,r){var o=("number"!=typeof e||t._.isNaN(e)?"0e+0":e.toExponential()).split("e");return n=n.replace(/e[\+|\-]{1}0/,""),t._.numberToFormat(Number(o[0]),n,r)+"e"+o[1]},unformat:function(e){var n=t._.includes(e,"e+")?e.split("e+"):e.split("e-"),r=Number(n[0]),o=Number(n[1]);return o=t._.includes(e,"e-")?o*=-1:o,t._.reduce([r,Math.pow(10,o)],(function(e,n,r,o){var c=t._.correctionFactor(e,n);return e*c*(n*c)/(c*c)}),1)}}),t.register("format","ordinal",{regexps:{format:/(o)/},format:function(e,n,r){var o=t.locales[t.options.currentLocale],c=t._.includes(n," o")?" ":"";return n=n.replace(/\s?o/,""),c+=o.ordinal(e),t._.numberToFormat(e,n,r)+c}}),t.register("format","percentage",{regexps:{format:/(%)/,unformat:/(%)/},format:function(e,n,r){var o,c=t._.includes(n," %")?" ":"";return t.options.scalePercentBy100&&(e*=100),n=n.replace(/\s?\%/,""),o=t._.numberToFormat(e,n,r),t._.includes(o,")")?((o=o.split("")).splice(-1,0,c+"%"),o=o.join("")):o=o+c+"%",o},unformat:function(e){var n=t._.stringToNumber(e);return t.options.scalePercentBy100?.01*n:n}}),t.register("format","time",{regexps:{format:/(:)/,unformat:/(:)/},format:function(t,e,n){var r=Math.floor(t/60/60),o=Math.floor((t-60*r*60)/60),c=Math.round(t-60*r*60-60*o);return r+":"+(o<10?"0"+o:o)+":"+(c<10?"0"+c:c)},unformat:function(t){var e=t.split(":"),n=0;return 3===e.length?(n+=60*Number(e[0])*60,n+=60*Number(e[1]),n+=Number(e[2])):2===e.length&&(n+=60*Number(e[0]),n+=Number(e[1])),Number(n)}}),t})?r.call(e,n,e,t):r)||(t.exports=o)},function(t,e,n){"use strict";var r=n(0),o=n(7),c=n(2),a=n.n(c),i=n(6),u=n(1);e.a=Object(i.withInstanceId)((function(t){var e=t.instanceId,n=t.label,c=t.className,i=t.selected,l=t.help,s=t.onChange,p=t.options,d=void 0===p?[]:p,m=t.multiple,f="inspector-thumbnail-control-".concat(e),_=function(t){return s(t.target.value)},b=m?"checkbox":"radio";return!Object(o.isEmpty)(d)&&Object(r.createElement)(u.BaseControl,{label:n,id:f,help:l,className:a()(c,"components-thumbnail-control")},d.map((function(t,e){var n=m?i.includes(t.value):t.value===i;return Object(r.createElement)("div",{key:"".concat(f,"-").concat(e),className:a()("components-thumbnail-control__option",{"components-thumbnail-control__option--selected":n})},Object(r.createElement)("div",{className:"components-thumbnail-control__wrap"},Object(r.createElement)("input",{id:"".concat(f,"-").concat(e),className:"components-thumbnail-control__input",type:b,name:f,value:t.value,onChange:_,checked:n,"aria-describedby":l?"".concat(f,"__help"):void 0}),n?Object(r.createElement)("div",{className:"components-thumbnail-control__checked"},Object(r.createElement)(u.Dashicon,{icon:"yes",size:"18",role:"presentation"})):null,Object(r.createElement)("label",{htmlFor:"".concat(f,"-").concat(e)},Object(r.createElement)("img",{className:"components-thumbnail-control__image",src:t.image,alt:t.label}),Object(r.createElement)("span",{className:"components-thumbnail-control__price",dangerouslySetInnerHTML:t.priceHtmml}),Object(r.createElement)("span",{className:"components-thumbnail-control__title"},t.label))))})))}))},function(t,e,n){"use strict";var r=n(0),o=n(7),c=n(2),a=n.n(c),i=n(6),u=n(1);e.a=Object(i.withInstanceId)((function(t){var e=t.instanceId,n=t.label,c=t.className,i=t.selected,l=t.help,s=t.onChange,p=t.options,d=void 0===p?[]:p,m="inspector-radio-control-".concat(e),f=function(t){return s(t.target.value)};return!Object(o.isEmpty)(d)&&Object(r.createElement)(u.BaseControl,{label:n,id:m,help:l,className:a()(c,"components-radio-control")},d.map((function(t,e){return Object(r.createElement)("div",{key:"".concat(m,"-").concat(e),className:a()("components-radio-control__option",{"components-radio-control__option--selected":t.value===i})},Object(r.createElement)("input",{id:"".concat(m,"-").concat(e),className:"components-radio-control__input",type:"radio",name:m,value:t.value,onChange:f,checked:t.value===i,"aria-describedby":l?"".concat(m,"__help"):void 0}),Object(r.createElement)("label",{htmlFor:"".concat(m,"-").concat(e)},Object(r.createElement)("span",{className:"components-radio-control__title"},t.label),Object(r.createElement)("span",{className:"components-radio-control__price",dangerouslySetInnerHTML:t.priceHtmml})))})))}))},function(t,e,n){"use strict";var r=n(0),o=n(7),c=n(2),a=n.n(c),i=n(6),u=n(1);e.a=Object(i.withInstanceId)((function(t){var e=t.instanceId,n=t.label,c=t.className,i=t.selected,l=t.help,s=t.onChange,p=t.options,d=void 0===p?[]:p,m="inspector-checkbox-control-".concat(e),f=function(t){return s(t.target.value)};return!Object(o.isEmpty)(d)&&Object(r.createElement)(u.BaseControl,{label:n,id:m,help:l,className:a()(c,"components-checkbox-control")},d.map((function(t,e){return Object(r.createElement)("div",{key:"".concat(m,"-").concat(e),className:a()("components-checkbox-control__option",{"components-checkbox-control__option--selected":i.includes(t.value)})},Object(r.createElement)("input",{id:"".concat(m,"-").concat(e),className:"components-checkbox-control__input",type:"checkbox",name:m,value:t.value,onChange:f,checked:i.includes(t.value),"aria-describedby":l?"".concat(m,"__help"):void 0}),Object(r.createElement)("label",{htmlFor:"".concat(m,"-").concat(e)},Object(r.createElement)("div",{className:a()("components-checkbox-control__checkbox",{"components-checkbox-control__checkbox-checked":i.includes(t.value)})},i.includes(t.value)?Object(r.createElement)(u.Dashicon,{icon:"yes",size:"18",role:"presentation"}):null),Object(r.createElement)("span",{className:"components-checkbox-control__title"},t.label),Object(r.createElement)("span",{className:"components-checkbox-control__price",dangerouslySetInnerHTML:t.priceHtmml})))})))}))},function(t,e,n){"use strict";var r=n(5),o=n.n(r),c=n(4),a=n.n(c),i=n(3),u=n(14),l=n.n(u),s=(n(28),n(8));function p(t,e){var n=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),n.push.apply(n,r)}return n}function d(t){for(var e=1;e<arguments.length;e++){var n=null!=arguments[e]?arguments[e]:{};e%2?p(Object(n),!0).forEach((function(e){a()(t,e,n[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(n)):p(Object(n)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(n,e))}))}return t}var m={displayPopup:!1,displayInlineComponents:!1,products:[],productSelected:null,popupProductId:null,productData:{},components:{},quantity:{},priceData:{},componentTotals:{},totals:{price:0,regular_price:0,price_incl_tax:0,price_excl_tax:0},addedToCart:{}};Object(i.registerStore)("composite-products",{reducer:function(){var t=arguments.length>0&&void 0!==arguments[0]?arguments[0]:m,e=arguments.length>1?arguments[1]:void 0,n=t.components,r=t.quantity;switch(e.type){case"DISPLAY_POPUP":return d({},t,{displayPopup:!0,popupProductId:e.productId});case"CLOSE_POPUP":return d({},t,{displayPopup:!1});case"DISPLAY_INLINE_COMPONENTS":return d({},t,{displayInlineComponents:!0,productSelected:e.productId});case"UPDATE_COMPONENTS":return d({},t,{components:d({},t.components,a()({},e.productId,e.components))});case"UPDATE_COMPONENT":var c=n[e.productId].map((function(t){if(t.id===e.id){if(t.multiple){var n=t.selected_option;return Array.isArray(n)||(n=n?[n]:[]),n.includes(e.option)?d({},t,{selected_option:n.reduce((function(t,n){return e.option===n?t:[].concat(o()(t),[n])}),[])}):d({},t,{selected_option:[].concat(o()(n),[e.option])})}return d({},t,{selected_option:"yes"===t.optional&&e.option===t.selected_option?"":e.option})}return t}));return d({},t,{components:d({},t.components,a()({},e.productId,c))});case"CALCULATE_SUBTOTALS":var i=void 0===r[e.productId]?1:parseInt(r[e.productId],10),u=n[e.productId].reduce((function(t,e){var n=e.quantity*i,r={price:0,regular_price:0,price_incl_tax:0,price_excl_tax:0};if(Array.isArray(e.selected_option))e.selected_option.forEach((function(t){var o=e.options.find((function(e){return e.option_id===t})).option_price_data;r.price+=n*o.price,r.regular_price+=n*o.regular_price,r.price_incl_tax+=n*o.price,r.price_excl_tax+=n*o.price}));else{var o=e.options.find((function(t){return t.option_id===e.selected_option})),c=void 0===o?r:o.option_price_data;r.price=n*c.price,r.regular_price=n*c.regular_price,r.price_incl_tax=n*c.price,r.price_excl_tax=n*c.price}return t[e.id]=r,t}),{});return d({},t,{componentTotals:u});case"CALCULATE_COMPONENT_SUBTOTALS":var l={price:0,regular_price:0,price_incl_tax:0,price_excl_tax:0},s=void 0===r[e.productId]?1:parseInt(r[e.productId],10),p=n[e.productId],f=p.find((function(t){return t.id===e.componentId}));if(Array.isArray(f.selected_option))f.selected_option.forEach((function(t){var e=f.options.find((function(e){return e.option_id===t})).option_price_data,n=f.quantity*s;l.price+=n*e.price,l.regular_price+=n*e.regular_price,l.price_incl_tax+=n*e.price,l.price_excl_tax+=n*e.price}));else{var _=f.options.find((function(t){return t.option_id===f.selected_option})),b=_.option_price_data,h=f.quantity*s;l.price=h*b.price,l.regular_price=h*b.regular_price,l.price_incl_tax=h*b.price,l.price_excl_tax=h*b.price}return d({},t,{componentTotals:d({},t.componentTotals,a()({},e.componentId,l))});case"UPDATE_PRICE_DATA":return d({},t,{priceData:d({},t.priceData,a()({},e.productId,e.priceData))});case"UPDATE_PRODUCT_QUANTITY":return d({},t,{quantity:d({},t.quantity,a()({},e.productId,e.quantity))});case"UPDATE_ADDED_TO_CART":return d({},t,{addedToCart:d({},t.addedToCart,a()({},e.productId,!0))});case"REMOVE_ADDED_TO_CART":return d({},t,{addedToCart:d({},t.addedToCart,a()({},e.productId,!1))});case"UPDATE_PRODUCT_DATA":return d({},t,{productData:d({},t.productData,a()({},e.productId,e.productData))})}return t},actions:{displayPopup:function(t){return{type:"DISPLAY_POPUP",productId:t}},displayInlineComponents:function(t){return{type:"DISPLAY_INLINE_COMPONENTS",productId:t}},closePopup:function(){return{type:"CLOSE_POPUP"}},fetchFromAPI:function(t){return{type:"FETCH_FROM_API",path:t}},updatedcomponents:function(t,e){return{type:"UPDATE_COMPONENTS",productId:t,components:e}},updatePriceData:function(t,e){return{type:"UPDATE_PRICE_DATA",productId:t,priceData:e}},updateComponent:function(t,e,n){return{type:"UPDATE_COMPONENT",productId:t,id:e,option:n}},updateProductQuantity:function(t,e){return{type:"UPDATE_PRODUCT_QUANTITY",productId:t,quantity:e}},updateAddedToCart:function(t){return{type:"UPDATE_ADDED_TO_CART",productId:t}},removeAddedToCart:function(t){return{type:"REMOVE_ADDED_TO_CART",productId:t}},calculateSubtotals:function(t){return{type:"CALCULATE_SUBTOTALS",productId:t}},calculateComponentSubtotals:function(t,e,n){return{type:"CALCULATE_COMPONENT_SUBTOTALS",productId:t,componentId:e,quantity:n}},updateProductData:function(t,e){return{type:"UPDATE_PRODUCT_DATA",productId:t,productData:e}}},selectors:{displayPopup:function(t){var e=t.displayPopup;return e},displayInlineComponents:function(t){var e=t.displayInlineComponents;return e},getComponents:function(t,e){return t.components[e]},getSelectedProductId:function(t){return t.productSelected},getPopupProductId:function(t){return t.popupProductId},getTotals:function(t,e){var n=t.components,r=t.componentTotals,o=t.quantity,c=t.priceData,a=void 0===o[e]?1:parseInt(o[e],10),i=c[e],u={price:0,regular_price:0,price_incl_tax:0,price_excl_tax:0};void 0!==i&&(u.price=i.base_price*a,u.regular_price=i.base_regular_price*a);var l=n[e];return void 0!==l&&l.forEach((function(t){var e=r[t.id];void 0!==e&&""!==t.selected_option&&(u.price+=e.price,u.regular_price+=e.regular_price,u.price_incl_tax+=e.price_incl_tax,u.price_excl_tax+=e.price_excl_tax)})),u},getPriceData:function(t,e){return t.priceData[e]},getProductQuantity:function(t,e){return t.quantity[e]||1},addedToCart:function(t,e){var n=t.addedToCart;return n[e]||!1},getProductData:function(t,e){var n=t.productData;return void 0===n[e]?{min_quantity:1,max_quantity:""}:n[e]},getProductTitle:function(t,e){var n=t.productData;return void 0===n[e]?Object(s.__)("No title"):n[e].title}},controls:{FETCH_FROM_API:function(t){return l()({path:t.path})}}})},function(t,e){t.exports=function(t){if(Array.isArray(t)){for(var e=0,n=new Array(t.length);e<t.length;e++)n[e]=t[e];return n}}},function(t,e){t.exports=function(t){if(Symbol.iterator in Object(t)||"[object Arguments]"===Object.prototype.toString.call(t))return Array.from(t)}},function(t,e){t.exports=function(){throw new TypeError("Invalid attempt to spread non-iterable instance")}},function(t,e){!function(){t.exports=this.wp.url}()},function(t,e){function n(t){return(n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function r(e){return"function"==typeof Symbol&&"symbol"===n(Symbol.iterator)?t.exports=r=function(t){return n(t)}:t.exports=r=function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":n(t)},r(e)}t.exports=r},function(t,e){function n(e,r){return t.exports=n=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},n(e,r)}t.exports=n},,,,,,,function(t,e,n){},,,,,,,,,,,,function(t,e,n){"use strict";n.r(e);var r,o=n(0),c=n(3),a=(n(24),n(5)),i=n.n(a),u=n(15),l=n.n(u),s=n(16),p=n.n(s),d=n(17),m=n.n(d),f=n(18),_=n.n(f),b=n(10),h=n.n(b),y=n(19),v=n.n(y),g=n(9),O=n.n(g),x=n(2),T=n.n(x),P=n(6),E=n(8),j=n(1),C=n(12),w=n(11),N=n(21),I=n(22),D=n(23),A=n(13),S=function(t){function e(t){var n;return l()(this,e),(n=m()(this,_()(e).call(this,t))).state={components:[],addingToCart:!1,addedToCart:!1},n.addToCart=n.addToCart.bind(h()(n)),n}return v()(e,t),p()(e,[{key:"addToCart",value:function(){var t=this.props,e=t.productId,n=t.quantity,r=t.updateAddedToCart,o=this.parseConfiguration(),c=this;c.setState({addingToCart:!0}),O.a.ajax({method:"POST",url:wpdriftSettings.root+"wpdrift/v1/add_to_cart",data:{product_id:e,quantity:n,config:o},success:function(t){!1===t&&location.reload()},complete:function(){O()(".widget_shopping_cart_content").empty(),O()(document.body).trigger("wc_fragment_refresh"),c.setState({addingToCart:!1,addedToCart:!0}),r(e)}})}},{key:"get_formatted_price_suffix",value:function(t){return""}},{key:"get_price_html",value:function(){var t=this.props.totals,e=wc_composite_params.i18n_total?'<span class="total">'+wc_composite_params.i18n_total+"</span>":"",n=Object(w.a)(t.price,!0),r=Object(w.a)(t.regular_price,!0),o=this.get_formatted_price_suffix(t);return t.regular_price>t.price&&(n=wc_composite_params.i18n_strikeout_price_string.replace("%f",r).replace("%t",n)),{__html:'<span class"price">'+wc_composite_params.i18n_price_format.replace("%t",e).replace("%p",n).replace("%s",o)+"</span>"}}},{key:"parseConfiguration",value:function(){return this.props.components.reduce((function(t,e){var n=e.selected_option,r=e.quantity,o=e.quantity_min,c=e.quantity_max,a=e.discount,u=e.optional,l=e.title,s=e.composite_id,p=e.type;if(""===n)return t;var d=void 0===t[e.id]?[]:t[e.id];if(Array.isArray(n)){var m=n.reduce((function(t,n){return[].concat(i()(t),[{product_id:n,quantity:r,quantity_min:o,quantity_max:c,discount:a,optional:u,static:e.static,title:l,composite_id:s,type:p}])}),[]);t[e.id]=[].concat(i()(d),i()(m))}else t[e.id]=[].concat(i()(d),[{product_id:n,quantity:r,quantity_min:o,quantity_max:c,discount:a,optional:u,static:e.static,title:l,composite_id:s,type:p}]);return t}),{})}},{key:"renderControl",value:function(t){var e=this.props,n=e.productId,r=e.updateComponent,c=e.calculateComponentSubtotals,a=t.options.reduce((function(t,e){var n=e.option_product_data;if("invalid-product"===n.product_type)return t;var r="";return void 0!==n&&(r=void 0!==n.image_data?n.image_data.image_src:""),[].concat(i()(t),[{label:e.option_title,value:e.option_id,image:r,priceHtmml:{__html:e.option_price_html},data:{productData:n,priceData:e.option_price_data}}])}),[]),u={key:t.id,id:t.id,label:t.title,help:t.description,selected:t.selected_option,options:a,multiple:t.multiple,onChange:function(e){r(n,t.id,e),c(n,t.id,1)}};return"thumbnails"===t.options_style?Object(o.createElement)(N.a,u):t.multiple?Object(o.createElement)(D.a,u):Object(o.createElement)(I.a,u)}},{key:"render",value:function(){var t=this,e=this.props,n=e.productId,r=e.components,c=e.productData,a=e.productTitle,i=e.quantity,u=e.updateProductQuantity,l=this.state,s=l.addingToCart,p=l.addedToCart,d=Array.isArray(r)&&r.length,m=Object(o.createElement)(o.Fragment,null,!d&&Object(o.createElement)(j.Placeholder,null,Object(o.createElement)(j.Spinner,null)),!!d&&Object(o.createElement)("div",{className:"components-modal__body"},r.map((function(e){return t.renderControl(e)})))),f=Object(o.createElement)("div",{className:"components-modal__bottom"},Object(o.createElement)(C.RichText.Content,{tagName:"a",className:T()("button","button--add",{loading:s}),value:p?Object(E.__)("Added to cart"):Object(E.__)("Add to cart"),onClick:this.addToCart})),_=Object(o.createElement)(o.Fragment,null,Object(o.createElement)("div",{className:"components-modal__content"},Object(o.createElement)(A.a,{productId:n,productTitle:a,productData:c,quantity:i,updateProductQuantity:u,priceHtml:this.get_price_html()}),m,f));return Object(o.createElement)("div",{className:"inline-components"},_)}}]),e}(o.Component),F=Object(c.withSelect)((function(t,e){var n=t("composite-products"),r=n.getSelectedProductId,o=n.getProductTitle,c=n.getComponents,a=n.getTotals,i=n.getPriceData,u=n.getProductQuantity,l=n.getProductData,s=r(),p=c(s)||[];return{productId:s,productTitle:o(s),components:p,priceData:i(s),totals:a(s),quantity:u(s),productData:l(s)}})),M=Object(c.withDispatch)((function(t,e){var n=t("composite-products"),r=n.updateComponent,o=n.updateProductQuantity,c=n.updateAddedToCart,a=n.calculateSubtotals;return{updateComponent:r,updateProductQuantity:function(t,e){o(t,e),a(t)},calculateComponentSubtotals:n.calculateComponentSubtotals,updateAddedToCart:c}})),k=Object(P.compose)(F,M)((function(t){return Object(o.createElement)(S,t)})),B=(n(37),B||{});r=jQuery,B.Composite=B.Composite||{},B.Composite.Product={start:function(){var t=Object(o.createElement)(k,null),e=document.getElementById("composite-components");e&&Object(o.render)(t,e);var n=wc_composite_params.composite_config.product_id;void 0!==n&&(this.setupData(),Object(c.dispatch)("composite-products").displayInlineComponents(n),Object(c.dispatch)("composite-products").calculateSubtotals(n))},setupData:function(){var t=wc_composite_params.composite_config.product_id,e=wc_composite_params.composite_config.product_data,n=wc_composite_params.composite_config.price_data,r=wc_composite_params.composite_config.components;Object(c.dispatch)("composite-products").updateProductData(t,e),Object(c.dispatch)("composite-products").updatePriceData(t,n),Object(c.dispatch)("composite-products").updatedcomponents(t,r)}},r(document).ready((function(){B.Composite.Product.start()}))}]);