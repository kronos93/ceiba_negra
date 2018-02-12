webpackJsonp([7],{

/***/ 189:
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

/***/ 191:
/***/ (function(module, exports, __webpack_require__) {

var __WEBPACK_AMD_DEFINE_FACTORY__, __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/**
 * jquery.mask.js
 * @version: v1.14.13
 * @author: Igor Escobar
 *
 * Created by Igor Escobar on 2012-03-10. Please report any bug at github.com/igorescobar/jQuery-Mask-Plugin
 *
 * Copyright (c) 2012 Igor Escobar http://igorescobar.com
 *
 * The MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

/* jshint laxbreak: true */
/* jshint maxcomplexity:17 */
/* global define */

// UMD (Universal Module Definition) patterns for JavaScript modules that work everywhere.
// https://github.com/umdjs/umd/blob/master/templates/jqueryPlugin.js
(function (factory, jQuery, Zepto) {

    if (true) {
        !(__WEBPACK_AMD_DEFINE_ARRAY__ = [__webpack_require__(22)], __WEBPACK_AMD_DEFINE_FACTORY__ = (factory),
				__WEBPACK_AMD_DEFINE_RESULT__ = (typeof __WEBPACK_AMD_DEFINE_FACTORY__ === 'function' ?
				(__WEBPACK_AMD_DEFINE_FACTORY__.apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__)) : __WEBPACK_AMD_DEFINE_FACTORY__),
				__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
    } else if (typeof exports === 'object') {
        module.exports = factory(require('jquery'));
    } else {
        factory(jQuery || Zepto);
    }

}(function ($) {
    'use strict';

    var Mask = function (el, mask, options) {

        var p = {
            invalid: [],
            getCaret: function () {
                try {
                    var sel,
                        pos = 0,
                        ctrl = el.get(0),
                        dSel = document.selection,
                        cSelStart = ctrl.selectionStart;

                    // IE Support
                    if (dSel && navigator.appVersion.indexOf('MSIE 10') === -1) {
                        sel = dSel.createRange();
                        sel.moveStart('character', -p.val().length);
                        pos = sel.text.length;
                    }
                    // Firefox support
                    else if (cSelStart || cSelStart === '0') {
                        pos = cSelStart;
                    }

                    return pos;
                } catch (e) {}
            },
            setCaret: function(pos) {
                try {
                    if (el.is(':focus')) {
                        var range, ctrl = el.get(0);

                        // Firefox, WebKit, etc..
                        if (ctrl.setSelectionRange) {
                            ctrl.setSelectionRange(pos, pos);
                        } else { // IE
                            range = ctrl.createTextRange();
                            range.collapse(true);
                            range.moveEnd('character', pos);
                            range.moveStart('character', pos);
                            range.select();
                        }
                    }
                } catch (e) {}
            },
            events: function() {
                el
                .on('keydown.mask', function(e) {
                    el.data('mask-keycode', e.keyCode || e.which);
                    el.data('mask-previus-value', el.val());
                    el.data('mask-previus-caret-pos', p.getCaret());
                    p.maskDigitPosMapOld = p.maskDigitPosMap;
                })
                .on($.jMaskGlobals.useInput ? 'input.mask' : 'keyup.mask', p.behaviour)
                .on('paste.mask drop.mask', function() {
                    setTimeout(function() {
                        el.keydown().keyup();
                    }, 100);
                })
                .on('change.mask', function(){
                    el.data('changed', true);
                })
                .on('blur.mask', function(){
                    if (oldValue !== p.val() && !el.data('changed')) {
                        el.trigger('change');
                    }
                    el.data('changed', false);
                })
                // it's very important that this callback remains in this position
                // otherwhise oldValue it's going to work buggy
                .on('blur.mask', function() {
                    oldValue = p.val();
                })
                // select all text on focus
                .on('focus.mask', function (e) {
                    if (options.selectOnFocus === true) {
                        $(e.target).select();
                    }
                })
                // clear the value if it not complete the mask
                .on('focusout.mask', function() {
                    if (options.clearIfNotMatch && !regexMask.test(p.val())) {
                       p.val('');
                   }
                });
            },
            getRegexMask: function() {
                var maskChunks = [], translation, pattern, optional, recursive, oRecursive, r;

                for (var i = 0; i < mask.length; i++) {
                    translation = jMask.translation[mask.charAt(i)];

                    if (translation) {

                        pattern = translation.pattern.toString().replace(/.{1}$|^.{1}/g, '');
                        optional = translation.optional;
                        recursive = translation.recursive;

                        if (recursive) {
                            maskChunks.push(mask.charAt(i));
                            oRecursive = {digit: mask.charAt(i), pattern: pattern};
                        } else {
                            maskChunks.push(!optional && !recursive ? pattern : (pattern + '?'));
                        }

                    } else {
                        maskChunks.push(mask.charAt(i).replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'));
                    }
                }

                r = maskChunks.join('');

                if (oRecursive) {
                    r = r.replace(new RegExp('(' + oRecursive.digit + '(.*' + oRecursive.digit + ')?)'), '($1)?')
                         .replace(new RegExp(oRecursive.digit, 'g'), oRecursive.pattern);
                }

                return new RegExp(r);
            },
            destroyEvents: function() {
                el.off(['input', 'keydown', 'keyup', 'paste', 'drop', 'blur', 'focusout', ''].join('.mask '));
            },
            val: function(v) {
                var isInput = el.is('input'),
                    method = isInput ? 'val' : 'text',
                    r;

                if (arguments.length > 0) {
                    if (el[method]() !== v) {
                        el[method](v);
                    }
                    r = el;
                } else {
                    r = el[method]();
                }

                return r;
            },
            calculateCaretPosition: function() {
                var oldVal = el.data('mask-previus-value') || '',
                    newVal = p.getMasked(),
                    caretPosNew = p.getCaret();
                if (oldVal !== newVal) {
                    var caretPosOld = el.data('mask-previus-caret-pos') || 0,
                        newValL = newVal.length,
                        oldValL = oldVal.length,
                        maskDigitsBeforeCaret = 0,
                        maskDigitsAfterCaret = 0,
                        maskDigitsBeforeCaretAll = 0,
                        maskDigitsBeforeCaretAllOld = 0,
                        i = 0;

                    for (i = caretPosNew; i < newValL; i++) {
                        if (!p.maskDigitPosMap[i]) {
                            break;
                        }
                        maskDigitsAfterCaret++;
                    }

                    for (i = caretPosNew - 1; i >= 0; i--) {
                        if (!p.maskDigitPosMap[i]) {
                            break;
                        }
                        maskDigitsBeforeCaret++;
                    }

                    for (i = caretPosNew - 1; i >= 0; i--) {
                        if (p.maskDigitPosMap[i]) {
                            maskDigitsBeforeCaretAll++;
                        }
                    }

                    for (i = caretPosOld - 1; i >= 0; i--) {
                        if (p.maskDigitPosMapOld[i]) {
                            maskDigitsBeforeCaretAllOld++;
                        }
                    }

                    // if the cursor is at the end keep it there
                    if (caretPosNew > oldValL) {
                      caretPosNew = newValL * 10;
                    } else if (caretPosOld >= caretPosNew && caretPosOld !== oldValL) {
                        if (!p.maskDigitPosMapOld[caretPosNew])  {
                          var caretPos = caretPosNew;
                          caretPosNew -= maskDigitsBeforeCaretAllOld - maskDigitsBeforeCaretAll;
                          caretPosNew -= maskDigitsBeforeCaret;
                          if (p.maskDigitPosMap[caretPosNew])  {
                            caretPosNew = caretPos;
                          }
                        }
                    }
                    else if (caretPosNew > caretPosOld) {
                        caretPosNew += maskDigitsBeforeCaretAll - maskDigitsBeforeCaretAllOld;
                        caretPosNew += maskDigitsAfterCaret;
                    }
                }
                return caretPosNew;
            },
            behaviour: function(e) {
                e = e || window.event;
                p.invalid = [];

                var keyCode = el.data('mask-keycode');

                if ($.inArray(keyCode, jMask.byPassKeys) === -1) {
                    var newVal   = p.getMasked(),
                        caretPos = p.getCaret();

                    // this is a compensation to devices/browsers that don't compensate
                    // caret positioning the right way
                    setTimeout(function() {
                      p.setCaret(p.calculateCaretPosition());
                    }, 10);

                    p.val(newVal);
                    p.setCaret(caretPos);
                    return p.callbacks(e);
                }
            },
            getMasked: function(skipMaskChars, val) {
                var buf = [],
                    value = val === undefined ? p.val() : val + '',
                    m = 0, maskLen = mask.length,
                    v = 0, valLen = value.length,
                    offset = 1, addMethod = 'push',
                    resetPos = -1,
                    maskDigitCount = 0,
                    maskDigitPosArr = [],
                    lastMaskChar,
                    check;

                if (options.reverse) {
                    addMethod = 'unshift';
                    offset = -1;
                    lastMaskChar = 0;
                    m = maskLen - 1;
                    v = valLen - 1;
                    check = function () {
                        return m > -1 && v > -1;
                    };
                } else {
                    lastMaskChar = maskLen - 1;
                    check = function () {
                        return m < maskLen && v < valLen;
                    };
                }

                var lastUntranslatedMaskChar;
                while (check()) {
                    var maskDigit = mask.charAt(m),
                        valDigit = value.charAt(v),
                        translation = jMask.translation[maskDigit];

                    if (translation) {
                        if (valDigit.match(translation.pattern)) {
                            buf[addMethod](valDigit);
                             if (translation.recursive) {
                                if (resetPos === -1) {
                                    resetPos = m;
                                } else if (m === lastMaskChar && m !== resetPos) {
                                    m = resetPos - offset;
                                }

                                if (lastMaskChar === resetPos) {
                                    m -= offset;
                                }
                            }
                            m += offset;
                        } else if (valDigit === lastUntranslatedMaskChar) {
                            // matched the last untranslated (raw) mask character that we encountered
                            // likely an insert offset the mask character from the last entry; fall
                            // through and only increment v
                            maskDigitCount--;
                            lastUntranslatedMaskChar = undefined;
                        } else if (translation.optional) {
                            m += offset;
                            v -= offset;
                        } else if (translation.fallback) {
                            buf[addMethod](translation.fallback);
                            m += offset;
                            v -= offset;
                        } else {
                          p.invalid.push({p: v, v: valDigit, e: translation.pattern});
                        }
                        v += offset;
                    } else {
                        if (!skipMaskChars) {
                            buf[addMethod](maskDigit);
                        }

                        if (valDigit === maskDigit) {
                            maskDigitPosArr.push(v);
                            v += offset;
                        } else {
                            lastUntranslatedMaskChar = maskDigit;
                            maskDigitPosArr.push(v + maskDigitCount);
                            maskDigitCount++;
                        }

                        m += offset;
                    }
                }

                var lastMaskCharDigit = mask.charAt(lastMaskChar);
                if (maskLen === valLen + 1 && !jMask.translation[lastMaskCharDigit]) {
                    buf.push(lastMaskCharDigit);
                }

                var newVal = buf.join('');
                p.mapMaskdigitPositions(newVal, maskDigitPosArr, valLen);
                return newVal;
            },
            mapMaskdigitPositions: function(newVal, maskDigitPosArr, valLen) {
              var maskDiff = options.reverse ? newVal.length - valLen : 0;
              p.maskDigitPosMap = {};
              for (var i = 0; i < maskDigitPosArr.length; i++) {
                p.maskDigitPosMap[maskDigitPosArr[i] + maskDiff] = 1;
              }
            },
            callbacks: function (e) {
                var val = p.val(),
                    changed = val !== oldValue,
                    defaultArgs = [val, e, el, options],
                    callback = function(name, criteria, args) {
                        if (typeof options[name] === 'function' && criteria) {
                            options[name].apply(this, args);
                        }
                    };

                callback('onChange', changed === true, defaultArgs);
                callback('onKeyPress', changed === true, defaultArgs);
                callback('onComplete', val.length === mask.length, defaultArgs);
                callback('onInvalid', p.invalid.length > 0, [val, e, el, p.invalid, options]);
            }
        };

        el = $(el);
        var jMask = this, oldValue = p.val(), regexMask;

        mask = typeof mask === 'function' ? mask(p.val(), undefined, el,  options) : mask;

        // public methods
        jMask.mask = mask;
        jMask.options = options;
        jMask.remove = function() {
            var caret = p.getCaret();
            p.destroyEvents();
            p.val(jMask.getCleanVal());
            p.setCaret(caret);
            return el;
        };

        // get value without mask
        jMask.getCleanVal = function() {
           return p.getMasked(true);
        };

        // get masked value without the value being in the input or element
        jMask.getMaskedVal = function(val) {
           return p.getMasked(false, val);
        };

       jMask.init = function(onlyMask) {
            onlyMask = onlyMask || false;
            options = options || {};

            jMask.clearIfNotMatch  = $.jMaskGlobals.clearIfNotMatch;
            jMask.byPassKeys       = $.jMaskGlobals.byPassKeys;
            jMask.translation      = $.extend({}, $.jMaskGlobals.translation, options.translation);

            jMask = $.extend(true, {}, jMask, options);

            regexMask = p.getRegexMask();

            if (onlyMask) {
                p.events();
                p.val(p.getMasked());
            } else {
                if (options.placeholder) {
                    el.attr('placeholder' , options.placeholder);
                }

                // this is necessary, otherwise if the user submit the form
                // and then press the "back" button, the autocomplete will erase
                // the data. Works fine on IE9+, FF, Opera, Safari.
                if (el.data('mask')) {
                  el.attr('autocomplete', 'off');
                }

                // detect if is necessary let the user type freely.
                // for is a lot faster than forEach.
                for (var i = 0, maxlength = true; i < mask.length; i++) {
                    var translation = jMask.translation[mask.charAt(i)];
                    if (translation && translation.recursive) {
                        maxlength = false;
                        break;
                    }
                }

                if (maxlength) {
                    el.attr('maxlength', mask.length);
                }

                p.destroyEvents();
                p.events();

                var caret = p.getCaret();
                p.val(p.getMasked());
                p.setCaret(caret);
            }
        };

        jMask.init(!el.is('input'));
    };

    $.maskWatchers = {};
    var HTMLAttributes = function () {
        var input = $(this),
            options = {},
            prefix = 'data-mask-',
            mask = input.attr('data-mask');

        if (input.attr(prefix + 'reverse')) {
            options.reverse = true;
        }

        if (input.attr(prefix + 'clearifnotmatch')) {
            options.clearIfNotMatch = true;
        }

        if (input.attr(prefix + 'selectonfocus') === 'true') {
           options.selectOnFocus = true;
        }

        if (notSameMaskObject(input, mask, options)) {
            return input.data('mask', new Mask(this, mask, options));
        }
    },
    notSameMaskObject = function(field, mask, options) {
        options = options || {};
        var maskObject = $(field).data('mask'),
            stringify = JSON.stringify,
            value = $(field).val() || $(field).text();
        try {
            if (typeof mask === 'function') {
                mask = mask(value);
            }
            return typeof maskObject !== 'object' || stringify(maskObject.options) !== stringify(options) || maskObject.mask !== mask;
        } catch (e) {}
    },
    eventSupported = function(eventName) {
        var el = document.createElement('div'), isSupported;

        eventName = 'on' + eventName;
        isSupported = (eventName in el);

        if ( !isSupported ) {
            el.setAttribute(eventName, 'return;');
            isSupported = typeof el[eventName] === 'function';
        }
        el = null;

        return isSupported;
    };

    $.fn.mask = function(mask, options) {
        options = options || {};
        var selector = this.selector,
            globals = $.jMaskGlobals,
            interval = globals.watchInterval,
            watchInputs = options.watchInputs || globals.watchInputs,
            maskFunction = function() {
                if (notSameMaskObject(this, mask, options)) {
                    return $(this).data('mask', new Mask(this, mask, options));
                }
            };

        $(this).each(maskFunction);

        if (selector && selector !== '' && watchInputs) {
            clearInterval($.maskWatchers[selector]);
            $.maskWatchers[selector] = setInterval(function(){
                $(document).find(selector).each(maskFunction);
            }, interval);
        }
        return this;
    };

    $.fn.masked = function(val) {
        return this.data('mask').getMaskedVal(val);
    };

    $.fn.unmask = function() {
        clearInterval($.maskWatchers[this.selector]);
        delete $.maskWatchers[this.selector];
        return this.each(function() {
            var dataMask = $(this).data('mask');
            if (dataMask) {
                dataMask.remove().removeData('mask');
            }
        });
    };

    $.fn.cleanVal = function() {
        return this.data('mask').getCleanVal();
    };

    $.applyDataMask = function(selector) {
        selector = selector || $.jMaskGlobals.maskElements;
        var $selector = (selector instanceof $) ? selector : $(selector);
        $selector.filter($.jMaskGlobals.dataMaskAttr).each(HTMLAttributes);
    };

    var globals = {
        maskElements: 'input,td,span,div',
        dataMaskAttr: '*[data-mask]',
        dataMask: true,
        watchInterval: 300,
        watchInputs: true,
        // old versions of chrome dont work great with input event
        useInput: !/Chrome\/[2-4][0-9]|SamsungBrowser/.test(window.navigator.userAgent) && eventSupported('input'),
        watchDataMask: false,
        byPassKeys: [9, 16, 17, 18, 36, 37, 38, 39, 40, 91],
        translation: {
            '0': {pattern: /\d/},
            '9': {pattern: /\d/, optional: true},
            '#': {pattern: /\d/, recursive: true},
            'A': {pattern: /[a-zA-Z0-9]/},
            'S': {pattern: /[a-zA-Z]/}
        }
    };

    $.jMaskGlobals = $.jMaskGlobals || {};
    globals = $.jMaskGlobals = $.extend(true, {}, globals, $.jMaskGlobals);

    // looking for inputs with data-mask attribute
    if (globals.dataMask) {
        $.applyDataMask();
    }

    setInterval(function() {
        if ($.jMaskGlobals.watchDataMask) {
            $.applyDataMask();
        }
    }, globals.watchInterval);
}, window.jQuery, window.Zepto));


/***/ }),

/***/ 24:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


__webpack_require__(191);

var _components = __webpack_require__(65);

var _util = __webpack_require__(44);

var _GenericFrm = __webpack_require__(189);

var _GenericFrm2 = _interopRequireDefault(_GenericFrm);

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }

$(function () {
    //USUARIOS
    var users_table = $('#users-table').DataTable({
        "initComplete": function initComplete(settings, json) {
            (0, _components.phone)();
        },
        "columns": [//Atributos para la tabla
        {
            "data": "id",
            "type": "num"
        }, { "data": "name" }, { "data": "email" }, { "data": "phone" }, { "data": "groups" }, { "data": "btn_activar_desactivar" }, { "data": "btn_editar" }],
        columnDefs: [//
        {
            //Quitar ordenamiento para estas columnas
            "sortable": false,
            "targets": [-1, 2, 3, 4, 5]
        }, {
            //Quitar busqueda para esta columna
            "targets": [],
            "searchable": false
        }],
        "order": [[1, "asc"]]
    });
    $('#userModal').on('shown.bs.modal', function (e) {
        console.log($("#frm-ion-user"));
        //Ocultar mensajes de la caja AJAX
        _util.ajax_msg.hidden();
        var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable
        var btnType = button.data('btnType');
        var config = {
            'frm': '#frm-ion-user',
            'urls': { 'edit': 'ajax/update_ion_user', 'add': 'ajax/add_ion_user' },
            'msgs': { 'edit': 'Usuario actualizado correctamente.', 'add': 'Usuario agregado correctamente.' },
            //'autoNumeric': ['superficie','precio_x_m2','precio'], //A que campos quitarle las comas y signos.
            //'readonly': { 'inputs': '#id_manzana' }, //Que campos son de lectura para agregar y quitar
            //'append': ["id_huerto"], //Que campo anexar de dtRow al data a enviar por AJAX
            'btn': button, //Boton que disparó el evento de abrir modal
            'dtTable': users_table //Data table que se parseará
        };
        var genericFrm = new _GenericFrm2.default(config);
        if (btnType) {
            genericFrm.edit = function () {
                this.url = this.urls.edit;
                this.msg = this.msgs.edit;
                this.fnOnDone = this.ajaxEditDone;
            };
            genericFrm[btnType]();
        }
    }).on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
    });
});

/***/ }),

/***/ 40:
/***/ (function(module, exports, __webpack_require__) {

// Thank's IE8 for his funny defineProperty
module.exports = !__webpack_require__(45)(function () {
  return Object.defineProperty({}, 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ 41:
/***/ (function(module, exports) {

module.exports = function (it) {
  return typeof it === 'object' ? it !== null : typeof it === 'function';
};


/***/ }),

/***/ 42:
/***/ (function(module, exports) {

// https://github.com/zloirock/core-js/issues/86#issuecomment-115759028
var global = module.exports = typeof window != 'undefined' && window.Math == Math
  ? window : typeof self != 'undefined' && self.Math == Math ? self
  // eslint-disable-next-line no-new-func
  : Function('return this')();
if (typeof __g == 'number') __g = global; // eslint-disable-line no-undef


/***/ }),

/***/ 43:
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

/***/ 44:
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

/***/ 45:
/***/ (function(module, exports) {

module.exports = function (exec) {
  try {
    return !!exec();
  } catch (e) {
    return true;
  }
};


/***/ }),

/***/ 46:
/***/ (function(module, exports) {

var core = module.exports = { version: '2.5.0' };
if (typeof __e == 'number') __e = core; // eslint-disable-line no-undef


/***/ }),

/***/ 47:
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

/***/ 49:
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(41);
module.exports = function (it) {
  if (!isObject(it)) throw TypeError(it + ' is not an object!');
  return it;
};


/***/ }),

/***/ 50:
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

/***/ 51:
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

/***/ 52:
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

/***/ 55:
/***/ (function(module, exports, __webpack_require__) {

module.exports = !__webpack_require__(40) && !__webpack_require__(45)(function () {
  return Object.defineProperty(__webpack_require__(56)('div'), 'a', { get: function () { return 7; } }).a != 7;
});


/***/ }),

/***/ 56:
/***/ (function(module, exports, __webpack_require__) {

var isObject = __webpack_require__(41);
var document = __webpack_require__(42).document;
// typeof document.createElement is 'object' in old IE
var is = isObject(document) && isObject(document.createElement);
module.exports = function (it) {
  return is ? document.createElement(it) : {};
};


/***/ }),

/***/ 58:
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

/***/ 59:
/***/ (function(module, exports) {

module.exports = function (it) {
  if (typeof it != 'function') throw TypeError(it + ' is not a function!');
  return it;
};


/***/ }),

/***/ 60:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


exports.__esModule = true;

exports.default = function (instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
};

/***/ }),

/***/ 61:
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

/***/ 62:
/***/ (function(module, exports, __webpack_require__) {

module.exports = { "default": __webpack_require__(63), __esModule: true };

/***/ }),

/***/ 63:
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(64);
var $Object = __webpack_require__(46).Object;
module.exports = function defineProperty(it, key, desc) {
  return $Object.defineProperty(it, key, desc);
};


/***/ }),

/***/ 64:
/***/ (function(module, exports, __webpack_require__) {

var $export = __webpack_require__(52);
// 19.1.2.4 / 15.2.3.6 Object.defineProperty(O, P, Attributes)
$export($export.S + $export.F * !__webpack_require__(40), 'Object', { defineProperty: __webpack_require__(43).f });


/***/ }),

/***/ 65:
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

});