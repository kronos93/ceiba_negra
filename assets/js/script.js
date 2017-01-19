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
        $('.superficie').autoNumeric(action); //Averiguar más del plugin para evitar menores a 0
    }
    if ($(".currency").length) {
        $(".currency").autoNumeric(action, {
            aSign: "$ "
        });
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
});

var parsedtRow;
var dtRow;

function get_data(dtTable, obj_dtTable) {
    console.log(this);
    $('#' + dtTable + ' tbody').on('click', 'button', function() {
        var target = this.dataset.target;
        dtRow = $(this).parents('tr');
        parsedtRow = obj_dtTable.row(dtRow).data();
        data_in_form_edit(target, parsedtRow);
    });
}

function data_in_form_edit(target, json_data) {
    var target = target;
    for (var data in json_data) {
        if ($("#" + data).length) {
            if (target != undefined) {
                var input = $(target + " " + "#" + data);
            } else {
                var input = $("#" + data);
            }
            if (input.hasClass('autoNumeric')) {
                input.autoNumeric('set', json_data[data]);
            } else {
                input.val(json_data[data]);
            }
        }
    }
}

function ajax_done(that, dtTable, msg, type, response) {
    if (type == "insert") {
        that.reset();
        //console.log(response);
        var newData = dtTable.row.add(response[0]).draw().node();
        /*$(newData)
            .css('background', 'blue')
            .animate({ 'font-size': '30px' });*/
        //dtTable.ajax.reload(null, false); // user paging is not reset on reload, usar row porque a max le gusta mas :v
        dtTable.order([0, 'desc']).draw(); //Ordenar por id
    } else if (type == "update") {
        for (var data in response[0]) {
            parsedtRow[data] = response[0][data];
        }
        dtTable.row(dtRow).data(parsedtRow).draw(false); //
        //console.log(dtTable.row(dtRow).selector.rows[0]);
    }
    if($('.container-icons').hasClass('showicon error')){
        $('.container-icons').removeClass('showicon error').find('i').removeClass('fa-times-circle-o');                       
    }    
    $('.container-icons').addClass('container-icons showicon ok').find('i').addClass('fa-check-circle-o'); 
    $('.container-icons').slideUp(0);   
    $('.container-icons').find('.message').text(msg);
    $('.container-icons').slideDown(625);
    $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');        
}

function ajax_fail(response) {
    if($('.container-icons').hasClass('showicon ok')){
        $('.container-icons').removeClass('showicon ok').find('i').removeClass('fa-check-circle-o');                
    }    
    $('.container-icons').addClass('container-icons showicon error').find('i').addClass('fa-times-circle-o');    

    var msg = "Mensaje de error: " + response.responseText;
    msg += "\nVerificar los datos ingresados con los registros existentes.";
    msg += "\nCódigo de error: " + response.status + ".";
    msg += "\nMensaje de código error: " + response.statusText + ".";
    $('.container-icons').slideUp(0);   
    $('.container-icons').find('.message').text(msg);
    $('.container-icons').slideDown(625);
    $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');
}

function templateCart(response) {

    $("#shopCartSale")
        .find('span')
        .attr("data-venta", response.count);

    var template = document.getElementById('template-venta').innerHTML;
    var output = Mustache.render(template, response);
    document.getElementById("listaVenta").innerHTML = output;
    if ($('#precio').length && $('#enganche').length && $('#abono').length) {
        /*$('#precio').autoNumeric('set', response.total);
        $('#enganche').autoNumeric('set', response.enganche);
        $('#abono').autoNumeric('set', response.abono);*/
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
    var form = $("#example-basic");
    form.validate({
        errorPlacement: function errorPlacement(error, element) { element.before(error); },
        lang: 'es'
    });
    form.steps({
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
                /*for (var campo in data) {
                    var input = $('#' + campo + '');
                    if (!input.hasClass('currency')) {
                        data[campo] = input.val();
                    } else {
                        data[campo] = input.autoNumeric('get');
                    }
                }*/
                $.ajax({
                    data: data,
                    url: base_url + "venta/prueba/",
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
            form.validate().settings.ignore = ":disabled,:hidden";
            return form.valid();
            //return true;
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled";
            return form.valid();
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
            { "data": "id_manzana" }, {
                "data": "manzana",
                "render": function(data, type, full, meta) {
                    return 'Mz.  ' + data;
                }
            },
            { "data": "calle" },
            {
                "data": "superficie",
                "render": function(data, type, full, meta) {
                    return '<span class="superficie">' + data + '</span> mt<sup>2</sup>.';
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
                //Ocultar columna*
                "targets": 0,
                "visible": false
            },
            {
                //Añadir boton dinamicamente, para esta columna*
                "targets": -1,
                "data": null,
                "defaultContent": '<button data-toggle="modal" data-target="#manzanaModal" data-title="Editar manzana" data-readonly="true" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>',
            },
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [2, -1, -2, -3, -4, -5]
            }, {
                //Quitar busqueda para esta columna
                "searchable": false,
                "targets": [-1, -2, -3, -4, -5]
            }
        ],
        "drawCallback": function(settings) {

        },
    });
    
    $('#manzanaModal').on('show.bs.modal', function(e) {
        //Compactar
        if($('.container-icons').find('.message').text().length > 0){ 
            $('.container-icons').slideUp(0);
            $('.container-icons').find('.message').text('');
        }
        //Remover iconos
        if($('.container-icons').hasClass('showicon ok')){
            $('.container-icons').removeClass('showicon ok').find('i').removeClass('fa-check-circle-o');               
        }else if($('.container-icons').hasClass('showicon error')){
            $('.container-icons').removeClass('showicon error').find('i').removeClass('fa-times-circle-o');                            
        }
           

        var button = $(e.relatedTarget); // Button that triggered the modal
        var title = button.data('title'); // Extract info from data-* attributes
        var readonly = button.data('readonly');

        var modal = $(this);
        modal.find('.model-title').html(title);
        var url = "";
        if (readonly) {
            $('#manzana').attr('readonly', true);
            var target = '#frm-manzana';
            dtRow = button.parents('tr');
            parsedtRow = manzanas_table.row(dtRow).data();
            data_in_form_edit(target, parsedtRow);
            url = "ajax/update_manzana";
        } else {
            $('#frm-manzana')[0].reset();
            $('#manzana').attr('readonly', false);
            url = "ajax/add_manzana";
        }
        $('#frm-manzana').off('submit').on('submit', function(e) {
            e.preventDefault();
            $(this.submitFrm).attr("disabled", true);

            if($('.container-icons').find('.message').text().length > 0){ 
                $('.container-icons').slideUp(0);
                $('.container-icons').find('.message').text('');
            }
            //Remover iconos
            if($('.container-icons').hasClass('showicon ok')){
                $('.container-icons').removeClass('showicon ok').find('i').removeClass('fa-check-circle-o');               
            }else if($('.container-icons').hasClass('showicon error')){
                $('.container-icons').removeClass('showicon error').find('i').removeClass('fa-times-circle-o');                            
            }
           
            
            if (!readonly) {
                //Para insertar
                var data = $(this).serializeArray(); //Serializar formulario
                
            } else {
                //Para editar
                var data = $(this).serializeArray(); //Serializar formulario
                data.push({ "name": "id_manzana", "value": parsedtRow.id_manzana }); //Añadimos el ID de la manzana en formato json               
                
            }
            var that = this; //Almacenar el formulario donde sucedio el evento submit
            
            //Llamada ajax
            $.ajax({
                    url: base_url + url,
                    type: "post",
                    data: data,
                    beforeSend: function(xhr) {
                        console.log(readonly);
                        $("input[type='submit']").next().css('visibility', 'visible');
                    }
                })
                .done(function(response) {
                    
                    if (!readonly) {
                        ajax_done(that, manzanas_table, "Manzana insertada correctamente", "insert", response);
                    }else{
                        ajax_done(that, manzanas_table, "Datos de la manzana actualizados correctamente", "update", response);
                    }
                })
                .fail(function(response) {
                    ajax_fail(response);
                });
        });
    });

    //HUERTOS
    //Datatable de los huertos
    $('.multiplicar').on('keyup', multiplicar);

    function multiplicar() {
        //El contexto es para saber en que formulario debe remplazarce el valor de un ID
        var context = '#' + $(this).parents('form').attr('id');
        var multiplicar = $(context + ' .multiplicar');
        var total = 1;
        for (var i = 0; i < multiplicar.length; i++) {
            total *= $(multiplicar[i]).autoNumeric('get');
        }
        $(context + " " + '#precio').autoNumeric('set', total);
    }
    var huertos_table = $('#huertos-table').DataTable({
        "ajax": base_url + 'ajax/get_huertos_pmz',
        "columns": [ //Atributos para la tabla
            { "data": "id_huerto" }, {
                "data": "manzana",
                "render": function(data, type, full, meta) {
                    return 'Mz.  ' + data;
                }
            }, {
                "data": "huerto",
                "render": function(data, type, full, meta) {
                    return 'Ht.  ' + data;
                }
            }, {
                "data": "superficie",
                "render": function(data, type, full, meta) {
                    return '<span class="superficie">' + data + '</span> mt<sup>2</sup>.';
                }
            },
            { "data": "precio_x_m2" },
            { "data": "precio" },
            { "data": "enganche" },
            { "data": "abono" }, {
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
                "targets": 0,
                "visible": false
            }, {
                //Añadir boton dinamicamente, para esta columna*
                "targets": -1,
                "data": null,
                "defaultContent": '<button data-toggle="modal" data-target="#edit-huerto" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>',
            }, {
                "targets": [4, 5, 6, 7],
                "className": "currency"
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
        "drawCallback": function(settings) {},
    });
    get_data("huertos-table", huertos_table);
    //Formulario para agregar huertos
    $('#frm-add-huertos').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serializeObject(); //Serializar formulario
        data.superficie = $(this.superficie).autoNumeric('get');
        data.precio_x_m2 = $(this.precio_x_m2).autoNumeric('get');
        var that = this; //Almacenar el formulario donde sucedio el evento submit
        //Llamada ajax
        $.ajax({
                url: base_url + "ajax/add_huerto",
                type: "post",
                data: data,
                beforeSend: function(xhr) {
                    $("input[type='submit']").attr("disabled", true).next().css('visibility', 'visible');
                }
            })
            .done(function(response) {
                ajax_done(that, huertos_table, "Huerto insertado correctamente", "insert", response);
            })
            .fail(function(response) {
                ajax_fail(response);
            });
    });
    //Formulario para editar lotes
    $("#frm-edit-huertos").on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serializeObject(); //Serializar formulario
        /*data.superficie = $(this.superficie).autoNumeric('get');
        data.precio_x_m2 = $(this.precio_x_m2).autoNumeric('get');*/
        data.id_huerto = parsedtRow.id_huerto; //Añadimos el ID de la manzana en formato json
        var that = this; //Almacenar el formulario donde sucedio el evento submit
        //Llamada ajax
        $.ajax({
                url: base_url + "ajax/update_huerto",
                type: "post",
                data: data,
                beforeSend: function(xhr) {
                    $("input[type='submit']").attr("disabled", true).next().css('visibility', 'visible');
                }
            })
            .done(function(response) {
                ajax_done(that, huertos_table, "Datos del huerto actualizados correctamente", "update", response);
            })
            .fail(function(response) {
                ajax_fail(response);
            });
    });
    //USUARIOS
    var users_table = $('#users-table').DataTable();

    $("#form-user").on('hidden.bs.modal', function() {
        $(this).removeData('bs.modal');
    });
    $("#add-user").on('shown.bs.modal', function() {
        $("#ion_addUser").on('submit', function(e) {
            e.preventDefault();
            var data = $(this).serializeArray();
            $.ajax({
                    url: base_url + "ajax/create_user/",
                    type: "post",
                    data: data,
                    beforeSend: function(xhr) {
                        //$("input[type='submit']").attr("disabled", true).next().css('visibility', 'visible');
                    }
                })
                .done(function(response) {
                    //ajax_done(that, huertos_table, "Huerto insertado correctamente", "insert", response);
                })
                .fail(function(response) {
                    //ajax_fail(response);
                });
        });
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
    mapplic.on('locationopened', function(e, self) {});
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