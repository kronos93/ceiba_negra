webpackJsonp([11],Array(23).concat([
/* 23 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _classCallCheck2 = __webpack_require__(60);

var _classCallCheck3 = _interopRequireDefault(_classCallCheck2);

var _createClass2 = __webpack_require__(61);

var _createClass3 = _interopRequireDefault(_createClass2);

var _util = __webpack_require__(44);

var _components = __webpack_require__(65);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var Cart = function () {
    function Cart() {
        (0, _classCallCheck3.default)(this, Cart);

        var that = this;
        $('#shopCartSale').off('click').on('click', function (e) {
            $(this).find('.my-dropdown').slideToggle('3500');
        });
        $('#shopCartSale').find('nav').off('click').on('click', function (e) {
            e.stopPropagation();
        });
        $(document).off('mouseup').on('mouseup', function (e) {
            var container = $(".my-dropdown");
            if (!container.is(e.target) && // if the target of the click isn't the container...
            container.has(e.target).length === 0) // ... nor a descendant of the container
                {
                    container.hide();
                }
        });
    }

    (0, _createClass3.default)(Cart, [{
        key: 'get',
        value: function get() {
            var that = this;
            $.get((0, _util.base_url)() + "ajax/add_cart", function (response) {
                that.templateCart(response);
            });
        }
    }, {
        key: 'delete',
        value: function _delete(rowid) {
            var that = this;
            $.ajax({
                url: (0, _util.base_url)() + "ajax/delete_cart/",
                data: { rowid: rowid },
                type: "post"
            }).done(function (response) {
                that.templateCart(response);
            }).fail(function (response) {});
        }
    }, {
        key: 'templateCart',
        value: function templateCart(response) {
            var that = this;
            $("#shopCartSale").find('span').attr("data-venta", response.count);
            var template = document.getElementById('template-venta').innerHTML;
            var output = Mustache.render(template, response);
            document.getElementById("listaVenta").innerHTML = output;
            (0, _components.format_numeric)('init');
            localStorage.setItem("precio", response.total);
            localStorage.setItem("enganche", response.enganche);
            localStorage.setItem("abono", response.abono);

            if (response.is_reserva) {
                localStorage.setItem("id_reserva", response.id_reserva);
                localStorage.setItem("id_lider", response.id_lider);
                localStorage.setItem("nombre_lider", response.nombre_lider);
                localStorage.setItem("nombre_cliente", response.nombre_cliente);
                localStorage.setItem("apellidos_cliente", response.apellidos_cliente);
                localStorage.setItem("email_cliente", response.email_cliente);
                localStorage.setItem("phone_cliente", response.phone_cliente);
                localStorage.setItem("comment", response.comment);
                localStorage.setItem("is_reserva", true);
            } else {
                localStorage.removeItem("id_reserva");
                localStorage.removeItem("id_lider");
                localStorage.removeItem("nombre_lider");
                localStorage.removeItem("nombre_cliente");
                localStorage.removeItem("apellidos_cliente");
                localStorage.removeItem("email_cliente");
                localStorage.removeItem("phone_cliente");
                localStorage.removeItem("comment");
                localStorage.setItem("is_reserva", false);
            }

            if ($('#frm-venta #precio').length && $('#frm-venta #enganche').length && $('#frm-venta #abono').length && $('#comision').length) {
                //Esto esta aquí por si se cambia el carrito dinamicamente se actualizen los datos
                //Dinamicamente,
                /////////////////////////////////////////////////////////////////////////
                $('#frm-venta #precio').autoNumeric('set', localStorage.getItem("precio"));
                $('#frm-venta #enganche').autoNumeric('set', localStorage.getItem("enganche"));
                $('#frm-venta #abono').autoNumeric('set', localStorage.getItem("abono"));

                var porcentaje_comision = $('#porcentaje_comision').val();
                $('#frm-venta #comision').autoNumeric('set', porcentaje_comision / 100 * $('#precio').autoNumeric('get'));
                ///////////////////////////////////////////////////////////////////////////////
                if (response.is_reserva) {
                    ///////////////////////////////////////////////////////////////////////////
                    $('#first_name').val(localStorage.getItem("nombre_cliente"));
                    $('#last_name').val(localStorage.getItem("apellidos_cliente"));
                    $('#id_lider').val(localStorage.getItem("id_lider"));
                    $('#lideres_autocomplete').val(localStorage.getItem("nombre_lider"));
                    $('#email').val(localStorage.getItem("email_cliente"));
                    $('#phone').val(localStorage.getItem("phone_cliente"));
                    if (localStorage.getItem("comment")) {
                        $('#comments').show().html(localStorage.getItem("comment"));
                    } else {
                        $('#comments').hide().html("");
                    }
                    $('#id_reserva').val(localStorage.getItem("id_reserva"));
                    ///////////////////////////////////////////////////////////////////////////////
                } else {
                    $('#first_name').val("");
                    $('#last_name').val("");
                    $('#id_lider').val("");
                    $('#lideres_autocomplete').val("");
                    $('#email').val("");
                    $('#phone').val("");
                    $('#comments').hide().empty();
                    $('#id_reserva').val("");
                }
            } else if ($('#frm-reserva #precio').length && $('#frm-reserva #enganche').length && $('#frm-reserva #abono').length) {
                $('#frm-reserva #precio').autoNumeric('set', localStorage.getItem("precio"));
                $('#frm-reserva #enganche').autoNumeric('set', localStorage.getItem("enganche"));
                $('#frm-reserva #abono').autoNumeric('set', localStorage.getItem("abono"));
            }

            //Validar cuando se vacie el carrito
            if (!response.count) {
                var uri = window.location.pathname;
                var uri_split = uri.split('/');
                var name_uri = uri_split[uri_split.length - 1];
                if (name_uri == 'venta' || name_uri == 'reserva') {
                    window.location.href = (0, _util.base_url)();
                }
            }
            $('.itemCartDelete').off('click').on('click', function (e) {
                var rowid = $(this).val();
                that.delete(rowid);
                e.stopPropagation();
            });
        }
    }]);
    return Cart;
}();

exports.default = Cart;

/***/ }),
/* 24 */,
/* 25 */,
/* 26 */,
/* 27 */,
/* 28 */,
/* 29 */,
/* 30 */,
/* 31 */,
/* 32 */,
/* 33 */,
/* 34 */,
/* 35 */,
/* 36 */,
/* 37 */,
/* 38 */,
/* 39 */,
/* 40 */
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(45)(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),
/* 41 */
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),
/* 42 */
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),
/* 43 */
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__(49);
var IE8_DOM_DEFINE = __webpack_require__(55);
var toPrimitive = __webpack_require__(51);
var dP = Object.defineProperty;

exports.f = __webpack_require__(40) ? Object.defineProperty : function defineProperty(O, P, Attributes) {
  anObject(O);
  P = toPrimitive(P, true);
  anObject(Attributes);
  if (IE8_DOM_DEFINE) try {
    return dP(O, P, Attributes);
  } catch (e) { /* empty */ }
  if ('get' in Attributes || 'set' in Attributes) throw TypeError('Accessors not supported!');
  if ('value' in Attributes) O[P] = Attributes.value;
  return O;
};


/***/ }),
/* 44 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {

    base_url: function base_url() {
        if (window.location.hostname === 'localhost' || window.location.hostname === '192.168.0.10' || window.location.hostname === '192.168.1.250') {
            return window.location.origin + '/ceiba_negra/';
        } else if (window.location.hostname === 'dev.huertoslaceiba.com') {
            return 'http://dev.huertoslaceiba.com/';
        } else {
            return 'http://huertoslaceiba.com/';
        }
    },
    multiplicar: function multiplicar() {

        //console.log($('.multiplicar'));
        var campos = $('.multiplicar');
        var resultado = 1;
        for (var i = 0; i < campos.length; i++) {
            resultado *= $(campos[i]).autoNumeric('get');
        }
        return resultado;
    },
    ajax_msg: {

        hidden: function hidden() {
            //Remover mensaje
            if ($('.container-icons').find('.message').text().length > 0) {
                $('.container-icons').slideUp(0);
                $('.container-icons').find('.message').text('');
            }
            this.clean_box();
        },
        show_error: function show_error(response) {
            this.clean_box();
            $('.container-icons').addClass('container-icons showicon error').find('i').addClass('fa-times-circle-o');
            var msg = "Mensaje de error: " + response.responseText;
            msg += "\nVerificar los datos ingresados con los registros existentes.";
            msg += "\nCódigo de error: " + response.status + ".";
            msg += "\nMensaje de código error: " + response.statusText + ".";
            this.set_msg(msg);
            $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');
        },
        show_success: function show_success(msg) {
            this.clean_box();
            $('.container-icons').addClass('container-icons showicon ok').find('i').addClass('fa-check-circle-o');
            this.set_msg(msg);
            $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');
        },
        clean_box: function clean_box() {
            //Remover iconos
            if ($('.container-icons').hasClass('showicon ok')) {
                $('.container-icons').removeClass('showicon ok').find('i').removeClass('fa-check-circle-o');
            } else if ($('.container-icons').hasClass('showicon error')) {
                $('.container-icons').removeClass('showicon error').find('i').removeClass('fa-times-circle-o');
            }
        },
        set_msg: function set_msg(msg) {
            $('.container-icons').slideUp(0);
            $('.container-icons').find('.message').empty().html(msg);
            $('.container-icons').slideDown(625);
        }
    },
    set_coordinates: function set_coordinates() {
        //Herramienta para capturar las coordenadas del mapa
        /*$(mapplic).on('locationopened', function(e, location) {
            var manzana = (location.category.replace("mz", ""));
            var lote = (location.title.replace("Huerto ", ""));
            var data = {
                manzana: manzana,
                lote: lote,
                x: ($(".mapplic-coordinates-x")[0].innerHTML),
                y: $(".mapplic-coordinates-y")[0].innerHTML
            };
            console.log(data);
            $.ajax({
                url: base_url() + "ajax/guardar_coordenadas/",
                type: 'post',
                asyn: true,
                data: data
            });
        });
        });*/
    }
};

/***/ }),
/* 45 */
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),
/* 46 */
/***/ (function(module, exports) {

var core = module.exports = { version: '2.5.0' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),
/* 47 */
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(43);
var createDesc = __webpack_require__(50);
module.exports = __webpack_require__(40) ? function (object, key, value) {
  return dP.f(object, key, createDesc(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),
/* 48 */,
/* 49 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(41);
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),
/* 50 */
/***/ (function(module, exports) {

module.exports = function (bitmap, value) {
  return {
    enumerable: !(bitmap & 1),
    configurable: !(bitmap & 2),
    writable: !(bitmap & 4),
    value: value
  };
};


/***/ }),
/* 51 */
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__(41);
// instead of the ES6 spec version, we didn't implement @@toPrimitive case
// and the second argument - flag - preferred type is a string
module.exports = function (it, S) {
  if (!isObject(it)) return it;
  var fn, val;
  if (S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  if (typeof (fn = it.valueOf) == 'function' && !isObject(val = fn.call(it))) return val;
  if (!S && typeof (fn = it.toString) == 'function' && !isObject(val = fn.call(it))) return val;
  throw TypeError("Can't convert object to primitive value");
};


/***/ }),
/* 52 */
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(42);
var core = __webpack_require__(46);
var ctx = __webpack_require__(58);
var hide = __webpack_require__(47);
var PROTOTYPE = 'prototype';

var $export = function (type, name, source) {
  var IS_FORCED = type & $export.F;
  var IS_GLOBAL = type & $export.G;
  var IS_STATIC = type & $export.S;
  var IS_PROTO = type & $export.P;
  var IS_BIND = type & $export.B;
  var IS_WRAP = type & $export.W;
  var exports = IS_GLOBAL ? core : core[name] || (core[name] = {});
  var expProto = exports[PROTOTYPE];
  var target = IS_GLOBAL ? global : IS_STATIC ? global[name] : (global[name] || {})[PROTOTYPE];
  var key, own, out;
  if (IS_GLOBAL) source = name;
  for (key in source) {
    // contains in native
    own = !IS_FORCED && target && target[key] !== undefined;
    if (own && key in exports) continue;
    // export native or passed
    out = own ? target[key] : source[key];
    // prevent global pollution for namespaces
    exports[key] = IS_GLOBAL && typeof target[key] != 'function' ? source[key]
    // bind timers to global for call from export context
    : IS_BIND && own ? ctx(out, global)
    // wrap global constructors for prevent change them in library
    : IS_WRAP && target[key] == out ? (function (C) {
      var F = function (a, b, c) {
        if (this instanceof C) {
          switch (arguments.length) {
            case 0: return new C();
            case 1: return new C(a);
            case 2: return new C(a, b);
          } return new C(a, b, c);
        } return C.apply(this, arguments);
      };
      F[PROTOTYPE] = C[PROTOTYPE];
      return F;
    // make static versions for prototype methods
    })(out) : IS_PROTO && typeof out == 'function' ? ctx(Function.call, out) : out;
    // export proto methods to core.%CONSTRUCTOR%.methods.%NAME%
    if (IS_PROTO) {
      (exports.virtual || (exports.virtual = {}))[key] = out;
      // export proto methods to core.%CONSTRUCTOR%.prototype.%NAME%
      if (type & $export.R && expProto && !expProto[key]) hide(expProto, key, out);
    }
  }
};
// type bitmap
$export.F = 1;   // forced
$export.G = 2;   // global
$export.S = 4;   // static
$export.P = 8;   // proto
$export.B = 16;  // bind
$export.W = 32;  // wrap
$export.U = 64;  // safe
$export.R = 128; // real proto method for `library`
module.exports = $export;


/***/ }),
/* 53 */,
/* 54 */,
/* 55 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(40) && !__webpack_require__(45)(function () {
  return Object.defineProperty(__webpack_require__(56)('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),
/* 56 */
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(41);
var document = __webpack_require__(42).document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),
/* 57 */,
/* 58 */
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__(59);
module.exports = function (fn, that, length) {
  aFunction(fn);
  if (that === undefined) return fn;
  switch (length) {
    case 1: return function (a) {
      return fn.call(that, a);
    };
    case 2: return function (a, b) {
      return fn.call(that, a, b);
    };
    case 3: return function (a, b, c) {
      return fn.call(that, a, b, c);
    };
  }
  return function (/* ...args */) {
    return fn.apply(that, arguments);
  };
};


/***/ }),
/* 59 */
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),
/* 60 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

exports.default = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

/***/ }),
/* 61 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _defineProperty = __webpack_require__(62);

var _defineProperty2 = _interopRequireDefault(_defineProperty);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = function () {
  function defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
      var descriptor = props[i];
      descriptor.enumerable = descriptor.enumerable || false;
      descriptor.configurable = true;
      if ("value" in descriptor) descriptor.writable = true;
      (0, _defineProperty2.default)(target, descriptor.key, descriptor);
    }
  }

  return function (Constructor, protoProps, staticProps) {
    if (protoProps) defineProperties(Constructor.prototype, protoProps);
    if (staticProps) defineProperties(Constructor, staticProps);
    return Constructor;
  };
}();

/***/ }),
/* 62 */
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(63), __esModule: true };

/***/ }),
/* 63 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(64);
var $Object = __webpack_require__(46).Object;
module.exports = function defineProperty(it, key, desc) {
  return $Object.defineProperty(it, key, desc);
};


/***/ }),
/* 64 */
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(52);
// 19.1.2.4 / 15.2.3.6 Object.defineProperty(O, P, Attributes)
$export($export.S + $export.F * !__webpack_require__(40), 'Object', { defineProperty: __webpack_require__(43).f });


/***/ }),
/* 65 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {
    autocompleteClientes: function autocompleteClientes(base_url) {
        var base = {
            "first_name": "Default",
            "last_name": "Default Default",
            "email": "default@huertoslaceiba.com",
            "phone": "99821234567",
            "calle": "Default",
            "no_ext": "100",
            "no_int": "100",
            "colonia": "Default",
            "municipio": "Default",
            "estado": "Default",
            "ciudad": "Default",
            "cp": "77777",
            "lugar_nacimiento": "Default",
            "fecha_nacimiento": "01-01-1999"

        };
        var extra = {
            "ciudad_expedicion": "Playa del Carmen",
            "testigo_1": "XXXX XXXX XXXX",
            "testigo_2": "XXXX XXXX XXXX"
        };
        $('#clientes_autocomplete').autocomplete({
            serviceUrl: base_url() + 'ajax/autocomplete_clientes',
            noCache: true,
            autoSelectFirst: true,
            onSelect: function onSelect(suggestion) {
                console.log(suggestion);
                for (var data in suggestion) {
                    if (suggestion[data] == "" || suggestion[data] == null || suggestion[data] == 0) {
                        suggestion[data] = base[data];
                    }
                    $('#' + data).val(suggestion[data]).trigger('input');
                }
                for (var data in extra) {
                    $('#' + data).val(extra[data]);
                }
                $('#id_cliente').val(suggestion.id_cliente);
            },
            onSearchError: function onSearchError(query, jqXHR, textStatus, errorThrown) {
                console.log("Ha ocurrido un error xhr.");
            },
            onInvalidateSelection: function onInvalidateSelection() {
                console.log("Ha ocurrido un error en la selección.");
                for (var data in base) {
                    $('#' + data).val("");
                }
                for (var data in extra) {
                    $('#' + data).val("");
                }
                $('#id_cliente').val("");
            }
        });
        $('#clientes_autocomplete').on('keyup', function () {
            if (this.value == "" || this.value == null || this.value == undefined) {
                console.log("Ha ocurrido un error en la selección.");
                for (var data in base) {
                    $('#' + data).val("");
                }
                for (var data in extra) {
                    $('#' + data).val("");
                }
                $('#id_cliente').val("");
            }
        });
    },
    autocompleteLideres: function autocompleteLideres(base_url) {
        $('#lideres_autocomplete').autocomplete({
            serviceUrl: base_url() + 'ajax/autocomplete_lideres',
            noCache: true,
            autoSelectFirst: true,
            onSelect: function onSelect(suggestion) {
                $("#id_lider").val(suggestion.id);
            },
            onSearchError: function onSearchError(query, jqXHR, textStatus, errorThrown) {
                console.log("Ha ocurrido un error.");
            },
            onInvalidateSelection: function onInvalidateSelection() {
                console.log("Ha ocurrido un error en la selección.");
            }
        });
    },
    autocompleteSaldosClientes: function autocompleteSaldosClientes(base_url) {
        var base = {
            "first_name": "Default",
            "last_name": "Default Default",
            "email": "default@huertoslaceiba.com",
            "phone": "99821234567",
            "calle": "Default",
            "no_ext": "100",
            "no_int": "100",
            "colonia": "Default",
            "municipio": "Default",
            "estado": "Default",
            "ciudad": "Default",
            "cp": "77777",
            "lugar_nacimiento": "Default",
            "fecha_nacimiento": "01-01-1999",
            "ciudad_expedicion": "Playa del Carmen",
            "testigo_1": "XXXX XXXX XXXX",
            "testigo_2": "XXXX XXXX XXXX"

        };
        $('#saldos_clientes_autocomplete').autocomplete({
            serviceUrl: base_url() + 'ajax/autocomplete_saldos_clientes',
            noCache: true,
            autoSelectFirst: true,
            onSelect: function onSelect(suggestion) {
                $('#saldos_clientes_autocomplete').val(suggestion.data);
                console.log(suggestion);
                for (var data in suggestion) {
                    if (suggestion[data] == "" || suggestion[data] == null || suggestion[data] == 0) {
                        suggestion[data] = base[data];
                    }
                    $('#' + data).val(suggestion[data]).trigger('input');
                }
                if (suggestion.pagado) {
                    $('#enganche').autoNumeric('set', suggestion.pagado);
                }
                $('#id_cliente').val(suggestion.id_cliente);
                $('#id_venta').val(suggestion.id_venta);
            },
            onSearchError: function onSearchError(query, jqXHR, textStatus, errorThrown) {
                console.log("Ha ocurrido un error xhr.");
            },
            onInvalidateSelection: function onInvalidateSelection() {
                console.log("Ha ocurrido un error en la selección.");
            },
            onSearchStart: function onSearchStart(query) {
                console.log(query);
            }
        });
        $('#saldos_clientes_autocomplete').on('keyup', function () {
            if (this.value == "" || this.value == null || this.value == undefined) {
                for (var data in base) {
                    $('#' + data).val("").trigger('input');
                }
                $('#id_cliente').val("");
                $('#id_venta').val("");
            }
        });
    },
    datepicker: function datepicker(moment) {
        var datepicker = $('.datepicker');
        datepicker.mask('00-00-0000');
        datepicker.datepicker({
            dateFormat: "dd-mm-yy"
        });

        if (datepicker.val() === "") {
            datepicker.datepicker("setDate", new Date());
        }
    },
    format_numeric: function format_numeric(action) {
        // console.log($('.superficie'));
        // console.log($(".currency"));
        if ($('.superficie').length > 0) {
            $('.superficie').autoNumeric(action, {
                currencySymbol: " m\xB2",
                currencySymbolPlacement: 's'
            }); //Averiguar más del plugin para evitar menores a 0
        }
        if ($(".currency").length > 0) {
            $(".currency").autoNumeric(action, {
                currencySymbol: "$"
            });
        }
    },
    phone: function phone() {
        var options = {
            onComplete: function onComplete(cep) {},
            onKeyPress: function onKeyPress(cep, event, currentField, options) {},
            onChange: function onChange(cep) {},
            onInvalid: function onInvalid(val, e, f, invalid, options) {
                var error = invalid[0];
                console.log("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
            },
            reverse: false
        };
        $('.phone').mask('(000) 000-0000', options);
    },
    number: function number() {
        var options = {
            onComplete: function onComplete(cep) {},
            onKeyPress: function onKeyPress(cep, event, currentField, options) {},
            onChange: function onChange(cep) {},
            onInvalid: function onInvalid(val, e, f, invalid, options) {
                var error = invalid[0];
                console.log("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
            },
            reverse: false
        };
        $('.number').mask('0#', options);
    },
    tabs: function tabs() {
        $('.tabgroup > div').hide();
        $('.tabgroup > div:first-of-type').show();
        $('.tabs a').click(function (e) {
            // console.log('tab');
            e.preventDefault();
            var $this = $(this),
                tabgroup = '#' + $this.parents('.tabs').data('tabgroup'),
                others = $this.closest('li').siblings().children('a'),
                target = $this.attr('href');
            others.removeClass('active');
            $this.addClass('active');
            $(tabgroup).children('div').hide();
            $(target).show();
        });
    },
    tarjeta: function tarjeta() {
        Inputmask('0000-0000-0000-0000').mask('.tarjeta');
    }
};

/***/ })
]));