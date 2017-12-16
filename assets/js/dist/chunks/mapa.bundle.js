webpackJsonp([5,11],{

/***/ 185:
/***/ (function(module, exports, __webpack_require__) {

var store = __webpack_require__(207)('wks');
var uid = __webpack_require__(191);
var Symbol = __webpack_require__(40).Symbol;
var USE_SYMBOL = typeof Symbol == 'function';

var $exports = module.exports = function (name) {
  return store[name] || (store[name] =
    USE_SYMBOL && Symbol[name] || (USE_SYMBOL ? Symbol : uid)('Symbol.' + name));
};

$exports.store = store;


/***/ }),

/***/ 190:
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.14 / 15.2.3.14 Object.keys(O)
var $keys = __webpack_require__(225);
var enumBugKeys = __webpack_require__(208);

module.exports = Object.keys || function keys(O) {
  return $keys(O, enumBugKeys);
};


/***/ }),

/***/ 191:
/***/ (function(module, exports) {

var id = 0;
var px = Math.random();
module.exports = function (key) {
  return 'Symbol('.concat(key === undefined ? '' : key, ')_', (++id + px).toString(36));
};


/***/ }),

/***/ 202:
/***/ (function(module, exports) {

// 7.1.4 ToInteger
var ceil = Math.ceil;
var floor = Math.floor;
module.exports = function (it) {
  return isNaN(it = +it) ? 0 : (it > 0 ? floor : ceil)(it);
};


/***/ }),

/***/ 203:
/***/ (function(module, exports) {

// 7.2.1 RequireObjectCoercible(argument)
module.exports = function (it) {
  if (it == undefined) throw TypeError("Can't call method on  " + it);
  return it;
};


/***/ }),

/***/ 204:
/***/ (function(module, exports) {

module.exports = true;


/***/ }),

/***/ 205:
/***/ (function(module, exports) {

module.exports = {};


/***/ }),

/***/ 206:
/***/ (function(module, exports, __webpack_require__) {

var shared = __webpack_require__(207)('keys');
var uid = __webpack_require__(191);
module.exports = function (key) {
  return shared[key] || (shared[key] = uid(key));
};


/***/ }),

/***/ 207:
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(40);
var SHARED = '__core-js_shared__';
var store = global[SHARED] || (global[SHARED] = {});
module.exports = function (key) {
  return store[key] || (store[key] = {});
};


/***/ }),

/***/ 208:
/***/ (function(module, exports) {

// IE 8- don't enum bug keys
module.exports = (
  'constructor,hasOwnProperty,isPrototypeOf,propertyIsEnumerable,toLocaleString,toString,valueOf'
).split(',');


/***/ }),

/***/ 209:
/***/ (function(module, exports, __webpack_require__) {

var def = __webpack_require__(41).f;
var has = __webpack_require__(64);
var TAG = __webpack_require__(185)('toStringTag');

module.exports = function (it, tag, stat) {
  if (it && !has(it = stat ? it : it.prototype, TAG)) def(it, TAG, { configurable: true, value: tag });
};


/***/ }),

/***/ 210:
/***/ (function(module, exports, __webpack_require__) {

exports.f = __webpack_require__(185);


/***/ }),

/***/ 211:
/***/ (function(module, exports, __webpack_require__) {

var global = __webpack_require__(40);
var core = __webpack_require__(44);
var LIBRARY = __webpack_require__(204);
var wksExt = __webpack_require__(210);
var defineProperty = __webpack_require__(41).f;
module.exports = function (name) {
  var $Symbol = core.Symbol || (core.Symbol = LIBRARY ? {} : global.Symbol || {});
  if (name.charAt(0) != '_' && !(name in $Symbol)) defineProperty($Symbol, name, { value: wksExt.f(name) });
};


/***/ }),

/***/ 212:
/***/ (function(module, exports) {

exports.f = {}.propertyIsEnumerable;


/***/ }),

/***/ 22:
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

var _components = __webpack_require__(63);

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

/***/ 222:
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var LIBRARY = __webpack_require__(204);
var $export = __webpack_require__(50);
var redefine = __webpack_require__(223);
var hide = __webpack_require__(45);
var has = __webpack_require__(64);
var Iterators = __webpack_require__(205);
var $iterCreate = __webpack_require__(245);
var setToStringTag = __webpack_require__(209);
var getPrototypeOf = __webpack_require__(252);
var ITERATOR = __webpack_require__(185)('iterator');
var BUGGY = !([].keys && 'next' in [].keys()); // Safari has buggy iterators w/o `next`
var FF_ITERATOR = '@@iterator';
var KEYS = 'keys';
var VALUES = 'values';

var returnThis = function () { return this; };

module.exports = function (Base, NAME, Constructor, next, DEFAULT, IS_SET, FORCED) {
  $iterCreate(Constructor, NAME, next);
  var getMethod = function (kind) {
    if (!BUGGY && kind in proto) return proto[kind];
    switch (kind) {
      case KEYS: return function keys() { return new Constructor(this, kind); };
      case VALUES: return function values() { return new Constructor(this, kind); };
    } return function entries() { return new Constructor(this, kind); };
  };
  var TAG = NAME + ' Iterator';
  var DEF_VALUES = DEFAULT == VALUES;
  var VALUES_BUG = false;
  var proto = Base.prototype;
  var $native = proto[ITERATOR] || proto[FF_ITERATOR] || DEFAULT && proto[DEFAULT];
  var $default = $native || getMethod(DEFAULT);
  var $entries = DEFAULT ? !DEF_VALUES ? $default : getMethod('entries') : undefined;
  var $anyNative = NAME == 'Array' ? proto.entries || $native : $native;
  var methods, key, IteratorPrototype;
  // Fix native
  if ($anyNative) {
    IteratorPrototype = getPrototypeOf($anyNative.call(new Base()));
    if (IteratorPrototype !== Object.prototype && IteratorPrototype.next) {
      // Set @@toStringTag to native iterators
      setToStringTag(IteratorPrototype, TAG, true);
      // fix for some old engines
      if (!LIBRARY && !has(IteratorPrototype, ITERATOR)) hide(IteratorPrototype, ITERATOR, returnThis);
    }
  }
  // fix Array#{values, @@iterator}.name in V8 / FF
  if (DEF_VALUES && $native && $native.name !== VALUES) {
    VALUES_BUG = true;
    $default = function values() { return $native.call(this); };
  }
  // Define iterator
  if ((!LIBRARY || FORCED) && (BUGGY || VALUES_BUG || !proto[ITERATOR])) {
    hide(proto, ITERATOR, $default);
  }
  // Plug for library
  Iterators[NAME] = $default;
  Iterators[TAG] = returnThis;
  if (DEFAULT) {
    methods = {
      values: DEF_VALUES ? $default : getMethod(VALUES),
      keys: IS_SET ? $default : getMethod(KEYS),
      entries: $entries
    };
    if (FORCED) for (key in methods) {
      if (!(key in proto)) redefine(proto, key, methods[key]);
    } else $export($export.P + $export.F * (BUGGY || VALUES_BUG), NAME, methods);
  }
  return methods;
};


/***/ }),

/***/ 223:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(45);


/***/ }),

/***/ 224:
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.2 / 15.2.3.5 Object.create(O [, Properties])
var anObject = __webpack_require__(47);
var dPs = __webpack_require__(246);
var enumBugKeys = __webpack_require__(208);
var IE_PROTO = __webpack_require__(206)('IE_PROTO');
var Empty = function () { /* empty */ };
var PROTOTYPE = 'prototype';

// Create object with fake `null` prototype: use iframe Object with cleared prototype
var createDict = function () {
  // Thrash, waste and sodomy: IE GC bug
  var iframe = __webpack_require__(54)('iframe');
  var i = enumBugKeys.length;
  var lt = '<';
  var gt = '>';
  var iframeDocument;
  iframe.style.display = 'none';
  __webpack_require__(251).appendChild(iframe);
  iframe.src = 'javascript:'; // eslint-disable-line no-script-url
  // createDict = iframe.contentWindow.Object;
  // html.removeChild(iframe);
  iframeDocument = iframe.contentWindow.document;
  iframeDocument.open();
  iframeDocument.write(lt + 'script' + gt + 'document.F=Object' + lt + '/script' + gt);
  iframeDocument.close();
  createDict = iframeDocument.F;
  while (i--) delete createDict[PROTOTYPE][enumBugKeys[i]];
  return createDict();
};

module.exports = Object.create || function create(O, Properties) {
  var result;
  if (O !== null) {
    Empty[PROTOTYPE] = anObject(O);
    result = new Empty();
    Empty[PROTOTYPE] = null;
    // add "__proto__" for Object.getPrototypeOf polyfill
    result[IE_PROTO] = O;
  } else result = createDict();
  return Properties === undefined ? result : dPs(result, Properties);
};


/***/ }),

/***/ 225:
/***/ (function(module, exports, __webpack_require__) {

var has = __webpack_require__(64);
var toIObject = __webpack_require__(65);
var arrayIndexOf = __webpack_require__(248)(false);
var IE_PROTO = __webpack_require__(206)('IE_PROTO');

module.exports = function (object, names) {
  var O = toIObject(object);
  var i = 0;
  var result = [];
  var key;
  for (key in O) if (key != IE_PROTO) has(O, key) && result.push(key);
  // Don't enum bug & hidden keys
  while (names.length > i) if (has(O, key = names[i++])) {
    ~arrayIndexOf(result, key) || result.push(key);
  }
  return result;
};


/***/ }),

/***/ 226:
/***/ (function(module, exports) {

var toString = {}.toString;

module.exports = function (it) {
  return toString.call(it).slice(8, -1);
};


/***/ }),

/***/ 227:
/***/ (function(module, exports) {

exports.f = Object.getOwnPropertySymbols;


/***/ }),

/***/ 228:
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.7 / 15.2.3.4 Object.getOwnPropertyNames(O)
var $keys = __webpack_require__(225);
var hiddenKeys = __webpack_require__(208).concat('length', 'prototype');

exports.f = Object.getOwnPropertyNames || function getOwnPropertyNames(O) {
  return $keys(O, hiddenKeys);
};


/***/ }),

/***/ 234:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

var _iterator = __webpack_require__(241);

var _iterator2 = _interopRequireDefault(_iterator);

var _symbol = __webpack_require__(258);

var _symbol2 = _interopRequireDefault(_symbol);

var _typeof = typeof _symbol2.default === "function" && typeof _iterator2.default === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj; };

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

exports.default = typeof _symbol2.default === "function" && _typeof(_iterator2.default) === "symbol" ? function (obj) {
  return typeof obj === "undefined" ? "undefined" : _typeof(obj);
} : function (obj) {
  return obj && typeof _symbol2.default === "function" && obj.constructor === _symbol2.default && obj !== _symbol2.default.prototype ? "symbol" : typeof obj === "undefined" ? "undefined" : _typeof(obj);
};

/***/ }),

/***/ 241:
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(242), __esModule: true };

/***/ }),

/***/ 242:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(243);
__webpack_require__(254);
module.exports = __webpack_require__(210).f('iterator');


/***/ }),

/***/ 243:
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var $at = __webpack_require__(244)(true);

// 21.1.3.27 String.prototype[@@iterator]()
__webpack_require__(222)(String, 'String', function (iterated) {
  this._t = String(iterated); // target
  this._i = 0;                // next index
// 21.1.5.2.1 %StringIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var index = this._i;
  var point;
  if (index >= O.length) return { value: undefined, done: true };
  point = $at(O, index);
  this._i += point.length;
  return { value: point, done: false };
});


/***/ }),

/***/ 244:
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(202);
var defined = __webpack_require__(203);
// true  -> String#at
// false -> String#codePointAt
module.exports = function (TO_STRING) {
  return function (that, pos) {
    var s = String(defined(that));
    var i = toInteger(pos);
    var l = s.length;
    var a, b;
    if (i < 0 || i >= l) return TO_STRING ? '' : undefined;
    a = s.charCodeAt(i);
    return a < 0xd800 || a > 0xdbff || i + 1 === l || (b = s.charCodeAt(i + 1)) < 0xdc00 || b > 0xdfff
      ? TO_STRING ? s.charAt(i) : a
      : TO_STRING ? s.slice(i, i + 2) : (a - 0xd800 << 10) + (b - 0xdc00) + 0x10000;
  };
};


/***/ }),

/***/ 245:
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var create = __webpack_require__(224);
var descriptor = __webpack_require__(48);
var setToStringTag = __webpack_require__(209);
var IteratorPrototype = {};

// 25.1.2.1.1 %IteratorPrototype%[@@iterator]()
__webpack_require__(45)(IteratorPrototype, __webpack_require__(185)('iterator'), function () { return this; });

module.exports = function (Constructor, NAME, next) {
  Constructor.prototype = create(IteratorPrototype, { next: descriptor(1, next) });
  setToStringTag(Constructor, NAME + ' Iterator');
};


/***/ }),

/***/ 246:
/***/ (function(module, exports, __webpack_require__) {

var dP = __webpack_require__(41);
var anObject = __webpack_require__(47);
var getKeys = __webpack_require__(190);

module.exports = __webpack_require__(38) ? Object.defineProperties : function defineProperties(O, Properties) {
  anObject(O);
  var keys = getKeys(Properties);
  var length = keys.length;
  var i = 0;
  var P;
  while (length > i) dP.f(O, P = keys[i++], Properties[P]);
  return O;
};


/***/ }),

/***/ 247:
/***/ (function(module, exports, __webpack_require__) {

// fallback for non-array-like ES3 and non-enumerable old V8 strings
var cof = __webpack_require__(226);
// eslint-disable-next-line no-prototype-builtins
module.exports = Object('z').propertyIsEnumerable(0) ? Object : function (it) {
  return cof(it) == 'String' ? it.split('') : Object(it);
};


/***/ }),

/***/ 248:
/***/ (function(module, exports, __webpack_require__) {

// false -> Array#indexOf
// true  -> Array#includes
var toIObject = __webpack_require__(65);
var toLength = __webpack_require__(249);
var toAbsoluteIndex = __webpack_require__(250);
module.exports = function (IS_INCLUDES) {
  return function ($this, el, fromIndex) {
    var O = toIObject($this);
    var length = toLength(O.length);
    var index = toAbsoluteIndex(fromIndex, length);
    var value;
    // Array#includes uses SameValueZero equality algorithm
    // eslint-disable-next-line no-self-compare
    if (IS_INCLUDES && el != el) while (length > index) {
      value = O[index++];
      // eslint-disable-next-line no-self-compare
      if (value != value) return true;
    // Array#indexOf ignores holes, Array#includes - not
    } else for (;length > index; index++) if (IS_INCLUDES || index in O) {
      if (O[index] === el) return IS_INCLUDES || index || 0;
    } return !IS_INCLUDES && -1;
  };
};


/***/ }),

/***/ 249:
/***/ (function(module, exports, __webpack_require__) {

// 7.1.15 ToLength
var toInteger = __webpack_require__(202);
var min = Math.min;
module.exports = function (it) {
  return it > 0 ? min(toInteger(it), 0x1fffffffffffff) : 0; // pow(2, 53) - 1 == 9007199254740991
};


/***/ }),

/***/ 250:
/***/ (function(module, exports, __webpack_require__) {

var toInteger = __webpack_require__(202);
var max = Math.max;
var min = Math.min;
module.exports = function (index, length) {
  index = toInteger(index);
  return index < 0 ? max(index + length, 0) : min(index, length);
};


/***/ }),

/***/ 251:
/***/ (function(module, exports, __webpack_require__) {

var document = __webpack_require__(40).document;
module.exports = document && document.documentElement;


/***/ }),

/***/ 252:
/***/ (function(module, exports, __webpack_require__) {

// 19.1.2.9 / 15.2.3.2 Object.getPrototypeOf(O)
var has = __webpack_require__(64);
var toObject = __webpack_require__(253);
var IE_PROTO = __webpack_require__(206)('IE_PROTO');
var ObjectProto = Object.prototype;

module.exports = Object.getPrototypeOf || function (O) {
  O = toObject(O);
  if (has(O, IE_PROTO)) return O[IE_PROTO];
  if (typeof O.constructor == 'function' && O instanceof O.constructor) {
    return O.constructor.prototype;
  } return O instanceof Object ? ObjectProto : null;
};


/***/ }),

/***/ 253:
/***/ (function(module, exports, __webpack_require__) {

// 7.1.13 ToObject(argument)
var defined = __webpack_require__(203);
module.exports = function (it) {
  return Object(defined(it));
};


/***/ }),

/***/ 254:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(255);
var global = __webpack_require__(40);
var hide = __webpack_require__(45);
var Iterators = __webpack_require__(205);
var TO_STRING_TAG = __webpack_require__(185)('toStringTag');

var DOMIterables = ('CSSRuleList,CSSStyleDeclaration,CSSValueList,ClientRectList,DOMRectList,DOMStringList,' +
  'DOMTokenList,DataTransferItemList,FileList,HTMLAllCollection,HTMLCollection,HTMLFormElement,HTMLSelectElement,' +
  'MediaList,MimeTypeArray,NamedNodeMap,NodeList,PaintRequestList,Plugin,PluginArray,SVGLengthList,SVGNumberList,' +
  'SVGPathSegList,SVGPointList,SVGStringList,SVGTransformList,SourceBufferList,StyleSheetList,TextTrackCueList,' +
  'TextTrackList,TouchList').split(',');

for (var i = 0; i < DOMIterables.length; i++) {
  var NAME = DOMIterables[i];
  var Collection = global[NAME];
  var proto = Collection && Collection.prototype;
  if (proto && !proto[TO_STRING_TAG]) hide(proto, TO_STRING_TAG, NAME);
  Iterators[NAME] = Iterators.Array;
}


/***/ }),

/***/ 255:
/***/ (function(module, exports, __webpack_require__) {

"use strict";

var addToUnscopables = __webpack_require__(256);
var step = __webpack_require__(257);
var Iterators = __webpack_require__(205);
var toIObject = __webpack_require__(65);

// 22.1.3.4 Array.prototype.entries()
// 22.1.3.13 Array.prototype.keys()
// 22.1.3.29 Array.prototype.values()
// 22.1.3.30 Array.prototype[@@iterator]()
module.exports = __webpack_require__(222)(Array, 'Array', function (iterated, kind) {
  this._t = toIObject(iterated); // target
  this._i = 0;                   // next index
  this._k = kind;                // kind
// 22.1.5.2.1 %ArrayIteratorPrototype%.next()
}, function () {
  var O = this._t;
  var kind = this._k;
  var index = this._i++;
  if (!O || index >= O.length) {
    this._t = undefined;
    return step(1);
  }
  if (kind == 'keys') return step(0, index);
  if (kind == 'values') return step(0, O[index]);
  return step(0, [index, O[index]]);
}, 'values');

// argumentsList[@@iterator] is %ArrayProto_values% (9.4.4.6, 9.4.4.7)
Iterators.Arguments = Iterators.Array;

addToUnscopables('keys');
addToUnscopables('values');
addToUnscopables('entries');


/***/ }),

/***/ 256:
/***/ (function(module, exports) {

module.exports = function () { /* empty */ };


/***/ }),

/***/ 257:
/***/ (function(module, exports) {

module.exports = function (done, value) {
  return { value: value, done: !!done };
};


/***/ }),

/***/ 258:
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(259), __esModule: true };

/***/ }),

/***/ 259:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(260);
__webpack_require__(267);
__webpack_require__(268);
__webpack_require__(269);
module.exports = __webpack_require__(44).Symbol;


/***/ }),

/***/ 260:
/***/ (function(module, exports, __webpack_require__) {

"use strict";

// ECMAScript 6 symbols shim
var global = __webpack_require__(40);
var has = __webpack_require__(64);
var DESCRIPTORS = __webpack_require__(38);
var $export = __webpack_require__(50);
var redefine = __webpack_require__(223);
var META = __webpack_require__(261).KEY;
var $fails = __webpack_require__(43);
var shared = __webpack_require__(207);
var setToStringTag = __webpack_require__(209);
var uid = __webpack_require__(191);
var wks = __webpack_require__(185);
var wksExt = __webpack_require__(210);
var wksDefine = __webpack_require__(211);
var keyOf = __webpack_require__(262);
var enumKeys = __webpack_require__(263);
var isArray = __webpack_require__(264);
var anObject = __webpack_require__(47);
var toIObject = __webpack_require__(65);
var toPrimitive = __webpack_require__(49);
var createDesc = __webpack_require__(48);
var _create = __webpack_require__(224);
var gOPNExt = __webpack_require__(265);
var $GOPD = __webpack_require__(266);
var $DP = __webpack_require__(41);
var $keys = __webpack_require__(190);
var gOPD = $GOPD.f;
var dP = $DP.f;
var gOPN = gOPNExt.f;
var $Symbol = global.Symbol;
var $JSON = global.JSON;
var _stringify = $JSON && $JSON.stringify;
var PROTOTYPE = 'prototype';
var HIDDEN = wks('_hidden');
var TO_PRIMITIVE = wks('toPrimitive');
var isEnum = {}.propertyIsEnumerable;
var SymbolRegistry = shared('symbol-registry');
var AllSymbols = shared('symbols');
var OPSymbols = shared('op-symbols');
var ObjectProto = Object[PROTOTYPE];
var USE_NATIVE = typeof $Symbol == 'function';
var QObject = global.QObject;
// Don't use setters in Qt Script, https://github.com/zloirock/core-js/issues/173
var setter = !QObject || !QObject[PROTOTYPE] || !QObject[PROTOTYPE].findChild;

// fallback for old Android, https://code.google.com/p/v8/issues/detail?id=687
var setSymbolDesc = DESCRIPTORS && $fails(function () {
  return _create(dP({}, 'a', {
    get: function () { return dP(this, 'a', { value: 7 }).a; }
  })).a != 7;
}) ? function (it, key, D) {
  var protoDesc = gOPD(ObjectProto, key);
  if (protoDesc) delete ObjectProto[key];
  dP(it, key, D);
  if (protoDesc && it !== ObjectProto) dP(ObjectProto, key, protoDesc);
} : dP;

var wrap = function (tag) {
  var sym = AllSymbols[tag] = _create($Symbol[PROTOTYPE]);
  sym._k = tag;
  return sym;
};

var isSymbol = USE_NATIVE && typeof $Symbol.iterator == 'symbol' ? function (it) {
  return typeof it == 'symbol';
} : function (it) {
  return it instanceof $Symbol;
};

var $defineProperty = function defineProperty(it, key, D) {
  if (it === ObjectProto) $defineProperty(OPSymbols, key, D);
  anObject(it);
  key = toPrimitive(key, true);
  anObject(D);
  if (has(AllSymbols, key)) {
    if (!D.enumerable) {
      if (!has(it, HIDDEN)) dP(it, HIDDEN, createDesc(1, {}));
      it[HIDDEN][key] = true;
    } else {
      if (has(it, HIDDEN) && it[HIDDEN][key]) it[HIDDEN][key] = false;
      D = _create(D, { enumerable: createDesc(0, false) });
    } return setSymbolDesc(it, key, D);
  } return dP(it, key, D);
};
var $defineProperties = function defineProperties(it, P) {
  anObject(it);
  var keys = enumKeys(P = toIObject(P));
  var i = 0;
  var l = keys.length;
  var key;
  while (l > i) $defineProperty(it, key = keys[i++], P[key]);
  return it;
};
var $create = function create(it, P) {
  return P === undefined ? _create(it) : $defineProperties(_create(it), P);
};
var $propertyIsEnumerable = function propertyIsEnumerable(key) {
  var E = isEnum.call(this, key = toPrimitive(key, true));
  if (this === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key)) return false;
  return E || !has(this, key) || !has(AllSymbols, key) || has(this, HIDDEN) && this[HIDDEN][key] ? E : true;
};
var $getOwnPropertyDescriptor = function getOwnPropertyDescriptor(it, key) {
  it = toIObject(it);
  key = toPrimitive(key, true);
  if (it === ObjectProto && has(AllSymbols, key) && !has(OPSymbols, key)) return;
  var D = gOPD(it, key);
  if (D && has(AllSymbols, key) && !(has(it, HIDDEN) && it[HIDDEN][key])) D.enumerable = true;
  return D;
};
var $getOwnPropertyNames = function getOwnPropertyNames(it) {
  var names = gOPN(toIObject(it));
  var result = [];
  var i = 0;
  var key;
  while (names.length > i) {
    if (!has(AllSymbols, key = names[i++]) && key != HIDDEN && key != META) result.push(key);
  } return result;
};
var $getOwnPropertySymbols = function getOwnPropertySymbols(it) {
  var IS_OP = it === ObjectProto;
  var names = gOPN(IS_OP ? OPSymbols : toIObject(it));
  var result = [];
  var i = 0;
  var key;
  while (names.length > i) {
    if (has(AllSymbols, key = names[i++]) && (IS_OP ? has(ObjectProto, key) : true)) result.push(AllSymbols[key]);
  } return result;
};

// 19.4.1.1 Symbol([description])
if (!USE_NATIVE) {
  $Symbol = function Symbol() {
    if (this instanceof $Symbol) throw TypeError('Symbol is not a constructor!');
    var tag = uid(arguments.length > 0 ? arguments[0] : undefined);
    var $set = function (value) {
      if (this === ObjectProto) $set.call(OPSymbols, value);
      if (has(this, HIDDEN) && has(this[HIDDEN], tag)) this[HIDDEN][tag] = false;
      setSymbolDesc(this, tag, createDesc(1, value));
    };
    if (DESCRIPTORS && setter) setSymbolDesc(ObjectProto, tag, { configurable: true, set: $set });
    return wrap(tag);
  };
  redefine($Symbol[PROTOTYPE], 'toString', function toString() {
    return this._k;
  });

  $GOPD.f = $getOwnPropertyDescriptor;
  $DP.f = $defineProperty;
  __webpack_require__(228).f = gOPNExt.f = $getOwnPropertyNames;
  __webpack_require__(212).f = $propertyIsEnumerable;
  __webpack_require__(227).f = $getOwnPropertySymbols;

  if (DESCRIPTORS && !__webpack_require__(204)) {
    redefine(ObjectProto, 'propertyIsEnumerable', $propertyIsEnumerable, true);
  }

  wksExt.f = function (name) {
    return wrap(wks(name));
  };
}

$export($export.G + $export.W + $export.F * !USE_NATIVE, { Symbol: $Symbol });

for (var es6Symbols = (
  // 19.4.2.2, 19.4.2.3, 19.4.2.4, 19.4.2.6, 19.4.2.8, 19.4.2.9, 19.4.2.10, 19.4.2.11, 19.4.2.12, 19.4.2.13, 19.4.2.14
  'hasInstance,isConcatSpreadable,iterator,match,replace,search,species,split,toPrimitive,toStringTag,unscopables'
).split(','), j = 0; es6Symbols.length > j;)wks(es6Symbols[j++]);

for (var wellKnownSymbols = $keys(wks.store), k = 0; wellKnownSymbols.length > k;) wksDefine(wellKnownSymbols[k++]);

$export($export.S + $export.F * !USE_NATIVE, 'Symbol', {
  // 19.4.2.1 Symbol.for(key)
  'for': function (key) {
    return has(SymbolRegistry, key += '')
      ? SymbolRegistry[key]
      : SymbolRegistry[key] = $Symbol(key);
  },
  // 19.4.2.5 Symbol.keyFor(sym)
  keyFor: function keyFor(key) {
    if (isSymbol(key)) return keyOf(SymbolRegistry, key);
    throw TypeError(key + ' is not a symbol!');
  },
  useSetter: function () { setter = true; },
  useSimple: function () { setter = false; }
});

$export($export.S + $export.F * !USE_NATIVE, 'Object', {
  // 19.1.2.2 Object.create(O [, Properties])
  create: $create,
  // 19.1.2.4 Object.defineProperty(O, P, Attributes)
  defineProperty: $defineProperty,
  // 19.1.2.3 Object.defineProperties(O, Properties)
  defineProperties: $defineProperties,
  // 19.1.2.6 Object.getOwnPropertyDescriptor(O, P)
  getOwnPropertyDescriptor: $getOwnPropertyDescriptor,
  // 19.1.2.7 Object.getOwnPropertyNames(O)
  getOwnPropertyNames: $getOwnPropertyNames,
  // 19.1.2.8 Object.getOwnPropertySymbols(O)
  getOwnPropertySymbols: $getOwnPropertySymbols
});

// 24.3.2 JSON.stringify(value [, replacer [, space]])
$JSON && $export($export.S + $export.F * (!USE_NATIVE || $fails(function () {
  var S = $Symbol();
  // MS Edge converts symbol values to JSON as {}
  // WebKit converts symbol values to JSON as null
  // V8 throws on boxed symbols
  return _stringify([S]) != '[null]' || _stringify({ a: S }) != '{}' || _stringify(Object(S)) != '{}';
})), 'JSON', {
  stringify: function stringify(it) {
    if (it === undefined || isSymbol(it)) return; // IE8 returns string on undefined
    var args = [it];
    var i = 1;
    var replacer, $replacer;
    while (arguments.length > i) args.push(arguments[i++]);
    replacer = args[1];
    if (typeof replacer == 'function') $replacer = replacer;
    if ($replacer || !isArray(replacer)) replacer = function (key, value) {
      if ($replacer) value = $replacer.call(this, key, value);
      if (!isSymbol(value)) return value;
    };
    args[1] = replacer;
    return _stringify.apply($JSON, args);
  }
});

// 19.4.3.4 Symbol.prototype[@@toPrimitive](hint)
$Symbol[PROTOTYPE][TO_PRIMITIVE] || __webpack_require__(45)($Symbol[PROTOTYPE], TO_PRIMITIVE, $Symbol[PROTOTYPE].valueOf);
// 19.4.3.5 Symbol.prototype[@@toStringTag]
setToStringTag($Symbol, 'Symbol');
// 20.2.1.9 Math[@@toStringTag]
setToStringTag(Math, 'Math', true);
// 24.3.3 JSON[@@toStringTag]
setToStringTag(global.JSON, 'JSON', true);


/***/ }),

/***/ 261:
/***/ (function(module, exports, __webpack_require__) {

var META = __webpack_require__(191)('meta');
var isObject = __webpack_require__(39);
var has = __webpack_require__(64);
var setDesc = __webpack_require__(41).f;
var id = 0;
var isExtensible = Object.isExtensible || function () {
  return true;
};
var FREEZE = !__webpack_require__(43)(function () {
  return isExtensible(Object.preventExtensions({}));
});
var setMeta = function (it) {
  setDesc(it, META, { value: {
    i: 'O' + ++id, // object ID
    w: {}          // weak collections IDs
  } });
};
var fastKey = function (it, create) {
  // return primitive with prefix
  if (!isObject(it)) return typeof it == 'symbol' ? it : (typeof it == 'string' ? 'S' : 'P') + it;
  if (!has(it, META)) {
    // can't set metadata to uncaught frozen object
    if (!isExtensible(it)) return 'F';
    // not necessary to add metadata
    if (!create) return 'E';
    // add missing metadata
    setMeta(it);
  // return object ID
  } return it[META].i;
};
var getWeak = function (it, create) {
  if (!has(it, META)) {
    // can't set metadata to uncaught frozen object
    if (!isExtensible(it)) return true;
    // not necessary to add metadata
    if (!create) return false;
    // add missing metadata
    setMeta(it);
  // return hash weak collections IDs
  } return it[META].w;
};
// add metadata on freeze-family methods calling
var onFreeze = function (it) {
  if (FREEZE && meta.NEED && isExtensible(it) && !has(it, META)) setMeta(it);
  return it;
};
var meta = module.exports = {
  KEY: META,
  NEED: false,
  fastKey: fastKey,
  getWeak: getWeak,
  onFreeze: onFreeze
};


/***/ }),

/***/ 262:
/***/ (function(module, exports, __webpack_require__) {

var getKeys = __webpack_require__(190);
var toIObject = __webpack_require__(65);
module.exports = function (object, el) {
  var O = toIObject(object);
  var keys = getKeys(O);
  var length = keys.length;
  var index = 0;
  var key;
  while (length > index) if (O[key = keys[index++]] === el) return key;
};


/***/ }),

/***/ 263:
/***/ (function(module, exports, __webpack_require__) {

// all enumerable object keys, includes symbols
var getKeys = __webpack_require__(190);
var gOPS = __webpack_require__(227);
var pIE = __webpack_require__(212);
module.exports = function (it) {
  var result = getKeys(it);
  var getSymbols = gOPS.f;
  if (getSymbols) {
    var symbols = getSymbols(it);
    var isEnum = pIE.f;
    var i = 0;
    var key;
    while (symbols.length > i) if (isEnum.call(it, key = symbols[i++])) result.push(key);
  } return result;
};


/***/ }),

/***/ 264:
/***/ (function(module, exports, __webpack_require__) {

// 7.2.2 IsArray(argument)
var cof = __webpack_require__(226);
module.exports = Array.isArray || function isArray(arg) {
  return cof(arg) == 'Array';
};


/***/ }),

/***/ 265:
/***/ (function(module, exports, __webpack_require__) {

// fallback for IE11 buggy Object.getOwnPropertyNames with iframe and window
var toIObject = __webpack_require__(65);
var gOPN = __webpack_require__(228).f;
var toString = {}.toString;

var windowNames = typeof window == 'object' && window && Object.getOwnPropertyNames
  ? Object.getOwnPropertyNames(window) : [];

var getWindowNames = function (it) {
  try {
    return gOPN(it);
  } catch (e) {
    return windowNames.slice();
  }
};

module.exports.f = function getOwnPropertyNames(it) {
  return windowNames && toString.call(it) == '[object Window]' ? getWindowNames(it) : gOPN(toIObject(it));
};


/***/ }),

/***/ 266:
/***/ (function(module, exports, __webpack_require__) {

var pIE = __webpack_require__(212);
var createDesc = __webpack_require__(48);
var toIObject = __webpack_require__(65);
var toPrimitive = __webpack_require__(49);
var has = __webpack_require__(64);
var IE8_DOM_DEFINE = __webpack_require__(53);
var gOPD = Object.getOwnPropertyDescriptor;

exports.f = __webpack_require__(38) ? gOPD : function getOwnPropertyDescriptor(O, P) {
  O = toIObject(O);
  P = toPrimitive(P, true);
  if (IE8_DOM_DEFINE) try {
    return gOPD(O, P);
  } catch (e) { /* empty */ }
  if (has(O, P)) return createDesc(!pIE.f.call(O, P), O[P]);
};


/***/ }),

/***/ 267:
/***/ (function(module, exports) {



/***/ }),

/***/ 268:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(211)('asyncIterator');


/***/ }),

/***/ 269:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(211)('observable');


/***/ }),

/***/ 34:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _util = __webpack_require__(42);

__webpack_require__(370);

var _components = __webpack_require__(63);

var mapplic = $('#mapplic').mapplic({
    source: (0, _util.base_url)() + 'ajax/get_mapa', // Using mall.json file as map data
    sidebar: true, // hahilita Panel izquierdo
    minimap: false, // Enable minimap
    markers: false, // Deshabilita Marcadores
    mapfill: true,
    fillcolor: '',
    fullscreen: true, // Enable fullscreen
    zoom: false,
    maxscale: 2, // Setting maxscale to 3
    smartip: false,
    deeplinking: false //inhabilita nombres en uri,
});
//Cuando se abra un popout, asignar función de formato a números y superifice
$(mapplic).on('locationopened', function (e, location) {
    (0, _components.format_numeric)('init');
});

/***/ }),

/***/ 370:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _util = __webpack_require__(42);

var _Cart = __webpack_require__(22);

var _Cart2 = _interopRequireDefault(_Cart);

__webpack_require__(371);

__webpack_require__(372);

__webpack_require__(373);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

(function ($) {
    "use strict";

    var Mapplic = function Mapplic(element) {

        var self = this;

        self.o = {
            source: 'locations.json',
            selector: '[id^=landmark] > *',
            landmark: false,
            mapfill: false,
            height: 420,
            markers: true,
            minimap: true,
            sidebar: true,
            search: true,
            deeplinking: true,
            clearbutton: true,
            zoombuttons: true,
            action: 'tooltip',
            lightbox: false,
            hovertip: {
                desc: false
            },
            tooltip: {
                thumb: true,
                desc: true,
                link: true
            },
            smartip: true,
            fillcolor: '#4d5e6d',
            fullscreen: true,
            developer: false,
            mousewheel: true,
            maxscale: 4,
            skin: '',
            zoom: true
        };
        self.el = element;

        self.init = function (options) {
            // Merging options with defaults
            self.o = $.extend(self.o, options);

            self.x = 0;
            self.y = 0;
            self.scale = 1;

            self.el.addClass('mapplic-element mapplic-loading').addClass(self.o.skin).height(self.o.height);

            // Disable modules when landmark mode is active
            if (self.o.landmark) {
                self.o.sidebar = false;
                self.o.zoombuttons = false;
                self.o.deeplinking = false;
            }

            if (typeof self.o.source === 'string') {
                // Loading .json file with AJAX
                $.getJSON(self.o.source, function (data) {
                    // Success
                    processData(data);
                    self.el.removeClass('mapplic-loading');
                }).fail(function () {
                    // Failure: couldn't load JSON file or it is invalid.
                    console.error('Couldn\'t load map data. (Make sure to run the script through a web server and not just open the html file in a browser directly)');
                    self.el.removeClass('mapplic-loading').addClass('mapplic-error');
                    alert('Data file missing or invalid!\nMake sure to run the script through web server.');
                });
            } else {
                // Inline json object
                processData(self.o.source);
                self.el.removeClass('mapplic-loading');
            }

            return self;
        };

        // Tooltip
        function Tooltip() {
            this.el = null;
            this.pin = null;
            this.shift = 6;
            this.drop = 0;
            this.location = null;

            this.init = function () {
                var s = this;

                // Construct
                this.el = $('<div></div>').addClass('mapplic-tooltip');
                this.close = $('<a></a>').addClass('mapplic-tooltip-close').attr('href', '#').appendTo(this.el);
                this.close.on('click touchend', function (e) {
                    e.preventDefault();
                    self.hideLocation();
                    if (!self.o.zoom) self.moveTo(0.5, 0.5, 1, 600, 'easeInOutCubic');
                });
                if (self.o.tooltip.thumb) this.thumbnail = $('<img>').addClass('mapplic-tooltip-thumbnail').hide().appendTo(this.el);
                this.content = $('<div></div>').addClass('mapplic-tooltip-content').appendTo(this.el);
                this.title = $('<h4></h4>').addClass('mapplic-tooltip-title').appendTo(this.content);
                if (self.o.tooltip.desc) this.desc = $('<div></div>').addClass('mapplic-tooltip-description').appendTo(this.content);
                if (self.o.tooltip.link) this.link = $('<button><i class="fa fa-plus"></i>&nbsp;Añadir</button>').addClass('mapplic-popup-link btn btn-success').attr('href', '#').on('click', function (e) {
                    e.preventDefault();
                    var id_huerto = this.getAttribute("href");
                    $.ajax({
                        url: (0, _util.base_url)() + "ajax/add_cart",
                        data: { id_huerto: id_huerto },
                        type: "post"
                    }).done(function (response) {
                        var cart = new _Cart2.default();
                        cart.templateCart(response);
                    }).fail(function (response) {
                        alert("Algún error, contacte al administrador");
                    });
                }).hide().appendTo(this.el);
                this.triangle = $('<div></div>').addClass('mapplic-tooltip-triangle').prependTo(this.el);

                // Append
                self.map.append(this.el);
            };

            this.show = function (location) {
                if (location) {
                    var s = this;

                    this.location = location;
                    if (self.hovertip) self.hovertip.hide();

                    if (self.o.tooltip.thumb) {
                        if (location.thumbnail) this.thumbnail.attr('src', location.thumbnail).show();else this.thumbnail.hide();
                    }

                    if (self.o.tooltip.link) {
                        if (location.link) this.link.attr('href', location.link).show();else this.link.hide();
                    }
                    this.title.text(location.title);
                    if (self.o.tooltip.desc) this.desc.html(location.description);
                    this.content[0].scrollTop = 0;

                    // Shift
                    this.pin = $('.mapplic-pin[data-location="' + location.id + '"]');
                    if (this.pin.length == 0) {
                        this.shift = 20;
                    } else this.shift = Math.abs(parseFloat(this.pin.css('margin-top'))) + 20;

                    // Loading & positioning
                    $('img', this.el).off('load').load(function () {
                        s.position();
                        s.zoom(location);
                    });
                    this.position();

                    // Making it visible
                    this.el.stop().show();
                    this.zoom(location);
                }
            };

            this.position = function () {
                if (this.location) {
                    var cx = self.map.offset().left + self.map.width() * this.location.x - self.container.offset().left,
                        cy = self.map.offset().top + self.map.height() * this.location.y - self.container.offset().top;

                    var x = this.location.x * 100,
                        y = this.location.y * 100,
                        mt = -this.el.outerHeight() - this.shift,
                        ml = -this.el.outerWidth() / 2;

                    if (self.o.smartip) {
                        var verticalPos = 0.5;

                        // Top check
                        if (Math.abs(mt) > cy) {
                            mt = 8 + 2;
                            if (this.pin && this.pin.length) mt = this.pin.height() + parseFloat(this.pin.css('margin-top')) + 20;
                            this.el.addClass('mapplic-bottom');
                        } else this.el.removeClass('mapplic-bottom');

                        // Left-right check
                        if (this.el.outerWidth() / 2 > cx) verticalPos = 0.5 - (this.el.outerWidth() / 2 - cx) / this.el.outerWidth();else if (self.container.width() - cx - this.el.outerWidth() / 2 < 0) verticalPos = 0.5 + (cx + this.el.outerWidth() / 2 - self.container.width()) / this.el.outerWidth();

                        verticalPos = Math.max(0, Math.min(1, verticalPos));
                        ml = -this.el.outerWidth() * verticalPos;
                        this.triangle.css('left', Math.max(5, Math.min(95, verticalPos * 100)) + '%');
                    }

                    this.el.css({
                        left: x + '%',
                        top: y + '%',
                        marginTop: mt,
                        marginLeft: ml
                    });
                    this.drop = this.el.outerHeight() + this.shift;
                }
            };

            this.zoom = function (location) {
                var ry = 0.5,
                    zoom = location.zoom ? parseFloat(location.zoom) : self.o.maxscale;

                ry = ((self.container.height() - this.drop) / 2 + this.drop) / self.container.height();
                self.moveTo(location.x, location.y, zoom, 600, 'easeInOutCubic', ry);
            };

            this.hide = function () {
                var s = this;

                this.location = null;

                this.el.stop().fadeOut(300, function () {
                    if (s.desc) s.desc.empty();
                });
            };
        }

        // Lightbox
        function Lightbox() {
            this.el = null;

            this.init = function () {
                // Construct
                this.el = $('<div></div>').addClass('mapplic-lightbox mfp-hide');
                this.title = $('<h2></h2>').addClass('mapplic-lightbox-title').appendTo(this.el);
                this.desc = $('<div></div>').addClass('mapplic-lightbox-description').appendTo(this.el);
                this.link = $('<a>More</a>').addClass('mapplic-popup-link').attr('href', '#').hide().appendTo(this.el);

                // Popup Image
                $('body').magnificPopup({
                    delegate: '.mapplic-popup-image',
                    type: 'image',
                    removalDelay: 300,
                    mainClass: 'mfp-fade'
                });

                // Append
                self.el.append(this.el);
            };

            this.show = function (location) {
                this.location = location;

                this.title.text(location.title);
                this.desc.html(location.description);

                if (location.link) this.link.attr('href', location.link).show();else this.link.hide();

                var s = this;

                $.magnificPopup.open({
                    items: {
                        src: this.el
                    },
                    type: 'inline',
                    removalDelay: 300,
                    mainClass: 'mfp-fade',
                    callbacks: {
                        beforeClose: function beforeClose() {
                            s.hide();
                        }
                    }
                });

                // Zoom
                var zoom = location.zoom ? location.zoom : self.o.maxscale;
                self.moveTo(location.x, location.y, zoom, 600, 'easeInOutCubic');

                // Hide tooltip
                if (self.tooltip) self.tooltip.hide();
            };

            this.hide = function () {
                //$.magnificPopup.close();
                this.location = null;
                self.hideLocation();
            };
        }

        // HoverTooltip
        function HoverTooltip() {
            this.el = null;
            this.pin = null;
            this.shift = 6;

            this.init = function () {
                var s = this;

                // Construct
                this.el = $('<div></div>').addClass('mapplic-tooltip mapplic-hovertip');
                this.title = $('<h4></h4>').addClass('mapplic-tooltip-title').appendTo(this.el);
                if (self.o.hovertip.desc) this.desc = $('<div></div>').addClass('mapplic-tooltip-description').appendTo(this.el);
                this.triangle = $('<div></div>').addClass('mapplic-tooltip-triangle').appendTo(this.el);

                // Events 
                // pins + old svg
                $(self.map).on('mouseover', '.mapplic-layer a', function () {
                    var id = '';
                    if ($(this).hasClass('mapplic-pin')) {
                        id = $(this).data('location');
                        s.pin = $('.mapplic-pin[data-location="' + id + '"]');
                        s.shift = Math.abs(parseFloat(s.pin.css('margin-top'))) + 20;
                    } else {
                        id = $(this).attr('xlink:href').slice(1);
                        s.shift = 20;
                    }

                    var location = self.getLocationData(id);
                    if (location) s.show(location);
                }).on('mouseout', function () {
                    s.hide();
                });

                // new svg
                if (self.o.selector) {
                    $(self.map).on('mouseover', self.o.selector, function () {
                        var location = self.getLocationData($(this).attr('id'));
                        s.shift = 20;
                        if (location) s.show(location);
                    }).on('mouseout', function () {
                        s.hide();
                    });
                }

                self.map.append(this.el);
            };

            this.show = function (location) {
                if (self.tooltip.location != location) {
                    this.title.text(location.title);

                    if (self.o.hovertip.desc) this.desc.html(location.description);

                    this.position(location);

                    this.el.stop().fadeIn(100);
                }
            };

            this.position = function (location) {
                var cx = self.map.offset().left + self.map.width() * location.x - self.container.offset().left,
                    cy = self.map.offset().top + self.map.height() * location.y - self.container.offset().top;

                var x = location.x * 100,
                    y = location.y * 100,
                    mt = -this.el.outerHeight() - this.shift,
                    ml = 0;

                var verticalPos = 0.5;

                // Top check
                if (Math.abs(mt) > cy) {
                    mt = 8 + 2;
                    if (this.pin && this.pin.length) mt = this.pin.height() + parseFloat(this.pin.css('margin-top')) + 20;
                    this.el.addClass('mapplic-bottom');
                } else this.el.removeClass('mapplic-bottom');

                // Left-right check
                if (this.el.outerWidth() / 2 > cx) verticalPos = 0.5 - (this.el.outerWidth() / 2 - cx) / this.el.outerWidth();else if (self.container.width() - cx - this.el.outerWidth() / 2 < 0) verticalPos = 0.5 + (cx + this.el.outerWidth() / 2 - self.container.width()) / this.el.outerWidth();

                ml = -this.el.outerWidth() * verticalPos;
                this.triangle.css('left', Math.max(10, Math.min(90, verticalPos * 100)) + '%');
                this.el.css({
                    left: x + '%',
                    top: y + '%',
                    marginTop: mt,
                    marginLeft: ml
                });
            };

            this.hide = function () {
                this.el.stop().fadeOut(200);
            };
        }

        // Deeplinking
        function Deeplinking() {
            this.param = 'location';

            this.init = function () {
                var s = this;
                this.check(0);

                window.onpopstate = function (e) {
                    if (e.state) {
                        s.check(600);
                    }
                    return false;
                };
            };

            this.check = function (easing) {
                var id = this.getUrlParam(this.param);
                self.showLocation(id, easing, true);
            };

            this.getUrlParam = function (name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                    results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            };

            this.update = function (id) {
                var url = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + this.param + '=' + id;
                window.history.pushState({ path: url }, '', url);
            };

            // Clear
            this.clear = function () {
                history.pushState('', document.title, window.location.pathname);
            };
        }

        // Old hash deeplinking method for old browsers
        function DeeplinkingHash() {
            this.param = 'location';

            this.init = function () {
                var s = this;
                this.check(0);

                $(window).on('hashchange', function () {
                    s.check(600);
                });
            };

            this.check = function (easing) {
                var id = location.hash.slice(this.param.length + 2);
                self.showLocation(id, easing, true);
            };

            this.update = function (id) {
                window.location.hash = this.param + '-' + id;
            };

            this.clear = function () {
                window.location.hash = this.param;
            };
        }

        // Minimap
        function Minimap() {
            this.el = null;
            this.opacity = null;

            this.init = function () {
                this.el = $('<div></div>').addClass('mapplic-minimap').appendTo(self.container);
                this.el.click(function (e) {
                    e.preventDefault();

                    var x = (e.pageX - $(this).offset().left) / $(this).width(),
                        y = (e.pageY - $(this).offset().top) / $(this).height();

                    self.moveTo(x, y, self.scale / self.fitscale, 100);
                });
            };

            this.addLayer = function (data) {
                var layer = $('<div></div>').addClass('mapplic-minimap-layer').addClass(data.id).appendTo(this.el),
                    s = this;

                $('<img>').attr('src', data.minimap).addClass('mapplic-minimap-background').appendTo(layer);
                $('<div></div>').addClass('mapplic-minimap-overlay').appendTo(layer);
                $('<img>').attr('src', data.minimap).addClass('mapplic-minimap-active').load(function () {
                    s.update();
                    $(this).addClass('mapplic-clip-transition');
                }).appendTo(layer);
            };

            this.show = function (target) {
                $('.mapplic-minimap-layer', this.el).hide();
                $('.mapplic-minimap-layer.' + target, this.el).show();
            };

            this.update = function (x, y) {
                var active = $('.mapplic-minimap-active', this.el);

                if (x === undefined) x = self.x;
                if (y === undefined) y = self.y;

                var width = Math.round(self.container.width() / self.contentWidth / self.scale * this.el.width()),
                    height = Math.round(self.container.height() / self.contentHeight / self.scale * this.el.height()),
                    top = Math.round(-y / self.contentHeight / self.scale * this.el.height()),
                    left = Math.round(-x / self.contentWidth / self.scale * this.el.width()),
                    right = left + width,
                    bottom = top + height;

                active.each(function () {
                    $(this)[0].style.clip = 'rect(' + top + 'px, ' + right + 'px, ' + bottom + 'px, ' + left + 'px)';
                });

                // Fade out effect
                var s = this;
                this.el.show();
                this.el.css('opacity', 1.0);
                clearTimeout(this.opacity);
                this.opacity = setTimeout(function () {
                    s.el.css('opacity', 0);
                    setTimeout(function () {
                        s.el.hide();
                    }, 600);
                }, 2000);
            };
        }

        // Sidebar
        function Sidebar() {
            this.el = null;
            this.list = null;

            this.init = function () {
                var s = this;

                this.el = $('<div></div>').addClass('mapplic-sidebar').appendTo(self.el);

                if (self.o.search) {
                    var form = $('<form></form>').addClass('mapplic-search-form').submit(function () {
                        return false;
                    }).appendTo(this.el);
                    self.clear = $('<button></button>').attr('type', 'button').addClass('mapplic-search-clear').click(function () {
                        input.val('');
                        input.keyup();
                    }).appendTo(form);
                    var input = $('<input>').attr({ 'type': 'text', 'spellcheck': 'false', 'placeholder': 'Search...' }).addClass('mapplic-search-input').keyup(function () {
                        var keyword = $(this).val();
                        s.search(keyword);
                    }).prependTo(form);
                }

                var listContainer = $('<div></div>').addClass('mapplic-list-container').appendTo(this.el);
                this.list = $('<ol></ol>').addClass('mapplic-list').appendTo(listContainer);
                this.notfound = $('<p></p>').addClass('mapplic-not-found').text('Nothing found. Please try a different search.').appendTo(listContainer);

                if (!self.o.search) listContainer.css('padding-top', '0');
            };

            this.addCategories = function (categories) {
                var list = this.list;

                if (categories) {
                    $.each(categories, function (index, category) {
                        var item = $('<li></li>').addClass('mapplic-list-category').attr('data-category', category.id);
                        var ol = $('<ol></ol>').css('border-color', category.color).appendTo(item);
                        if (category.show == 'false') ol.hide();else item.addClass('mapplic-opened');
                        var link = $('<a></a>').attr('href', '#').attr('title', category.title).css('background-color', category.color).text(category.title).prependTo(item);
                        link.on('click', function (e) {
                            e.preventDefault();
                            item.toggleClass('mapplic-opened');
                            ol.slideToggle(200);
                        });
                        if (category.icon) $('<img>').attr('src', category.icon).addClass('mapplic-list-thumbnail').prependTo(link);
                        $('<span></span>').text('0').addClass('mapplic-list-count').prependTo(link);
                        list.append(item);
                    });
                }
            };

            this.addLocation = function (data) {
                var item = $('<li></li>').addClass('mapplic-list-location').addClass('mapplic-list-shown').attr('data-location', data.id);
                var link = $('<a></a>').attr('href', '#').click(function (e) {
                    e.preventDefault();
                    self.showLocation(data.id, 600);

                    // Scroll back to map on mobile
                    if ($(window).width() < 668) {
                        $('html, body').animate({
                            scrollTop: self.container.offset().top
                        }, 400);
                    }
                }).appendTo(item);

                if (data.thumbnail) $('<img>').attr('src', data.thumbnail).addClass('mapplic-list-thumbnail').appendTo(link);
                $('<h4></h4>').text(data.title).appendTo(link);
                $('<span></span>').html(data.about).appendTo(link);
                var category = $('.mapplic-list-category[data-category="' + data.category + '"]');

                if (category.length) $('ol', category).append(item);else this.list.append(item);

                // Count
                $('.mapplic-list-count', category).text($('.mapplic-list-shown', category).length);
            };

            this.search = function (keyword) {
                if (keyword) self.clear.fadeIn(100);else self.clear.fadeOut(100);

                $('.mapplic-list li', self.el).each(function () {
                    if ($(this).text().search(new RegExp(keyword, "i")) < 0) {
                        $(this).removeClass('mapplic-list-shown');
                        $(this).slideUp(200);
                    } else {
                        $(this).addClass('mapplic-list-shown');
                        $(this).show();
                    }
                });

                $('.mapplic-list > li', self.el).each(function () {
                    var count = $('.mapplic-list-shown', this).length;
                    $('.mapplic-list-count', this).text(count);
                });

                // Show not-found text
                if ($('.mapplic-list > li.mapplic-list-shown').length > 0) this.notfound.fadeOut(200);else this.notfound.fadeIn(200);
            };
        }

        // Developer tools
        function DevTools() {
            this.el = null;

            this.init = function () {
                this.el = $('<div></div>').addClass('mapplic-coordinates').appendTo(self.container);
                this.el.append('x: ');
                $('<code></code>').addClass('mapplic-coordinates-x').appendTo(this.el);
                this.el.append(' y: ');
                $('<code></code>').addClass('mapplic-coordinates-y').appendTo(this.el);

                $('.mapplic-layer', self.map).on('mousemove', function (e) {
                    var x = (e.pageX - self.map.offset().left) / self.map.width(),
                        y = (e.pageY - self.map.offset().top) / self.map.height();
                    $('.mapplic-coordinates-x').text(parseFloat(x).toFixed(4));
                    $('.mapplic-coordinates-y').text(parseFloat(y).toFixed(4));
                });
            };
        }

        // Clear Button
        function ClearButton() {
            this.el = null;

            this.init = function () {
                this.el = $('<a></a>').attr('href', '#').addClass('mapplic-clear-button').appendTo(self.container);

                this.el.on('click touchstart', function (e) {
                    e.preventDefault();
                    self.hideLocation();
                    self.moveTo(0.5, 0.5, 1, 400, 'easeInOutCubic');
                });
            };
        }

        // Zoom Buttons
        function ZoomButtons() {
            this.el = null;

            this.init = function () {
                this.el = $('<div></div>').addClass('mapplic-zoom-buttons').appendTo(self.container);

                // Zoom in button
                this.zoomin = $('<a></ha>').attr('href', '#').addClass('mapplic-zoomin-button').appendTo(this.el);
                this.zoomin.on('click touchstart', function (e) {
                    e.preventDefault();

                    var scale = self.scale;
                    self.scale = normalizeScale(scale + scale * 0.8);

                    self.x = normalizeX(self.x - (self.container.width() / 2 - self.x) * (self.scale / scale - 1));
                    self.y = normalizeY(self.y - (self.container.height() / 2 - self.y) * (self.scale / scale - 1));

                    zoomTo(self.x, self.y, self.scale, 400, 'easeInOutCubic');
                });

                // Zoom out button
                this.zoomout = $('<a></ha>').attr('href', '#').addClass('mapplic-zoomout-button').appendTo(this.el);
                this.zoomout.on('click touchstart', function (e) {
                    e.preventDefault();

                    var scale = self.scale;
                    self.scale = normalizeScale(scale - scale * 0.4);

                    self.x = normalizeX(self.x - (self.container.width() / 2 - self.x) * (self.scale / scale - 1));
                    self.y = normalizeY(self.y - (self.container.height() / 2 - self.y) * (self.scale / scale - 1));

                    zoomTo(self.x, self.y, self.scale, 400, 'easeInOutCubic');
                });
            };

            this.update = function (scale) {
                this.zoomin.removeClass('mapplic-disabled');
                this.zoomout.removeClass('mapplic-disabled');
                if (scale == self.fitscale) this.zoomout.addClass('mapplic-disabled');else if (scale == self.o.maxscale) this.zoomin.addClass('mapplic-disabled');
            };
        }

        // Full Screen
        function FullScreen() {
            this.el = null;

            this.init = function () {
                var s = this;
                this.element = self.el[0];

                $('<a></a>').attr('href', '#').attr('href', '#').addClass('mapplic-fullscreen-button').click(function (e) {
                    e.preventDefault();

                    if (s.isFull()) s.exitFull();else s.goFull();
                }).appendTo(self.container);
            };

            this.goFull = function () {
                if (this.element.requestFullscreen) this.element.requestFullscreen();else if (this.element.mozRequestFullScreen) this.element.mozRequestFullScreen();else if (this.element.webkitRequestFullscreen) this.element.webkitRequestFullscreen();else if (this.element.msRequestFullscreen) this.element.msRequestFullscreen();
            };

            this.exitFull = function () {
                if (document.exitFullscreen) document.exitFullscreen();else if (document.mozCancelFullScreen) document.mozCancelFullScreen();else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            };

            this.isFull = function () {
                if (window.innerHeight == screen.height) return true;else return false;
            };
        }

        // Functions
        var processData = function processData(data) {
            self.data = data;
            var shownLevel = null;

            self.container = $('<div></div>').addClass('mapplic-container').appendTo(self.el);
            self.map = $('<div></div>').addClass('mapplic-map').appendTo(self.container);
            if (self.o.zoom) self.map.addClass('mapplic-zoomable');

            self.levelselect = $('<select></select>').addClass('mapplic-levels-select');

            self.contentWidth = parseFloat(data.mapwidth);
            self.contentHeight = parseFloat(data.mapheight);

            // Create minimap
            if (self.o.minimap) {
                self.minimap = new Minimap();
                self.minimap.init();
            }

            // Create sidebar
            if (self.o.sidebar) {
                self.sidebar = new Sidebar();
                self.sidebar.init();
                self.sidebar.addCategories(data.categories);
            } else self.container.css('width', '100%');

            // Iterate through levels
            var nrlevels = 0;

            if (data.levels) {
                $.each(data.levels, function (index, level) {
                    var source = level.map,
                        extension = source.substr(source.lastIndexOf('.') + 1).toLowerCase();

                    // Create new map layer
                    var layer = $('<div></div>').addClass('mapplic-layer').attr('data-floor', level.id).hide().appendTo(self.map);
                    switch (extension) {

                        // Image formats
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            $('<img>').attr('src', source).addClass('mapplic-map-image').appendTo(layer);
                            break;

                        // Vector format
                        case 'svg':
                            $('<div></div>').addClass('mapplic-map-image').load(source, function () {
                                // Setting up the locations on the map
                                $(self.o.selector, this).each(function () {
                                    var location = self.getLocationData($(this).attr('id'));
                                    if (location) {
                                        $(this).attr('class', 'mapplic-clickable');
                                        location.el = $(this);

                                        var fill = null;
                                        if (location.fill) fill = location.fill;else if (self.o.fillcolor) fill = self.o.fillcolor;

                                        if (fill) {
                                            $(this).css('fill', fill);
                                            $('> *', this).css('fill', fill);
                                        }

                                        // Landmark mode
                                        if (self.o.landmark === location.id) $(this).attr('class', 'mapplic-active');
                                    }
                                });

                                // Click event
                                $(self.o.selector, this).on('click touchend', function () {
                                    if (!self.dragging) self.showLocation($(this).attr('id'), 600);
                                });

                                // Support for the old map format
                                $('svg a', this).each(function () {
                                    var location = self.getLocationData($(this).attr('xlink:href').substr(1));
                                    if (location) {
                                        $(this).attr('class', 'mapplic-clickable');
                                        location.el = $(this);
                                    }
                                });

                                $('svg a', this).click(function (e) {
                                    var id = $(this).attr('xlink:href').substr(1);
                                    self.showLocation(id, 600);
                                    e.preventDefault();
                                });
                            }).appendTo(layer);
                            break;

                        // Other 
                        default:
                            alert('File type ' + extension + ' is not supported!');
                    }

                    // Create new minimap layer
                    if (self.minimap) self.minimap.addLayer(level);

                    // Build layer control
                    self.levelselect.prepend($('<option></option>').attr('value', level.id).text(level.title));

                    // Shown level
                    if (!shownLevel || level.show) shownLevel = level.id;

                    // Iterate through locations
                    $.each(level.locations, function (index, location) {
                        // Geolocation
                        if (location.lat && location.lng) {
                            var pos = latlngToPos(location.lat, location.lng);
                            location.x = pos.x;
                            location.y = pos.y;
                        }

                        var top = location.y * 100,
                            left = location.x * 100;

                        if (!location.pin) location.pin = 'default';
                        if (location.pin.indexOf('hidden') == -1) {
                            if (self.o.markers) {
                                var pin = $('<a></a>').attr('href', '#').addClass('mapplic-pin').css({ 'top': top + '%', 'left': left + '%' }).appendTo(layer);
                                pin.on('click touchend', function (e) {
                                    e.preventDefault();
                                    self.showLocation(location.id, 600);
                                });
                                if (location.label) pin.html(location.label);
                                if (location.fill) pin.css('background-color', location.fill);
                                pin.attr('data-location', location.id);
                                pin.addClass(location.pin);
                                location.el = pin;
                            }
                        }

                        if (self.sidebar) self.sidebar.addLocation(location);
                    });

                    nrlevels++;
                });
            }

            // COMPONENTS

            // Tooltip (default)
            self.tooltip = new Tooltip();
            self.tooltip.init();

            // Lightbox
            if (self.o.lightbox) {
                self.lightbox = new Lightbox();
                self.lightbox.init();
            }

            // Hover Tooltip
            if (self.o.hovertip) {
                self.hovertip = new HoverTooltip();
                self.hovertip.init();
            }

            // Developer tools
            if (self.o.developer) self.devtools = new DevTools().init();

            // Clear button
            if (self.o.clearbutton) self.clearbutton = new ClearButton().init();

            // Zoom buttons
            if (self.o.zoombuttons) {
                self.zoombuttons = new ZoomButtons();
                self.zoombuttons.init();
                if (!self.o.clearbutton) self.zoombuttons.el.css('bottom', '0');
            }

            // Fullscreen
            if (self.o.fullscreen) self.fullscreen = new FullScreen().init();

            // Level switcher
            if (nrlevels > 1) {
                self.levels = $('<div></div>').addClass('mapplic-levels');
                var up = $('<a href="#"></a>').addClass('mapplic-levels-up').appendTo(self.levels);
                self.levelselect.appendTo(self.levels);
                var down = $('<a href="#"></a>').addClass('mapplic-levels-down').appendTo(self.levels);
                self.container.append(self.levels);

                self.levelselect.change(function () {
                    var value = $(this).val();
                    self.switchLevel(value);
                });

                up.click(function (e) {
                    e.preventDefault();
                    if (!$(this).hasClass('mapplic-disabled')) self.switchLevel('+');
                });

                down.click(function (e) {
                    e.preventDefault();
                    if (!$(this).hasClass('mapplic-disabled')) self.switchLevel('-');
                });
            }
            self.switchLevel(shownLevel);

            // Browser resize
            $(window).resize(function () {
                // Mobile
                if ($(window).width() < 668) {
                    var height = Math.min(Math.max(self.container.width() * self.contentHeight / self.contentWidth, $(window).height() * 2 / 3), $(window).height() - 66);
                    self.container.height(height);
                } else self.container.height('100%');

                var wr = self.container.width() / self.contentWidth,
                    hr = self.container.height() / self.contentHeight;

                if (self.o.mapfill) {
                    if (wr > hr) self.fitscale = wr;else self.fitscale = hr;
                } else {
                    if (wr < hr) self.fitscale = wr;else self.fitscale = hr;
                }

                self.scale = normalizeScale(self.scale);
                self.x = normalizeX(self.x);
                self.y = normalizeY(self.y);

                zoomTo(self.x, self.y, self.scale, 100);
            }).resize();

            // Landmark mode
            if (self.o.landmark) {
                self.showLocation(self.o.landmark, 0);
            } else {
                self.moveTo(0.5, 0.5, 1, 0);
            }

            // Deeplinking
            if (self.o.deeplinking) {
                if (history.pushState) self.deeplinking = new Deeplinking();else self.deeplinking = new DeeplinkingHash();

                self.deeplinking.init();
            }

            // Trigger event
            self.el.trigger('mapready', self);

            // Controls
            if (self.o.zoom) addControls();
        };

        var addControls = function addControls() {
            var map = self.map,
                mapbody = $('.mapplic-map-image', self.map);

            document.ondragstart = function () {
                return false;
            }; // IE drag fix

            // Drag & drop
            mapbody.on('mousedown', function (e) {
                self.dragging = false;
                map.stop();

                map.data('mouseX', e.pageX);
                map.data('mouseY', e.pageY);
                map.data('lastX', self.x);
                map.data('lastY', self.y);

                map.addClass('mapplic-dragging');

                self.map.on('mousemove', function (e) {
                    self.dragging = true;

                    var x = e.pageX - map.data('mouseX') + self.x,
                        y = e.pageY - map.data('mouseY') + self.y;

                    x = normalizeX(x);
                    y = normalizeY(y);

                    zoomTo(x, y);
                    map.data('lastX', x);
                    map.data('lastY', y);
                });

                $(document).on('mouseup', function () {
                    self.x = map.data('lastX');
                    self.y = map.data('lastY');

                    self.map.off('mousemove');
                    $(document).off('mouseup');

                    map.removeClass('mapplic-dragging');
                });
            });

            // Double click
            self.el.on('dblclick', '.mapplic-map-image', function (e) {
                e.preventDefault();

                var scale = self.scale;
                self.scale = normalizeScale(scale * 2);

                self.x = normalizeX(self.x - (e.pageX - self.container.offset().left - self.x) * (self.scale / scale - 1));
                self.y = normalizeY(self.y - (e.pageY - self.container.offset().top - self.y) * (self.scale / scale - 1));

                zoomTo(self.x, self.y, self.scale, 400, 'easeInOutCubic');
            });

            // Mousewheel
            if (self.o.mousewheel) {
                $('.mapplic-layer', self.el).bind('mousewheel DOMMouseScroll', function (e, delta) {
                    var scale = self.scale;

                    self.scale = normalizeScale(scale + scale * delta / 5);

                    // Disable page scroll when zoom is applicable
                    //if (scale != self.scale)
                    e.preventDefault();

                    self.x = normalizeX(self.x - (e.pageX - self.container.offset().left - self.x) * (self.scale / scale - 1));
                    self.y = normalizeY(self.y - (e.pageY - self.container.offset().top - self.y) * (self.scale / scale - 1));

                    zoomTo(self.x, self.y, self.scale, 200, 'easeOutCubic');
                });
            }

            // Touch support
            if (!('ontouchstart' in window || 'onmsgesturechange' in window)) return true;
            mapbody.on('touchstart', function (e) {
                self.dragging = false;

                var orig = e.originalEvent,
                    pos = map.position();

                map.data('touchY', orig.changedTouches[0].pageY - pos.top);
                map.data('touchX', orig.changedTouches[0].pageX - pos.left);

                mapbody.on('touchmove', function (e) {
                    e.preventDefault();
                    self.dragging = true;

                    var orig = e.originalEvent;
                    var touches = orig.touches.length;

                    if (touches == 1) {
                        self.x = normalizeX(orig.changedTouches[0].pageX - map.data('touchX'));
                        self.y = normalizeY(orig.changedTouches[0].pageY - map.data('touchY'));

                        zoomTo(self.x, self.y, self.scale, 50);
                    } else {
                        mapbody.off('touchmove');
                    }
                });

                mapbody.on('touchend', function (e) {
                    mapbody.off('touchmove touchend');
                });
            });

            // Pinch zoom
            var hammer = new Hammer(self.map[0], {
                transform_always_block: true,
                drag_block_horizontal: true,
                drag_block_vertical: true
            });
            hammer.get('pinch').set({ enable: true });

            var scale = 1,
                last_scale;
            hammer.on('pinchstart', function (e) {
                self.dragging = false;

                scale = self.scale / self.fitscale;
                last_scale = scale;
            });

            hammer.on('pinch', function (e) {
                self.dragging = true;

                if (e.scale != 1) scale = Math.max(1, Math.min(last_scale * e.scale, 100));

                var oldscale = self.scale;
                self.scale = normalizeScale(scale * self.fitscale);

                self.x = normalizeX(self.x - (e.center.x - self.container.offset().left - self.x) * (self.scale / oldscale - 1));
                self.y = normalizeY(self.y - (e.center.y - self.y) * (self.scale / oldscale - 1)); // - self.container.offset().top

                zoomTo(self.x, self.y, self.scale, 100);
            });
        };

        /* PRIVATE METHODS */

        // Web Mercator (EPSG:3857) lat/lng projection
        var latlngToPos = function latlngToPos(lat, lng) {
            var deltaLng = self.data.rightLng - self.data.leftLng,
                bottomLatDegree = self.data.bottomLat * Math.PI / 180,
                mapWidth = self.data.mapwidth / deltaLng * 360 / (2 * Math.PI),
                mapOffsetY = mapWidth / 2 * Math.log((1 + Math.sin(bottomLatDegree)) / (1 - Math.sin(bottomLatDegree)));

            lat = lat * Math.PI / 180;

            return {
                x: (lng - self.data.leftLng) * (self.data.mapwidth / deltaLng) / self.data.mapwidth,
                y: (self.data.mapheight - (mapWidth / 2 * Math.log((1 + Math.sin(lat)) / (1 - Math.sin(lat))) - mapOffsetY)) / self.data.mapheight
            };
        };

        // jQuery bug add/remove class workaround (will be fixed in jQuery 3)
        var addClass = function addClass(element, c) {
            var classes = element.attr('class');
            if (classes.indexOf(c) == -1) element.attr('class', classes + ' ' + c);
        };

        var removeClass = function removeClass(element, c) {
            var classes = element.attr('class');
            if (classes) element.attr('class', classes.replace(c, '').trim());
        };

        var hasClass = function hasClass(element, c) {
            var classes = element.attr('class');
            return classes.indexOf(c) > -1;
        };

        // Normalizing x, y and scale
        var normalizeX = function normalizeX(x) {
            var minX = self.container.width() - self.contentWidth * self.scale;

            if (minX < 0) {
                if (x > 0) x = 0;else if (x < minX) x = minX;
            } else x = minX / 2;

            return x;
        };

        var normalizeY = function normalizeY(y) {
            var minY = self.container.height() - self.contentHeight * self.scale;

            if (minY < 0) {
                if (y >= 0) y = 0;else if (y < minY) y = minY;
            } else y = minY / 2;

            return y;
        };

        var normalizeScale = function normalizeScale(scale) {
            if (scale < self.fitscale) scale = self.fitscale;else if (scale > self.o.maxscale) scale = self.o.maxscale;

            if (self.zoombuttons) self.zoombuttons.update(scale);

            return scale;
        };

        var zoomTo = function zoomTo(x, y, scale, d, easing) {
            if (scale !== undefined) {
                self.map.stop().animate({
                    'left': x,
                    'top': y,
                    'width': self.contentWidth * scale,
                    'height': self.contentHeight * scale
                }, d, easing, function () {
                    if (self.tooltip) self.tooltip.position();
                });
            } else {
                self.map.css({
                    'left': x,
                    'top': y
                });
            }
            if (self.tooltip) self.tooltip.position();
            if (self.minimap) self.minimap.update(x, y);

            // Trigger event
            self.el.trigger('positionchanged', self);
        };

        /* PUBLIC METHODS */
        self.switchLevel = function (target) {
            switch (target) {
                case '+':
                    target = $('option:selected', self.levelselect).removeAttr('selected').prev().prop('selected', 'selected').val();
                    break;
                case '-':
                    target = $('option:selected', self.levelselect).removeAttr('selected').next().prop('selected', 'selected').val();
                    break;
                default:
                    $('option[value="' + target + '"]', self.levelselect).prop('selected', 'selected');
            }

            // No such layer
            if (!target) return;

            var layer = $('.mapplic-layer[data-floor="' + target + '"]', self.el);

            // Target layer is already active
            if (layer.is(':visible')) return;

            // Hide Tooltip
            if (self.tooltip) self.tooltip.hide();

            // Show target layer
            $('.mapplic-layer:visible', self.map).hide();
            layer.show();

            // Show target minimap layer
            if (self.minimap) self.minimap.show(target);

            // Update control
            var index = self.levelselect.get(0).selectedIndex,
                up = $('.mapplic-levels-up', self.el),
                down = $('.mapplic-levels-down', self.el);

            up.removeClass('mapplic-disabled');
            down.removeClass('mapplic-disabled');
            if (index == 0) up.addClass('mapplic-disabled');else if (index == self.levelselect.get(0).length - 1) down.addClass('mapplic-disabled');

            // Trigger event
            self.el.trigger('levelswitched', target);
        };

        self.moveTo = function (x, y, s, duration, easing, ry) {
            duration = typeof duration !== 'undefined' ? duration : 400;
            ry = typeof ry !== 'undefined' ? ry : 0.5;
            s = typeof s !== 'undefined' ? s : self.scale / self.fitscale;

            self.scale = normalizeScale(self.fitscale * s);

            self.x = normalizeX(self.container.width() * 0.5 - self.scale * self.contentWidth * x);
            self.y = normalizeY(self.container.height() * ry - self.scale * self.contentHeight * y);

            zoomTo(self.x, self.y, self.scale, duration, easing);
        };

        self.getLocationData = function (id) {
            var data = null;
            $.each(self.data.levels, function (index, level) {
                $.each(level.locations, function (index, location) {
                    if (location.id == id) {
                        data = location;
                    }
                });
            });
            return data;
        };

        self.showLocation = function (id, duration, check) {
            $.each(self.data.levels, function (index, level) {
                if (level.id == id) {
                    self.switchLevel(level.id);
                    return false;
                }
                $.each(level.locations, function (index, location) {
                    if (location.id == id) {
                        var action = location.action && location.action != 'default' ? location.action : self.o.action;
                        switch (action) {
                            case 'open-link':
                                window.location.href = location.link;
                                return false;
                            case 'open-link-new-tab':
                                window.open(location.link);
                                return false;
                            case 'select':
                                if (location.el) {
                                    if (hasClass(location.el, 'mapplic-active')) removeClass(location.el, 'mapplic-active');else addClass(location.el, 'mapplic-active');
                                }
                                return false;
                            case 'none':
                                var zoom = location.zoom ? location.zoom : self.o.maxscale;
                                self.moveTo(location.x, location.y, zoom, 600, 'easeInOutCubic');
                                break;
                            case 'lightbox':
                                self.switchLevel(level.id);
                                self.lightbox.show(location);
                                break;
                            default:
                                self.switchLevel(level.id);
                                self.tooltip.show(location);
                        }

                        // Active state
                        removeClass($('.mapplic-active', self.el), 'mapplic-active');
                        if (location.el) addClass(location.el, 'mapplic-active');

                        // Deeplinking
                        if (self.o.deeplinking && !check) self.deeplinking.update(id);

                        // Trigger event
                        self.el.trigger('locationopened', location);
                    }
                });
            });
        };

        self.hideLocation = function () {
            removeClass($('.mapplic-active', self.el), 'mapplic-active');
            if (self.deeplinking) self.deeplinking.clear();
            if (self.tooltip) self.tooltip.hide();

            // Trigger event
            self.el.trigger('locationclosed');
        };

        self.updateLocation = function (id) {
            var location = self.getLocationData(id);

            if (location.id == id && location.el.is('a')) {
                // Geolocation
                if (location.lat && location.lng) {
                    var pos = latlngToPos(location.lat, location.lng);
                    location.x = pos.x;
                    location.y = pos.y;
                }

                var top = location.y * 100,
                    left = location.x * 100;

                location.el.css({ 'top': top + '%', 'left': left + '%' });
            }
        };
    };

    // Easing functions used by default
    // For the full list of easing functions use jQuery Easing Plugin
    $.extend($.easing, {
        def: 'easeOutQuad',
        swing: function swing(x, t, b, c, d) {
            //alert(jQuery.easing.default);
            return jQuery.easing[jQuery.easing.def](x, t, b, c, d);
        },
        easeOutQuad: function easeOutQuad(x, t, b, c, d) {
            return -c * (t /= d) * (t - 2) + b;
        },
        easeOutCubic: function easeOutCubic(x, t, b, c, d) {
            return c * ((t = t / d - 1) * t * t + 1) + b;
        },
        easeInOutCubic: function easeInOutCubic(x, t, b, c, d) {
            if ((t /= d / 2) < 1) return c / 2 * t * t * t + b;
            return c / 2 * ((t -= 2) * t * t + 2) + b;
        }
    });

    // jQuery Plugin
    $.fn.mapplic = function (options) {

        return this.each(function () {
            var element = $(this);

            // Plugin already initiated on element
            if (element.data('mapplic')) return;

            var instance = new Mapplic(element).init(options);

            // Store plugin object in element's data
            element.data('mapplic', instance);
        });
    };
})(jQuery); /*
             * Mapplic - Custom Interactive Map Plugin by @sekler
             * Version 4.0
             * http://www.mapplic.com/
             */

/***/ }),

/***/ 371:
/***/ (function(module, exports, __webpack_require__) {

"use strict";
var __WEBPACK_AMD_DEFINE_RESULT__;

var _typeof2 = __webpack_require__(234);

var _typeof3 = _interopRequireDefault(_typeof2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/*! Hammer.JS - v2.0.6 - 2016-01-06
 * http://hammerjs.github.io/
 *
 * Copyright (c) 2016 Jorik Tangelder;
 * Licensed under the  license */
!function (a, b, c, d) {
  "use strict";
  function e(a, b, c) {
    return setTimeout(j(a, c), b);
  }function f(a, b, c) {
    return Array.isArray(a) ? (g(a, c[b], c), !0) : !1;
  }function g(a, b, c) {
    var e;if (a) if (a.forEach) a.forEach(b, c);else if (a.length !== d) for (e = 0; e < a.length;) {
      b.call(c, a[e], e, a), e++;
    } else for (e in a) {
      a.hasOwnProperty(e) && b.call(c, a[e], e, a);
    }
  }function h(b, c, d) {
    var e = "DEPRECATED METHOD: " + c + "\n" + d + " AT \n";return function () {
      var c = new Error("get-stack-trace"),
          d = c && c.stack ? c.stack.replace(/^[^\(]+?[\n$]/gm, "").replace(/^\s+at\s+/gm, "").replace(/^Object.<anonymous>\s*\(/gm, "{anonymous}()@") : "Unknown Stack Trace",
          f = a.console && (a.console.warn || a.console.log);return f && f.call(a.console, e, d), b.apply(this, arguments);
    };
  }function i(a, b, c) {
    var d,
        e = b.prototype;d = a.prototype = Object.create(e), d.constructor = a, d._super = e, c && ha(d, c);
  }function j(a, b) {
    return function () {
      return a.apply(b, arguments);
    };
  }function k(a, b) {
    return (typeof a === "undefined" ? "undefined" : (0, _typeof3.default)(a)) == ka ? a.apply(b ? b[0] || d : d, b) : a;
  }function l(a, b) {
    return a === d ? b : a;
  }function m(a, b, c) {
    g(q(b), function (b) {
      a.addEventListener(b, c, !1);
    });
  }function n(a, b, c) {
    g(q(b), function (b) {
      a.removeEventListener(b, c, !1);
    });
  }function o(a, b) {
    for (; a;) {
      if (a == b) return !0;a = a.parentNode;
    }return !1;
  }function p(a, b) {
    return a.indexOf(b) > -1;
  }function q(a) {
    return a.trim().split(/\s+/g);
  }function r(a, b, c) {
    if (a.indexOf && !c) return a.indexOf(b);for (var d = 0; d < a.length;) {
      if (c && a[d][c] == b || !c && a[d] === b) return d;d++;
    }return -1;
  }function s(a) {
    return Array.prototype.slice.call(a, 0);
  }function t(a, b, c) {
    for (var d = [], e = [], f = 0; f < a.length;) {
      var g = b ? a[f][b] : a[f];r(e, g) < 0 && d.push(a[f]), e[f] = g, f++;
    }return c && (d = b ? d.sort(function (a, c) {
      return a[b] > c[b];
    }) : d.sort()), d;
  }function u(a, b) {
    for (var c, e, f = b[0].toUpperCase() + b.slice(1), g = 0; g < ia.length;) {
      if (c = ia[g], e = c ? c + f : b, e in a) return e;g++;
    }return d;
  }function v() {
    return qa++;
  }function w(b) {
    var c = b.ownerDocument || b;return c.defaultView || c.parentWindow || a;
  }function x(a, b) {
    var c = this;this.manager = a, this.callback = b, this.element = a.element, this.target = a.options.inputTarget, this.domHandler = function (b) {
      k(a.options.enable, [a]) && c.handler(b);
    }, this.init();
  }function y(a) {
    var b,
        c = a.options.inputClass;return new (b = c ? c : ta ? M : ua ? P : sa ? R : L)(a, z);
  }function z(a, b, c) {
    var d = c.pointers.length,
        e = c.changedPointers.length,
        f = b & Aa && d - e === 0,
        g = b & (Ca | Da) && d - e === 0;c.isFirst = !!f, c.isFinal = !!g, f && (a.session = {}), c.eventType = b, A(a, c), a.emit("hammer.input", c), a.recognize(c), a.session.prevInput = c;
  }function A(a, b) {
    var c = a.session,
        d = b.pointers,
        e = d.length;c.firstInput || (c.firstInput = D(b)), e > 1 && !c.firstMultiple ? c.firstMultiple = D(b) : 1 === e && (c.firstMultiple = !1);var f = c.firstInput,
        g = c.firstMultiple,
        h = g ? g.center : f.center,
        i = b.center = E(d);b.timeStamp = na(), b.deltaTime = b.timeStamp - f.timeStamp, b.angle = I(h, i), b.distance = H(h, i), B(c, b), b.offsetDirection = G(b.deltaX, b.deltaY);var j = F(b.deltaTime, b.deltaX, b.deltaY);b.overallVelocityX = j.x, b.overallVelocityY = j.y, b.overallVelocity = ma(j.x) > ma(j.y) ? j.x : j.y, b.scale = g ? K(g.pointers, d) : 1, b.rotation = g ? J(g.pointers, d) : 0, b.maxPointers = c.prevInput ? b.pointers.length > c.prevInput.maxPointers ? b.pointers.length : c.prevInput.maxPointers : b.pointers.length, C(c, b);var k = a.element;o(b.srcEvent.target, k) && (k = b.srcEvent.target), b.target = k;
  }function B(a, b) {
    var c = b.center,
        d = a.offsetDelta || {},
        e = a.prevDelta || {},
        f = a.prevInput || {};(b.eventType === Aa || f.eventType === Ca) && (e = a.prevDelta = { x: f.deltaX || 0, y: f.deltaY || 0 }, d = a.offsetDelta = { x: c.x, y: c.y }), b.deltaX = e.x + (c.x - d.x), b.deltaY = e.y + (c.y - d.y);
  }function C(a, b) {
    var c,
        e,
        f,
        g,
        h = a.lastInterval || b,
        i = b.timeStamp - h.timeStamp;if (b.eventType != Da && (i > za || h.velocity === d)) {
      var j = b.deltaX - h.deltaX,
          k = b.deltaY - h.deltaY,
          l = F(i, j, k);e = l.x, f = l.y, c = ma(l.x) > ma(l.y) ? l.x : l.y, g = G(j, k), a.lastInterval = b;
    } else c = h.velocity, e = h.velocityX, f = h.velocityY, g = h.direction;b.velocity = c, b.velocityX = e, b.velocityY = f, b.direction = g;
  }function D(a) {
    for (var b = [], c = 0; c < a.pointers.length;) {
      b[c] = { clientX: la(a.pointers[c].clientX), clientY: la(a.pointers[c].clientY) }, c++;
    }return { timeStamp: na(), pointers: b, center: E(b), deltaX: a.deltaX, deltaY: a.deltaY };
  }function E(a) {
    var b = a.length;if (1 === b) return { x: la(a[0].clientX), y: la(a[0].clientY) };for (var c = 0, d = 0, e = 0; b > e;) {
      c += a[e].clientX, d += a[e].clientY, e++;
    }return { x: la(c / b), y: la(d / b) };
  }function F(a, b, c) {
    return { x: b / a || 0, y: c / a || 0 };
  }function G(a, b) {
    return a === b ? Ea : ma(a) >= ma(b) ? 0 > a ? Fa : Ga : 0 > b ? Ha : Ia;
  }function H(a, b, c) {
    c || (c = Ma);var d = b[c[0]] - a[c[0]],
        e = b[c[1]] - a[c[1]];return Math.sqrt(d * d + e * e);
  }function I(a, b, c) {
    c || (c = Ma);var d = b[c[0]] - a[c[0]],
        e = b[c[1]] - a[c[1]];return 180 * Math.atan2(e, d) / Math.PI;
  }function J(a, b) {
    return I(b[1], b[0], Na) + I(a[1], a[0], Na);
  }function K(a, b) {
    return H(b[0], b[1], Na) / H(a[0], a[1], Na);
  }function L() {
    this.evEl = Pa, this.evWin = Qa, this.allow = !0, this.pressed = !1, x.apply(this, arguments);
  }function M() {
    this.evEl = Ta, this.evWin = Ua, x.apply(this, arguments), this.store = this.manager.session.pointerEvents = [];
  }function N() {
    this.evTarget = Wa, this.evWin = Xa, this.started = !1, x.apply(this, arguments);
  }function O(a, b) {
    var c = s(a.touches),
        d = s(a.changedTouches);return b & (Ca | Da) && (c = t(c.concat(d), "identifier", !0)), [c, d];
  }function P() {
    this.evTarget = Za, this.targetIds = {}, x.apply(this, arguments);
  }function Q(a, b) {
    var c = s(a.touches),
        d = this.targetIds;if (b & (Aa | Ba) && 1 === c.length) return d[c[0].identifier] = !0, [c, c];var e,
        f,
        g = s(a.changedTouches),
        h = [],
        i = this.target;if (f = c.filter(function (a) {
      return o(a.target, i);
    }), b === Aa) for (e = 0; e < f.length;) {
      d[f[e].identifier] = !0, e++;
    }for (e = 0; e < g.length;) {
      d[g[e].identifier] && h.push(g[e]), b & (Ca | Da) && delete d[g[e].identifier], e++;
    }return h.length ? [t(f.concat(h), "identifier", !0), h] : void 0;
  }function R() {
    x.apply(this, arguments);var a = j(this.handler, this);this.touch = new P(this.manager, a), this.mouse = new L(this.manager, a);
  }function S(a, b) {
    this.manager = a, this.set(b);
  }function T(a) {
    if (p(a, db)) return db;var b = p(a, eb),
        c = p(a, fb);return b && c ? db : b || c ? b ? eb : fb : p(a, cb) ? cb : bb;
  }function U(a) {
    this.options = ha({}, this.defaults, a || {}), this.id = v(), this.manager = null, this.options.enable = l(this.options.enable, !0), this.state = gb, this.simultaneous = {}, this.requireFail = [];
  }function V(a) {
    return a & lb ? "cancel" : a & jb ? "end" : a & ib ? "move" : a & hb ? "start" : "";
  }function W(a) {
    return a == Ia ? "down" : a == Ha ? "up" : a == Fa ? "left" : a == Ga ? "right" : "";
  }function X(a, b) {
    var c = b.manager;return c ? c.get(a) : a;
  }function Y() {
    U.apply(this, arguments);
  }function Z() {
    Y.apply(this, arguments), this.pX = null, this.pY = null;
  }function $() {
    Y.apply(this, arguments);
  }function _() {
    U.apply(this, arguments), this._timer = null, this._input = null;
  }function aa() {
    Y.apply(this, arguments);
  }function ba() {
    Y.apply(this, arguments);
  }function ca() {
    U.apply(this, arguments), this.pTime = !1, this.pCenter = !1, this._timer = null, this._input = null, this.count = 0;
  }function da(a, b) {
    return b = b || {}, b.recognizers = l(b.recognizers, da.defaults.preset), new ea(a, b);
  }function ea(a, b) {
    this.options = ha({}, da.defaults, b || {}), this.options.inputTarget = this.options.inputTarget || a, this.handlers = {}, this.session = {}, this.recognizers = [], this.element = a, this.input = y(this), this.touchAction = new S(this, this.options.touchAction), fa(this, !0), g(this.options.recognizers, function (a) {
      var b = this.add(new a[0](a[1]));a[2] && b.recognizeWith(a[2]), a[3] && b.requireFailure(a[3]);
    }, this);
  }function fa(a, b) {
    var c = a.element;c.style && g(a.options.cssProps, function (a, d) {
      c.style[u(c.style, d)] = b ? a : "";
    });
  }function ga(a, c) {
    var d = b.createEvent("Event");d.initEvent(a, !0, !0), d.gesture = c, c.target.dispatchEvent(d);
  }var ha,
      ia = ["", "webkit", "Moz", "MS", "ms", "o"],
      ja = b.createElement("div"),
      ka = "function",
      la = Math.round,
      ma = Math.abs,
      na = Date.now;ha = "function" != typeof Object.assign ? function (a) {
    if (a === d || null === a) throw new TypeError("Cannot convert undefined or null to object");for (var b = Object(a), c = 1; c < arguments.length; c++) {
      var e = arguments[c];if (e !== d && null !== e) for (var f in e) {
        e.hasOwnProperty(f) && (b[f] = e[f]);
      }
    }return b;
  } : Object.assign;var oa = h(function (a, b, c) {
    for (var e = Object.keys(b), f = 0; f < e.length;) {
      (!c || c && a[e[f]] === d) && (a[e[f]] = b[e[f]]), f++;
    }return a;
  }, "extend", "Use `assign`."),
      pa = h(function (a, b) {
    return oa(a, b, !0);
  }, "merge", "Use `assign`."),
      qa = 1,
      ra = /mobile|tablet|ip(ad|hone|od)|android/i,
      sa = "ontouchstart" in a,
      ta = u(a, "PointerEvent") !== d,
      ua = sa && ra.test(navigator.userAgent),
      va = "touch",
      wa = "pen",
      xa = "mouse",
      ya = "kinect",
      za = 25,
      Aa = 1,
      Ba = 2,
      Ca = 4,
      Da = 8,
      Ea = 1,
      Fa = 2,
      Ga = 4,
      Ha = 8,
      Ia = 16,
      Ja = Fa | Ga,
      Ka = Ha | Ia,
      La = Ja | Ka,
      Ma = ["x", "y"],
      Na = ["clientX", "clientY"];x.prototype = { handler: function handler() {}, init: function init() {
      this.evEl && m(this.element, this.evEl, this.domHandler), this.evTarget && m(this.target, this.evTarget, this.domHandler), this.evWin && m(w(this.element), this.evWin, this.domHandler);
    }, destroy: function destroy() {
      this.evEl && n(this.element, this.evEl, this.domHandler), this.evTarget && n(this.target, this.evTarget, this.domHandler), this.evWin && n(w(this.element), this.evWin, this.domHandler);
    } };var Oa = { mousedown: Aa, mousemove: Ba, mouseup: Ca },
      Pa = "mousedown",
      Qa = "mousemove mouseup";i(L, x, { handler: function handler(a) {
      var b = Oa[a.type];b & Aa && 0 === a.button && (this.pressed = !0), b & Ba && 1 !== a.which && (b = Ca), this.pressed && this.allow && (b & Ca && (this.pressed = !1), this.callback(this.manager, b, { pointers: [a], changedPointers: [a], pointerType: xa, srcEvent: a }));
    } });var Ra = { pointerdown: Aa, pointermove: Ba, pointerup: Ca, pointercancel: Da, pointerout: Da },
      Sa = { 2: va, 3: wa, 4: xa, 5: ya },
      Ta = "pointerdown",
      Ua = "pointermove pointerup pointercancel";a.MSPointerEvent && !a.PointerEvent && (Ta = "MSPointerDown", Ua = "MSPointerMove MSPointerUp MSPointerCancel"), i(M, x, { handler: function handler(a) {
      var b = this.store,
          c = !1,
          d = a.type.toLowerCase().replace("ms", ""),
          e = Ra[d],
          f = Sa[a.pointerType] || a.pointerType,
          g = f == va,
          h = r(b, a.pointerId, "pointerId");e & Aa && (0 === a.button || g) ? 0 > h && (b.push(a), h = b.length - 1) : e & (Ca | Da) && (c = !0), 0 > h || (b[h] = a, this.callback(this.manager, e, { pointers: b, changedPointers: [a], pointerType: f, srcEvent: a }), c && b.splice(h, 1));
    } });var Va = { touchstart: Aa, touchmove: Ba, touchend: Ca, touchcancel: Da },
      Wa = "touchstart",
      Xa = "touchstart touchmove touchend touchcancel";i(N, x, { handler: function handler(a) {
      var b = Va[a.type];if (b === Aa && (this.started = !0), this.started) {
        var c = O.call(this, a, b);b & (Ca | Da) && c[0].length - c[1].length === 0 && (this.started = !1), this.callback(this.manager, b, { pointers: c[0], changedPointers: c[1], pointerType: va, srcEvent: a });
      }
    } });var Ya = { touchstart: Aa, touchmove: Ba, touchend: Ca, touchcancel: Da },
      Za = "touchstart touchmove touchend touchcancel";i(P, x, { handler: function handler(a) {
      var b = Ya[a.type],
          c = Q.call(this, a, b);c && this.callback(this.manager, b, { pointers: c[0], changedPointers: c[1], pointerType: va, srcEvent: a });
    } }), i(R, x, { handler: function handler(a, b, c) {
      var d = c.pointerType == va,
          e = c.pointerType == xa;if (d) this.mouse.allow = !1;else if (e && !this.mouse.allow) return;b & (Ca | Da) && (this.mouse.allow = !0), this.callback(a, b, c);
    }, destroy: function destroy() {
      this.touch.destroy(), this.mouse.destroy();
    } });var $a = u(ja.style, "touchAction"),
      _a = $a !== d,
      ab = "compute",
      bb = "auto",
      cb = "manipulation",
      db = "none",
      eb = "pan-x",
      fb = "pan-y";S.prototype = { set: function set(a) {
      a == ab && (a = this.compute()), _a && this.manager.element.style && (this.manager.element.style[$a] = a), this.actions = a.toLowerCase().trim();
    }, update: function update() {
      this.set(this.manager.options.touchAction);
    }, compute: function compute() {
      var a = [];return g(this.manager.recognizers, function (b) {
        k(b.options.enable, [b]) && (a = a.concat(b.getTouchAction()));
      }), T(a.join(" "));
    }, preventDefaults: function preventDefaults(a) {
      if (!_a) {
        var b = a.srcEvent,
            c = a.offsetDirection;if (this.manager.session.prevented) return void b.preventDefault();var d = this.actions,
            e = p(d, db),
            f = p(d, fb),
            g = p(d, eb);if (e) {
          var h = 1 === a.pointers.length,
              i = a.distance < 2,
              j = a.deltaTime < 250;if (h && i && j) return;
        }if (!g || !f) return e || f && c & Ja || g && c & Ka ? this.preventSrc(b) : void 0;
      }
    }, preventSrc: function preventSrc(a) {
      this.manager.session.prevented = !0, a.preventDefault();
    } };var gb = 1,
      hb = 2,
      ib = 4,
      jb = 8,
      kb = jb,
      lb = 16,
      mb = 32;U.prototype = { defaults: {}, set: function set(a) {
      return ha(this.options, a), this.manager && this.manager.touchAction.update(), this;
    }, recognizeWith: function recognizeWith(a) {
      if (f(a, "recognizeWith", this)) return this;var b = this.simultaneous;return a = X(a, this), b[a.id] || (b[a.id] = a, a.recognizeWith(this)), this;
    }, dropRecognizeWith: function dropRecognizeWith(a) {
      return f(a, "dropRecognizeWith", this) ? this : (a = X(a, this), delete this.simultaneous[a.id], this);
    }, requireFailure: function requireFailure(a) {
      if (f(a, "requireFailure", this)) return this;var b = this.requireFail;return a = X(a, this), -1 === r(b, a) && (b.push(a), a.requireFailure(this)), this;
    }, dropRequireFailure: function dropRequireFailure(a) {
      if (f(a, "dropRequireFailure", this)) return this;a = X(a, this);var b = r(this.requireFail, a);return b > -1 && this.requireFail.splice(b, 1), this;
    }, hasRequireFailures: function hasRequireFailures() {
      return this.requireFail.length > 0;
    }, canRecognizeWith: function canRecognizeWith(a) {
      return !!this.simultaneous[a.id];
    }, emit: function emit(a) {
      function b(b) {
        c.manager.emit(b, a);
      }var c = this,
          d = this.state;jb > d && b(c.options.event + V(d)), b(c.options.event), a.additionalEvent && b(a.additionalEvent), d >= jb && b(c.options.event + V(d));
    }, tryEmit: function tryEmit(a) {
      return this.canEmit() ? this.emit(a) : void (this.state = mb);
    }, canEmit: function canEmit() {
      for (var a = 0; a < this.requireFail.length;) {
        if (!(this.requireFail[a].state & (mb | gb))) return !1;a++;
      }return !0;
    }, recognize: function recognize(a) {
      var b = ha({}, a);return k(this.options.enable, [this, b]) ? (this.state & (kb | lb | mb) && (this.state = gb), this.state = this.process(b), void (this.state & (hb | ib | jb | lb) && this.tryEmit(b))) : (this.reset(), void (this.state = mb));
    }, process: function process(a) {}, getTouchAction: function getTouchAction() {}, reset: function reset() {} }, i(Y, U, { defaults: { pointers: 1 }, attrTest: function attrTest(a) {
      var b = this.options.pointers;return 0 === b || a.pointers.length === b;
    }, process: function process(a) {
      var b = this.state,
          c = a.eventType,
          d = b & (hb | ib),
          e = this.attrTest(a);return d && (c & Da || !e) ? b | lb : d || e ? c & Ca ? b | jb : b & hb ? b | ib : hb : mb;
    } }), i(Z, Y, { defaults: { event: "pan", threshold: 10, pointers: 1, direction: La }, getTouchAction: function getTouchAction() {
      var a = this.options.direction,
          b = [];return a & Ja && b.push(fb), a & Ka && b.push(eb), b;
    }, directionTest: function directionTest(a) {
      var b = this.options,
          c = !0,
          d = a.distance,
          e = a.direction,
          f = a.deltaX,
          g = a.deltaY;return e & b.direction || (b.direction & Ja ? (e = 0 === f ? Ea : 0 > f ? Fa : Ga, c = f != this.pX, d = Math.abs(a.deltaX)) : (e = 0 === g ? Ea : 0 > g ? Ha : Ia, c = g != this.pY, d = Math.abs(a.deltaY))), a.direction = e, c && d > b.threshold && e & b.direction;
    }, attrTest: function attrTest(a) {
      return Y.prototype.attrTest.call(this, a) && (this.state & hb || !(this.state & hb) && this.directionTest(a));
    }, emit: function emit(a) {
      this.pX = a.deltaX, this.pY = a.deltaY;var b = W(a.direction);b && (a.additionalEvent = this.options.event + b), this._super.emit.call(this, a);
    } }), i($, Y, { defaults: { event: "pinch", threshold: 0, pointers: 2 }, getTouchAction: function getTouchAction() {
      return [db];
    }, attrTest: function attrTest(a) {
      return this._super.attrTest.call(this, a) && (Math.abs(a.scale - 1) > this.options.threshold || this.state & hb);
    }, emit: function emit(a) {
      if (1 !== a.scale) {
        var b = a.scale < 1 ? "in" : "out";a.additionalEvent = this.options.event + b;
      }this._super.emit.call(this, a);
    } }), i(_, U, { defaults: { event: "press", pointers: 1, time: 251, threshold: 9 }, getTouchAction: function getTouchAction() {
      return [bb];
    }, process: function process(a) {
      var b = this.options,
          c = a.pointers.length === b.pointers,
          d = a.distance < b.threshold,
          f = a.deltaTime > b.time;if (this._input = a, !d || !c || a.eventType & (Ca | Da) && !f) this.reset();else if (a.eventType & Aa) this.reset(), this._timer = e(function () {
        this.state = kb, this.tryEmit();
      }, b.time, this);else if (a.eventType & Ca) return kb;return mb;
    }, reset: function reset() {
      clearTimeout(this._timer);
    }, emit: function emit(a) {
      this.state === kb && (a && a.eventType & Ca ? this.manager.emit(this.options.event + "up", a) : (this._input.timeStamp = na(), this.manager.emit(this.options.event, this._input)));
    } }), i(aa, Y, { defaults: { event: "rotate", threshold: 0, pointers: 2 }, getTouchAction: function getTouchAction() {
      return [db];
    }, attrTest: function attrTest(a) {
      return this._super.attrTest.call(this, a) && (Math.abs(a.rotation) > this.options.threshold || this.state & hb);
    } }), i(ba, Y, { defaults: { event: "swipe", threshold: 10, velocity: .3, direction: Ja | Ka, pointers: 1 }, getTouchAction: function getTouchAction() {
      return Z.prototype.getTouchAction.call(this);
    }, attrTest: function attrTest(a) {
      var b,
          c = this.options.direction;return c & (Ja | Ka) ? b = a.overallVelocity : c & Ja ? b = a.overallVelocityX : c & Ka && (b = a.overallVelocityY), this._super.attrTest.call(this, a) && c & a.offsetDirection && a.distance > this.options.threshold && a.maxPointers == this.options.pointers && ma(b) > this.options.velocity && a.eventType & Ca;
    }, emit: function emit(a) {
      var b = W(a.offsetDirection);b && this.manager.emit(this.options.event + b, a), this.manager.emit(this.options.event, a);
    } }), i(ca, U, { defaults: { event: "tap", pointers: 1, taps: 1, interval: 300, time: 250, threshold: 9, posThreshold: 10 }, getTouchAction: function getTouchAction() {
      return [cb];
    }, process: function process(a) {
      var b = this.options,
          c = a.pointers.length === b.pointers,
          d = a.distance < b.threshold,
          f = a.deltaTime < b.time;if (this.reset(), a.eventType & Aa && 0 === this.count) return this.failTimeout();if (d && f && c) {
        if (a.eventType != Ca) return this.failTimeout();var g = this.pTime ? a.timeStamp - this.pTime < b.interval : !0,
            h = !this.pCenter || H(this.pCenter, a.center) < b.posThreshold;this.pTime = a.timeStamp, this.pCenter = a.center, h && g ? this.count += 1 : this.count = 1, this._input = a;var i = this.count % b.taps;if (0 === i) return this.hasRequireFailures() ? (this._timer = e(function () {
          this.state = kb, this.tryEmit();
        }, b.interval, this), hb) : kb;
      }return mb;
    }, failTimeout: function failTimeout() {
      return this._timer = e(function () {
        this.state = mb;
      }, this.options.interval, this), mb;
    }, reset: function reset() {
      clearTimeout(this._timer);
    }, emit: function emit() {
      this.state == kb && (this._input.tapCount = this.count, this.manager.emit(this.options.event, this._input));
    } }), da.VERSION = "2.0.6", da.defaults = { domEvents: !1, touchAction: ab, enable: !0, inputTarget: null, inputClass: null, preset: [[aa, { enable: !1 }], [$, { enable: !1 }, ["rotate"]], [ba, { direction: Ja }], [Z, { direction: Ja }, ["swipe"]], [ca], [ca, { event: "doubletap", taps: 2 }, ["tap"]], [_]], cssProps: { userSelect: "none", touchSelect: "none", touchCallout: "none", contentZooming: "none", userDrag: "none", tapHighlightColor: "rgba(0,0,0,0)" } };var nb = 1,
      ob = 2;ea.prototype = { set: function set(a) {
      return ha(this.options, a), a.touchAction && this.touchAction.update(), a.inputTarget && (this.input.destroy(), this.input.target = a.inputTarget, this.input.init()), this;
    }, stop: function stop(a) {
      this.session.stopped = a ? ob : nb;
    }, recognize: function recognize(a) {
      var b = this.session;if (!b.stopped) {
        this.touchAction.preventDefaults(a);var c,
            d = this.recognizers,
            e = b.curRecognizer;(!e || e && e.state & kb) && (e = b.curRecognizer = null);for (var f = 0; f < d.length;) {
          c = d[f], b.stopped === ob || e && c != e && !c.canRecognizeWith(e) ? c.reset() : c.recognize(a), !e && c.state & (hb | ib | jb) && (e = b.curRecognizer = c), f++;
        }
      }
    }, get: function get(a) {
      if (a instanceof U) return a;for (var b = this.recognizers, c = 0; c < b.length; c++) {
        if (b[c].options.event == a) return b[c];
      }return null;
    }, add: function add(a) {
      if (f(a, "add", this)) return this;var b = this.get(a.options.event);return b && this.remove(b), this.recognizers.push(a), a.manager = this, this.touchAction.update(), a;
    }, remove: function remove(a) {
      if (f(a, "remove", this)) return this;if (a = this.get(a)) {
        var b = this.recognizers,
            c = r(b, a);-1 !== c && (b.splice(c, 1), this.touchAction.update());
      }return this;
    }, on: function on(a, b) {
      var c = this.handlers;return g(q(a), function (a) {
        c[a] = c[a] || [], c[a].push(b);
      }), this;
    }, off: function off(a, b) {
      var c = this.handlers;return g(q(a), function (a) {
        b ? c[a] && c[a].splice(r(c[a], b), 1) : delete c[a];
      }), this;
    }, emit: function emit(a, b) {
      this.options.domEvents && ga(a, b);var c = this.handlers[a] && this.handlers[a].slice();if (c && c.length) {
        b.type = a, b.preventDefault = function () {
          b.srcEvent.preventDefault();
        };for (var d = 0; d < c.length;) {
          c[d](b), d++;
        }
      }
    }, destroy: function destroy() {
      this.element && fa(this, !1), this.handlers = {}, this.session = {}, this.input.destroy(), this.element = null;
    } }, ha(da, { INPUT_START: Aa, INPUT_MOVE: Ba, INPUT_END: Ca, INPUT_CANCEL: Da, STATE_POSSIBLE: gb, STATE_BEGAN: hb, STATE_CHANGED: ib, STATE_ENDED: jb, STATE_RECOGNIZED: kb, STATE_CANCELLED: lb, STATE_FAILED: mb, DIRECTION_NONE: Ea, DIRECTION_LEFT: Fa, DIRECTION_RIGHT: Ga, DIRECTION_UP: Ha, DIRECTION_DOWN: Ia, DIRECTION_HORIZONTAL: Ja, DIRECTION_VERTICAL: Ka, DIRECTION_ALL: La, Manager: ea, Input: x, TouchAction: S, TouchInput: P, MouseInput: L, PointerEventInput: M, TouchMouseInput: R, SingleTouchInput: N, Recognizer: U, AttrRecognizer: Y, Tap: ca, Pan: Z, Swipe: ba, Pinch: $, Rotate: aa, Press: _, on: m, off: n, each: g, merge: pa, extend: oa, assign: ha, inherit: i, bindFn: j, prefixed: u });var pb = "undefined" != typeof a ? a : "undefined" != typeof self ? self : {};pb.Hammer = da,  true ? !(__WEBPACK_AMD_DEFINE_RESULT__ = (function () {
    return da;
  }).call(exports, __webpack_require__, exports, module),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__)) : "undefined" != typeof module && module.exports ? module.exports = da : a[c] = da;
}(window, document, "Hammer");
//# sourceMappingURL=hammer.min.map

/***/ }),

/***/ 372:
/***/ (function(module, exports, __webpack_require__) {

"use strict";
var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;

var _typeof2 = __webpack_require__(234);

var _typeof3 = _interopRequireDefault(_typeof2);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

/*!
 * jQuery Mousewheel 3.1.13
 *
 * Copyright jQuery Foundation and other contributors
 * Released under the MIT license
 * http://jquery.org/license
 */

(function (factory) {
    if (true) {
        // AMD. Register as an anonymous module.
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(21)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else if ((typeof exports === 'undefined' ? 'undefined' : (0, _typeof3.default)(exports)) === 'object') {
        // Node/CommonJS style for Browserify
        module.exports = factory;
    } else {
        // Browser globals
        factory(jQuery);
    }
})(function ($) {

    var toFix = ['wheel', 'mousewheel', 'DOMMouseScroll', 'MozMousePixelScroll'],
        toBind = 'onwheel' in document || document.documentMode >= 9 ? ['wheel'] : ['mousewheel', 'DomMouseScroll', 'MozMousePixelScroll'],
        slice = Array.prototype.slice,
        nullLowestDeltaTimeout,
        lowestDelta;

    if ($.event.fixHooks) {
        for (var i = toFix.length; i;) {
            $.event.fixHooks[toFix[--i]] = $.event.mouseHooks;
        }
    }

    var special = $.event.special.mousewheel = {
        version: '3.1.12',

        setup: function setup() {
            if (this.addEventListener) {
                for (var i = toBind.length; i;) {
                    this.addEventListener(toBind[--i], handler, false);
                }
            } else {
                this.onmousewheel = handler;
            }
            // Store the line height and page height for this particular element
            $.data(this, 'mousewheel-line-height', special.getLineHeight(this));
            $.data(this, 'mousewheel-page-height', special.getPageHeight(this));
        },

        teardown: function teardown() {
            if (this.removeEventListener) {
                for (var i = toBind.length; i;) {
                    this.removeEventListener(toBind[--i], handler, false);
                }
            } else {
                this.onmousewheel = null;
            }
            // Clean up the data we added to the element
            $.removeData(this, 'mousewheel-line-height');
            $.removeData(this, 'mousewheel-page-height');
        },

        getLineHeight: function getLineHeight(elem) {
            var $elem = $(elem),
                $parent = $elem['offsetParent' in $.fn ? 'offsetParent' : 'parent']();
            if (!$parent.length) {
                $parent = $('body');
            }
            return parseInt($parent.css('fontSize'), 10) || parseInt($elem.css('fontSize'), 10) || 16;
        },

        getPageHeight: function getPageHeight(elem) {
            return $(elem).height();
        },

        settings: {
            adjustOldDeltas: true, // see shouldAdjustOldDeltas() below
            normalizeOffset: true // calls getBoundingClientRect for each event
        }
    };

    $.fn.extend({
        mousewheel: function mousewheel(fn) {
            return fn ? this.bind('mousewheel', fn) : this.trigger('mousewheel');
        },

        unmousewheel: function unmousewheel(fn) {
            return this.unbind('mousewheel', fn);
        }
    });

    function handler(event) {
        var orgEvent = event || window.event,
            args = slice.call(arguments, 1),
            delta = 0,
            deltaX = 0,
            deltaY = 0,
            absDelta = 0,
            offsetX = 0,
            offsetY = 0;
        event = $.event.fix(orgEvent);
        event.type = 'mousewheel';

        // Old school scrollwheel delta
        if ('detail' in orgEvent) {
            deltaY = orgEvent.detail * -1;
        }
        if ('wheelDelta' in orgEvent) {
            deltaY = orgEvent.wheelDelta;
        }
        if ('wheelDeltaY' in orgEvent) {
            deltaY = orgEvent.wheelDeltaY;
        }
        if ('wheelDeltaX' in orgEvent) {
            deltaX = orgEvent.wheelDeltaX * -1;
        }

        // Firefox < 17 horizontal scrolling related to DOMMouseScroll event
        if ('axis' in orgEvent && orgEvent.axis === orgEvent.HORIZONTAL_AXIS) {
            deltaX = deltaY * -1;
            deltaY = 0;
        }

        // Set delta to be deltaY or deltaX if deltaY is 0 for backwards compatabilitiy
        delta = deltaY === 0 ? deltaX : deltaY;

        // New school wheel delta (wheel event)
        if ('deltaY' in orgEvent) {
            deltaY = orgEvent.deltaY * -1;
            delta = deltaY;
        }
        if ('deltaX' in orgEvent) {
            deltaX = orgEvent.deltaX;
            if (deltaY === 0) {
                delta = deltaX * -1;
            }
        }

        // No change actually happened, no reason to go any further
        if (deltaY === 0 && deltaX === 0) {
            return;
        }

        // Need to convert lines and pages to pixels if we aren't already in pixels
        // There are three delta modes:
        //   * deltaMode 0 is by pixels, nothing to do
        //   * deltaMode 1 is by lines
        //   * deltaMode 2 is by pages
        if (orgEvent.deltaMode === 1) {
            var lineHeight = $.data(this, 'mousewheel-line-height');
            delta *= lineHeight;
            deltaY *= lineHeight;
            deltaX *= lineHeight;
        } else if (orgEvent.deltaMode === 2) {
            var pageHeight = $.data(this, 'mousewheel-page-height');
            delta *= pageHeight;
            deltaY *= pageHeight;
            deltaX *= pageHeight;
        }

        // Store lowest absolute delta to normalize the delta values
        absDelta = Math.max(Math.abs(deltaY), Math.abs(deltaX));

        if (!lowestDelta || absDelta < lowestDelta) {
            lowestDelta = absDelta;

            // Adjust older deltas if necessary
            if (shouldAdjustOldDeltas(orgEvent, absDelta)) {
                lowestDelta /= 40;
            }
        }

        // Adjust older deltas if necessary
        if (shouldAdjustOldDeltas(orgEvent, absDelta)) {
            // Divide all the things by 40!
            delta /= 40;
            deltaX /= 40;
            deltaY /= 40;
        }

        // Get a whole, normalized value for the deltas
        delta = Math[delta >= 1 ? 'floor' : 'ceil'](delta / lowestDelta);
        deltaX = Math[deltaX >= 1 ? 'floor' : 'ceil'](deltaX / lowestDelta);
        deltaY = Math[deltaY >= 1 ? 'floor' : 'ceil'](deltaY / lowestDelta);

        // Normalise offsetX and offsetY properties
        if (special.settings.normalizeOffset && this.getBoundingClientRect) {
            var boundingRect = this.getBoundingClientRect();
            offsetX = event.clientX - boundingRect.left;
            offsetY = event.clientY - boundingRect.top;
        }

        // Add information to the event object
        event.deltaX = deltaX;
        event.deltaY = deltaY;
        event.deltaFactor = lowestDelta;
        event.offsetX = offsetX;
        event.offsetY = offsetY;
        // Go ahead and set deltaMode to 0 since we converted to pixels
        // Although this is a little odd since we overwrite the deltaX/Y
        // properties with normalized deltas.
        event.deltaMode = 0;

        // Add event and delta to the front of the arguments
        args.unshift(event, delta, deltaX, deltaY);

        // Clearout lowestDelta after sometime to better
        // handle multiple device types that give different
        // a different lowestDelta
        // Ex: trackpad = 3 and mouse wheel = 120
        if (nullLowestDeltaTimeout) {
            clearTimeout(nullLowestDeltaTimeout);
        }
        nullLowestDeltaTimeout = setTimeout(nullLowestDelta, 200);

        return ($.event.dispatch || $.event.handle).apply(this, args);
    }

    function nullLowestDelta() {
        lowestDelta = null;
    }

    function shouldAdjustOldDeltas(orgEvent, absDelta) {
        // If this is an older event and the delta is divisable by 120,
        // then we are assuming that the browser is treating this as an
        // older mouse wheel event and that we should divide the deltas
        // by 40 to try and get a more usable deltaFactor.
        // Side note, this actually impacts the reported scroll distance
        // in older browsers and can cause scrolling to be slower than native.
        // Turn this off by setting $.event.special.mousewheel.settings.adjustOldDeltas to false.
        return special.settings.adjustOldDeltas && orgEvent.type === 'mousewheel' && absDelta % 120 === 0;
    }
});

/***/ }),

/***/ 373:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


/* Landing page scripts */
$(document).ready(function () {
	$('.usage').click(function (e) {
		e.preventDefault();
		$('.editor-window').slideToggle(200);
	});

	$(document).on('mousemove', '.mapplic-layer', function (e) {
		var map = $('.mapplic-map'),
		    x = (e.pageX - map.offset().left) / map.width(),
		    y = (e.pageY - map.offset().top) / map.height();
		$('.mapplic-coordinates-x').text(parseFloat(x).toFixed(4));
		$('.mapplic-coordinates-y').text(parseFloat(y).toFixed(4));
	});

	$('.editor-window .window-mockup').click(function () {
		$('.editor-window').slideUp(200);
	});
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


/***/ }),

/***/ 63:
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

/***/ }),

/***/ 64:
/***/ (function(module, exports) {

var hasOwnProperty = {}.hasOwnProperty;
module.exports = function (it, key) {
  return hasOwnProperty.call(it, key);
};


/***/ }),

/***/ 65:
/***/ (function(module, exports, __webpack_require__) {

// to indexed object, toObject with fallback for non-array-like ES3 strings
var IObject = __webpack_require__(247);
var defined = __webpack_require__(203);
module.exports = function (it) {
  return IObject(defined(it));
};


/***/ })

});