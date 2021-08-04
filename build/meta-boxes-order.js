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
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/meta-boxes-order.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/meta-boxes-order.js":
/*!*********************************!*\
  !*** ./src/meta-boxes-order.js ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

/* global wc_composite_admin_order_params */

/* global woocommerce_admin_meta_boxes */
jQuery(function ($) {
  var $order_items = $("#woocommerce-order-items"),
      view = false,
      functions = {
    handle_events: function handle_events() {
      $order_items.on("click", "button.configure_composite", {
        action: "configure"
      }, this.clicked_edit_button).on("click", "button.edit_composite", {
        action: "edit"
      }, this.clicked_edit_button);
    },
    clicked_edit_button: function clicked_edit_button(event) {
      var WCCPBackboneModal = $.WCBackboneModal.View.extend({
        addButton: functions.clicked_done_button
      });
      var $item = $(this).closest("tr.item"),
          item_id = $item.attr("data-order_item_id");
      view = new WCCPBackboneModal({
        target: "wc-modal-edit-composite",
        string: {
          action: "configure" === event.data.action ? wc_composite_admin_order_params.i18n_configure : wc_composite_admin_order_params.i18n_edit,
          item_id: item_id
        }
      });
      functions.populate_form();
      return false;
    },
    clicked_done_button: function clicked_done_button(event) {
      functions.block(view.$el.find(".wc-backbone-modal-content"));
      var data = $.extend({}, functions.get_taxable_address(), {
        action: "woocommerce_edit_composite_in_order",
        item_id: view._string.item_id,
        fields: view.$el.find("input, select, textarea").serialize(),
        dataType: "json",
        order_id: woocommerce_admin_meta_boxes.post_id,
        security: wc_composite_admin_order_params.edit_composite_nonce
      });
      $.post(woocommerce_admin_meta_boxes.ajax_url, data, function (response) {
        if (response.result && "success" === response.result) {
          $order_items.find(".inside").empty();
          $order_items.find(".inside").append(response.html);

          if ("yes" === wc_composite_admin_order_params.is_wc_version_gte_3_4) {
            $order_items.trigger("wc_order_items_reloaded");
          } else {
            functions.core.init_tiptip();
            functions.core.stupidtable.init();
          }

          functions.unblock(view.$el.find(".wc-backbone-modal-content")); // Make it look like something changed.

          functions.block($order_items, {
            fadeIn: 0
          });
          setTimeout(function () {
            functions.unblock($order_items);
          }, 250);
          view.closeButton(event);
        } else {
          window.alert(response.error ? response.error : wc_composite_admin_order_params.i18n_validation_error);
          functions.unblock(view.$el.find(".wc-backbone-modal-content"));
        }
      });
    },
    populate_form: function populate_form() {
      functions.block(view.$el.find(".wc-backbone-modal-content"));
      var data = {
        action: "woocommerce_configure_composite_order_item",
        item_id: view._string.item_id,
        dataType: "json",
        order_id: woocommerce_admin_meta_boxes.post_id,
        security: wc_composite_admin_order_params.edit_composite_nonce
      };
      $.post(woocommerce_admin_meta_boxes.ajax_url, data, function (response) {
        if (response.result && "success" === response.result) {
          view.$el.find("form").html(response.html);
          $(document.body).trigger("wc-enhanced-select-init");
          view.$el.find(".composite_component").each(function () {
            new Component($(this));
          });
          functions.unblock(view.$el.find(".wc-backbone-modal-content"));
        } else {
          window.alert(wc_composite_admin_order_params.i18n_form_error);
          functions.unblock(view.$el.find(".wc-backbone-modal-content"));
          view.$el.find(".modal-close").trigger("click");
        }
      });
    },
    get_taxable_address: function get_taxable_address() {
      var country = "";
      var state = "";
      var postcode = "";
      var city = "";

      if ("shipping" === woocommerce_admin_meta_boxes.tax_based_on) {
        country = $("#_shipping_country").val();
        state = $("#_shipping_state").val();
        postcode = $("#_shipping_postcode").val();
        city = $("#_shipping_city").val();
      }

      if ("billing" === woocommerce_admin_meta_boxes.tax_based_on || !country) {
        country = $("#_billing_country").val();
        state = $("#_billing_state").val();
        postcode = $("#_billing_postcode").val();
        city = $("#_billing_city").val();
      }

      return {
        country: country,
        state: state,
        postcode: postcode,
        city: city
      };
    },
    block: function block($target, params) {
      var defaults = {
        message: null,
        overlayCSS: {
          background: "#fff",
          opacity: 0.6
        }
      };
      var opts = $.extend({}, defaults, params || {});
      $target.block(opts);
    },
    unblock: function unblock($target) {
      $target.unblock();
    }
  };
  /*
   * Add some extra duplicated bits if the 'wc_order_items_reloaded' event handler is missing from WC core.
   */

  if ("no" === wc_composite_admin_order_params.is_wc_version_gte_3_4) {
    functions.core = {
      init_tiptip: function init_tiptip() {
        $("#tiptip_holder").removeAttr("style");
        $("#tiptip_arrow").removeAttr("style");
        $(".tips").tipTip({
          attribute: "data-tip",
          fadeIn: 50,
          fadeOut: 50,
          delay: 200
        });
      },
      stupidtable: {
        init: function init() {
          $(".woocommerce_order_items").stupidtable();
          $(".woocommerce_order_items").on("aftertablesort", this.add_arrows);
        },
        add_arrows: function add_arrows(event, data) {
          var th = $(this).find("th");
          var arrow = data.direction === "asc" ? "&uarr;" : "&darr;";
          var index = data.column;
          th.find(".wc-arrow").remove();
          th.eq(index).append('<span class="wc-arrow">' + arrow + "</span>");
        }
      }
    };
  }
  /*
   * Initialize.
   */


  functions.handle_events();

  var Component = function Component($component) {
    var component = this;
    this.$selection_el = $component.find("select.component_option_select");
    this.$view_el = $component.find(".component_option_selection_details_wrapper");
    this.component_id = $component.data("component_data").component_id;
    this.composite_id = $component.data("component_data").composite_id;
    /**
     * Component selection model.
     */

    var Component_Selection_Order_Model = Backbone.Model.extend({
      selected_product_data: {
        product_html: ""
      },
      initialize: function initialize() {
        this.set({
          selected_product: component.$selection_el.val()
        });
      },
      update_selection: function update_selection(selected_product) {
        if (!selected_product) {
          this.update_selected_product("", {
            product_html: ""
          });
          return;
        }

        this.load_selection_data(selected_product);
      },
      load_selection_data: function load_selection_data(product_id) {
        var model = this,
            data = {
          action: "woocommerce_get_composited_product_data",
          product_id: product_id,
          component_id: component.component_id,
          composite_id: component.composite_id
        };
        $.ajax({
          type: "POST",
          url: woocommerce_admin_meta_boxes.ajax_url,
          data: data,
          timeout: 15000,
          dataType: "json",
          success: function success(response) {
            if ("success" === response.result) {
              var product_data = response.product_data;
              model.trigger("selected_product_data_loaded", product_id, product_data);
              model.update_selected_product(product_id, product_data);
            } else {
              model.trigger("selected_product_data_load_error", product_id);
            }
          },
          error: function error() {
            model.trigger("selected_product_data_load_error", product_id);
          }
        });
      },
      update_selected_product: function update_selected_product(product, product_data) {
        this.selected_product_data = product_data;
        this.set({
          selected_product: product
        });
      },
      get_selected_product_data: function get_selected_product_data() {
        return this.selected_product_data;
      }
    });
    /**
     * Component selection view.
     */

    var Component_Selection_Order_View = Backbone.View.extend({
      initialize: function initialize() {
        this.listenTo(this.model, "change:selected_product", this.render);
        this.listenTo(this.model, "selected_product_data_load_error", this.selection_data_load_error);
        component.$selection_el.on("change", this.selection_changed);
      },
      selection_changed: function selection_changed() {
        var selected_product = $(this).val() || "";

        if (component.selection_model.get("selected_product") === selected_product) {
          return false;
        }

        if (selected_product) {
          component.selection_view.block();
          component.selection_model.update_selection(selected_product);
        } else {
          component.selection_model.update_selection("");
        }
      },
      render: function render() {
        component.$view_el.html(this.model.get_selected_product_data().product_html);

        if (this.model.get("selected_product")) {
          this.unblock();
        }
      },
      selection_data_load_error: function selection_data_load_error() {
        var selected_product = this.model.get("selected_product");
        window.alert(wc_composite_admin_order_params.i18n_selection_request_timeout);
        component.$selection_el.val(selected_product).change();
        this.unblock();
      },
      block: function block() {
        functions.block(component.$view_el);
      },
      unblock: function unblock() {
        functions.unblock(component.$view_el);
      }
    }); // Initialize model.

    this.selection_model = new Component_Selection_Order_Model(); // Initialize view.

    this.selection_view = new Component_Selection_Order_View({
      el: component.$view_el,
      model: component.selection_model
    });
  };
});

/***/ })

/******/ });
//# sourceMappingURL=meta-boxes-order.js.map