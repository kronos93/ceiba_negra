webpackJsonp([15],{

/***/ 33:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


var _util = __webpack_require__(42);

/* import '../configs/datatables'; */
var huertos_table = $('#comisiones-per-lider-table').DataTable({
    "ajax": (0, _util.base_url)() + 'ajax/get_comision_per_lider',
    "columns": [//Atributos para la tabla
    {
        "data": "nombre"
    }, {
        "data": "comision",
        "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
        "type": "num-fmt"
    }, {
        "data": "comisiones_pendientes",
        "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
        "type": "num-fmt"
    }, {
        "data": "comisiones_pagadas",
        "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
        "type": "num-fmt"
    }]
});

/***/ }),

/***/ 42:
/***/ (function(module, exports, __webpack_require__) {

"use strict";


module.exports = {

    base_url: function base_url() {
        if (window.location.hostname === 'localhost' || window.location.hostname === '192.168.0.8') {
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

/***/ })

});