//Datos globales
var base_url = 'http://' + window.location.hostname + '/ceiba_negra/';
//var base_url = 'http://huertoslaceiba.com/';
var lang_esp_datatables = "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json";
////////////////////////////////////////////////
moment.locale('es');
////////////////////////////////////////////////
$.datepicker.setDefaults($.datepicker.regional["es"]);
////////////////////////////////////////////////
function format_numeric(action) {
    console.log("Dar formato de moneda y superficie");
    if ($('.superficie').length) {
        $('.superficie').autoNumeric(action, {
            aSign: ' m\u00B2',
            pSign: 's'
        }); //Averiguar más del plugin para evitar menores a 0
    }
    $(".currency").autoNumeric(action, {
        aSign: "$ "
    });
}
////////////////////////////////////////////////
var ajax_msg = {
        hidden: function() {
            //Remover mensaje
            if ($('.container-icons').find('.message').text().length > 0) {
                $('.container-icons').slideUp(0);
                $('.container-icons').find('.message').text('');
            }
            this.clean_box();
        },
        show_error: function(response) {
            this.clean_box();
            $('.container-icons').addClass('container-icons showicon error').find('i').addClass('fa-times-circle-o');
            var msg = "Mensaje de error: " + response.responseText;
            msg += "\nVerificar los datos ingresados con los registros existentes.";
            msg += "\nCódigo de error: " + response.status + ".";
            msg += "\nMensaje de código error: " + response.statusText + ".";
            this.set_msg(msg);
            $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');
        },
        show_success: function(msg) {
            this.clean_box();
            $('.container-icons').addClass('container-icons showicon ok').find('i').addClass('fa-check-circle-o');
            this.set_msg(msg);
            $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');
        },
        clean_box: function() {
            //Remover iconos
            if ($('.container-icons').hasClass('showicon ok')) {
                $('.container-icons').removeClass('showicon ok').find('i').removeClass('fa-check-circle-o');
            } else if ($('.container-icons').hasClass('showicon error')) {
                $('.container-icons').removeClass('showicon error').find('i').removeClass('fa-times-circle-o');
            }
        },
        set_msg: function(msg) {
            $('.container-icons').slideUp(0);
            $('.container-icons').find('.message').empty().html(msg);
            $('.container-icons').slideDown(625);
        }
    }
    ////////////////////////////////////////////////
    //Funcion FormToObject
$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
//Preconfiguración de los datatable
$.extend(true, $.fn.dataTable.defaults, {
    "pagingType": "full_numbers",
    "language": {
        "url": lang_esp_datatables,
        "decimal": ".",
        "thousands": ","
    },
    /*"search": {
        "caseInsensitive": false
    },*/
    "responsive": true,
    "deferRender": true,
    "pageLength": 25,
});
////////////////////////////////////////////////////////
jQuery.extend(jQuery.validator.messages, {
    required: "Este campo es obligatorio.",
    remote: "Por favor, rellena este campo.",
    email: "Por favor, escribe una dirección de correo válida",
    url: "Por favor, escribe una URL válida.",
    date: "Por favor, escribe una fecha válida.",
    dateISO: "Por favor, escribe una fecha (ISO) válida.",
    number: "Por favor, escribe un número entero válido.",
    digits: "Por favor, escribe sólo dígitos.",
    creditcard: "Por favor, escribe un número de tarjeta válido.",
    equalTo: "Por favor, escribe el mismo valor de nuevo.",
    accept: "Por favor, escribe un valor con una extensión aceptada.",
    maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
    minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres."),
    rangelength: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
    range: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1}."),
    max: jQuery.validator.format("Por favor, escribe un valor menor o igual a {0}."),
    min: jQuery.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
});
///////////////////////////////////////////////////
var GenericFrm = function(config) {
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

    this.dtRow = this.btn.parents('tr');
    this.parseDtRow = this.dtTable.row(this.dtRow).data();
    this.response;
    this.fnOnDone;

    this.on_submit();
};
GenericFrm.prototype.add = function() {
    this.frm[0].reset();
    this.data = {};
    if (this.readonly !== undefined) {
        this.readonly.status = false;
        this.fnReadonly();
    }
    this.url = this.urls.add;
    this.msg = this.msgs.add;
    this.fnOnDone = this.ajaxAddDone;
};
GenericFrm.prototype.edit = function() {

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
};
GenericFrm.prototype.fnReadonly = function() {
    $(this.readonly.inputs).attr('readonly', this.readonly.status);
};
GenericFrm.prototype.submit = function() {
    var self = this;
    $.ajax({
            url: base_url + self.url,
            type: "post",
            data: self.data,
            beforeSend: function(xhr) {
                $("input[type='submit']").next().css('visibility', 'visible');
            }
        })
        .done(function(response) {
            self.response = response;
            self.fnOnDone.apply(self);
        })
        .fail(function(response) {
            ajax_msg.show_error(response);
        });
}
GenericFrm.prototype.ajaxAddDone = function() {
    this.frm[0].reset();
    console.log(this.response);
    var newData = this.dtTable.row.add(this.response[0]).draw(false).node();
    $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX
    this.dtTable.order([0, 'desc']).draw(); //Ordenar por id
    ajax_msg.show_success(this.msg);
}
GenericFrm.prototype.ajaxEditDone = function() {
    console.log(this.response[0]);

    for (var data in this.response[0]) {
        this.parseDtRow[data] = this.response[0][data];
    }
    var newData = this.dtTable.row(this.dtRow).data(this.parseDtRow).draw(false).node(); //
    $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX
    ajax_msg.show_success(this.msg);
}
GenericFrm.prototype.on_submit = function() {
    var self = this;
    this.frm.off('submit').on('submit', function(e) {
        e.preventDefault();
        Object.assign(self.data, $(this).serializeObject());
        for (data in self.autoNumeric) { //Convertir de númerico a número
            if ($('#' + self.autoNumeric[data]).length > 0) {
                self.data[self.autoNumeric[data]] = $('#' + self.autoNumeric[data]).autoNumeric('get');
            }
        }
        self.submit();
    });
};
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
            window.location.href = base_url;
        }
    }
    $(".itemCartDelete").on('click', function(e) {
        $.ajax({
                url: base_url + "ajax/delete_cart/",
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
    $.get(base_url + "ajax/add_cart", function(response) { templateCart(response) });
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
        content_css: base_url + '/assets/css/tinymce.css',
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
                serviceUrl: base_url + 'ajax/autocomplete_clientes',
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
                serviceUrl: base_url + 'ajax/autocomplete_lideres',
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
                        url: base_url + "venta/generar_contrato/",
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
                            url: base_url + "venta/guardar_contrato/",
                            async: true,
                            type: 'post',
                            beforeSend: function() {
                                //$('a[href="#finish"]').attr("disabled", true);
                            },
                            success: function(xhr) {

                            }
                        }).done(function(response) {
                            swal("¡Contrato generado exitosamente!");
                            window.location.href = base_url + "venta/historial_de_ventas";

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
    //MANZANAS
    //Estructura de Datatable para las Manzanas (La tabla de vista)
    var manzanas_table = $('#manzanas-table').DataTable({
        "ajax": base_url + 'ajax/get_manzanas', //URL de datos
        "columns": [ //Atributos para la tabla
            {
                "data": "id_manzana",
                "type": "num",
            }, {
                "data": "manzana",
                "render": function(data, type, full, meta) {
                    return "<span class='mz'>" + data + "</span>";
                },
                "type": "html-num",
            },
            { "data": "calle" }, {
                "data": "superficie",
                "render": function(data, type, full, meta) {
                    return '<span class="superficie">' + data + '</span>';
                }
            }, {
                "data": "disponibilidad",
                //Supa kawaiesko funcion para el render
                "render": function(data, type, full, meta) {
                    if (!parseInt(data)) {
                        return '<span class="label label-primary">No disponible</span>';
                    } else {
                        return '<span class="label label-success">Disponible</span>';
                    }

                }
            },
            { "data": "col_norte" },
            { "data": "col_noreste" },
            { "data": "col_este" },
            { "data": "col_sureste" },
            { "data": "col_sur" },
            { "data": "col_suroeste" },
            { "data": "col_oeste" },
            { "data": "col_noroeste" },
            { "data": "" } //Espacio extra para el boton o botones de opciones
        ],
        columnDefs: [ //Configuracion de la tabla de manzanas
            {
                //Añadir boton dinamicamente, para esta columna*
                "targets": -1,
                "data": null,
                "defaultContent": '<button data-toggle="modal" data-target="#manzanaModal" data-title="Editar manzana" data-btn-type="edit" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>',
            }, {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [-1, -2, -3, -4, -5, -6, -7, -8, -9, -12]
            }, {
                //Quitar busqueda para esta columna
                "searchable": false,
                "targets": [-1]
            }
        ],
        "order": [
            [1, "asc"]
        ],
        "drawCallback": function(settings) {
            format_numeric('init');
        },
    });
    manzanas_table.on('responsive-display', function(e, datatable, row, showHide, update) {
        format_numeric('init');
    });
    $('#manzanaModal').on('show.bs.modal', function(e) {
        //Ocultar mensajes de la caja AJAX
        ajax_msg.hidden();

        var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable)
        var title = button.data('title'); // Extraer informacipon desde atributos data-* 
        var btnType = button.data('btnType');
        console.log(button);
        var modal = $(this);
        modal.find('.model-title').html(title);

        //console.log(btnType);
        var config = {
            'frm': '#frm-manzana',
            'urls': { 'edit': 'ajax/update_manzana', 'add': 'ajax/add_manzana' },
            'msgs': { 'edit': 'Manzana actualizada correctamente.', 'add': 'Manzana agregada correctamente.' },
            'autoNumeric': ["superficie"], //A que campos quitarle las comas y signos.
            'readonly': { 'inputs': '#manzana' }, //Que campos son de lectura para agregar y quitar
            'append': ["id_manzana"], //Que campo anexar de dtRow al data a enviar por AJAX
            'btn': button, //Boton que disparó el evento de abrir modal
            'dtTable': manzanas_table, //Data table que se parseará
        };
        var genericFrm = new GenericFrm(config);
        genericFrm[btnType]();
    });
    //HUERTOS
    //Datatable de los huertos
    $('.multiplicar').on('keyup', multiplicar);

    function multiplicar() {
        //console.log($('.multiplicar'));
        var campos = $('.multiplicar');
        var resultado = 1;
        for (var i = 0; i < campos.length; i++) {
            resultado *= ($(campos[i]).autoNumeric('get'));
        }
        $('#precio').autoNumeric('set', resultado);
    }
    var huertos_table = $('#huertos-table').DataTable({
        "ajax": base_url + 'ajax/get_huertos_pmz',
        "columns": [ //Atributos para la tabla
            {
                "data": "id_huerto",
                "type": "num",
            }, {
                "data": "manzana",
                "render": function(data, type, full, meta) {
                    return '<span class="mz">' + data + '</span>';
                },
                "type": "html-num",
            }, {
                "data": "huerto",
                "render": function(data, type, full, meta) {
                    return '<span class="ht">' + data + '</span>';
                },
                "type": "html-num",
            }, {
                "data": "superficie",
                "render": function(data, type, full, meta) {
                    return '<span class="superficie">' + data + '</span>';
                }
            }, {
                "data": "precio_x_m2",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            }, {
                "data": "precio",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            }, {
                "data": "enganche",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            }, {
                "data": "abono",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            }, {
                "data": "vendido", //Supa kawaiesko funcion para el render
                "render": function(data, type, full, meta) {
                    if (!parseInt(data)) {
                        return '<span class="label label-primary">No vendido</span>';
                    } else {
                        return '<span class="label label-success">Vendido</span>';
                    }

                }
            },
            { "data": "col_norte" },
            { "data": "col_noreste" },
            { "data": "col_este" },
            { "data": "col_sureste" },
            { "data": "col_sur" },
            { "data": "col_suroeste" },
            { "data": "col_oeste" },
            { "data": "col_noroeste" },
            { "data": "" } //Espacio extra para el boton o botones de opciones
        ],
        columnDefs: [ //
            {
                //Añadir boton dinamicamente, para esta columna*
                "targets": -1,
                "data": null,
                "defaultContent": '<button data-toggle="modal" data-title="Editar huerto" data-btn-type="edit" data-target="#huertoModal" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>',
            }, {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [-1, -2, -3, -4, -5, -6, -7, -8, -9]
            }, {
                //Quitar busqueda para esta columna
                "targets": [-1, -2, -3, -4, -5],
                "searchable": false,
            }
        ],
        "order": [
            [1, "asc"]
        ],
        "drawCallback": function(settings) {
            format_numeric('init');
        },
    });
    huertos_table.on('responsive-display', function(e, datatable, row, showHide, update) {
        format_numeric('init');
    });
    $('#huertoModal').on('show.bs.modal', function(e) {
        //Ocultar mensajes de la caja AJAX
        ajax_msg.hidden();
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
            'dtTable': huertos_table, //Data table que se parseará
        };
        var genericFrm = new GenericFrm(config);
        genericFrm[btnType]();
    });
    //OPCIONES DE INGRESO
    var opciones_de_ingreso_table = $('#opciones-de-ingreso-table').DataTable({
        "ajax": base_url + 'ajax/get_opciones_de_ingreso',
        "columns": [ //Atributos para la tabla
            { "data": "id_opcion_ingreso" },
            { "data": "nombre" }, {
                "data": "cuenta",
                "render": function(data, type, full, meta) {
                    if (parseInt(data) === 0) {
                        return '';
                    } else {
                        return data;
                    }
                }
            }, {
                "data": "tarjeta",
                "render": function(data, type, full, meta) {
                    if (parseInt(data) === 0) {
                        return '';
                    } else {
                        return data;
                    }
                }
            }, {
                "data": "",
                "render": function(data, type, full, meta) {
                    var btnEditar = '<button data-toggle="modal" data-title="Editar opción de ingreso" data-btn-type="edit" data-target="#opcionDeIngresoModal" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>';
                    var btnShowIngresos = '<a href="ingresos/' + full.id_opcion_ingreso + '" class="btn btn-info btn-sm"><i class="fa fa-fw fa-search"></i>Ver ingresos<a/>';
                    if (full.id_opcion_ingreso === 1 || full.nombre === 'CAJA') {
                        return btnShowIngresos;
                    } else {
                        return btnEditar + ' ' + btnShowIngresos;
                    }
                }
            },
        ],
        columnDefs: [ //
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [1, 4]
            }
        ]
    });
    $('#opcionDeIngresoModal').on('show.bs.modal', function(e) {
        //Ocultar mensajes de la caja AJAX
        ajax_msg.hidden();
        var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable)
        var title = button.data('title'); // Extraer informacipon desde atributos data-* 
        var btnType = button.data('btnType');
        var modal = $(this);
        modal.find('.model-title').html(title);

        var config = {
            'frm': '#frm-opcion-ingreso',
            'urls': { 'edit': 'ajax/update_opcion_ingreso', 'add': 'ajax/add_opcion_ingreso' },
            'msgs': { 'edit': 'Opción de ingreso actualizada correctamente.', 'add': 'Opción de ingreso agregada correctamente.' },
            'autoNumeric': [], //A que campos quitarle las comas y signos.
            //'readonly': { 'inputs': '#id_manzana' }, //Que campos son de lectura para agregar y quitar
            'append': ["id_opcion_ingreso"], //Que campo anexar de dtRow al data a enviar por AJAX
            'btn': button, //Boton que disparó el evento de abrir modal
            'dtTable': opciones_de_ingreso_table, //Data table que se parseará
        };
        var genericFrm = new GenericFrm(config);
        genericFrm[btnType]();
    });
    //USUARIOS
    var users_table = $('#users-table').DataTable({
        "columns": [ //Atributos para la tabla
            { "data": "first_name" },
            { "data": "last_name" },
            { "data": "email" },
            { "data": "groups" },
            { "data": "btn_activar_desactivar" },
            { "data": "btn_editar" },
        ],
        columnDefs: [ //
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [1, 2, 3, 4, 5],
            }, {
                //Quitar busqueda para esta columna
                "targets": [],
                "searchable": false,
            }
        ],
        "order": [
            [0, "asc"]
        ],
    });
    $('#userModal').on('shown.bs.modal', function(e) {
        console.log($("#frm-ion-user"));
        //Ocultar mensajes de la caja AJAX
        ajax_msg.hidden();
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
            'dtTable': users_table, //Data table que se parseará
        };
        var genericFrm = new GenericFrm(config);
        if (btnType) {
            genericFrm.edit = function() {
                console.log("Haz nada");
                this.url = this.urls.edit;
                this.msg = this.msgs.edit;
                this.fnOnDone = this.ajaxEditDone;
            };
            genericFrm[btnType]();
        }
    }).on('hidden.bs.modal', function() {
        $(this).removeData('bs.modal');
    });
    var historial_ventas_table = $('#historial-ventas-table').DataTable({
        responsive: {
            details: {
                display: $.fn.dataTable.Responsive.display.childRowImmediate,
            }
        },
        "columns": [ //Atributos para la tabla
            { "data": "id_venta" },
            { "data": "nombre_cliente" },
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
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
            },
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
        pagoDtRow = $(this).parents('tr');
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
                        url: base_url + "ajax/cancelar_venta/",
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
        pagoDtRow = $(this).parents('tr');
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
                        url: base_url + "ajax/activar_venta/",
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
        pagoDtRow = $(this).parents('tr');
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
                        url: base_url + "ajax/eliminar_venta/",
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
        pagoDtRow = $(this).parents('tr');
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
                        url: base_url + "ajax/recuperar_venta/",
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
                url: base_url + "ajax/get_pagos/",
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
                url: base_url + "ajax/pagar/",
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
                url: base_url + "ajax/get_comision/",
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
                url: base_url + "ajax/pagar_comision/",
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
                        url: base_url + "ajax/remover_pago/",
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
    //MAPA
    //Desplegar mapa
    var mapplic = $('#mapplic').mapplic({
        source: base_url + 'ajax/get_mapa', // Using mall.json file as map data
        sidebar: true, // hahilita Panel izquierdo
        minimap: false, // Enable minimap
        markers: false, // Deshabilita Marcadores
        // hovertip: false, //Activa o desactiba tooltip en hover
        mapfill: true,
        fillcolor: '',
        fullscreen: true, // Enable fullscreen
        developer: true,
        zoom: false,
        maxscale: 2, // Setting maxscale to 3
        smartip: false,
        deeplinking: false, //inhabilita nombres en uri,

    });
    //Poner formato númerico
    mapplic.on('locationopened', function(e, self) {
        format_numeric('init');
    });

    /* //Herramienta para capturar las coordenadas del mapa
    mapplic.on('locationopened', function(e, location) {
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
            url: base_url + "ajax/guardar_coordenadas/",
            type: 'post',
            asyn: true,
            data: data
        });
    });*/


});