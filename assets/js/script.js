//Datos globales
var base_url = 'http://' + window.location.hostname + '/ceiba_negra/';
var lang_esp_datatables = "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json";
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
});

var parsedtRow;
var dtRow;

function get_data(dtTable, obj_dtTable) {
    $('#' + dtTable + ' tbody').on('click', 'button', function() {
        dtRow = $(this).parents('tr');
        parsedtRow = obj_dtTable.row(dtRow).data();
        console.log(parsedtRow);
        data_in_form_edit(parsedtRow);
    });
}

function data_in_form_edit(json_data) {
    for (var data in json_data) {
        if ($("#" + data).length) {
            //console.log(json_data);
            $("#" + data).val(json_data[data]);
        }
    }
}

function ajax_done(that, dtTable, msj, type, response) {
    if (type == "insert") {
        that.reset();
        console.log(response);
        var newData = dtTable.row.add(response[0]).draw().node();
        $(newData)
            .css('background', 'blue')
            .animate({ 'font-size': '30px' });
        //dtTable.ajax.reload(null, false); // user paging is not reset on reload, usar row porque a max le gusta mas :v
        dtTable.order([0, 'desc']).draw(); //Ordenar por id
    } else if (type == "update") {
        for (var data in response[0]) {
            parsedtRow[data] = response[0][data];
        }
        dtTable.row(dtRow).data(parsedtRow).draw();
    }

    $('.container-icons').removeClass().addClass('container-icons showicon ok').find('i').removeClass().addClass('fa fa-check-circle-o');
    $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');
    $('.container-icons').find('.message').text(msj);
}

function ajax_fail(response) {
    $('.container-icons').removeClass().addClass('container-icons showicon error').find('i').removeClass().addClass('fa fa-times-circle-o');
    $('.modal-body').find('.container-icons.error').fadeIn();
    $("input[type='submit']").attr("disabled", false).next().css('visibility', 'hidden');
    var mensaje = "Mensaje de error: " + response.responseText;
    mensaje += "\nVerificar los datos ingresados con los registros existentes.";
    mensaje += "\nCódigo de error: " + response.status + ".";
    mensaje += "\nMensaje de código error: " + response.statusText + ".";
    $('.container-icons').find('.message').text(mensaje);
}
//Al cargar la página
$(document).ready(function() {
    //GENERAL
    if ($('.superficie').length) {
        $('.superficie').autoNumeric(); //Averiguar más del plugin para evitar menores a 0
    }
    $('#shopCartSale').on('click', function(e) {
        $(this).find('.my-dropdown').slideToggle('3500');
    });
    $('#shopCartSale').find('nav').on('click', function(e) {
        e.stopPropagation();
        console.log("Click button");
    });
    //USUARIOS
    $("#edit-user").on('hidden.bs.modal', function() {
        $(this).removeData('bs.modal');
    });
    $("#add-user").on('shown.bs.modal', function() {
        $("#ion_addUser").on('submit', function(e) {
            e.preventDefault();
            console.log($(this).serializeArray());
        });
    }).on('hidden.bs.modal', function() {
        $(this).removeData('bs.modal');
    });
    $.get(base_url + "ajax/add_cart", function(response) {
        var template = document.getElementById('template-venta').innerHTML;
        var output = Mustache.render(template, response);
        document.getElementById("listaVenta").innerHTML = output;
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
            { "data": "col_sur" },
            { "data": "col_este" },
            { "data": "col_oeste" },
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
                "defaultContent": '<button data-toggle="modal" data-target="#edit-manzana" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>'
            },
            {
                //Añadir super clase kawai, para esta columna*
                "targets": 1,
                "className": "col-mz"
            },
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [2, -1, -2, -3, -4, -5]
            },
            {
                //Quitar busqueda para esta columna
                "searchable": false,
                "targets": [-1, -2, -3, -4, -5]
            }
        ]
    });
    //Añade funcion de editar al datatable
    get_data("manzanas-table", manzanas_table);
    //Formulario para agregar manzana
    $('#frm-add-manzanas').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serializeArray(); //Serializar formulario
        var that = this; //Almacenar el formulario donde sucedio el evento submit
        //Llamada ajax
        $.ajax({
                url: base_url + "ajax/add_manzana",
                type: "post",
                data: data,
                beforeSend: function(xhr) {
                    $("input[type='submit']").attr("disabled", true).next().css('visibility', 'visible');
                }
            })
            .done(function(response) {
                ajax_done(that, manzanas_table, "Manzana insertada correctamente", "insert", response);
            })
            .fail(function(response) {
                ajax_fail(response);
            });
    });
    //Formulario para editar manzanas
    $("#frm-edit-manzanas").on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serializeArray(); //Serializar formulario
        data.push({ "name": "id_manzana", "value": parsedtRow.id_manzana }); //Añadimos el ID de la manzana en formato json
        var that = this; //Almacenar el formulario donde sucedio el evento submit
        //Llamada ajax
        $.ajax({
                url: base_url + "ajax/update_manzana",
                type: "post",
                data: data,
                beforeSend: function(xhr) {
                    $("input[type='submit']").attr("disabled", true).next().css('visibility', 'visible');
                }
            })
            .done(function(response) {
                ajax_done(that, manzanas_table, "Datos de la manzana actualizados correctamente", "update", response);
            })
            .fail(function(response) {
                ajax_fail(response);
            });
    });
    //Huertos
    //Datatable de los huertos
    var huertos_table = $('#huertos-table').DataTable({
        "ajax": base_url + 'ajax/get_huertos_pmz',
        "columns": [ //Atributos para la tabla
            { "data": "id_huerto" },
            {
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
                    return '<span class="superficie">' + data + '</span> mt<sup>2</sup>.';
                }
            },
            { "data": "precio" },
            { "data": "enganche" },
            { "data": "abono" },
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
            { "data": "col_sur" },
            { "data": "col_este" },
            { "data": "col_oeste" },
            { "data": "" } //Espacio extra para el boton o botones de opciones
        ],
        columnDefs: [ //
            {
                "targets": 0,
                "visible": false
            },
            {
                //Añadir boton dinamicamente, para esta columna*
                "targets": -1,
                "data": null,
                "defaultContent": '<button data-toggle="modal" data-target="#edit-huerto" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>'
            },
            {
                "targets": [4, 5, 6],
                "className": "currency"
            },

            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [-1, -2, -3, -4, -5]
            },
            {
                //Quitar busqueda para esta columna
                "targets": [-1, -2, -3, -4, -5],
                "searchable": false,
            }
        ],
        "drawCallback": function(settings) {
            $(".currency").autoNumeric({
                aSign: "$ "
            });
            $(".superficie").autoNumeric();
        },
    });
    get_data("huertos-table", huertos_table);
    //Formulario para agregar huertos
    $('#frm-add-huertos').on('submit', function(e) {
        e.preventDefault();
        var data = $(this).serializeObject(); //Serializar formulario
        data.superficie = $(this.superficie).autoNumeric('get');
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
        var data = $(this).serializeArray(); //Serializar formulario
        data.push({ "name": "id_huerto", "value": parsedtRow.id_lote }); //Añadimos el ID de la manzana en formato json
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
    // Datatables de Usuarios
    $('#tableUsers').DataTable();
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
    //Herramienta para capturar las coordenadas del mapa
    // mapplic.on('locationopened', function(e, location) {
    //     var manzana = (location.category.replace("mz", ""));
    //     var lote = (location.title.replace("Lote número ", ""));
    //     var data = {
    //         manzana: manzana,
    //         lote: lote,
    //         x: ($(".mapplic-coordinates-x")[0].innerHTML),
    //         y: $(".mapplic-coordinates-y")[0].innerHTML
    //     };
    //     console.log(data);
    //     $.ajax({
    //         url: base_url + "ajax/guardar_coordenadas/",
    //         type: 'post',
    //         asyn: true,
    //         data: data
    //     });
    // });
});