webpackJsonp([10],{

/***/ 186:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});

var _classCallCheck2 = __webpack_require__(58);

var _classCallCheck3 = _interopRequireDefault(_classCallCheck2);

var _createClass2 = __webpack_require__(59);

var _createClass3 = _interopRequireDefault(_createClass2);

var _util = __webpack_require__(42);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var GenericFrm = function () {
    function GenericFrm(config) {
        (0, _classCallCheck3.default)(this, GenericFrm);

        this.data = {};
        this.urls = config.urls;
        this.url = "";
        this.msgs = config.msgs;
        this.msg = "";
        this.frm = $(config.frm);
        this.btn = config.btn;

        this.append = config.append;
        if (config.readonly !== undefined) {
            this.readonly = config.readonly;
        }

        this.dtTable = config.dtTable;
        this.autoNumeric = config.autoNumeric;
        this.dtRow = this.btn.closest('tr').hasClass('child') ? this.btn.closest('tr').prev('tr.parent') : this.btn.parents('tr');

        this.parseDtRow = this.dtTable.row(this.dtRow).data();
        this.response;
        this.fnOnDone;

        this.on_submit();
    }

    (0, _createClass3.default)(GenericFrm, [{
        key: "add",
        value: function add() {
            this.frm[0].reset();
            this.data = {};
            if (this.readonly !== undefined) {
                this.readonly.status = false;
                this.fnReadonly();
            }
            this.url = this.urls.add;
            this.msg = this.msgs.add;
            this.fnOnDone = this.ajaxAddDone;
        }
    }, {
        key: "edit",
        value: function edit() {

            this.data = {};
            if (this.readonly !== undefined) {
                this.readonly.status = true;
                this.fnReadonly();
            }
            this.url = this.urls.edit;
            this.msg = this.msgs.edit;
            this.fnOnDone = this.ajaxEditDone;
            for (var data in this.parseDtRow) {
                //Sí existe el elemento con id
                if ($("#" + data).length) {
                    var input = $("#" + data);
                    if (input.hasClass('autoNumeric')) {
                        input.autoNumeric('set', this.parseDtRow[data]);
                    } else {
                        input.val(this.parseDtRow[data]);
                    }
                }
            }
            for (var data in this.append) {
                this.data[this.append[data]] = this.parseDtRow[this.append[data]];
            }
        }
    }, {
        key: "fnReadonly",
        value: function fnReadonly() {
            $(this.readonly.inputs).attr('readonly', this.readonly.status);
        }
    }, {
        key: "submit",
        value: function submit() {
            var self = this;
            $.ajax({
                url: (0, _util.base_url)() + self.url,
                type: "post",
                data: self.data,
                beforeSend: function beforeSend(xhr) {
                    $("input[type='submit']").next().css('visibility', 'visible');
                }
            }).done(function (response) {
                self.response = response;
                self.fnOnDone.apply(self);
            }).fail(function (response) {
                _util.ajax_msg.show_error(response);
            });
        }
    }, {
        key: "ajaxAddDone",
        value: function ajaxAddDone() {
            this.frm[0].reset();
            console.log(this.response[0]);
            var newData = this.dtTable.row.add(this.response[0]).draw(false).node();
            console.log(newData);
            $(newData).css({ backgroundColor: 'yellow' }); //Animación para MAX
            this.dtTable.order([0, 'desc']).draw(); //Ordenar por id
            _util.ajax_msg.show_success(this.msg);
        }
    }, {
        key: "ajaxEditDone",
        value: function ajaxEditDone() {
            for (var data in this.response[0]) {
                this.parseDtRow[data] = this.response[0][data];
            }
            var newData = this.dtTable.row(this.dtRow).data(this.parseDtRow).draw(false).node(); //
            $(newData).css({ backgroundColor: 'yellow' }); //Animación para MAX
            _util.ajax_msg.show_success(this.msg);
        }
    }, {
        key: "on_submit",
        value: function on_submit() {
            var self = this;
            this.frm.off('submit').on('submit', function (e) {
                e.preventDefault();
                Object.assign(self.data, $(this).serializeObject());
                for (var data in self.autoNumeric) {
                    //Convertir de númerico a número
                    if ($('#' + self.autoNumeric[data]).length > 0) {
                        self.data[self.autoNumeric[data]] = $('#' + self.autoNumeric[data]).autoNumeric('get');
                    }
                }
                self.submit();
            });
        }
    }]);
    return GenericFrm;
}();

exports.default = GenericFrm;

/***/ }),

/***/ 25:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _util = __webpack_require__(42);

var _GenericFrm = __webpack_require__(186);

var _GenericFrm2 = _interopRequireDefault(_GenericFrm);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

var huertos_table = $('#huertos-table').DataTable({
  dom: '<"container-fluid" <"row" B> >lfrtip',
  buttons: [
  /*{
      extend: 'copy',
      text: 'Copiar al portapapeles',
      //className: 'btn btn-default',
  },*/
  {
    extend: 'excelHtml5',
    text: 'Descargar en formato excel'
  },
  /*{
      extend: 'pdf',
      text: 'Descargar en formato PDF'
  },*/
  /*{
      extend: 'print',
      text: 'Imprimir área visible'
  },*/
  {
    text: 'Huertos disponibles',
    action: function action(e, dt, node, config) {
      window.location.href = (0, _util.base_url)() + 'reportes/huertos_disponibles';
      // window.open(
      //     base_url() + 'reportes/huertos_disponibles',
      //     '_blank' // <- This is what makes it open in a new window.
      // );
    }
  }, {
    text: 'Huertos cancelados',
    action: function action(e, dt, node, config) {
      window.location.href = (0, _util.base_url)() + 'reportes/huertos_cancelados';
      // window.open(
      //     base_url() + 'reportes/huertos_cancelados',
      //     '_blank' // <- This is what makes it open in a new window.
      // );
    }
  }, {
    text: 'Huertos vendidos',
    action: function action(e, dt, node, config) {
      window.location.href = (0, _util.base_url)() + 'reportes/huertos_vendidos';
      // window.open(
      //     base_url() + 'reportes/huertos_vendidos',
      //     '_blank' // <- This is what makes it open in a new window.
      // );
    }
  }],
  "ajax": (0, _util.base_url)() + 'ajax/get_huertos_pmz',
  "columns": [//Atributos para la tabla
  {
    "data": "manzana",
    "render": $.fn.dataTable.render.number(',', '.', 0, 'Mz. ', ''),
    "type": "num-fmt"
  }, {
    "data": "huerto",
    "render": $.fn.dataTable.render.number(',', '.', 0, 'Ht. ', ''),
    "type": "num-fmt"
  }, {
    "data": "superficie",
    "render": $.fn.dataTable.render.number(',', '.', 2, '', ' m<sup>2</sup>'),
    "type": "num-fmt"
  }, {
    "data": "precio_x_m2",
    "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
    "type": "num-fmt"
  }, {
    "data": "precio",
    "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
    "type": "num-fmt"
  }, {
    "data": "enganche",
    "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
    "type": "num-fmt"
  }, {
    "data": "abono",
    "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
    "type": "num-fmt"
  }, {
    "data": "vendido", //Supa kawaiesko funcion para el render
    "render": function render(data, type, full, meta) {
      if (!parseInt(data)) {
        return '<span class="label label-primary">No vendido</span>';
      } else {
        return '<span class="label label-success">Vendido</span>';
      }
    }
  }, { "data": "col_norte" }, { "data": "col_noreste" }, { "data": "col_este" }, { "data": "col_sureste" }, { "data": "col_sur" }, { "data": "col_suroeste" }, { "data": "col_oeste" }, { "data": "col_noroeste" }, { "data": "" //Espacio extra para el boton o botones de opciones
  }],
  columnDefs: [//
  {
    //Añadir boton dinamicamente, para esta columna*
    "targets": -1,
    "data": null,
    "defaultContent": '<button data-toggle="modal" data-title="Editar huerto" data-btn-type="edit" data-target="#huertoModal" class="btn btn-info btn-sm pull-right"><i class="fa fa-fw fa-pencil"></i></button>'
  }, {
    //Quitar ordenamiento para estas columnas
    "sortable": false,
    "targets": [-1, -2, -3, -4, -5, -6, -7, -8, -9]
  }, {
    //Quitar busqueda para esta columna
    "targets": [-1, -2, -3, -4, -5],
    "searchable": false
  }],
  "order": [[0, "asc"]]
});
$('#huertoModal').on('show.bs.modal', function (e) {
  //Ocultar mensajes de la caja AJAX
  _util.ajax_msg.hidden();
  var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable)
  var title = button.data('title'); // Extraer informacipon desde atributos data-*
  var btnType = button.data('btnType');
  var modal = $(this);
  modal.find('.model-title').html(title);

  var config = {
    'frm': '#frm-huerto',
    'urls': { 'edit': 'ajax/update_huerto', 'add': 'ajax/add_huerto' },
    'msgs': { 'edit': 'Huerto actualizado correctamente.', 'add': 'Huerto agregado correctamente.' },
    'autoNumeric': ['superficie', 'precio_x_m2', 'precio'], //A que campos quitarle las comas y signos.
    //'readonly': { 'inputs': '#id_manzana' }, //Que campos son de lectura para agregar y quitar
    'append': ["id_huerto"], //Que campo anexar de dtRow al data a enviar por AJAX
    'btn': button, //Boton que disparó el evento de abrir modal
    'dtTable': huertos_table //Data table que se parseará
  };
  var genericFrm = new _GenericFrm2.default(config);
  genericFrm[btnType]();
});
$('.multiplicar').on('keyup', function () {
  var resultado = (0, _util.multiplicar)();
  $('#precio').autoNumeric('set', resultado);
});

/***/ }),

/***/ 38:
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(43)(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ 39:
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),

/***/ 40:
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),

/***/ 41:
/***/ (function(module, exports, __webpack_require__) {

var anObject = __webpack_require__(47);
var IE8_DOM_DEFINE = __webpack_require__(53);
var toPrimitive = __webpack_require__(49);
var dP = Object.defineProperty;

exports.f = __webpack_require__(38) ? Object.defineProperty : function defineProperty(O, P, Attributes) {
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

/***/ 42:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {

    base_url: function base_url() {
        if (window.location.hostname === 'localhost' || window.location.hostname === '192.168.0.10') {
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

/***/ 43:
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),

/***/ 44:
/***/ (function(module, exports) {

var core = module.exports = { version: '2.5.0' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),

/***/ 45:
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(41);
var createDesc = __webpack_require__(48);
module.exports = __webpack_require__(38) ? function (object, key, value) {
  return dP.f(object, key, createDesc(1, value));
} : function (object, key, value) {
  object[key] = value;
  return object;
};


/***/ }),

/***/ 47:
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(39);
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),

/***/ 48:
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

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

// 7.1.1 ToPrimitive(input [, PreferredType])
var isObject = __webpack_require__(39);
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

/***/ 50:
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(40);
var core = __webpack_require__(44);
var ctx = __webpack_require__(56);
var hide = __webpack_require__(45);
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

/***/ 53:
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(38) && !__webpack_require__(43)(function () {
  return Object.defineProperty(__webpack_require__(54)('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ 54:
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(39);
var document = __webpack_require__(40).document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),

/***/ 56:
/***/ (function(module, exports, __webpack_require__) {

// optional / simple context binding
var aFunction = __webpack_require__(57);
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

/***/ 57:
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),

/***/ 58:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

exports.default = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

/***/ }),

/***/ 59:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _defineProperty = __webpack_require__(60);

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

/***/ 60:
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(61), __esModule: true };

/***/ }),

/***/ 61:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(62);
var $Object = __webpack_require__(44).Object;
module.exports = function defineProperty(it, key, desc) {
  return $Object.defineProperty(it, key, desc);
};


/***/ }),

/***/ 62:
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(50);
// 19.1.2.4 / 15.2.3.6 Object.defineProperty(O, P, Attributes)
$export($export.S + $export.F * !__webpack_require__(38), 'Object', { defineProperty: __webpack_require__(41).f });


/***/ })

});