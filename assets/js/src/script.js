import $ from 'jquery';
import { base_url } from './utils/util';
import './config';

import './mapplic';
import './tables/manzanas';
import './tables/huertos';

import './tables/opciones_ingreso';
import './tables/historial_ventas';
import './tables/pagos';
import './tables/usuarios';

import Cart from './Cart';
moment.locale('es');

///////////////////////////////////////////////////

//Al cargar la página
$(document).ready(function() {

    var cart = new Cart();
    cart.get();


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



    $('#fecha_pago').mask('00-00-0000');
    $("#fecha_pago").datepicker({
        dateFormat: "dd-mm-yy"
    }).datepicker("setDate", new Date());

    $('#init_date').mask('00-00-0000');
    $("#init_date").datepicker({
        dateFormat: "dd-mm-yy"
    });
    $('#end_date').mask('00-00-0000');
    $("#end_date").datepicker({
        dateFormat: "dd-mm-yy"
    });
});

if (module.hot) {
    module.hot.accept();
}