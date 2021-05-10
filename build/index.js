/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js":
/*!*****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayLikeToArray.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) {
    arr2[i] = arr[i];
  }

  return arr2;
}

module.exports = _arrayLikeToArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return arrayLikeToArray(arr);
}

module.exports = _arrayWithoutHoles;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js":
/*!**********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/assertThisInitialized.js ***!
  \**********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return self;
}

module.exports = _assertThisInitialized;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/classCallCheck.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/classCallCheck.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

module.exports = _classCallCheck;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/createClass.js":
/*!************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/createClass.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  return Constructor;
}

module.exports = _createClass;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/defineProperty.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/defineProperty.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

module.exports = _defineProperty;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/getPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/getPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _getPrototypeOf(o) {
  module.exports = _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  module.exports["default"] = module.exports, module.exports.__esModule = true;
  return _getPrototypeOf(o);
}

module.exports = _getPrototypeOf;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/inherits.js":
/*!*********************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/inherits.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var setPrototypeOf = __webpack_require__(/*! ./setPrototypeOf.js */ "./node_modules/@babel/runtime/helpers/setPrototypeOf.js");

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }

  subClass.prototype = Object.create(superClass && superClass.prototype, {
    constructor: {
      value: subClass,
      writable: true,
      configurable: true
    }
  });
  if (superClass) setPrototypeOf(subClass, superClass);
}

module.exports = _inherits;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArray.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArray.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}

module.exports = _iterableToArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableSpread.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

module.exports = _nonIterableSpread;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js":
/*!**************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var _typeof = __webpack_require__(/*! @babel/runtime/helpers/typeof */ "./node_modules/@babel/runtime/helpers/typeof.js")["default"];

var assertThisInitialized = __webpack_require__(/*! ./assertThisInitialized.js */ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js");

function _possibleConstructorReturn(self, call) {
  if (call && (_typeof(call) === "object" || typeof call === "function")) {
    return call;
  }

  return assertThisInitialized(self);
}

module.exports = _possibleConstructorReturn;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/setPrototypeOf.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/setPrototypeOf.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _setPrototypeOf(o, p) {
  module.exports = _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  module.exports["default"] = module.exports, module.exports.__esModule = true;
  return _setPrototypeOf(o, p);
}

module.exports = _setPrototypeOf;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/toConsumableArray.js":
/*!******************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/toConsumableArray.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithoutHoles = __webpack_require__(/*! ./arrayWithoutHoles.js */ "./node_modules/@babel/runtime/helpers/arrayWithoutHoles.js");

var iterableToArray = __webpack_require__(/*! ./iterableToArray.js */ "./node_modules/@babel/runtime/helpers/iterableToArray.js");

var unsupportedIterableToArray = __webpack_require__(/*! ./unsupportedIterableToArray.js */ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js");

var nonIterableSpread = __webpack_require__(/*! ./nonIterableSpread.js */ "./node_modules/@babel/runtime/helpers/nonIterableSpread.js");

function _toConsumableArray(arr) {
  return arrayWithoutHoles(arr) || iterableToArray(arr) || unsupportedIterableToArray(arr) || nonIterableSpread();
}

module.exports = _toConsumableArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/typeof.js":
/*!*******************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/typeof.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) {
  "@babel/helpers - typeof";

  if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
    module.exports = _typeof = function _typeof(obj) {
      return typeof obj;
    };

    module.exports["default"] = module.exports, module.exports.__esModule = true;
  } else {
    module.exports = _typeof = function _typeof(obj) {
      return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
    };

    module.exports["default"] = module.exports, module.exports.__esModule = true;
  }

  return _typeof(obj);
}

module.exports = _typeof;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js":
/*!***************************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/unsupportedIterableToArray.js ***!
  \***************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayLikeToArray = __webpack_require__(/*! ./arrayLikeToArray.js */ "./node_modules/@babel/runtime/helpers/arrayLikeToArray.js");

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return arrayLikeToArray(o, minLen);
}

module.exports = _unsupportedIterableToArray;
module.exports["default"] = module.exports, module.exports.__esModule = true;

/***/ }),

/***/ "./node_modules/classnames/index.js":
/*!******************************************!*\
  !*** ./node_modules/classnames/index.js ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
  Copyright (c) 2018 Jed Watson.
  Licensed under the MIT License (MIT), see
  http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames() {
		var classes = [];

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (!arg) continue;

			var argType = typeof arg;

			if (argType === 'string' || argType === 'number') {
				classes.push(arg);
			} else if (Array.isArray(arg)) {
				if (arg.length) {
					var inner = classNames.apply(null, arg);
					if (inner) {
						classes.push(inner);
					}
				}
			} else if (argType === 'object') {
				if (arg.toString === Object.prototype.toString) {
					for (var key in arg) {
						if (hasOwn.call(arg, key) && arg[key]) {
							classes.push(key);
						}
					}
				} else {
					classes.push(arg.toString());
				}
			}
		}

		return classes.join(' ');
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./src/sass/composite-add-to-cart.scss":
/*!**************************************************************************************************************************!*\
  !*** ./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./src/sass/composite-add-to-cart.scss ***!
  \**************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// Imports
var ___CSS_LOADER_API_IMPORT___ = __webpack_require__(/*! ../../node_modules/css-loader/dist/runtime/api.js */ "./node_modules/css-loader/dist/runtime/api.js");
exports = ___CSS_LOADER_API_IMPORT___(false);
// Module
exports.push([module.i, ".inline-components .components-base-control,\n.components-modal__content--single .components-base-control,\n.components-modal__content--splitted .components-base-control {\n  display: block;\n  float: left;\n  width: 100%; }\n  .inline-components .components-base-control__label,\n  .components-modal__content--single .components-base-control__label,\n  .components-modal__content--splitted .components-base-control__label {\n    text-align: left; }\n  .inline-components .components-base-control__help,\n  .components-modal__content--single .components-base-control__help,\n  .components-modal__content--splitted .components-base-control__help {\n    margin-top: 0; }\n\n.inline-components .components-checkbox-control__price, .inline-components .components-checkbox-control__title,\n.inline-components .components-radio-control__price,\n.inline-components .components-radio-control__title,\n.components-modal__content--single .components-checkbox-control__price,\n.components-modal__content--single .components-checkbox-control__title,\n.components-modal__content--single .components-radio-control__price,\n.components-modal__content--single .components-radio-control__title,\n.components-modal__content--splitted .components-checkbox-control__price,\n.components-modal__content--splitted .components-checkbox-control__title,\n.components-modal__content--splitted .components-radio-control__price,\n.components-modal__content--splitted .components-radio-control__title {\n  display: block; }\n\n.inline-components .components-checkbox-control__price ins,\n.inline-components .components-radio-control__price ins,\n.components-modal__content--single .components-checkbox-control__price ins,\n.components-modal__content--single .components-radio-control__price ins,\n.components-modal__content--splitted .components-checkbox-control__price ins,\n.components-modal__content--splitted .components-radio-control__price ins {\n  background-color: inherit;\n  color: inherit; }\n\n.inline-components .components-checkbox-control__option,\n.inline-components .components-radio-control__option,\n.components-modal__content--single .components-checkbox-control__option,\n.components-modal__content--single .components-radio-control__option,\n.components-modal__content--splitted .components-checkbox-control__option,\n.components-modal__content--splitted .components-radio-control__option {\n  text-align: left; }\n  .inline-components .components-checkbox-control__option label,\n  .inline-components .components-radio-control__option label,\n  .components-modal__content--single .components-checkbox-control__option label,\n  .components-modal__content--single .components-radio-control__option label,\n  .components-modal__content--splitted .components-checkbox-control__option label,\n  .components-modal__content--splitted .components-radio-control__option label {\n    position: relative; }\n    .inline-components .components-checkbox-control__option label::before,\n    .inline-components .components-radio-control__option label::before,\n    .components-modal__content--single .components-checkbox-control__option label::before,\n    .components-modal__content--single .components-radio-control__option label::before,\n    .components-modal__content--splitted .components-checkbox-control__option label::before,\n    .components-modal__content--splitted .components-radio-control__option label::before {\n      content: \"\";\n      position: absolute;\n      top: 3px;\n      left: 0;\n      height: 18px;\n      width: 18px;\n      background-color: #eee; }\n    .inline-components .components-checkbox-control__option label::after,\n    .inline-components .components-radio-control__option label::after,\n    .components-modal__content--single .components-checkbox-control__option label::after,\n    .components-modal__content--single .components-radio-control__option label::after,\n    .components-modal__content--splitted .components-checkbox-control__option label::after,\n    .components-modal__content--splitted .components-radio-control__option label::after {\n      content: \"\";\n      display: none;\n      position: absolute; }\n    .inline-components .components-checkbox-control__option label:hover::before,\n    .inline-components .components-radio-control__option label:hover::before,\n    .components-modal__content--single .components-checkbox-control__option label:hover::before,\n    .components-modal__content--single .components-radio-control__option label:hover::before,\n    .components-modal__content--splitted .components-checkbox-control__option label:hover::before,\n    .components-modal__content--splitted .components-radio-control__option label:hover::before {\n      background-color: #ccc; }\n\n.inline-components .components-checkbox-control__option--selected label::before, .inline-components .components-checkbox-control__option--selected label:hover::before,\n.inline-components .components-radio-control__option--selected label::before,\n.inline-components .components-radio-control__option--selected label:hover::before,\n.components-modal__content--single .components-checkbox-control__option--selected label::before,\n.components-modal__content--single .components-checkbox-control__option--selected label:hover::before,\n.components-modal__content--single .components-radio-control__option--selected label::before,\n.components-modal__content--single .components-radio-control__option--selected label:hover::before,\n.components-modal__content--splitted .components-checkbox-control__option--selected label::before,\n.components-modal__content--splitted .components-checkbox-control__option--selected label:hover::before,\n.components-modal__content--splitted .components-radio-control__option--selected label::before,\n.components-modal__content--splitted .components-radio-control__option--selected label:hover::before {\n  background-color: #28a745; }\n\n.inline-components .components-checkbox-control__option--selected label::after,\n.inline-components .components-radio-control__option--selected label::after,\n.components-modal__content--single .components-checkbox-control__option--selected label::after,\n.components-modal__content--single .components-radio-control__option--selected label::after,\n.components-modal__content--splitted .components-checkbox-control__option--selected label::after,\n.components-modal__content--splitted .components-radio-control__option--selected label::after {\n  display: block; }\n\n.inline-components .components-checkbox-control input[type='checkbox'] + label,\n.inline-components .components-checkbox-control input[type='radio'] + label,\n.inline-components .components-radio-control input[type='checkbox'] + label,\n.inline-components .components-radio-control input[type='radio'] + label,\n.components-modal__content--single .components-checkbox-control input[type='checkbox'] + label,\n.components-modal__content--single .components-checkbox-control input[type='radio'] + label,\n.components-modal__content--single .components-radio-control input[type='checkbox'] + label,\n.components-modal__content--single .components-radio-control input[type='radio'] + label,\n.components-modal__content--splitted .components-checkbox-control input[type='checkbox'] + label,\n.components-modal__content--splitted .components-checkbox-control input[type='radio'] + label,\n.components-modal__content--splitted .components-radio-control input[type='checkbox'] + label,\n.components-modal__content--splitted .components-radio-control input[type='radio'] + label {\n  display: flex;\n  justify-content: space-between;\n  margin-bottom: 8px;\n  margin-left: 0;\n  padding-left: 2rem;\n  cursor: pointer; }\n\n.inline-components .components-checkbox-control__input,\n.inline-components .components-radio-control__input,\n.components-modal__content--single .components-checkbox-control__input,\n.components-modal__content--single .components-radio-control__input,\n.components-modal__content--splitted .components-checkbox-control__input,\n.components-modal__content--splitted .components-radio-control__input {\n  position: absolute;\n  opacity: 0;\n  cursor: pointer; }\n\n.inline-components .components-thumbnail-control,\n.components-modal__content--single .components-thumbnail-control,\n.components-modal__content--splitted .components-thumbnail-control {\n  display: flex;\n  flex-direction: column; }\n  .inline-components .components-thumbnail-control:hover,\n  .components-modal__content--single .components-thumbnail-control:hover,\n  .components-modal__content--splitted .components-thumbnail-control:hover {\n    cursor: pointer; }\n  .inline-components .components-thumbnail-control__image, .inline-components .components-thumbnail-control__price, .inline-components .components-thumbnail-control__title,\n  .components-modal__content--single .components-thumbnail-control__image,\n  .components-modal__content--single .components-thumbnail-control__price,\n  .components-modal__content--single .components-thumbnail-control__title,\n  .components-modal__content--splitted .components-thumbnail-control__image,\n  .components-modal__content--splitted .components-thumbnail-control__price,\n  .components-modal__content--splitted .components-thumbnail-control__title {\n    cursor: pointer; }\n  .inline-components .components-thumbnail-control__option,\n  .components-modal__content--single .components-thumbnail-control__option,\n  .components-modal__content--splitted .components-thumbnail-control__option {\n    font-size: 0.875rem; }\n  .inline-components .components-thumbnail-control__wrap,\n  .components-modal__content--single .components-thumbnail-control__wrap,\n  .components-modal__content--splitted .components-thumbnail-control__wrap {\n    display: block;\n    position: relative;\n    padding: .25rem;\n    border: 1px solid #ced4da;\n    border-radius: 0.25rem;\n    text-align: center;\n    cursor: pointer; }\n  .inline-components .components-thumbnail-control__option--selected .components-thumbnail-control__wrap,\n  .components-modal__content--single .components-thumbnail-control__option--selected .components-thumbnail-control__wrap,\n  .components-modal__content--splitted .components-thumbnail-control__option--selected .components-thumbnail-control__wrap {\n    background-color: #e9ecef; }\n  .inline-components .components-thumbnail-control__option:not(:last-child),\n  .components-modal__content--single .components-thumbnail-control__option:not(:last-child),\n  .components-modal__content--splitted .components-thumbnail-control__option:not(:last-child) {\n    margin-bottom: 4px; }\n  .inline-components .components-thumbnail-control__input[type=\"radio\"], .inline-components .components-thumbnail-control__input[type=\"checkbox\"],\n  .components-modal__content--single .components-thumbnail-control__input[type=\"radio\"],\n  .components-modal__content--single .components-thumbnail-control__input[type=\"checkbox\"],\n  .components-modal__content--splitted .components-thumbnail-control__input[type=\"radio\"],\n  .components-modal__content--splitted .components-thumbnail-control__input[type=\"checkbox\"] {\n    display: none; }\n    .inline-components .components-thumbnail-control__input[type=\"radio\"] + label, .inline-components .components-thumbnail-control__input[type=\"checkbox\"] + label,\n    .components-modal__content--single .components-thumbnail-control__input[type=\"radio\"] + label,\n    .components-modal__content--single .components-thumbnail-control__input[type=\"checkbox\"] + label,\n    .components-modal__content--splitted .components-thumbnail-control__input[type=\"radio\"] + label,\n    .components-modal__content--splitted .components-thumbnail-control__input[type=\"checkbox\"] + label {\n      margin: 0; }\n  .inline-components .components-thumbnail-control__checked,\n  .components-modal__content--single .components-thumbnail-control__checked,\n  .components-modal__content--splitted .components-thumbnail-control__checked {\n    display: inline-block;\n    width: 20px;\n    height: 20px;\n    line-height: 18px;\n    border-radius: 50%;\n    position: absolute;\n    top: 8px;\n    left: 8px;\n    z-index: 2;\n    background: #28a745;\n    color: #fff; }\n  .inline-components .components-thumbnail-control label,\n  .components-modal__content--single .components-thumbnail-control label,\n  .components-modal__content--splitted .components-thumbnail-control label {\n    position: relative;\n    display: block; }\n  .inline-components .components-thumbnail-control__image,\n  .components-modal__content--single .components-thumbnail-control__image,\n  .components-modal__content--splitted .components-thumbnail-control__image {\n    display: inline-block;\n    max-height: 3rem;\n    max-width: 3rem;\n    margin-right: 2rem; }\n  .inline-components .components-thumbnail-control__title,\n  .components-modal__content--single .components-thumbnail-control__title,\n  .components-modal__content--splitted .components-thumbnail-control__title {\n    display: block;\n    white-space: nowrap;\n    overflow: hidden;\n    text-overflow: ellipsis; }\n  .inline-components .components-thumbnail-control__price,\n  .components-modal__content--single .components-thumbnail-control__price,\n  .components-modal__content--splitted .components-thumbnail-control__price {\n    display: flex;\n    flex-direction: column;\n    position: absolute;\n    top: .25rem;\n    right: .25rem;\n    text-align: right; }\n    .inline-components .components-thumbnail-control__price del,\n    .inline-components .components-thumbnail-control__price ins,\n    .components-modal__content--single .components-thumbnail-control__price del,\n    .components-modal__content--single .components-thumbnail-control__price ins,\n    .components-modal__content--splitted .components-thumbnail-control__price del,\n    .components-modal__content--splitted .components-thumbnail-control__price ins {\n      display: block;\n      line-height: 1;\n      font-size: 0.875rem; }\n    .inline-components .components-thumbnail-control__price ins,\n    .components-modal__content--single .components-thumbnail-control__price ins,\n    .components-modal__content--splitted .components-thumbnail-control__price ins {\n      order: 1;\n      font-size: 1rem;\n      background-color: inherit;\n      color: inherit; }\n    .inline-components .components-thumbnail-control__price del,\n    .components-modal__content--single .components-thumbnail-control__price del,\n    .components-modal__content--splitted .components-thumbnail-control__price del {\n      order: 2; }\n\n.inline-components .components-thumbnail-control .components-base-control__field,\n.components-modal__content--single .components-thumbnail-control .components-base-control__field,\n.components-modal__content--splitted .components-thumbnail-control .components-base-control__field {\n  display: flex;\n  flex-wrap: wrap;\n  margin-right: -15px;\n  margin-left: -15px;\n  margin-right: -5px;\n  margin-left: -5px;\n  justify-content: center; }\n\n.inline-components .components-thumbnail-control .components-base-control__help, .inline-components .components-thumbnail-control .components-base-control__label,\n.components-modal__content--single .components-thumbnail-control .components-base-control__help,\n.components-modal__content--single .components-thumbnail-control .components-base-control__label,\n.components-modal__content--splitted .components-thumbnail-control .components-base-control__help,\n.components-modal__content--splitted .components-thumbnail-control .components-base-control__label {\n  position: relative;\n  width: 100%;\n  padding-right: 15px;\n  padding-left: 15px;\n  flex: 0 0 100%;\n  max-width: 100%;\n  padding-right: 5px;\n  padding-left: 5px; }\n\n.inline-components .components-thumbnail-control__option,\n.components-modal__content--single .components-thumbnail-control__option,\n.components-modal__content--splitted .components-thumbnail-control__option {\n  position: relative;\n  width: 100%;\n  padding-right: 15px;\n  padding-left: 15px;\n  padding-right: 5px;\n  padding-left: 5px;\n  text-align: center; }\n\n.inline-components .components-thumbnail-control__option,\n.components-modal__content--single .components-thumbnail-control__option {\n  flex: 0 0 25%;\n  max-width: 25%; }\n\n.summary__wrapper + .composite-components .inline-components .components-thumbnail-control__option,\n.components-modal__content--splitted .components-thumbnail-control__option {\n  flex: 0 0 50%;\n  max-width: 50%; }\n\n@media (max-width: 767.98px) {\n  .inline-components .components-thumbnail-control__option,\n  .summary__wrapper + .composite-components .inline-components .components-thumbnail-control__option,\n  .components-modal__content--single .components-thumbnail-control__option,\n  .components-modal__content--splitted .components-thumbnail-control__option {\n    flex: 0 0 100%;\n    max-width: 100%; } }\n\n.inline-components .components-radio-control__option,\n.components-modal__content--single .components-radio-control__option,\n.components-modal__content--splitted .components-radio-control__option {\n  font-size: 0.875rem; }\n  .inline-components .components-radio-control__option label::before,\n  .components-modal__content--single .components-radio-control__option label::before,\n  .components-modal__content--splitted .components-radio-control__option label::before {\n    border-radius: 50%; }\n  .inline-components .components-radio-control__option label::after,\n  .components-modal__content--single .components-radio-control__option label::after,\n  .components-modal__content--splitted .components-radio-control__option label::after {\n    top: 9px;\n    left: 6px;\n    width: 6px;\n    height: 6px;\n    border-radius: 50%;\n    background: white; }\n\n.components-modal__content--single .components-checkbox-control__option,\n.components-modal__content--splitted .components-checkbox-control__option,\n.inline-components .components-checkbox-control__option {\n  font-size: 0.875rem; }\n  .components-modal__content--single .components-checkbox-control__option label::after, .components-modal__content--single .components-checkbox-control__option label::before,\n  .components-modal__content--splitted .components-checkbox-control__option label::after,\n  .components-modal__content--splitted .components-checkbox-control__option label::before,\n  .inline-components .components-checkbox-control__option label::after,\n  .inline-components .components-checkbox-control__option label::before {\n    display: none; }\n\n.components-modal__content--single .components-checkbox-control__input[type=\"checkbox\"],\n.components-modal__content--splitted .components-checkbox-control__input[type=\"checkbox\"],\n.inline-components .components-checkbox-control__input[type=\"checkbox\"] {\n  display: none; }\n\n.components-modal__content--single .components-checkbox-control__checkbox,\n.components-modal__content--splitted .components-checkbox-control__checkbox,\n.inline-components .components-checkbox-control__checkbox {\n  display: inline-block;\n  width: 20px;\n  height: 20px;\n  line-height: 18px;\n  border-radius: 0.25rem;\n  position: absolute;\n  top: 3px;\n  left: 0;\n  z-index: 2;\n  background-color: #e9ecef;\n  color: #fff; }\n  .components-modal__content--single .components-checkbox-control__checkbox-checked,\n  .components-modal__content--splitted .components-checkbox-control__checkbox-checked,\n  .inline-components .components-checkbox-control__checkbox-checked {\n    background: #28a745; }\n\n@media (min-width: 992px) {\n  .storefront-full-width-content.single-product div.product-type-composite .summary__wrapper,\n  .single-product div.product-type-composite .summary__wrapper {\n    width: 35%;\n    float: left;\n    margin-right: 4.347826087%;\n    margin-bottom: 3.706325903em; }\n    .storefront-full-width-content.single-product div.product-type-composite .summary__wrapper .summary,\n    .storefront-full-width-content.single-product div.product-type-composite .summary__wrapper .woocommerce-product-gallery,\n    .single-product div.product-type-composite .summary__wrapper .summary,\n    .single-product div.product-type-composite .summary__wrapper .woocommerce-product-gallery {\n      width: 100%;\n      float: none;\n      margin: 0; }\n    .storefront-full-width-content.single-product div.product-type-composite .summary__wrapper + .composite-components,\n    .single-product div.product-type-composite .summary__wrapper + .composite-components {\n      width: 60%;\n      float: right;\n      margin-right: 0;\n      margin-bottom: 3.706325903em; } }\n\n.composite-product__wrap-totals {\n  display: flex;\n  justify-content: space-between;\n  align-items: center; }\n  .composite-product__wrap-totals .price del + ins {\n    margin-left: 0; }\n  .composite-product__wrap-totals .components-text-control__input {\n    width: 100%;\n    text-align: center; }\n\n.inline-components {\n  width: 100%;\n  display: block;\n  overflow: hidden;\n  margin-bottom: 2rem;\n  border: 1px solid #e9ecef;\n  border-radius: 0.25rem; }\n\n.components-placeholder {\n  margin-bottom: 0; }\n\n.ReactModal__Content {\n  display: flex;\n  align-items: center;\n  max-width: 700px; }\n\n.components-modal__row {\n  display: flex;\n  flex-wrap: wrap;\n  margin-right: -15px;\n  margin-left: -15px; }\n\n.components-modal__col {\n  position: relative;\n  width: 100%;\n  padding-right: 15px;\n  padding-left: 15px;\n  flex: 0 0 100%;\n  max-width: 100%; }\n\n.components-modal__body, .components-modal__bottom, .components-modal__head, .components-modal__product-details {\n  padding: 0.75rem 1.5rem; }\n\n.components-modal__head {\n  display: flex;\n  flex-wrap: wrap;\n  margin-right: -15px;\n  margin-left: -15px;\n  align-items: center;\n  border-bottom: 1px solid #e9ecef; }\n  .components-modal__head-section {\n    position: relative;\n    width: 100%;\n    padding-right: 15px;\n    padding-left: 15px;\n    flex: 0 0 50%;\n    max-width: 50%;\n    display: inline-flex;\n    align-items: center; }\n    .components-modal__head-section--start {\n      justify-content: flex-start; }\n    .components-modal__head-section--end {\n      justify-content: flex-end; }\n  .components-modal__head .components-text-control__input {\n    max-width: 100%;\n    text-align: center; }\n\n.components-modal__product-details h2 {\n  margin-bottom: 1rem;\n  font-size: 1.25rem; }\n\n.components-modal__body {\n  max-height: calc(100vh - 200px);\n  overflow-y: scroll;\n  /* width */\n  /* Track */\n  /* Handle */\n  /* Handle on hover */ }\n  .components-modal__body::-webkit-scrollbar {\n    width: 3px; }\n  .components-modal__body::-webkit-scrollbar-thumb {\n    background: #888; }\n  .components-modal__body::-webkit-scrollbar-thumb:hover {\n    background: #555; }\n\n.components-modal__bottom {\n  display: flex;\n  justify-content: flex-end;\n  border-top: 1px solid #e9ecef; }\n  .components-modal__bottom a.button {\n    margin-left: 0.75rem;\n    cursor: pointer; }\n    .components-modal__bottom a.button div {\n      display: inline-block; }\n\n.components-modal__head .composite-product__quantity, .components-modal__wrap .composite-product__quantity {\n  flex: 0 0 75px;\n  max-width: 75px; }\n\n.components-modal__head .composite-product__title, .components-modal__wrap .composite-product__title {\n  margin-bottom: 0;\n  white-space: nowrap;\n  overflow: hidden;\n  text-overflow: ellipsis; }\n\n.components-modal__head .composite-product__description, .components-modal__wrap .composite-product__description {\n  font-size: 0.875rem; }\n\n.components-modal__head .composite-product__title, .components-modal__head .composite-product__totals, .components-modal__wrap .composite-product__title, .components-modal__wrap .composite-product__totals {\n  font-size: 1.5rem;\n  color: #000; }\n\n.components-modal__head .composite-product__totals, .components-modal__wrap .composite-product__totals {\n  flex: 0 0 100px;\n  max-width: 100px;\n  margin-bottom: 0;\n  text-align: right; }\n  .components-modal__head .composite-product__totals .price, .components-modal__wrap .composite-product__totals .price {\n    margin-bottom: 0; }\n  .components-modal__head .composite-product__totals del,\n  .components-modal__head .composite-product__totals ins, .components-modal__wrap .composite-product__totals del,\n  .components-modal__wrap .composite-product__totals ins {\n    display: block;\n    line-height: 1; }\n  .components-modal__head .composite-product__totals del, .components-modal__wrap .composite-product__totals del {\n    font-size: 1.25rem; }\n\n.components-modal__wrap .composite-product__quantity-control {\n  display: flex;\n  justify-content: flex-end; }\n\n.components-modal__wrap .composite-product__totals .price {\n  text-align: left; }\n\n.components-modal__wrap .components-base-control__field {\n  margin-left: 0; }\n\n.components-modal__product-details {\n  display: none;\n  padding: 0.75rem 1.5rem; }\n\n@media (min-width: 992px) {\n  .components-modal__content--splitted .components-modal__head {\n    display: none; }\n  .components-modal__content--splitted .components-modal__product-details {\n    padding-right: 0; }\n  .components-modal__content--splitted .components-modal__body {\n    padding-left: 0; }\n  .components-modal__product-details {\n    display: block; }\n    .components-modal__product-details .components-base-control {\n      display: inline-block;\n      width: auto; }\n  .components-modal__col {\n    flex: 0 0 50%;\n    max-width: 50%; }\n    .components-modal__col:first-child::before {\n      content: '';\n      position: absolute;\n      top: 16px;\n      right: 0;\n      width: 1px;\n      height: calc(100vh - 232px);\n      background-color: #e9ecef; } }\n\n.components-number-control .components-base-control__field {\n  position: relative;\n  display: flex;\n  flex-wrap: wrap;\n  align-items: stretch;\n  width: 100%; }\n\n.components-modal__body .components-base-control {\n  padding-bottom: 1rem;\n  margin-bottom: 1rem;\n  border-bottom: 1px solid #e9ecef; }\n  .components-modal__body .components-base-control:last-child {\n    margin-bottom: 0;\n    border-bottom: 0; }\n  .components-modal__body .components-base-control__help {\n    margin-bottom: 0; }\n\n.components-modal__content {\n  position: relative;\n  display: flex;\n  flex-direction: column;\n  width: 100%;\n  pointer-events: auto;\n  background-color: #fff;\n  background-clip: padding-box;\n  border: 1px solid rgba(0, 0, 0, 0.2);\n  border-radius: 0.3rem;\n  outline: 0; }\n\n.summary .inline-components .button--add {\n  display: block;\n  width: 100%;\n  margin-left: 0;\n  text-align: center; }\n\n.single-product div.product .inline-components p.price {\n  margin: 0; }\n\n.components-modal__content--splitted .composite-product__thumbnail, .components-modal__content--splitted .composite-product__wrap-totals {\n  margin-bottom: 1rem; }\n\n@media (max-width: 767.98px) {\n  .ReactModal__Content {\n    max-width: 100%;\n    align-items: end; }\n  .components-modal__content {\n    border: 0;\n    border-radius: 0; }\n  .components-modal__body {\n    max-height: calc(100vh - 209px); } }\n", ""]);
// Exports
module.exports = exports;


/***/ }),

/***/ "./node_modules/css-loader/dist/runtime/api.js":
/*!*****************************************************!*\
  !*** ./node_modules/css-loader/dist/runtime/api.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
// eslint-disable-next-line func-names
module.exports = function (useSourceMap) {
  var list = []; // return the list of modules as css string

  list.toString = function toString() {
    return this.map(function (item) {
      var content = cssWithMappingToString(item, useSourceMap);

      if (item[2]) {
        return "@media ".concat(item[2], " {").concat(content, "}");
      }

      return content;
    }).join('');
  }; // import a list of modules into the list
  // eslint-disable-next-line func-names


  list.i = function (modules, mediaQuery, dedupe) {
    if (typeof modules === 'string') {
      // eslint-disable-next-line no-param-reassign
      modules = [[null, modules, '']];
    }

    var alreadyImportedModules = {};

    if (dedupe) {
      for (var i = 0; i < this.length; i++) {
        // eslint-disable-next-line prefer-destructuring
        var id = this[i][0];

        if (id != null) {
          alreadyImportedModules[id] = true;
        }
      }
    }

    for (var _i = 0; _i < modules.length; _i++) {
      var item = [].concat(modules[_i]);

      if (dedupe && alreadyImportedModules[item[0]]) {
        // eslint-disable-next-line no-continue
        continue;
      }

      if (mediaQuery) {
        if (!item[2]) {
          item[2] = mediaQuery;
        } else {
          item[2] = "".concat(mediaQuery, " and ").concat(item[2]);
        }
      }

      list.push(item);
    }
  };

  return list;
};

function cssWithMappingToString(item, useSourceMap) {
  var content = item[1] || ''; // eslint-disable-next-line prefer-destructuring

  var cssMapping = item[3];

  if (!cssMapping) {
    return content;
  }

  if (useSourceMap && typeof btoa === 'function') {
    var sourceMapping = toComment(cssMapping);
    var sourceURLs = cssMapping.sources.map(function (source) {
      return "/*# sourceURL=".concat(cssMapping.sourceRoot || '').concat(source, " */");
    });
    return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
  }

  return [content].join('\n');
} // Adapted from convert-source-map (MIT)


function toComment(sourceMap) {
  // eslint-disable-next-line no-undef
  var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
  var data = "sourceMappingURL=data:application/json;charset=utf-8;base64,".concat(base64);
  return "/*# ".concat(data, " */");
}

/***/ }),

/***/ "./node_modules/numeral/numeral.js":
/*!*****************************************!*\
  !*** ./node_modules/numeral/numeral.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_RESULT__;/*! @preserve
 * numeral.js
 * version : 2.0.6
 * author : Adam Draper
 * license : MIT
 * http://adamwdraper.github.com/Numeral-js/
 */

(function (global, factory) {
    if (true) {
        !(__WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.call(exports, __webpack_require__, exports, module)) :
				__WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else {}
}(this, function () {
    /************************************
        Variables
    ************************************/

    var numeral,
        _,
        VERSION = '2.0.6',
        formats = {},
        locales = {},
        defaults = {
            currentLocale: 'en',
            zeroFormat: null,
            nullFormat: null,
            defaultFormat: '0,0',
            scalePercentBy100: true
        },
        options = {
            currentLocale: defaults.currentLocale,
            zeroFormat: defaults.zeroFormat,
            nullFormat: defaults.nullFormat,
            defaultFormat: defaults.defaultFormat,
            scalePercentBy100: defaults.scalePercentBy100
        };


    /************************************
        Constructors
    ************************************/

    // Numeral prototype object
    function Numeral(input, number) {
        this._input = input;

        this._value = number;
    }

    numeral = function(input) {
        var value,
            kind,
            unformatFunction,
            regexp;

        if (numeral.isNumeral(input)) {
            value = input.value();
        } else if (input === 0 || typeof input === 'undefined') {
            value = 0;
        } else if (input === null || _.isNaN(input)) {
            value = null;
        } else if (typeof input === 'string') {
            if (options.zeroFormat && input === options.zeroFormat) {
                value = 0;
            } else if (options.nullFormat && input === options.nullFormat || !input.replace(/[^0-9]+/g, '').length) {
                value = null;
            } else {
                for (kind in formats) {
                    regexp = typeof formats[kind].regexps.unformat === 'function' ? formats[kind].regexps.unformat() : formats[kind].regexps.unformat;

                    if (regexp && input.match(regexp)) {
                        unformatFunction = formats[kind].unformat;

                        break;
                    }
                }

                unformatFunction = unformatFunction || numeral._.stringToNumber;

                value = unformatFunction(input);
            }
        } else {
            value = Number(input)|| null;
        }

        return new Numeral(input, value);
    };

    // version number
    numeral.version = VERSION;

    // compare numeral object
    numeral.isNumeral = function(obj) {
        return obj instanceof Numeral;
    };

    // helper functions
    numeral._ = _ = {
        // formats numbers separators, decimals places, signs, abbreviations
        numberToFormat: function(value, format, roundingFunction) {
            var locale = locales[numeral.options.currentLocale],
                negP = false,
                optDec = false,
                leadingCount = 0,
                abbr = '',
                trillion = 1000000000000,
                billion = 1000000000,
                million = 1000000,
                thousand = 1000,
                decimal = '',
                neg = false,
                abbrForce, // force abbreviation
                abs,
                min,
                max,
                power,
                int,
                precision,
                signed,
                thousands,
                output;

            // make sure we never format a null value
            value = value || 0;

            abs = Math.abs(value);

            // see if we should use parentheses for negative number or if we should prefix with a sign
            // if both are present we default to parentheses
            if (numeral._.includes(format, '(')) {
                negP = true;
                format = format.replace(/[\(|\)]/g, '');
            } else if (numeral._.includes(format, '+') || numeral._.includes(format, '-')) {
                signed = numeral._.includes(format, '+') ? format.indexOf('+') : value < 0 ? format.indexOf('-') : -1;
                format = format.replace(/[\+|\-]/g, '');
            }

            // see if abbreviation is wanted
            if (numeral._.includes(format, 'a')) {
                abbrForce = format.match(/a(k|m|b|t)?/);

                abbrForce = abbrForce ? abbrForce[1] : false;

                // check for space before abbreviation
                if (numeral._.includes(format, ' a')) {
                    abbr = ' ';
                }

                format = format.replace(new RegExp(abbr + 'a[kmbt]?'), '');

                if (abs >= trillion && !abbrForce || abbrForce === 't') {
                    // trillion
                    abbr += locale.abbreviations.trillion;
                    value = value / trillion;
                } else if (abs < trillion && abs >= billion && !abbrForce || abbrForce === 'b') {
                    // billion
                    abbr += locale.abbreviations.billion;
                    value = value / billion;
                } else if (abs < billion && abs >= million && !abbrForce || abbrForce === 'm') {
                    // million
                    abbr += locale.abbreviations.million;
                    value = value / million;
                } else if (abs < million && abs >= thousand && !abbrForce || abbrForce === 'k') {
                    // thousand
                    abbr += locale.abbreviations.thousand;
                    value = value / thousand;
                }
            }

            // check for optional decimals
            if (numeral._.includes(format, '[.]')) {
                optDec = true;
                format = format.replace('[.]', '.');
            }

            // break number and format
            int = value.toString().split('.')[0];
            precision = format.split('.')[1];
            thousands = format.indexOf(',');
            leadingCount = (format.split('.')[0].split(',')[0].match(/0/g) || []).length;

            if (precision) {
                if (numeral._.includes(precision, '[')) {
                    precision = precision.replace(']', '');
                    precision = precision.split('[');
                    decimal = numeral._.toFixed(value, (precision[0].length + precision[1].length), roundingFunction, precision[1].length);
                } else {
                    decimal = numeral._.toFixed(value, precision.length, roundingFunction);
                }

                int = decimal.split('.')[0];

                if (numeral._.includes(decimal, '.')) {
                    decimal = locale.delimiters.decimal + decimal.split('.')[1];
                } else {
                    decimal = '';
                }

                if (optDec && Number(decimal.slice(1)) === 0) {
                    decimal = '';
                }
            } else {
                int = numeral._.toFixed(value, 0, roundingFunction);
            }

            // check abbreviation again after rounding
            if (abbr && !abbrForce && Number(int) >= 1000 && abbr !== locale.abbreviations.trillion) {
                int = String(Number(int) / 1000);

                switch (abbr) {
                    case locale.abbreviations.thousand:
                        abbr = locale.abbreviations.million;
                        break;
                    case locale.abbreviations.million:
                        abbr = locale.abbreviations.billion;
                        break;
                    case locale.abbreviations.billion:
                        abbr = locale.abbreviations.trillion;
                        break;
                }
            }


            // format number
            if (numeral._.includes(int, '-')) {
                int = int.slice(1);
                neg = true;
            }

            if (int.length < leadingCount) {
                for (var i = leadingCount - int.length; i > 0; i--) {
                    int = '0' + int;
                }
            }

            if (thousands > -1) {
                int = int.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + locale.delimiters.thousands);
            }

            if (format.indexOf('.') === 0) {
                int = '';
            }

            output = int + decimal + (abbr ? abbr : '');

            if (negP) {
                output = (negP && neg ? '(' : '') + output + (negP && neg ? ')' : '');
            } else {
                if (signed >= 0) {
                    output = signed === 0 ? (neg ? '-' : '+') + output : output + (neg ? '-' : '+');
                } else if (neg) {
                    output = '-' + output;
                }
            }

            return output;
        },
        // unformats numbers separators, decimals places, signs, abbreviations
        stringToNumber: function(string) {
            var locale = locales[options.currentLocale],
                stringOriginal = string,
                abbreviations = {
                    thousand: 3,
                    million: 6,
                    billion: 9,
                    trillion: 12
                },
                abbreviation,
                value,
                i,
                regexp;

            if (options.zeroFormat && string === options.zeroFormat) {
                value = 0;
            } else if (options.nullFormat && string === options.nullFormat || !string.replace(/[^0-9]+/g, '').length) {
                value = null;
            } else {
                value = 1;

                if (locale.delimiters.decimal !== '.') {
                    string = string.replace(/\./g, '').replace(locale.delimiters.decimal, '.');
                }

                for (abbreviation in abbreviations) {
                    regexp = new RegExp('[^a-zA-Z]' + locale.abbreviations[abbreviation] + '(?:\\)|(\\' + locale.currency.symbol + ')?(?:\\))?)?$');

                    if (stringOriginal.match(regexp)) {
                        value *= Math.pow(10, abbreviations[abbreviation]);
                        break;
                    }
                }

                // check for negative number
                value *= (string.split('-').length + Math.min(string.split('(').length - 1, string.split(')').length - 1)) % 2 ? 1 : -1;

                // remove non numbers
                string = string.replace(/[^0-9\.]+/g, '');

                value *= Number(string);
            }

            return value;
        },
        isNaN: function(value) {
            return typeof value === 'number' && isNaN(value);
        },
        includes: function(string, search) {
            return string.indexOf(search) !== -1;
        },
        insert: function(string, subString, start) {
            return string.slice(0, start) + subString + string.slice(start);
        },
        reduce: function(array, callback /*, initialValue*/) {
            if (this === null) {
                throw new TypeError('Array.prototype.reduce called on null or undefined');
            }

            if (typeof callback !== 'function') {
                throw new TypeError(callback + ' is not a function');
            }

            var t = Object(array),
                len = t.length >>> 0,
                k = 0,
                value;

            if (arguments.length === 3) {
                value = arguments[2];
            } else {
                while (k < len && !(k in t)) {
                    k++;
                }

                if (k >= len) {
                    throw new TypeError('Reduce of empty array with no initial value');
                }

                value = t[k++];
            }
            for (; k < len; k++) {
                if (k in t) {
                    value = callback(value, t[k], k, t);
                }
            }
            return value;
        },
        /**
         * Computes the multiplier necessary to make x >= 1,
         * effectively eliminating miscalculations caused by
         * finite precision.
         */
        multiplier: function (x) {
            var parts = x.toString().split('.');

            return parts.length < 2 ? 1 : Math.pow(10, parts[1].length);
        },
        /**
         * Given a variable number of arguments, returns the maximum
         * multiplier that must be used to normalize an operation involving
         * all of them.
         */
        correctionFactor: function () {
            var args = Array.prototype.slice.call(arguments);

            return args.reduce(function(accum, next) {
                var mn = _.multiplier(next);
                return accum > mn ? accum : mn;
            }, 1);
        },
        /**
         * Implementation of toFixed() that treats floats more like decimals
         *
         * Fixes binary rounding issues (eg. (0.615).toFixed(2) === '0.61') that present
         * problems for accounting- and finance-related software.
         */
        toFixed: function(value, maxDecimals, roundingFunction, optionals) {
            var splitValue = value.toString().split('.'),
                minDecimals = maxDecimals - (optionals || 0),
                boundedPrecision,
                optionalsRegExp,
                power,
                output;

            // Use the smallest precision value possible to avoid errors from floating point representation
            if (splitValue.length === 2) {
              boundedPrecision = Math.min(Math.max(splitValue[1].length, minDecimals), maxDecimals);
            } else {
              boundedPrecision = minDecimals;
            }

            power = Math.pow(10, boundedPrecision);

            // Multiply up by precision, round accurately, then divide and use native toFixed():
            output = (roundingFunction(value + 'e+' + boundedPrecision) / power).toFixed(boundedPrecision);

            if (optionals > maxDecimals - boundedPrecision) {
                optionalsRegExp = new RegExp('\\.?0{1,' + (optionals - (maxDecimals - boundedPrecision)) + '}$');
                output = output.replace(optionalsRegExp, '');
            }

            return output;
        }
    };

    // avaliable options
    numeral.options = options;

    // avaliable formats
    numeral.formats = formats;

    // avaliable formats
    numeral.locales = locales;

    // This function sets the current locale.  If
    // no arguments are passed in, it will simply return the current global
    // locale key.
    numeral.locale = function(key) {
        if (key) {
            options.currentLocale = key.toLowerCase();
        }

        return options.currentLocale;
    };

    // This function provides access to the loaded locale data.  If
    // no arguments are passed in, it will simply return the current
    // global locale object.
    numeral.localeData = function(key) {
        if (!key) {
            return locales[options.currentLocale];
        }

        key = key.toLowerCase();

        if (!locales[key]) {
            throw new Error('Unknown locale : ' + key);
        }

        return locales[key];
    };

    numeral.reset = function() {
        for (var property in defaults) {
            options[property] = defaults[property];
        }
    };

    numeral.zeroFormat = function(format) {
        options.zeroFormat = typeof(format) === 'string' ? format : null;
    };

    numeral.nullFormat = function (format) {
        options.nullFormat = typeof(format) === 'string' ? format : null;
    };

    numeral.defaultFormat = function(format) {
        options.defaultFormat = typeof(format) === 'string' ? format : '0.0';
    };

    numeral.register = function(type, name, format) {
        name = name.toLowerCase();

        if (this[type + 's'][name]) {
            throw new TypeError(name + ' ' + type + ' already registered.');
        }

        this[type + 's'][name] = format;

        return format;
    };


    numeral.validate = function(val, culture) {
        var _decimalSep,
            _thousandSep,
            _currSymbol,
            _valArray,
            _abbrObj,
            _thousandRegEx,
            localeData,
            temp;

        //coerce val to string
        if (typeof val !== 'string') {
            val += '';

            if (console.warn) {
                console.warn('Numeral.js: Value is not string. It has been co-erced to: ', val);
            }
        }

        //trim whitespaces from either sides
        val = val.trim();

        //if val is just digits return true
        if (!!val.match(/^\d+$/)) {
            return true;
        }

        //if val is empty return false
        if (val === '') {
            return false;
        }

        //get the decimal and thousands separator from numeral.localeData
        try {
            //check if the culture is understood by numeral. if not, default it to current locale
            localeData = numeral.localeData(culture);
        } catch (e) {
            localeData = numeral.localeData(numeral.locale());
        }

        //setup the delimiters and currency symbol based on culture/locale
        _currSymbol = localeData.currency.symbol;
        _abbrObj = localeData.abbreviations;
        _decimalSep = localeData.delimiters.decimal;
        if (localeData.delimiters.thousands === '.') {
            _thousandSep = '\\.';
        } else {
            _thousandSep = localeData.delimiters.thousands;
        }

        // validating currency symbol
        temp = val.match(/^[^\d]+/);
        if (temp !== null) {
            val = val.substr(1);
            if (temp[0] !== _currSymbol) {
                return false;
            }
        }

        //validating abbreviation symbol
        temp = val.match(/[^\d]+$/);
        if (temp !== null) {
            val = val.slice(0, -1);
            if (temp[0] !== _abbrObj.thousand && temp[0] !== _abbrObj.million && temp[0] !== _abbrObj.billion && temp[0] !== _abbrObj.trillion) {
                return false;
            }
        }

        _thousandRegEx = new RegExp(_thousandSep + '{2}');

        if (!val.match(/[^\d.,]/g)) {
            _valArray = val.split(_decimalSep);
            if (_valArray.length > 2) {
                return false;
            } else {
                if (_valArray.length < 2) {
                    return ( !! _valArray[0].match(/^\d+.*\d$/) && !_valArray[0].match(_thousandRegEx));
                } else {
                    if (_valArray[0].length === 1) {
                        return ( !! _valArray[0].match(/^\d+$/) && !_valArray[0].match(_thousandRegEx) && !! _valArray[1].match(/^\d+$/));
                    } else {
                        return ( !! _valArray[0].match(/^\d+.*\d$/) && !_valArray[0].match(_thousandRegEx) && !! _valArray[1].match(/^\d+$/));
                    }
                }
            }
        }

        return false;
    };


    /************************************
        Numeral Prototype
    ************************************/

    numeral.fn = Numeral.prototype = {
        clone: function() {
            return numeral(this);
        },
        format: function(inputString, roundingFunction) {
            var value = this._value,
                format = inputString || options.defaultFormat,
                kind,
                output,
                formatFunction;

            // make sure we have a roundingFunction
            roundingFunction = roundingFunction || Math.round;

            // format based on value
            if (value === 0 && options.zeroFormat !== null) {
                output = options.zeroFormat;
            } else if (value === null && options.nullFormat !== null) {
                output = options.nullFormat;
            } else {
                for (kind in formats) {
                    if (format.match(formats[kind].regexps.format)) {
                        formatFunction = formats[kind].format;

                        break;
                    }
                }

                formatFunction = formatFunction || numeral._.numberToFormat;

                output = formatFunction(value, format, roundingFunction);
            }

            return output;
        },
        value: function() {
            return this._value;
        },
        input: function() {
            return this._input;
        },
        set: function(value) {
            this._value = Number(value);

            return this;
        },
        add: function(value) {
            var corrFactor = _.correctionFactor.call(null, this._value, value);

            function cback(accum, curr, currI, O) {
                return accum + Math.round(corrFactor * curr);
            }

            this._value = _.reduce([this._value, value], cback, 0) / corrFactor;

            return this;
        },
        subtract: function(value) {
            var corrFactor = _.correctionFactor.call(null, this._value, value);

            function cback(accum, curr, currI, O) {
                return accum - Math.round(corrFactor * curr);
            }

            this._value = _.reduce([value], cback, Math.round(this._value * corrFactor)) / corrFactor;

            return this;
        },
        multiply: function(value) {
            function cback(accum, curr, currI, O) {
                var corrFactor = _.correctionFactor(accum, curr);
                return Math.round(accum * corrFactor) * Math.round(curr * corrFactor) / Math.round(corrFactor * corrFactor);
            }

            this._value = _.reduce([this._value, value], cback, 1);

            return this;
        },
        divide: function(value) {
            function cback(accum, curr, currI, O) {
                var corrFactor = _.correctionFactor(accum, curr);
                return Math.round(accum * corrFactor) / Math.round(curr * corrFactor);
            }

            this._value = _.reduce([this._value, value], cback);

            return this;
        },
        difference: function(value) {
            return Math.abs(numeral(this._value).subtract(value).value());
        }
    };

    /************************************
        Default Locale && Format
    ************************************/

    numeral.register('locale', 'en', {
        delimiters: {
            thousands: ',',
            decimal: '.'
        },
        abbreviations: {
            thousand: 'k',
            million: 'm',
            billion: 'b',
            trillion: 't'
        },
        ordinal: function(number) {
            var b = number % 10;
            return (~~(number % 100 / 10) === 1) ? 'th' :
                (b === 1) ? 'st' :
                (b === 2) ? 'nd' :
                (b === 3) ? 'rd' : 'th';
        },
        currency: {
            symbol: '$'
        }
    });

    

(function() {
        numeral.register('format', 'bps', {
            regexps: {
                format: /(BPS)/,
                unformat: /(BPS)/
            },
            format: function(value, format, roundingFunction) {
                var space = numeral._.includes(format, ' BPS') ? ' ' : '',
                    output;

                value = value * 10000;

                // check for space before BPS
                format = format.replace(/\s?BPS/, '');

                output = numeral._.numberToFormat(value, format, roundingFunction);

                if (numeral._.includes(output, ')')) {
                    output = output.split('');

                    output.splice(-1, 0, space + 'BPS');

                    output = output.join('');
                } else {
                    output = output + space + 'BPS';
                }

                return output;
            },
            unformat: function(string) {
                return +(numeral._.stringToNumber(string) * 0.0001).toFixed(15);
            }
        });
})();


(function() {
        var decimal = {
            base: 1000,
            suffixes: ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
        },
        binary = {
            base: 1024,
            suffixes: ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB']
        };

    var allSuffixes =  decimal.suffixes.concat(binary.suffixes.filter(function (item) {
            return decimal.suffixes.indexOf(item) < 0;
        }));
        var unformatRegex = allSuffixes.join('|');
        // Allow support for BPS (http://www.investopedia.com/terms/b/basispoint.asp)
        unformatRegex = '(' + unformatRegex.replace('B', 'B(?!PS)') + ')';

    numeral.register('format', 'bytes', {
        regexps: {
            format: /([0\s]i?b)/,
            unformat: new RegExp(unformatRegex)
        },
        format: function(value, format, roundingFunction) {
            var output,
                bytes = numeral._.includes(format, 'ib') ? binary : decimal,
                suffix = numeral._.includes(format, ' b') || numeral._.includes(format, ' ib') ? ' ' : '',
                power,
                min,
                max;

            // check for space before
            format = format.replace(/\s?i?b/, '');

            for (power = 0; power <= bytes.suffixes.length; power++) {
                min = Math.pow(bytes.base, power);
                max = Math.pow(bytes.base, power + 1);

                if (value === null || value === 0 || value >= min && value < max) {
                    suffix += bytes.suffixes[power];

                    if (min > 0) {
                        value = value / min;
                    }

                    break;
                }
            }

            output = numeral._.numberToFormat(value, format, roundingFunction);

            return output + suffix;
        },
        unformat: function(string) {
            var value = numeral._.stringToNumber(string),
                power,
                bytesMultiplier;

            if (value) {
                for (power = decimal.suffixes.length - 1; power >= 0; power--) {
                    if (numeral._.includes(string, decimal.suffixes[power])) {
                        bytesMultiplier = Math.pow(decimal.base, power);

                        break;
                    }

                    if (numeral._.includes(string, binary.suffixes[power])) {
                        bytesMultiplier = Math.pow(binary.base, power);

                        break;
                    }
                }

                value *= (bytesMultiplier || 1);
            }

            return value;
        }
    });
})();


(function() {
        numeral.register('format', 'currency', {
        regexps: {
            format: /(\$)/
        },
        format: function(value, format, roundingFunction) {
            var locale = numeral.locales[numeral.options.currentLocale],
                symbols = {
                    before: format.match(/^([\+|\-|\(|\s|\$]*)/)[0],
                    after: format.match(/([\+|\-|\)|\s|\$]*)$/)[0]
                },
                output,
                symbol,
                i;

            // strip format of spaces and $
            format = format.replace(/\s?\$\s?/, '');

            // format the number
            output = numeral._.numberToFormat(value, format, roundingFunction);

            // update the before and after based on value
            if (value >= 0) {
                symbols.before = symbols.before.replace(/[\-\(]/, '');
                symbols.after = symbols.after.replace(/[\-\)]/, '');
            } else if (value < 0 && (!numeral._.includes(symbols.before, '-') && !numeral._.includes(symbols.before, '('))) {
                symbols.before = '-' + symbols.before;
            }

            // loop through each before symbol
            for (i = 0; i < symbols.before.length; i++) {
                symbol = symbols.before[i];

                switch (symbol) {
                    case '$':
                        output = numeral._.insert(output, locale.currency.symbol, i);
                        break;
                    case ' ':
                        output = numeral._.insert(output, ' ', i + locale.currency.symbol.length - 1);
                        break;
                }
            }

            // loop through each after symbol
            for (i = symbols.after.length - 1; i >= 0; i--) {
                symbol = symbols.after[i];

                switch (symbol) {
                    case '$':
                        output = i === symbols.after.length - 1 ? output + locale.currency.symbol : numeral._.insert(output, locale.currency.symbol, -(symbols.after.length - (1 + i)));
                        break;
                    case ' ':
                        output = i === symbols.after.length - 1 ? output + ' ' : numeral._.insert(output, ' ', -(symbols.after.length - (1 + i) + locale.currency.symbol.length - 1));
                        break;
                }
            }


            return output;
        }
    });
})();


(function() {
        numeral.register('format', 'exponential', {
        regexps: {
            format: /(e\+|e-)/,
            unformat: /(e\+|e-)/
        },
        format: function(value, format, roundingFunction) {
            var output,
                exponential = typeof value === 'number' && !numeral._.isNaN(value) ? value.toExponential() : '0e+0',
                parts = exponential.split('e');

            format = format.replace(/e[\+|\-]{1}0/, '');

            output = numeral._.numberToFormat(Number(parts[0]), format, roundingFunction);

            return output + 'e' + parts[1];
        },
        unformat: function(string) {
            var parts = numeral._.includes(string, 'e+') ? string.split('e+') : string.split('e-'),
                value = Number(parts[0]),
                power = Number(parts[1]);

            power = numeral._.includes(string, 'e-') ? power *= -1 : power;

            function cback(accum, curr, currI, O) {
                var corrFactor = numeral._.correctionFactor(accum, curr),
                    num = (accum * corrFactor) * (curr * corrFactor) / (corrFactor * corrFactor);
                return num;
            }

            return numeral._.reduce([value, Math.pow(10, power)], cback, 1);
        }
    });
})();


(function() {
        numeral.register('format', 'ordinal', {
        regexps: {
            format: /(o)/
        },
        format: function(value, format, roundingFunction) {
            var locale = numeral.locales[numeral.options.currentLocale],
                output,
                ordinal = numeral._.includes(format, ' o') ? ' ' : '';

            // check for space before
            format = format.replace(/\s?o/, '');

            ordinal += locale.ordinal(value);

            output = numeral._.numberToFormat(value, format, roundingFunction);

            return output + ordinal;
        }
    });
})();


(function() {
        numeral.register('format', 'percentage', {
        regexps: {
            format: /(%)/,
            unformat: /(%)/
        },
        format: function(value, format, roundingFunction) {
            var space = numeral._.includes(format, ' %') ? ' ' : '',
                output;

            if (numeral.options.scalePercentBy100) {
                value = value * 100;
            }

            // check for space before %
            format = format.replace(/\s?\%/, '');

            output = numeral._.numberToFormat(value, format, roundingFunction);

            if (numeral._.includes(output, ')')) {
                output = output.split('');

                output.splice(-1, 0, space + '%');

                output = output.join('');
            } else {
                output = output + space + '%';
            }

            return output;
        },
        unformat: function(string) {
            var number = numeral._.stringToNumber(string);
            if (numeral.options.scalePercentBy100) {
                return number * 0.01;
            }
            return number;
        }
    });
})();


(function() {
        numeral.register('format', 'time', {
        regexps: {
            format: /(:)/,
            unformat: /(:)/
        },
        format: function(value, format, roundingFunction) {
            var hours = Math.floor(value / 60 / 60),
                minutes = Math.floor((value - (hours * 60 * 60)) / 60),
                seconds = Math.round(value - (hours * 60 * 60) - (minutes * 60));

            return hours + ':' + (minutes < 10 ? '0' + minutes : minutes) + ':' + (seconds < 10 ? '0' + seconds : seconds);
        },
        unformat: function(string) {
            var timeArray = string.split(':'),
                seconds = 0;

            // turn hours and minutes into seconds and add them all up
            if (timeArray.length === 3) {
                // hours
                seconds = seconds + (Number(timeArray[0]) * 60 * 60);
                // minutes
                seconds = seconds + (Number(timeArray[1]) * 60);
                // seconds
                seconds = seconds + Number(timeArray[2]);
            } else if (timeArray.length === 2) {
                // minutes
                seconds = seconds + (Number(timeArray[0]) * 60);
                // seconds
                seconds = seconds + Number(timeArray[1]);
            }
            return Number(seconds);
        }
    });
})();

return numeral;
}));


/***/ }),

/***/ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js":
/*!****************************************************************************!*\
  !*** ./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js ***!
  \****************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var isOldIE = function isOldIE() {
  var memo;
  return function memorize() {
    if (typeof memo === 'undefined') {
      // Test for IE <= 9 as proposed by Browserhacks
      // @see http://browserhacks.com/#hack-e71d8692f65334173fee715c222cb805
      // Tests for existence of standard globals is to allow style-loader
      // to operate correctly into non-standard environments
      // @see https://github.com/webpack-contrib/style-loader/issues/177
      memo = Boolean(window && document && document.all && !window.atob);
    }

    return memo;
  };
}();

var getTarget = function getTarget() {
  var memo = {};
  return function memorize(target) {
    if (typeof memo[target] === 'undefined') {
      var styleTarget = document.querySelector(target); // Special case to return head of iframe instead of iframe itself

      if (window.HTMLIFrameElement && styleTarget instanceof window.HTMLIFrameElement) {
        try {
          // This will throw an exception if access to iframe is blocked
          // due to cross-origin restrictions
          styleTarget = styleTarget.contentDocument.head;
        } catch (e) {
          // istanbul ignore next
          styleTarget = null;
        }
      }

      memo[target] = styleTarget;
    }

    return memo[target];
  };
}();

var stylesInDom = [];

function getIndexByIdentifier(identifier) {
  var result = -1;

  for (var i = 0; i < stylesInDom.length; i++) {
    if (stylesInDom[i].identifier === identifier) {
      result = i;
      break;
    }
  }

  return result;
}

function modulesToDom(list, options) {
  var idCountMap = {};
  var identifiers = [];

  for (var i = 0; i < list.length; i++) {
    var item = list[i];
    var id = options.base ? item[0] + options.base : item[0];
    var count = idCountMap[id] || 0;
    var identifier = "".concat(id, " ").concat(count);
    idCountMap[id] = count + 1;
    var index = getIndexByIdentifier(identifier);
    var obj = {
      css: item[1],
      media: item[2],
      sourceMap: item[3]
    };

    if (index !== -1) {
      stylesInDom[index].references++;
      stylesInDom[index].updater(obj);
    } else {
      stylesInDom.push({
        identifier: identifier,
        updater: addStyle(obj, options),
        references: 1
      });
    }

    identifiers.push(identifier);
  }

  return identifiers;
}

function insertStyleElement(options) {
  var style = document.createElement('style');
  var attributes = options.attributes || {};

  if (typeof attributes.nonce === 'undefined') {
    var nonce =  true ? __webpack_require__.nc : undefined;

    if (nonce) {
      attributes.nonce = nonce;
    }
  }

  Object.keys(attributes).forEach(function (key) {
    style.setAttribute(key, attributes[key]);
  });

  if (typeof options.insert === 'function') {
    options.insert(style);
  } else {
    var target = getTarget(options.insert || 'head');

    if (!target) {
      throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");
    }

    target.appendChild(style);
  }

  return style;
}

function removeStyleElement(style) {
  // istanbul ignore if
  if (style.parentNode === null) {
    return false;
  }

  style.parentNode.removeChild(style);
}
/* istanbul ignore next  */


var replaceText = function replaceText() {
  var textStore = [];
  return function replace(index, replacement) {
    textStore[index] = replacement;
    return textStore.filter(Boolean).join('\n');
  };
}();

function applyToSingletonTag(style, index, remove, obj) {
  var css = remove ? '' : obj.media ? "@media ".concat(obj.media, " {").concat(obj.css, "}") : obj.css; // For old IE

  /* istanbul ignore if  */

  if (style.styleSheet) {
    style.styleSheet.cssText = replaceText(index, css);
  } else {
    var cssNode = document.createTextNode(css);
    var childNodes = style.childNodes;

    if (childNodes[index]) {
      style.removeChild(childNodes[index]);
    }

    if (childNodes.length) {
      style.insertBefore(cssNode, childNodes[index]);
    } else {
      style.appendChild(cssNode);
    }
  }
}

function applyToTag(style, options, obj) {
  var css = obj.css;
  var media = obj.media;
  var sourceMap = obj.sourceMap;

  if (media) {
    style.setAttribute('media', media);
  } else {
    style.removeAttribute('media');
  }

  if (sourceMap && typeof btoa !== 'undefined') {
    css += "\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))), " */");
  } // For old IE

  /* istanbul ignore if  */


  if (style.styleSheet) {
    style.styleSheet.cssText = css;
  } else {
    while (style.firstChild) {
      style.removeChild(style.firstChild);
    }

    style.appendChild(document.createTextNode(css));
  }
}

var singleton = null;
var singletonCounter = 0;

function addStyle(obj, options) {
  var style;
  var update;
  var remove;

  if (options.singleton) {
    var styleIndex = singletonCounter++;
    style = singleton || (singleton = insertStyleElement(options));
    update = applyToSingletonTag.bind(null, style, styleIndex, false);
    remove = applyToSingletonTag.bind(null, style, styleIndex, true);
  } else {
    style = insertStyleElement(options);
    update = applyToTag.bind(null, style, options);

    remove = function remove() {
      removeStyleElement(style);
    };
  }

  update(obj);
  return function updateStyle(newObj) {
    if (newObj) {
      if (newObj.css === obj.css && newObj.media === obj.media && newObj.sourceMap === obj.sourceMap) {
        return;
      }

      update(obj = newObj);
    } else {
      remove();
    }
  };
}

module.exports = function (list, options) {
  options = options || {}; // Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
  // tags it will allow on a page

  if (!options.singleton && typeof options.singleton !== 'boolean') {
    options.singleton = isOldIE();
  }

  list = list || [];
  var lastIdentifiers = modulesToDom(list, options);
  return function update(newList) {
    newList = newList || [];

    if (Object.prototype.toString.call(newList) !== '[object Array]') {
      return;
    }

    for (var i = 0; i < lastIdentifiers.length; i++) {
      var identifier = lastIdentifiers[i];
      var index = getIndexByIdentifier(identifier);
      stylesInDom[index].references--;
    }

    var newLastIdentifiers = modulesToDom(newList, options);

    for (var _i = 0; _i < lastIdentifiers.length; _i++) {
      var _identifier = lastIdentifiers[_i];

      var _index = getIndexByIdentifier(_identifier);

      if (stylesInDom[_index].references === 0) {
        stylesInDom[_index].updater();

        stylesInDom.splice(_index, 1);
      }
    }

    lastIdentifiers = newLastIdentifiers;
  };
};

/***/ }),

/***/ "./src/api/index.js":
/*!**************************!*\
  !*** ./src/api/index.js ***!
  \**************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "./node_modules/@babel/runtime/helpers/defineProperty.js");
/* harmony import */ var _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/url */ "@wordpress/url");
/* harmony import */ var _wordpress_url__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_url__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__);



function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) { symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); } keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

/**
 * WordPress dependencies
 */




var DEFAULT_STATE = {
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
};
var actions = {
  displayPopup: function displayPopup(productId) {
    return {
      type: 'DISPLAY_POPUP',
      productId: productId
    };
  },
  displayInlineComponents: function displayInlineComponents(productId) {
    return {
      type: 'DISPLAY_INLINE_COMPONENTS',
      productId: productId
    };
  },
  closePopup: function closePopup() {
    return {
      type: 'CLOSE_POPUP'
    };
  },
  fetchFromAPI: function fetchFromAPI(path) {
    return {
      type: 'FETCH_FROM_API',
      path: path
    };
  },
  updatedcomponents: function updatedcomponents(productId, components) {
    return {
      type: 'UPDATE_COMPONENTS',
      productId: productId,
      components: components
    };
  },
  updatePriceData: function updatePriceData(productId, priceData) {
    return {
      type: 'UPDATE_PRICE_DATA',
      productId: productId,
      priceData: priceData
    };
  },
  updateComponent: function updateComponent(productId, id, option) {
    return {
      type: 'UPDATE_COMPONENT',
      productId: productId,
      id: id,
      option: option
    };
  },
  updateProductQuantity: function updateProductQuantity(productId, quantity) {
    return {
      type: 'UPDATE_PRODUCT_QUANTITY',
      productId: productId,
      quantity: quantity
    };
  },
  updateAddedToCart: function updateAddedToCart(productId) {
    return {
      type: 'UPDATE_ADDED_TO_CART',
      productId: productId
    };
  },
  removeAddedToCart: function removeAddedToCart(productId) {
    return {
      type: 'REMOVE_ADDED_TO_CART',
      productId: productId
    };
  },
  calculateSubtotals: function calculateSubtotals(productId) {
    return {
      type: 'CALCULATE_SUBTOTALS',
      productId: productId
    };
  },
  calculateComponentSubtotals: function calculateComponentSubtotals(productId, componentId, quantity) {
    return {
      type: 'CALCULATE_COMPONENT_SUBTOTALS',
      productId: productId,
      componentId: componentId,
      quantity: quantity
    };
  },
  updateProductData: function updateProductData(productId, productData) {
    return {
      type: 'UPDATE_PRODUCT_DATA',
      productId: productId,
      productData: productData
    };
  }
};
Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_2__["registerStore"])('composite-products', {
  reducer: function reducer() {
    var state = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : DEFAULT_STATE;
    var action = arguments.length > 1 ? arguments[1] : undefined;
    var components = state.components,
        quantity = state.quantity;

    switch (action.type) {
      case 'DISPLAY_POPUP':
        return _objectSpread(_objectSpread({}, state), {}, {
          displayPopup: true,
          popupProductId: action.productId
        });

      case 'CLOSE_POPUP':
        return _objectSpread(_objectSpread({}, state), {}, {
          displayPopup: false
        });

      case 'DISPLAY_INLINE_COMPONENTS':
        return _objectSpread(_objectSpread({}, state), {}, {
          displayInlineComponents: true,
          productSelected: action.productId
        });

      case 'UPDATE_COMPONENTS':
        return _objectSpread(_objectSpread({}, state), {}, {
          components: _objectSpread(_objectSpread({}, state.components), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.productId, action.components))
        });

      case 'UPDATE_COMPONENT':
        var updatedcomponents = components[action.productId].map(function (component) {
          if (component.id === action.id) {
            if (component.multiple) {
              var selectedOptions = component.selected_option;

              if (!Array.isArray(selectedOptions)) {
                selectedOptions = selectedOptions ? [selectedOptions] : [];
              }

              if (selectedOptions.includes(action.option)) {
                var updatedOptions = selectedOptions.reduce(function (acc, option) {
                  if (action.option === option) {
                    return acc;
                  }

                  return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(acc), [option]);
                }, []);
                return _objectSpread(_objectSpread({}, component), {}, {
                  selected_option: updatedOptions
                });
              } else {
                var _updatedOptions = [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(selectedOptions), [action.option]);

                return _objectSpread(_objectSpread({}, component), {}, {
                  selected_option: _updatedOptions
                });
              }
            }

            var selectedOption = component.optional === 'yes' && action.option === component.selected_option ? "" : action.option;
            return _objectSpread(_objectSpread({}, component), {}, {
              selected_option: selectedOption
            });
          } else {
            return component;
          }
        });
        return _objectSpread(_objectSpread({}, state), {}, {
          components: _objectSpread(_objectSpread({}, state.components), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.productId, updatedcomponents))
        });

      case 'CALCULATE_SUBTOTALS':
        var qty = typeof quantity[action.productId] === 'undefined' ? 1 : parseInt(quantity[action.productId], 10);
        var updatedComponentTotals = components[action.productId].reduce(function (acc, cp) {
          var componentQty = cp.quantity * qty;
          var totals = {
            price: 0.0,
            regular_price: 0.0,
            price_incl_tax: 0.0,
            price_excl_tax: 0.0
          };

          if (Array.isArray(cp.selected_option)) {
            cp.selected_option.forEach(function (optionId) {
              var selectedOption = cp.options.find(function (_ref) {
                var option_id = _ref.option_id;
                return option_id === optionId;
              });
              var priceData = selectedOption.option_price_data;
              totals.price += componentQty * priceData.price;
              totals.regular_price += componentQty * priceData.regular_price;
              totals.price_incl_tax += componentQty * priceData.price;
              totals.price_excl_tax += componentQty * priceData.price;
            });
          } else {
            var selectedOption = cp.options.find(function (_ref2) {
              var option_id = _ref2.option_id;
              return option_id === cp.selected_option;
            });
            var priceData = selectedOption === undefined ? totals : selectedOption.option_price_data;
            totals.price = componentQty * priceData.price;
            totals.regular_price = componentQty * priceData.regular_price;
            totals.price_incl_tax = componentQty * priceData.price;
            totals.price_excl_tax = componentQty * priceData.price;
          }

          acc[cp.id] = totals;
          return acc;
        }, {});
        return _objectSpread(_objectSpread({}, state), {}, {
          componentTotals: updatedComponentTotals
        });

      case 'CALCULATE_COMPONENT_SUBTOTALS':
        var totals = {
          price: 0.0,
          regular_price: 0.0,
          price_incl_tax: 0.0,
          price_excl_tax: 0.0
        };
        var selectedQty = typeof quantity[action.productId] === 'undefined' ? 1 : parseInt(quantity[action.productId], 10);
        var selectedComponents = components[action.productId];
        var selectedComponent = selectedComponents.find(function (_ref3) {
          var id = _ref3.id;
          return id === action.componentId;
        });

        if (Array.isArray(selectedComponent.selected_option)) {
          selectedComponent.selected_option.forEach(function (optionId) {
            var selectedOption = selectedComponent.options.find(function (_ref4) {
              var option_id = _ref4.option_id;
              return option_id === optionId;
            });
            var priceData = selectedOption.option_price_data;
            var actualQty = selectedComponent.quantity * selectedQty;
            totals.price += actualQty * priceData.price;
            totals.regular_price += actualQty * priceData.regular_price;
            totals.price_incl_tax += actualQty * priceData.price;
            totals.price_excl_tax += actualQty * priceData.price;
          });
        } else {
          var selectedOption = selectedComponent.options.find(function (_ref5) {
            var option_id = _ref5.option_id;
            return option_id === selectedComponent.selected_option;
          });
          var priceData = selectedOption.option_price_data;
          var actualQty = selectedComponent.quantity * selectedQty;
          totals.price = actualQty * priceData.price;
          totals.regular_price = actualQty * priceData.regular_price;
          totals.price_incl_tax = actualQty * priceData.price;
          totals.price_excl_tax = actualQty * priceData.price;
        }

        return _objectSpread(_objectSpread({}, state), {}, {
          componentTotals: _objectSpread(_objectSpread({}, state.componentTotals), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.componentId, totals))
        });

      case 'UPDATE_PRICE_DATA':
        return _objectSpread(_objectSpread({}, state), {}, {
          priceData: _objectSpread(_objectSpread({}, state.priceData), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.productId, action.priceData))
        });

      case 'UPDATE_PRODUCT_QUANTITY':
        return _objectSpread(_objectSpread({}, state), {}, {
          quantity: _objectSpread(_objectSpread({}, state.quantity), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.productId, action.quantity))
        });

      case 'UPDATE_ADDED_TO_CART':
        return _objectSpread(_objectSpread({}, state), {}, {
          addedToCart: _objectSpread(_objectSpread({}, state.addedToCart), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.productId, true))
        });

      case 'REMOVE_ADDED_TO_CART':
        return _objectSpread(_objectSpread({}, state), {}, {
          addedToCart: _objectSpread(_objectSpread({}, state.addedToCart), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.productId, false))
        });

      case 'UPDATE_PRODUCT_DATA':
        return _objectSpread(_objectSpread({}, state), {}, {
          productData: _objectSpread(_objectSpread({}, state.productData), {}, _babel_runtime_helpers_defineProperty__WEBPACK_IMPORTED_MODULE_1___default()({}, action.productId, action.productData))
        });
    }

    return state;
  },
  actions: actions,
  selectors: {
    displayPopup: function displayPopup(state) {
      var displayPopup = state.displayPopup;
      return displayPopup;
    },
    displayInlineComponents: function displayInlineComponents(state) {
      var displayInlineComponents = state.displayInlineComponents;
      return displayInlineComponents;
    },
    getComponents: function getComponents(state, productId) {
      var components = state.components;
      return components[productId];
    },
    getSelectedProductId: function getSelectedProductId(state) {
      var productSelected = state.productSelected;
      return productSelected;
    },
    getPopupProductId: function getPopupProductId(state) {
      var popupProductId = state.popupProductId;
      return popupProductId;
    },
    getTotals: function getTotals(state, productId) {
      var components = state.components,
          componentTotals = state.componentTotals,
          quantity = state.quantity,
          priceData = state.priceData;
      var qty = typeof quantity[productId] === 'undefined' ? 1 : parseInt(quantity[productId], 10);
      var basePriceData = priceData[productId];
      var totals = {
        price: 0.0,
        regular_price: 0.0,
        price_incl_tax: 0.0,
        price_excl_tax: 0.0
      };

      if (basePriceData !== undefined) {
        totals.price = basePriceData.base_price * qty;
        totals.regular_price = basePriceData.base_regular_price * qty;
      }

      var comps = components[productId];

      if (comps !== undefined) {
        comps.forEach(function (component) {
          var component_totals = componentTotals[component.id];

          if (component_totals !== undefined && component.selected_option !== "") {
            totals.price += component_totals.price;
            totals.regular_price += component_totals.regular_price;
            totals.price_incl_tax += component_totals.price_incl_tax;
            totals.price_excl_tax += component_totals.price_excl_tax;
          }
        });
      }

      return totals;
    },
    getPriceData: function getPriceData(state, productId) {
      var priceData = state.priceData;
      return priceData[productId];
    },
    getProductQuantity: function getProductQuantity(state, productId) {
      var quantity = state.quantity;
      return quantity[productId] || 1;
    },
    addedToCart: function addedToCart(state, productId) {
      var addedToCart = state.addedToCart;
      return addedToCart[productId] || false;
    },
    getProductData: function getProductData(state, productId) {
      var productData = state.productData;

      if (productData[productId] === undefined) {
        return {
          min_quantity: 1,
          max_quantity: ''
        };
      }

      return productData[productId];
    },
    getProductTitle: function getProductTitle(state, productId) {
      var productData = state.productData;

      if (productData[productId] === undefined) {
        return Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_5__["__"])('No title');
      }

      return productData[productId].title;
    }
  },
  controls: {
    FETCH_FROM_API: function FETCH_FROM_API(action) {
      return _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_3___default()({
        path: action.path
      });
    }
  }
});

/***/ }),

/***/ "./src/composite-products/CompositeProduct.js":
/*!****************************************************!*\
  !*** ./src/composite-products/CompositeProduct.js ***!
  \****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/toConsumableArray */ "./node_modules/@babel/runtime/helpers/toConsumableArray.js");
/* harmony import */ var _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @babel/runtime/helpers/classCallCheck */ "./node_modules/@babel/runtime/helpers/classCallCheck.js");
/* harmony import */ var _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @babel/runtime/helpers/createClass */ "./node_modules/@babel/runtime/helpers/createClass.js");
/* harmony import */ var _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @babel/runtime/helpers/assertThisInitialized */ "./node_modules/@babel/runtime/helpers/assertThisInitialized.js");
/* harmony import */ var _babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @babel/runtime/helpers/inherits */ "./node_modules/@babel/runtime/helpers/inherits.js");
/* harmony import */ var _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @babel/runtime/helpers/possibleConstructorReturn */ "./node_modules/@babel/runtime/helpers/possibleConstructorReturn.js");
/* harmony import */ var _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @babel/runtime/helpers/getPrototypeOf */ "./node_modules/@babel/runtime/helpers/getPrototypeOf.js");
/* harmony import */ var _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_7___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_8___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_8__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_9___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_9__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_10___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_10__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_11___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_11__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_12___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_13__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_13___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_13__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_14__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_14___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_14__);
/* harmony import */ var _utility__WEBPACK_IMPORTED_MODULE_15__ = __webpack_require__(/*! ./utility */ "./src/composite-products/utility.js");
/* harmony import */ var _components_thumbnails_control__WEBPACK_IMPORTED_MODULE_16__ = __webpack_require__(/*! ./components/thumbnails-control */ "./src/composite-products/components/thumbnails-control/index.js");
/* harmony import */ var _components_radio_control__WEBPACK_IMPORTED_MODULE_17__ = __webpack_require__(/*! ./components/radio-control */ "./src/composite-products/components/radio-control/index.js");
/* harmony import */ var _components_checkbox_control__WEBPACK_IMPORTED_MODULE_18__ = __webpack_require__(/*! ./components/checkbox-control */ "./src/composite-products/components/checkbox-control/index.js");
/* harmony import */ var _components_ProductHeader__WEBPACK_IMPORTED_MODULE_19__ = __webpack_require__(/*! ./components/ProductHeader */ "./src/composite-products/components/ProductHeader.js");









function _createSuper(Derived) { var hasNativeReflectConstruct = _isNativeReflectConstruct(); return function _createSuperInternal() { var Super = _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6___default()(Derived), result; if (hasNativeReflectConstruct) { var NewTarget = _babel_runtime_helpers_getPrototypeOf__WEBPACK_IMPORTED_MODULE_6___default()(this).constructor; result = Reflect.construct(Super, arguments, NewTarget); } else { result = Super.apply(this, arguments); } return _babel_runtime_helpers_possibleConstructorReturn__WEBPACK_IMPORTED_MODULE_5___default()(this, result); }; }

function _isNativeReflectConstruct() { if (typeof Reflect === "undefined" || !Reflect.construct) return false; if (Reflect.construct.sham) return false; if (typeof Proxy === "function") return true; try { Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {})); return true; } catch (e) { return false; } }

/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */







/**
 * Internal dependencies
 */







var Composite = /*#__PURE__*/function (_Component) {
  _babel_runtime_helpers_inherits__WEBPACK_IMPORTED_MODULE_4___default()(Composite, _Component);

  var _super = _createSuper(Composite);

  function Composite(props) {
    var _this;

    _babel_runtime_helpers_classCallCheck__WEBPACK_IMPORTED_MODULE_1___default()(this, Composite);

    _this = _super.call(this, props);
    _this.state = {
      components: [],
      addingToCart: false,
      addedToCart: false
    };
    _this.addToCart = _this.addToCart.bind(_babel_runtime_helpers_assertThisInitialized__WEBPACK_IMPORTED_MODULE_3___default()(_this));
    return _this;
  }

  _babel_runtime_helpers_createClass__WEBPACK_IMPORTED_MODULE_2___default()(Composite, [{
    key: "addToCart",
    value: function addToCart() {
      var _this$props = this.props,
          productId = _this$props.productId,
          quantity = _this$props.quantity,
          updateAddedToCart = _this$props.updateAddedToCart;
      var config = this.parseConfiguration();
      var self = this;
      self.setState({
        addingToCart: true
      });
      jquery__WEBPACK_IMPORTED_MODULE_8___default.a.ajax({
        method: "POST",
        url: wpdriftSettings.root + "wpdrift/v1/add_to_cart",
        data: {
          product_id: productId,
          quantity: quantity,
          config: config
        },
        success: function success(response) {
          if (response === false) {
            location.reload();
          }
        },
        complete: function complete() {
          jquery__WEBPACK_IMPORTED_MODULE_8___default()(".widget_shopping_cart_content").empty();
          jquery__WEBPACK_IMPORTED_MODULE_8___default()(document.body).trigger("wc_fragment_refresh");
          self.setState({
            addingToCart: false,
            addedToCart: true
          });
          updateAddedToCart(productId);
        }
      });
    }
  }, {
    key: "get_formatted_price_suffix",
    value: function get_formatted_price_suffix(totals) {
      var suffix = "";
      return suffix;
    }
  }, {
    key: "get_price_html",
    value: function get_price_html() {
      var totals = this.props.totals;
      var total_string = wc_composite_params.i18n_total ? '<span class="total">' + wc_composite_params.i18n_total + "</span>" : "";
      var price_html = "";
      var formatted_price = Object(_utility__WEBPACK_IMPORTED_MODULE_15__["wc_price_format"])(totals.price, true);
      var formatted_regular_price = Object(_utility__WEBPACK_IMPORTED_MODULE_15__["wc_price_format"])(totals.regular_price, true);
      var formatted_suffix = this.get_formatted_price_suffix(totals);

      if (totals.regular_price > totals.price) {
        formatted_price = wc_composite_params.i18n_strikeout_price_string.replace("%f", formatted_regular_price).replace("%t", formatted_price);
      }

      price_html = wc_composite_params.i18n_price_format.replace("%t", total_string).replace("%p", formatted_price).replace("%s", formatted_suffix);
      price_html = '<span class"price">' + price_html + "</span>";
      return {
        __html: price_html
      };
    }
  }, {
    key: "parseConfiguration",
    value: function parseConfiguration() {
      var components = this.props.components;
      return components.reduce(function (acc, cp) {
        var selected_option = cp.selected_option,
            quantity = cp.quantity,
            quantity_min = cp.quantity_min,
            quantity_max = cp.quantity_max,
            discount = cp.discount,
            optional = cp.optional,
            title = cp.title,
            composite_id = cp.composite_id,
            type = cp.type;

        if (selected_option === "") {
          return acc;
        }

        var items = acc[cp.id] === undefined ? [] : acc[cp.id];

        if (Array.isArray(selected_option)) {
          var selectedItems = selected_option.reduce(function (acc, option) {
            return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(acc), [{
              product_id: option,
              quantity: quantity,
              quantity_min: quantity_min,
              quantity_max: quantity_max,
              discount: discount,
              optional: optional,
              static: cp.static,
              title: title,
              composite_id: composite_id,
              type: type
            }]);
          }, []);
          acc[cp.id] = [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(items), _babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(selectedItems));
        } else {
          acc[cp.id] = [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(items), [{
            product_id: selected_option,
            quantity: quantity,
            quantity_min: quantity_min,
            quantity_max: quantity_max,
            discount: discount,
            optional: optional,
            static: cp.static,
            title: title,
            composite_id: composite_id,
            type: type
          }]);
        }

        return acc;
      }, {});
    }
  }, {
    key: "renderControl",
    value: function renderControl(args) {
      var _this$props2 = this.props,
          productId = _this$props2.productId,
          updateComponent = _this$props2.updateComponent,
          calculateComponentSubtotals = _this$props2.calculateComponentSubtotals;
      var options = args["options"].reduce(function (acc, option) {
        var productData = option.option_product_data;

        if (productData.product_type === "invalid-product") {
          return acc;
        }

        var imageSrc = "";

        if (productData !== undefined) {
          imageSrc = productData.image_data !== undefined ? productData.image_data.image_src : "";
        }

        return [].concat(_babel_runtime_helpers_toConsumableArray__WEBPACK_IMPORTED_MODULE_0___default()(acc), [{
          label: option.option_title,
          value: option.option_id,
          image: imageSrc,
          priceHtmml: {
            __html: option.option_price_html
          },
          data: {
            productData: productData,
            priceData: option.option_price_data
          }
        }]);
      }, []);
      var props = {
        key: args.id,
        id: args.id,
        label: args.title,
        help: args.description,
        selected: args.selected_option,
        options: options,
        multiple: args.multiple,
        onChange: function onChange(option) {
          updateComponent(productId, args.id, option);
          calculateComponentSubtotals(productId, args.id, 1);
        }
      };

      if (args.options_style === "thumbnails") {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_components_thumbnails_control__WEBPACK_IMPORTED_MODULE_16__["default"], props);
      }

      if (args.multiple) {
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_components_checkbox_control__WEBPACK_IMPORTED_MODULE_18__["default"], props);
      }

      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_components_radio_control__WEBPACK_IMPORTED_MODULE_17__["default"], props);
    }
  }, {
    key: "render",
    value: function render() {
      var _this2 = this;

      var _this$props3 = this.props,
          productId = _this$props3.productId,
          components = _this$props3.components,
          productData = _this$props3.productData,
          productTitle = _this$props3.productTitle,
          quantity = _this$props3.quantity,
          updateProductQuantity = _this$props3.updateProductQuantity;
      var _this$state = this.state,
          addingToCart = _this$state.addingToCart,
          addedToCart = _this$state.addedToCart;
      var hasComponents = Array.isArray(components) && components.length;
      var modalBody = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["Fragment"], null, !hasComponents && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_13__["Placeholder"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_13__["Spinner"], null)), !!hasComponents && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])("div", {
        className: "components-modal__body"
      }, components.map(function (args) {
        return _this2.renderControl(args);
      })));
      var modalBottom = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])("div", {
        className: "components-modal__bottom"
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_13__["Button"], {
        className: classnames__WEBPACK_IMPORTED_MODULE_9___default()("button", "button--add", {
          loading: addingToCart
        }),
        onClick: this.addToCart
      }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_12__["__"])("Add to cart")));
      var renderedComponents = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])("div", {
        className: "components-modal__content"
      }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(_components_ProductHeader__WEBPACK_IMPORTED_MODULE_19__["default"], {
        productId: productId,
        productTitle: productTitle,
        productData: productData,
        quantity: quantity,
        updateProductQuantity: updateProductQuantity,
        priceHtml: this.get_price_html()
      }), modalBody, modalBottom));
      return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])("div", {
        className: "inline-components"
      }, renderedComponents);
    }
  }]);

  return Composite;
}(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["Component"]);

var CompositeProduct = function CompositeProduct(props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_7__["createElement"])(Composite, props);
};

var applyWithSelect = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_10__["withSelect"])(function (select, ownProps) {
  var _select = select("composite-products"),
      getSelectedProductId = _select.getSelectedProductId,
      getProductTitle = _select.getProductTitle,
      getComponents = _select.getComponents,
      getTotals = _select.getTotals,
      getPriceData = _select.getPriceData,
      getProductQuantity = _select.getProductQuantity,
      getProductData = _select.getProductData;

  var productId = getSelectedProductId();
  var components = getComponents(productId) || [];
  return {
    productId: productId,
    productTitle: getProductTitle(productId),
    components: components,
    priceData: getPriceData(productId),
    totals: getTotals(productId),
    quantity: getProductQuantity(productId),
    productData: getProductData(productId)
  };
});
var applyWithDispatch = Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_10__["withDispatch"])(function (dispatch, ownProps) {
  var _dispatch = dispatch("composite-products"),
      updateComponent = _dispatch.updateComponent,
      _updateProductQuantity = _dispatch.updateProductQuantity,
      updateAddedToCart = _dispatch.updateAddedToCart,
      calculateSubtotals = _dispatch.calculateSubtotals,
      calculateComponentSubtotals = _dispatch.calculateComponentSubtotals;

  return {
    updateComponent: updateComponent,
    updateProductQuantity: function updateProductQuantity(productId, quantity) {
      _updateProductQuantity(productId, quantity);

      calculateSubtotals(productId);
    },
    calculateComponentSubtotals: calculateComponentSubtotals,
    updateAddedToCart: updateAddedToCart
  };
});
/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_11__["compose"])(applyWithSelect, applyWithDispatch)(CompositeProduct));

/***/ }),

/***/ "./src/composite-products/components/ProductHeader.js":
/*!************************************************************!*\
  !*** ./src/composite-products/components/ProductHeader.js ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__);



var ProductHeader = function ProductHeader(props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "components-modal__head"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "components-modal__head-section components-modal__head-section--start"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("h2", {
    className: "composite-product__title"
  }, props.productTitle)), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "components-modal__head-section components-modal__head-section--end"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_1__["TextControl"], {
    className: "composite-product__quantity",
    value: props.quantity,
    onChange: function onChange(quantity) {
      return props.updateProductQuantity(props.productId, quantity);
    },
    min: props.productData.min_quantity,
    max: 0 < props.productData.max_quantity ? props.productData.max_quantity : "",
    type: "number"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "composite-product__totals",
    dangerouslySetInnerHTML: props.priceHtml
  })));
};

/* harmony default export */ __webpack_exports__["default"] = (ProductHeader);

/***/ }),

/***/ "./src/composite-products/components/checkbox-control/index.js":
/*!*********************************************************************!*\
  !*** ./src/composite-products/components/checkbox-control/index.js ***!
  \*********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);


/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */


/**
 * WordPress dependencies
 */



function CheckboxControl(_ref) {
  var instanceId = _ref.instanceId,
      label = _ref.label,
      className = _ref.className,
      selected = _ref.selected,
      help = _ref.help,
      onChange = _ref.onChange,
      _ref$options = _ref.options,
      options = _ref$options === void 0 ? [] : _ref$options;
  var id = "inspector-checkbox-control-".concat(instanceId);

  var onChangeValue = function onChangeValue(event) {
    return onChange(event.target.value);
  };

  return !Object(lodash__WEBPACK_IMPORTED_MODULE_1__["isEmpty"])(options) && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["BaseControl"], {
    label: label,
    id: id,
    help: help,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()(className, 'components-checkbox-control')
  }, options.map(function (option, index) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      key: "".concat(id, "-").concat(index),
      className: classnames__WEBPACK_IMPORTED_MODULE_2___default()('components-checkbox-control__option', {
        'components-checkbox-control__option--selected': selected.includes(option.value)
      })
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("input", {
      id: "".concat(id, "-").concat(index),
      className: "components-checkbox-control__input",
      type: "checkbox",
      name: id,
      value: option.value,
      onChange: onChangeValue,
      checked: selected.includes(option.value),
      "aria-describedby": !!help ? "".concat(id, "__help") : undefined
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
      htmlFor: "".concat(id, "-").concat(index)
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      className: classnames__WEBPACK_IMPORTED_MODULE_2___default()('components-checkbox-control__checkbox', {
        'components-checkbox-control__checkbox-checked': selected.includes(option.value)
      })
    }, selected.includes(option.value) ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["Dashicon"], {
      icon: "yes",
      size: "18",
      role: "presentation"
    }) : null), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", {
      className: "components-checkbox-control__title"
    }, option.label), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", {
      className: "components-checkbox-control__price",
      dangerouslySetInnerHTML: option.priceHtmml
    })));
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["withInstanceId"])(CheckboxControl));

/***/ }),

/***/ "./src/composite-products/components/radio-control/index.js":
/*!******************************************************************!*\
  !*** ./src/composite-products/components/radio-control/index.js ***!
  \******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);


/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */


/**
 * WordPress dependencies
 */



function RadioControl(_ref) {
  var instanceId = _ref.instanceId,
      label = _ref.label,
      className = _ref.className,
      selected = _ref.selected,
      help = _ref.help,
      onChange = _ref.onChange,
      _ref$options = _ref.options,
      options = _ref$options === void 0 ? [] : _ref$options;
  var id = "inspector-radio-control-".concat(instanceId);

  var onChangeValue = function onChangeValue(event) {
    return onChange(event.target.value);
  };

  return !Object(lodash__WEBPACK_IMPORTED_MODULE_1__["isEmpty"])(options) && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["BaseControl"], {
    label: label,
    id: id,
    help: help,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()(className, 'components-radio-control')
  }, options.map(function (option, index) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      key: "".concat(id, "-").concat(index),
      className: classnames__WEBPACK_IMPORTED_MODULE_2___default()('components-radio-control__option', {
        'components-radio-control__option--selected': option.value === selected
      })
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("input", {
      id: "".concat(id, "-").concat(index),
      className: "components-radio-control__input",
      type: "radio",
      name: id,
      value: option.value,
      onChange: onChangeValue,
      checked: option.value === selected,
      "aria-describedby": !!help ? "".concat(id, "__help") : undefined
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
      htmlFor: "".concat(id, "-").concat(index)
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", {
      className: "components-radio-control__title"
    }, option.label), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", {
      className: "components-radio-control__price",
      dangerouslySetInnerHTML: option.priceHtmml
    })));
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["withInstanceId"])(RadioControl));

/***/ }),

/***/ "./src/composite-products/components/thumbnails-control/index.js":
/*!***********************************************************************!*\
  !*** ./src/composite-products/components/thumbnails-control/index.js ***!
  \***********************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash */ "lodash");
/* harmony import */ var lodash__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! classnames */ "./node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);


/**
 * External dependencies
 */


/**
 * WordPress dependencies
 */


/**
 * WordPress dependencies
 */



function ThumbnailsControl(_ref) {
  var instanceId = _ref.instanceId,
      label = _ref.label,
      className = _ref.className,
      selected = _ref.selected,
      help = _ref.help,
      onChange = _ref.onChange,
      _ref$options = _ref.options,
      options = _ref$options === void 0 ? [] : _ref$options,
      multiple = _ref.multiple;
  var id = "inspector-thumbnail-control-".concat(instanceId);

  var onChangeValue = function onChangeValue(event) {
    return onChange(event.target.value);
  };

  var inputType = multiple ? 'checkbox' : 'radio';
  return !Object(lodash__WEBPACK_IMPORTED_MODULE_1__["isEmpty"])(options) && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["BaseControl"], {
    label: label,
    id: id,
    help: help,
    className: classnames__WEBPACK_IMPORTED_MODULE_2___default()(className, 'components-thumbnail-control')
  }, options.map(function (option, index) {
    var checked = multiple ? selected.includes(option.value) : option.value === selected;
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      key: "".concat(id, "-").concat(index),
      className: classnames__WEBPACK_IMPORTED_MODULE_2___default()('components-thumbnail-control__option', {
        'components-thumbnail-control__option--selected': checked
      })
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      className: "components-thumbnail-control__wrap"
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("input", {
      id: "".concat(id, "-").concat(index),
      className: "components-thumbnail-control__input",
      type: inputType,
      name: id,
      value: option.value,
      onChange: onChangeValue,
      checked: checked,
      "aria-describedby": !!help ? "".concat(id, "__help") : undefined
    }), checked ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      className: "components-thumbnail-control__checked"
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__["Dashicon"], {
      icon: "yes",
      size: "18",
      role: "presentation"
    })) : null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
      htmlFor: "".concat(id, "-").concat(index)
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("img", {
      className: "components-thumbnail-control__image",
      src: option.image,
      alt: option.label
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", {
      className: "components-thumbnail-control__price",
      dangerouslySetInnerHTML: option.priceHtmml
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", {
      className: "components-thumbnail-control__title"
    }, option.label))));
  }));
}

/* harmony default export */ __webpack_exports__["default"] = (Object(_wordpress_compose__WEBPACK_IMPORTED_MODULE_3__["withInstanceId"])(ThumbnailsControl));

/***/ }),

/***/ "./src/composite-products/utility.js":
/*!*******************************************!*\
  !*** ./src/composite-products/utility.js ***!
  \*******************************************/
/*! exports provided: wc_price_format, wc_woocommerce_number_format, wc_number_format */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "wc_price_format", function() { return wc_price_format; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "wc_woocommerce_number_format", function() { return wc_woocommerce_number_format; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "wc_number_format", function() { return wc_number_format; });
/* harmony import */ var numeral__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! numeral */ "./node_modules/numeral/numeral.js");
/* harmony import */ var numeral__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(numeral__WEBPACK_IMPORTED_MODULE_0__);

/**
 * Converts numbers to formatted price strings. Respects WC price format settings.
 */

function wc_price_format(price, plain) {
  plain = typeof plain === 'undefined' ? false : plain;
  return wc_woocommerce_number_format(wc_number_format(price), plain);
}
/**
 * Formats price strings according to WC settings.
 */

function wc_woocommerce_number_format(price, plain) {
  var remove = wc_composite_params.currency_format_decimal_sep,
      position = wc_composite_params.currency_position,
      symbol = wc_composite_params.currency_symbol,
      trim_zeros = wc_composite_params.currency_format_trim_zeros,
      decimals = wc_composite_params.currency_format_num_decimals,
      format = '0a';
  plain = typeof plain === 'undefined' ? false : plain;

  if (trim_zeros == 'yes' && decimals > 0) {
    for (var i = 0; i < decimals; i++) {
      remove = remove + '0';
    }

    price = price.replace(remove, '');
  }

  if (decimals > 0) {
    var zeros = '';

    for (var i = 0; i < decimals; i++) {
      zeros = zeros + '0';
    }

    format = '0.' + zeros + 'a';
  }

  var formatted_price = numeral__WEBPACK_IMPORTED_MODULE_0___default()(price).format(format),
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

function wc_number_format(number) {
  var decimals = wc_composite_params.currency_format_num_decimals;
  var decimal_sep = wc_composite_params.currency_format_decimal_sep;
  var thousands_sep = wc_composite_params.currency_format_thousand_sep;
  var n = number,
      c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
  var d = typeof decimal_sep === 'undefined' ? ',' : decimal_sep;
  var t = typeof thousands_sep === 'undefined' ? '.' : thousands_sep,
      s = n < 0 ? '-' : '';
  var i = parseInt(n = Math.abs(+n || 0).toFixed(c), 10) + '',
      j = (j = i.length) > 3 ? j % 3 : 0;
  return s + (j ? i.substr(0, j) + t : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, '$1' + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : '');
}

/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/data */ "@wordpress/data");
/* harmony import */ var _wordpress_data__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _api__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./api */ "./src/api/index.js");
/* harmony import */ var _composite_products_CompositeProduct__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./composite-products/CompositeProduct */ "./src/composite-products/CompositeProduct.js");
/* harmony import */ var _sass_composite_add_to_cart_scss__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./sass/composite-add-to-cart.scss */ "./src/sass/composite-add-to-cart.scss");
/* harmony import */ var _sass_composite_add_to_cart_scss__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_sass_composite_add_to_cart_scss__WEBPACK_IMPORTED_MODULE_4__);


/**
 * WordPress dependencies
 */


/**
 * Internal dependencies
 */




var wpDrift = wpDrift || {};

(function ($) {
  "use strict";

  wpDrift.Composite = wpDrift.Composite || {};
  wpDrift.Composite.Product = {
    start: function start() {
      var element = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_composite_products_CompositeProduct__WEBPACK_IMPORTED_MODULE_3__["default"], null);
      var appRoot = document.getElementById("composite-components");

      if (appRoot) {
        Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["render"])(element, appRoot);
      }

      var productId = wc_composite_params.composite_config.product_id;

      if (productId !== undefined) {
        this.setupData();
        Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])("composite-products").displayInlineComponents(productId);
        Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])("composite-products").calculateSubtotals(productId);
      }
    },
    setupData: function setupData() {
      var productId = wc_composite_params.composite_config.product_id;
      var productData = wc_composite_params.composite_config.product_data;
      var productPriceData = wc_composite_params.composite_config.price_data;
      var productComponents = wc_composite_params.composite_config.components;
      Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])("composite-products").updateProductData(productId, productData);
      Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])("composite-products").updatePriceData(productId, productPriceData);
      Object(_wordpress_data__WEBPACK_IMPORTED_MODULE_1__["dispatch"])("composite-products").updatedcomponents(productId, productComponents);
    }
  };
  $(document).ready(function () {
    wpDrift.Composite.Product.start();
  });
})(jQuery);

/***/ }),

/***/ "./src/sass/composite-add-to-cart.scss":
/*!*********************************************!*\
  !*** ./src/sass/composite-add-to-cart.scss ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var api = __webpack_require__(/*! ../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
            var content = __webpack_require__(/*! !../../node_modules/css-loader/dist/cjs.js!../../node_modules/sass-loader/dist/cjs.js!./composite-add-to-cart.scss */ "./node_modules/css-loader/dist/cjs.js!./node_modules/sass-loader/dist/cjs.js!./src/sass/composite-add-to-cart.scss");

            content = content.__esModule ? content.default : content;

            if (typeof content === 'string') {
              content = [[module.i, content, '']];
            }

var options = {};

options.insert = "head";
options.singleton = false;

var update = api(content, options);



module.exports = content.locals || {};

/***/ }),

/***/ "@wordpress/api-fetch":
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["apiFetch"]; }());

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["blockEditor"]; }());

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["compose"]; }());

/***/ }),

/***/ "@wordpress/data":
/*!******************************!*\
  !*** external ["wp","data"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["data"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["i18n"]; }());

/***/ }),

/***/ "@wordpress/url":
/*!*****************************!*\
  !*** external ["wp","url"] ***!
  \*****************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["url"]; }());

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["jQuery"]; }());

/***/ }),

/***/ "lodash":
/*!*************************!*\
  !*** external "lodash" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["lodash"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map