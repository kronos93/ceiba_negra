import $ from 'jquery';
import { base_url } from './utils/util';
import { autocompleteClientes, autocompleteLideres, datepicker, phone, number } from './components/components.js';
import 'jquery-steps/build/jquery.steps';
import 'jquery-validation/dist/jquery.validate'
import 'jquery-mask-plugin/dist/jquery.mask';
import 'jquery-ui/ui/widgets/datepicker';
import './configs/validator';

var form_venta = $("#frm-venta");
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
        phone();
        number();
        datepicker();
        autocompleteClientes(base_url);
        autocompleteLideres(base_url);

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
            if (op == '1-15' || op == 'quincena-mes' || op == 'fin-mes') {
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