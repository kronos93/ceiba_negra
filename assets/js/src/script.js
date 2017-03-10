import $ from 'jquery';
import { base_url } from './utils/util';
import './config';

import './mapplic';
import './manzanas';
import './huertos';
import './opciones_ingreso';
import './usuarios';
moment.locale('es');

function format_numeric(action) {
    console.log("Dar formato de moneda y superficie");
    if ($('.superficie').length) {
        $('.superficie').autoNumeric(action, {
            aSign: ' m\u00B2',
            pSign: 's'
        }); //Averiguar más del plugin para evitar menores a 0
    }
    $(".currency").autoNumeric(action, {
        aSign: "$"
    });
}

///////////////////////////////////////////////////
function templateCart(response) {
    $("#shopCartSale")
        .find('span')
        .attr("data-venta", response.count);
    var template = document.getElementById('template-venta').innerHTML;
    var output = Mustache.render(template, response);
    document.getElementById("listaVenta").innerHTML = output;
    format_numeric('init');
    if ($('#precio').length && $('#enganche').length && $('#abono').length) {
        format_numeric('init');
        $('#precio').autoNumeric('set', response.total);
        $('#enganche').autoNumeric('set', response.enganche);
        $('#abono').autoNumeric('set', response.abono);

        ///////////////////////////////////////////////////////////////////////////
        var default_porcentaje_comision = 10;
        $('#porcentaje_comision').val(default_porcentaje_comision);
        $('#comision').autoNumeric('set', (default_porcentaje_comision / 100) * $('#precio').autoNumeric('get'));
        ////////////////////////////////////////////////////////////////////////////

    }
    //Validar cuando se vacie el carrito
    if (!response.count) {
        var uri = window.location.pathname;
        var uri_split = uri.split('/');
        var name_uri = uri_split[uri_split.length - 1];
        if (name_uri == 'venta') {
            window.location.href = base_url();
        }
    }
    $(".itemCartDelete").on('click', function(e) {
        $.ajax({
                url: base_url() + "ajax/delete_cart/",
                data: { rowid: $(this).val() },
                type: "post",
            })
            .done(function(response) {
                format_numeric('init');
                templateCart(response);
            })
            .fail(function(response) {

            });
    });

}

//Al cargar la página
$(document).ready(function() {
    //Carro
    ////////////////////////////////////////////////////////////////////////////
    $.get(base_url() + "ajax/add_cart", function(response) { templateCart(response) });
    $('#shopCartSale').on('click', function(e) {
        $(this).find('.my-dropdown').slideToggle('3500');
    });
    $('#shopCartSale').find('nav').on('click', function(e) {
        e.stopPropagation();
    });
    $(document).mouseup(function(e) {
        var container = $(".my-dropdown");

        if (!container.is(e.target) // if the target of the click isn't the container...
            &&
            container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.hide();
        }
    });
    //////////////////////////////
    tinymce.init({
        selector: '#contrato_html',
        mode: 'specifics_textareas',
        editor_selector: 'mceEditor',
        height: '600px',
        plugins: [
            'advlist autolink lists charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'nonbreaking save table directionality',
            'template paste textcolor colorpicker textpattern'
        ],
        toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        content_css: base_url() + '/assets/css/tinymce.css',
        setup: function(ed) {
            ed.on('init', function(args) {
                //console.log(this);
                // this ==  tinymce.activeEditor;
                //tinyMCE.activeEditor.dom.select('.fecha_init'); Trae datos por clase probar después

            });
        },
        init_instance_callback: function(editor) {
            editor.on('SetContent', function(e) {
                console.log("Asignando contenido dinamico");
                /*console.log(this.dom.select('.fecha_init'));
                console.log(tinymce.activeEditor.dom.select('.fecha_init'));*/
                var fechas = ['fecha_primer_pago', 'fecha_ultimo_pago', 'fecha_init_1', 'fecha_init_2', 'fecha_init_3', 'fecha_init_4', 'fecha_init_5'];
                for (var fecha in fechas) {
                    var fecha_tiny = this.dom.get(fechas[fecha]);
                    var fecha_val = $(fecha_tiny).html();
                    var fecha_moment = moment(fecha_val, 'DD-MM-YYYY');
                    this.dom.setHTML(fecha_tiny, fecha_moment.format("[el día ] dddd, DD [de] MMMM [del] [año] YYYY"));
                }

                var currencies = ['precio_1', 'precio_2', 'enganche', 'abono_1', 'abono_2', 'porcentaje'];
                for (var currency in currencies) {
                    var currency_tiny = this.dom.get(currencies[currency]);
                    var currency_val = $(currency_tiny).html();
                    if (currencies[currency] == 'porcentaje') {
                        var currency_format = NumeroALetras(currency_val).replace(/\b00\/100 MN\b/, '').replace(/\bPeso\b/, '').replace(/\bCON\b/g, 'PUNTO').replace(/\bPESOS\b/g, '').replace(/\bCENTAVOS\b/g, '').replace(/\s{2,}/g, " ");
                    } else {
                        var currency_format = NumeroALetras(currency_val).replace(/\s{2,}/g, " ");
                    }
                    this.dom.setHTML(currency_tiny, currency_format);
                }
            });
        }
    });
    ///////////////////////////////////////////////

    var form_venta = $("#frm-venta");
    var content = "";
    form_venta.validate({
        errorPlacement: function errorPlacement(error, element) { element.before(error); },
        lang: 'es'
    });
    form_venta.steps({
        headerTag: "h3",
        bodyTag: "div",
        transitionEffect: "slide",
        autoFocus: true,
        onInit: function() {
            $('#fecha_init').mask('00-00-0000');
            $("#fecha_init").datepicker({
                dateFormat: "dd-mm-yy"
            });
            $('#fecha_nacimiento').mask('00-00-0000');
            $("#fecha_nacimiento").datepicker({
                dateFormat: "dd-mm-yy"
            });
            $('#clientes_autocomplete').autocomplete({
                serviceUrl: base_url() + 'ajax/autocomplete_clientes',
                noCache: true,
                autoSelectFirst: true,
                onSelect: function(suggestion) {
                    console.log(suggestion);
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

                    }
                    var extra = {
                        "ciudad_expedicion": "Playa del Carmen",
                        "testigo_1": "XXXX XXXX XXXX",
                        "testigo_2": "XXXX XXXX XXXX",
                    };
                    for (var data in suggestion) {
                        if (suggestion[data] == "" || suggestion[data] == null || suggestion[data] == 0) {
                            suggestion[data] = base[data];
                        }
                        $('#' + data).val(suggestion[data]);
                    }
                    for (var data in extra) {
                        $('#' + data).val(extra[data]);
                    }
                    $('#id_cliente').val(suggestion.id_cliente);
                },
                onSearchError: function(query, jqXHR, textStatus, errorThrown) {
                    console.log("Ha ocurrido un error xhr.");
                },
                onInvalidateSelection: function() {
                    console.log("Ha ocurrido un error en la selección.");
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
                        "cp": "77777s",
                        "lugar_nacimiento": "Default",
                        "fecha_nacimiento": "01-01-1999",

                    }
                    var extra = {
                        "ciudad_expedicion": "Playa del Carmen",
                        "testigo_1": "XXXX XXXX XXXX",
                        "testigo_2": "XXXX XXXX XXXX",
                    };
                    for (var data in base) {
                        $('#' + data).val("");
                    }
                    for (var data in extra) {
                        $('#' + data).val("");
                    }
                    $('#id_cliente').val("");
                },
            });
            $('#lideres_autocomplete').autocomplete({
                serviceUrl: base_url() + 'ajax/autocomplete_lideres',
                noCache: true,
                autoSelectFirst: true,
                onSelect: function(suggestion) {
                    $("#id_lider").val(suggestion.id);
                },
                onSearchError: function(query, jqXHR, textStatus, errorThrown) {
                    console.log("Ha ocurrido un error.");
                }
            });
            $("#precio").on('change keyup', function() {
                var precio = parseFloat($(this).autoNumeric('get'));
                var porcentaje = parseFloat($('#porcentaje_comision').val());
                var enganche = parseFloat($('#enganche').autoNumeric('get'));
                $('#comision').autoNumeric('set', (porcentaje / 100) * precio);

                if (precio == enganche) {
                    console.log('iguales');
                    $('.select-periodos').slideUp(300);
                    $('#abono').autoNumeric('set', 0);
                } else if (precio < enganche) {
                    console.log('menor');
                    $('#enganche').autoNumeric('set', precio);
                    $('#abono').autoNumeric('set', 0);
                    $('.select-periodos').slideUp(300);
                } else {
                    console.log('default');
                    $('.select-periodos').slideDown(300);
                }
            });
            $("#enganche").on('change keyup', function() {
                var precio = parseFloat($('#precio').autoNumeric('get'));
                var enganche = parseFloat($(this).autoNumeric('get'));
                if (enganche === precio) {
                    $('.select-periodos').slideUp(300);
                    $('#abono').autoNumeric('set', 0);
                } else if (enganche > precio) {
                    $(this).autoNumeric('set', precio);
                    $('.select-periodos').slideUp(300);
                    $('#abono').autoNumeric('set', 0);
                } else {
                    $('.select-periodos').slideDown(300);
                }
            });
            $('#porcentaje_comision').on('change keyup', function() {
                var porcentaje = this.value;
                var precio = $('#precio').autoNumeric('get');
                $('#comision').autoNumeric('set', (porcentaje / 100) * precio);
            });
            $('#comision').on('change keyup', function() {
                var comision = $(this).autoNumeric('get');
                var precio = $('#precio').autoNumeric('get');
                $('#porcentaje_comision').val((100 * (comision / precio)).toFixed(2));
            });
            $("#tipo_historial").on('change', function() {
                var op = $(this).val();
                if (op == 'ini-mes' || op == 'quincena-mes' || op == '1-15') {
                    $('#empezar_pago').show();
                } else {
                    $('#empezar_pago').hide();
                }
            });
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            if (newIndex == 2) {
                tinymce.activeEditor.setContent("");

                var data = {
                    'first_name': '',
                    'last_name': '',
                    'email': '',
                    'phone': '',
                    'lugar_nacimiento': '',
                    'fecha_nacimiento': '',
                    'calle': '',
                    'no_ext': '',
                    'no_int': '',
                    'colonia': '',
                    'municipio': '',
                    'estado': '',
                    'ciudad': '',
                    'cp': '',
                    'testigo_1': '',
                    'testigo_2': '',
                    'ciudad_expedicion': '',
                    'tipo_historial': '',
                    'confirmyes': '',
                    'confirmno': '',
                    'id_lider': '',
                    'fecha_init': '',
                    'precio': 0,
                    'enganche': 0,
                    'abono': 0,
                    'porcentaje_penalizacion': 0,
                    'maximo_retrasos_permitidos': 0
                };
                var op = $('#tipo_historial').val();
                console.log(op);
                if (op == '1-15' || op == 'quincena-mes' || op == 'fin-mes') {
                    console.log(op);
                    var n_pago = $('#n_pago').val();
                    data.n_pago = n_pago;
                }

                for (var campo in data) {
                    var input = $('#' + campo + '');
                    if (!input.hasClass('currency')) {
                        data[campo] = input.val();
                    } else {
                        data[campo] = input.autoNumeric('get');
                    }
                }
                $.ajax({
                        data: data,
                        url: base_url() + "venta/generar_contrato/",
                        async: true,
                        type: 'post',
                        beforeSend: function() {
                            tinymce.activeEditor.setContent("");
                        },
                    }).done(function(response) {
                        tinymce.activeEditor.selection.setContent(response.html);
                    })
                    .fail(function(response) {
                        sweetAlert("¡Error!", "Algo salió mal, contactar al administrador.", "error");
                    });
            }

            form_venta.validate().settings.ignore = ":disabled,:hidden";
            return form_venta.valid();
        },

        onFinishing: function(event, currentIndex) {
            form_venta.validate().settings.ignore = ":disabled";
            return form_venta.valid();
        },
        onFinished: function(event, currentIndex) {
            var data = $(this).serializeObject();
            data.contrato_html = tinymce.activeEditor.getContent();
            data.precio = $('#precio').autoNumeric('get');
            data.enganche = $('#enganche').autoNumeric('get');
            data.abono = $('#abono').autoNumeric('get');
            swal({
                    title: "¿Desea guardar los datos?",
                    text: "Generar contrato",
                    type: "info",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    showLoaderOnConfirm: true,
                },
                function() {
                    $.ajax({
                            data: data,
                            url: base_url() + "venta/guardar_contrato/",
                            async: true,
                            type: 'post',
                            beforeSend: function() {
                                //$('a[href="#finish"]').attr("disabled", true);
                            },
                            success: function(xhr) {

                            }
                        }).done(function(response) {
                            swal("¡Contrato generado exitosamente!");
                            window.location.href = base_url() + "venta/historial_de_ventas";

                        })
                        .fail(function(response) {
                            sweetAlert("¡Error!", "Algo salió mal, contactar al administrador.", "error");
                        });
                });
            console.log(data);

        },
        labels: {
            cancel: "Cancelar",
            current: "Paso Actual:",
            pagination: "Pagination",
            finish: "Finalizar",
            next: "Siguiente",
            previous: "Anterior",
            loading: "Cargando ..."
        }
    });
    ////////////////////////////////////////////////////////////////////////////



    var historial_ventas_table = $('#historial-ventas-table').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.childRowImmediate,
            }
        },
        "columns": [ //Atributos para la tabla
            { "data": "id_venta" },
            { "data": "nombre_cliente" },
            /*{ "data": "detalles" },*/
            { "data": "retraso" },
            { "data": "retrasados" },
            { "data": "adelantados" },
            { "data": "en_tiempo" },
            { "data": "realizados" },
            { "data": "descripcion" },
            { "data": "precio" },
            { "data": "comision" },
            { "data": "abonado" },
            { "data": "comisiones" },
            { "data": "nombre_lider" },
            { "data": "nombre_user" },
            { "data": "" },
        ],
        columnDefs: [ //
            /*{
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
            },*/
            {
                //Quitar busqueda para esta columna
                "targets": [],
                "searchable": false,
            }
        ],
        "order": [
            [1, "asc"]
        ],
    });
    $('#historial-ventas-table').on('click', '.cancelar-venta', function() {
        pagoDtRow = ($(this).closest('tr').hasClass('parent') || $(this).closest('tr').hasClass('child')) ?
            $(this).closest('tr').prev('tr.parent') :
            $(this).parents('tr');
        var parseDtRow = historial_ventas_table.row(pagoDtRow).data();
        var data = {
            id_venta: parseDtRow.id_venta
        };
        swal({
                title: "Cancelar venta",
                text: "Esta a punto de cancelar una venta, ¿Desea continuar?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                cancelButtonText: 'CANCELAR',
            },
            function() {
                $.ajax({
                        url: base_url() + "ajax/cancelar_venta/",
                        data: data,
                        type: "post",
                    })
                    .done(function(response) {
                        /*var newData = pagos_table.row(pagoDtRow).data(response).draw(false).node(); //
                        $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX*/
                        swal("¡Pago removido!");
                    })
                    .fail(function(response) {

                    });
            });
    });
    $('#historial-ventas-table').on('click', '.activar-venta', function() {
        pagoDtRow = ($(this).closest('tr').hasClass('parent') || $(this).closest('tr').hasClass('child')) ?
            $(this).closest('tr').prev('tr.parent') :
            $(this).parents('tr');
        var parseDtRow = historial_ventas_table.row(pagoDtRow).data();
        var data = {
            id_venta: parseDtRow.id_venta
        };
        swal({
                title: "Cancelar venta",
                text: "Esta a punto de cancelar una venta, ¿Desea continuar?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                cancelButtonText: 'CANCELAR',
            },
            function() {
                $.ajax({
                        url: base_url() + "ajax/activar_venta/",
                        data: data,
                        type: "post",
                    })
                    .done(function(response) {
                        /*var newData = pagos_table.row(pagoDtRow).data(response).draw(false).node(); //
                        $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX*/
                        swal("¡Pago removido!");
                    })
                    .fail(function(response) {

                    });
            });
    });
    $('#historial-ventas-table').on('click', '.eliminar-venta', function() {
        pagoDtRow = ($(this).closest('tr').hasClass('parent') || $(this).closest('tr').hasClass('child')) ?
            $(this).closest('tr').prev('tr.parent') :
            $(this).parents('tr');
        var parseDtRow = historial_ventas_table.row(pagoDtRow).data();
        var data = {
            id_venta: parseDtRow.id_venta
        };
        swal({
                title: "Cancelar venta",
                text: "Esta a punto de cancelar una venta, ¿Desea continuar?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                cancelButtonText: 'CANCELAR',
            },
            function() {
                $.ajax({
                        url: base_url() + "ajax/eliminar_venta/",
                        data: data,
                        type: "post",
                    })
                    .done(function(response) {
                        /*var newData = pagos_table.row(pagoDtRow).data(response).draw(false).node(); //
                        $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX*/
                        swal("¡Pago removido!");
                    })
                    .fail(function(response) {

                    });
            });
    });
    $('#historial-ventas-table').on('click', '.recuperar-venta', function() {
        pagoDtRow = (this.btn.closest('tr').hasClass('parent') || this.btn.closest('tr').hasClass('child')) ?
            this.btn.closest('tr').prev('tr.parent') :
            this.btn.parents('tr')
        var parseDtRow = historial_ventas_table.row(pagoDtRow).data();
        var data = {
            id_venta: parseDtRow.id_venta
        };
        swal({
                title: "Cancelar venta",
                text: "Esta a punto de cancelar una venta, ¿Desea continuar?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                cancelButtonText: 'CANCELAR',
            },
            function() {
                $.ajax({
                        url: base_url() + "ajax/recuperar_venta/",
                        data: data,
                        type: "post",
                    })
                    .done(function(response) {
                        /*var newData = pagos_table.row(pagoDtRow).data(response).draw(false).node(); //
                        $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX*/
                        swal("¡Pago removido!");
                    })
                    .fail(function(response) {

                    });
            });
    });
    var pagos_table = $('#pagos-table').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.childRowImmediate,
            }
        },
        "columns": [ //Atributos para la tabla
            { "data": "id_historial" },
            { "data": "nombre_cliente" },
            { "data": "nombre_lider" },
            { "data": "concepto" },
            { "data": "abono" },
            { "data": "fecha" },
            { "data": "estado" },
            { "data": "detalles" }, {
                "data": "",
                "render": function(data, type, full, meta) {

                    if (full.pago != undefined && !parseFloat(full.pago)) {
                        return '<button class="btn btn-success" data-toggle="modal" data-target="#pagoModal">Registrar pago</button> ';
                    } else if (full.pago != undefined && parseFloat(full.pago) > 0) {
                        if (full.comision != undefined && !parseFloat(full.comision)) {
                            if (full.is_admin) {
                                return '<button class="btn btn-danger removerPago">Remover pago</button> <button class="btn btn-warning" data-toggle="modal" data-target="#pagoComisionModal">Registrar comisión</button>';
                            } else {
                                return '<button class="btn btn-warning" data-toggle="modal" data-target="#pagoComisionModal">Registrar comisión</button>';
                            }
                        } else {
                            if (full.is_admin) {
                                return '<button class="btn btn-danger removerPago">Remover pago</button> ';
                            } else {
                                return '';
                            }
                        }

                    } else {
                        return data;
                    }
                }

            },
        ],
        columnDefs: [ //
            {
                //Añadir boton dinamicamente, para esta columna*
                "targets": -1,
                "data": null,
                "defaultContent": "",
            },
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [1, 2, 3, 4, 5, 6, 7],
            },
            {
                //Añadir boton dinamicamente, para esta columna*
                "targets": 0,
                "type": "num",
            }
        ],
        "order": [
            [0, "asc"]
        ],

    });
    $('#fecha_pago').mask('00-00-0000');
    $("#fecha_pago").datepicker({
        dateFormat: "dd-mm-yy"
    }).datepicker("setDate", new Date());
    var id_historial = 0;
    var pagoDtRow;
    $('#pagoModal').on('shown.bs.modal', function(e) {
        var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable
        pagoDtRow = $(button).parents('tr');
        var parseDtRow = pagos_table.row(pagoDtRow).data();
        id_historial = parseDtRow.id_historial;
        $.ajax({
                url: base_url() + "ajax/get_pagos/",
                data: { id_historial: id_historial },
                type: "post",
            })
            .done(function(response) {
                $('#pago').autoNumeric('set', response.abono);
                $('#id_lider').val(response.id_lider);
                $('#comision').autoNumeric('set', response.comision);
                $('#porcentaje_comision').val(response.porcentaje_comision);
                $('#penalizacion').autoNumeric('set', response.penalizacion);
                $('#porcentaje_penalizacion').val(response.porcentaje_penalizacion);
                $('#daysAccumulated').val(response.daysAccumulated);

            })
            .fail(function(response) {

            });
    });
    $('#frm-pago #porcentaje_comision').on('change keyup', function() {
        var porcentaje_comision = $(this).val();
        var monto = $('#frm-pago #pago').autoNumeric('get');
        $('#frm-pago #comision').autoNumeric('set', monto * (porcentaje_comision / 100));
    });
    $('#frm-pago #comision').on('keyup', function() {
        var comision = $(this).autoNumeric('get');
        var monto = $('#frm-pago #pago').autoNumeric('get');
        $('#frm-pago #porcentaje_comision').val((100 * (comision / monto)).toFixed(2));
    });
    $('#frm-pago #porcentaje_penalizacion').on('change keyup', function() {
        var porcentaje_penalizacion = $(this).val();
        var monto = $('#frm-pago #pago').autoNumeric('get');
        var dias = $('#daysAccumulated').val();
        $('#frm-pago #penalizacion').autoNumeric('set', (monto * (porcentaje_penalizacion / 100)) * dias);
    });
    $('#frm-pago #penalizacion').on('change keyup', function() {
        var penalizacion = $('#penalizacion').autoNumeric('get');
        var monto = $('#frm-pago #pago').autoNumeric('get');
        var dias = $('#daysAccumulated').val();
        $('#frm-pago #porcentaje_penalizacion').val((100 * (penalizacion / (monto * dias))).toFixed(2));;
    });
    $("#frm-pago").on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serializeObject();
        data.id_historial = id_historial;
        data.pago = $('#pago').autoNumeric('get');
        data.penalizacion = $('#penalizacion').autoNumeric('get');
        data.comision = $('#comision').autoNumeric('get');
        $.ajax({
                url: base_url() + "ajax/pagar/",
                data: data,
                type: "post",
            })
            .done(function(response) {
                $('#pagoModal').modal('hide');
                var newData = pagos_table.row(pagoDtRow).data(response).draw(false).node(); //
                $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX
            })
            .fail(function(response) {

            });
    });
    $('#pagoComisionModal').on('shown.bs.modal', function(e) {
        var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable
        pagoDtRow = $(button).parents('tr');
        var parseDtRow = pagos_table.row(pagoDtRow).data();
        id_historial = parseDtRow.id_historial;
        $.ajax({
                url: base_url() + "ajax/get_comision/",
                data: { id_historial: id_historial },
                type: "post",
            })
            .done(function(response) {
                $('#pago2').autoNumeric('set', response.abono);
                $('#id_lider2').val(response.id_lider);
                $('#comision2').autoNumeric('set', response.comision);
                $('#porcentaje_comision2').val(response.porcentaje_comision);
            })
            .fail(function(response) {

            });
    });
    $('#porcentaje_comision2').on('change keyup', function() {
        var porcentaje_comision = $(this).val();
        var monto = $('#pago2').autoNumeric('get');
        $('#comision2').autoNumeric('set', monto * (porcentaje_comision / 100));
    });
    $("#frm-pago-comision").on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serializeObject();
        data.id_historial = id_historial;
        data.pago = $('#pago2').autoNumeric('get');
        data.comision = $('#comision2').autoNumeric('get');
        $.ajax({
                url: base_url() + "ajax/pagar_comision/",
                data: data,
                type: "post",
            })
            .done(function(response) {
                $('#pagoComisionModal').modal('hide');
                var newData = pagos_table.row(pagoDtRow).data(response).draw(false).node(); //
                $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX
            })
            .fail(function(response) {

            });
    });
    $('#pagos-table').on('click', '.removerPago', function() {
        pagoDtRow = $(this).parents('tr');
        var parseDtRow = pagos_table.row(pagoDtRow).data();
        var data = {
            id_historial: parseDtRow.id_historial
        };
        swal({
                title: "Remover pago",
                text: "Esta a punto de remover un pago, ¿Desea continuar?",
                type: "info",
                showCancelButton: true,
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
                cancelButtonText: 'CANCELAR',
            },
            function() {
                $.ajax({
                        url: base_url() + "ajax/remover_pago/",
                        data: data,
                        type: "post",
                    })
                    .done(function(response) {
                        var newData = pagos_table.row(pagoDtRow).data(response).draw(false).node(); //
                        $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX
                        swal("¡Pago removido!");
                    })
                    .fail(function(response) {

                    });
            });
    });
    $('#init_date').mask('00-00-0000');
    $("#init_date").datepicker({
        dateFormat: "dd-mm-yy"
    });
    $('#end_date').mask('00-00-0000');
    $("#end_date").datepicker({
        dateFormat: "dd-mm-yy"
    });




    $('[data-toggle=popover]').on('click', function(e) {
        console.log(e);
        var event = e;
        $('[data-toggle=popover]').popover('hide');
        $(this).popover({
                'html': true,
            }).popover('show')
            .on('shown.bs.popover', function() {
                event.stopPropagation();
                $('.popover').attr('tabindex', "10").on('click', function(e) {
                    e.stopPropagation();
                });
            }).on('hidden.bs.popover', function() {
                event.stopPropagation();
            });
        event.stopPropagation();
    });

});
console.log('hola');
if (module.hot) {
    module.hot.accept();
}