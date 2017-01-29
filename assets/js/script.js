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
            $('.container-icons').find('.message').text(msg);
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
        "url": lang_esp_datatables
    },
    "search": {
        "caseInsensitive": false
    },
    "responsive": true,
    "deferRender": true,
    "pageLength": 25,
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
        });;
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
    if ($('#precio').length && $('#enganche').length && $('#abono').length) {
        format_numeric('init');
        $('#precio').autoNumeric('set', response.total);
        $('#enganche').autoNumeric('set', response.enganche);
        $('#abono').autoNumeric('set', response.abono);
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
                templateCart(response);
            })
            .fail(function(response) {

            });
    });

}

//Al cargar la página
$(document).ready(function() {
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
                var fechas = ['fecha_primer_pago', 'fecha_ultimo_pago', 'fecha_init_1', 'fecha_init_2', 'fecha_init_3', 'fecha_init_4'];
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
    form_venta.validate({
        errorPlacement: function errorPlacement(error, element) { element.before(error); },
        lang: 'es'
    });
    form_venta.steps({
        headerTag: "h3",
        bodyTag: "div",
        transitionEffect: "slide",
        autoFocus: true,
        onInit: function() {},
        onStepChanging: function(event, currentIndex, newIndex) {
            /*tinymce.remove('#contrato_html');*/
            if (newIndex == 2) {
                var data = {
                    'first_name': '',
                    'last_name': '',
                    'email': '',
                    'phone': '',
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

                    'fecha_init': '',
                    'precio': 0,
                    'enganche': 0,
                    'abono': 0,
                    'porcentaje_penalizacion': 0,
                    'maximo_retrasos_permitidos': 0
                };
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
                    url: base_url + "venta/generacion_contrato/",
                    async: true,
                    type: 'post',
                    beforeSend: function() {
                        tinymce.activeEditor.setContent("");
                    },
                    success: function(xhr) {
                        tinymce.activeEditor.selection.setContent(xhr.html);
                    }
                });
            }
            form_venta.validate().settings.ignore = ":disabled,:hidden";
            return form_venta.valid();
            //return true;
        },
        onFinishing: function(event, currentIndex) {
            form_venta.validate().settings.ignore = ":disabled";
            return form_venta.valid();
            //return true;
        },
        onFinished: function(event, currentIndex) {
            console.log($(this).serialize());
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
    ///////////////////////////////////////////////
    //GENERAL
    $('#shopCartSale').on('click', function(e) {
        $(this).find('.my-dropdown').slideToggle('3500');
    });
    $('#shopCartSale').find('nav').on('click', function(e) {
        e.stopPropagation();
    });
    $.get(base_url + "ajax/add_cart", function(response) { templateCart(response) });

    $('#fecha_init').mask('00-00-0000');
    $("#fecha_init").datepicker({
        dateFormat: "dd-mm-yy"
    });
    $('#clientes_autocomplete').autocomplete({
        serviceUrl: base_url + 'ajax/autocomplete_clientes',
        onSelect: function(suggestion) {
            data_in_form_edit('', suggestion);
        }
    });
    $('#lideres_autocomplete').autocomplete({
        serviceUrl: base_url + 'ajax/autocomplete_lideres',
        onSelect: function(suggestion) {

        }
    });
    //MANZANAS
    //Estructura de Datatable para las Manzanas (La tabla de vista)
    var manzanas_table = $('#manzanas-table').DataTable({
        "ajax": base_url + 'ajax/get_manzanas', //URL de datos
        "columns": [ //Atributos para la tabla
            { "data": "id_manzana" },
            {
                "data": "manzana",
                "render": function(data, type, full, meta) {
                    return 'Mz.  ' + data;
                }
            },
            { "data": "calle" },
            {
                "data": "superficie",
                "render": function(data, type, full, meta) {
                    return '<span class="superficie">' + data + '</span>';
                }
            },
            {
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
            },
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [-1, -2, -3, -4, -5, -6, -7, -8, -9, -12]
            }, {
                //Quitar busqueda para esta columna
                "searchable": false,
                "targets": [-1]
            }
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
            { "data": "id_huerto" }, {
                "data": "manzana",
                "render": function(data, type, full, meta) {
                    return 'Mz.  ' + data;
                }
            },
            {
                "data": "huerto",
                "render": function(data, type, full, meta) {
                    return 'Ht.  ' + data;
                }
            },
            {
                "data": "superficie",
                "render": function(data, type, full, meta) {
                    return '<span class="superficie">' + data + '</span>';
                }
            },
            {
                "data": "precio_x_m2",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            },
            {
                "data": "precio",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            },
            {
                "data": "enganche",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            },
            {
                "data": "abono",
                "render": function(data, type, full, meta) {
                    return '<span class="currency">' + data + '</span>';
                }
            },
            {
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
            },
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [-1, -2, -3, -4, -5]
            }, {
                //Quitar busqueda para esta columna
                "targets": [-1, -2, -3, -4, -5],
                "searchable": false,
            }
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
        genericFrm.prototype.edit = function() { console.log("Haz nada"); };
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
        ]
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
        fullscreen: false, // Enable fullscreen
        developer: true,
        zoom: false,
        maxscale: 0.65, // Setting maxscale to 3
        smartip: false,
        deeplinking: false, //inhabilita nombres en uri,

    });
    //Poner formato númerico
    mapplic.on('locationopened', function(e, self) {
        format_numeric('init');
    });
    /*//Herramienta para capturar las coordenadas del mapa
    mapplic.on('locationopened', function(e, location) {
        var manzana = (location.category.replace("mz", ""));
        var lote = (location.title.replace("Huerto número ", ""));
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