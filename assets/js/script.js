//Datos globales
var base_url = 'http://' + window.location.hostname + '/ceiba_negra/';
var lang_esp_datatables = "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json";
//Funcion FormToObject
$.fn.serializeObject = function () {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function () {
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

//Al cargar la página
$(document).ready(function () {
    //Preconfiguración de los datatable
    $.extend( true, $.fn.dataTable.defaults, {
        "pagingType": "full_numbers",
        "language": {
            "url": lang_esp_datatables
        },
    } );
    //Estructura de Datatable para las Manzanas
    var manzanas_table = $('#manzanas').DataTable({
        "ajax": base_url + 'ajax/get_manzanas', //URL de datos
        "columns": [    //Atributos para la tabla
            { "data": "id_manzana" },
            { "data": "manzana" },
            { "data": "calle" },
            { "data": "disponibilidad" },
            { "data": "" } //Espacio extra para el boton o botones de opciones
        ],
        columnDefs: [   //Configuracion de la tabla de manzanas
            {
                "targets": 0,
                "visible": false
            },
            {
                "targets": 1,
                "className": "col-mz"
            },
            {
                "targets": -1,
                "data": null,
                "defaultContent": '<button data-toggle="modal" data-target="#edit-manzana" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>'
            },
            {
                "sortable": false,
                "targets": [-1, -3]
            },
            {
                "searchable": false,
                "targets": [-2]
            }
        ]
    });
    //Obtener id del datatable de manzanas
    $('#manzanas tbody').on('click', 'button', function () {
        var manzana = manzanas_table.row($(this).parents('tr')).data();
        console.log(manzana);
        $("#frm-edit-manzanas #manzana").val(manzana.manzana);
        $("#frm-edit-manzanas #calle").val(manzana.calle);
        $("#frm-edit-manzanas #calle").val(manzana.calle);
        $("#frm-edit-manzanas #disponibilidad").val(manzana.disponibilidad);
    });
    //Datatable de Lotes
    $('#lotes').DataTable({
        "ajax": base_url + 'ajax/get_lotes_pmz',
        "language": {
            "url": lang_esp_datatables
        },
        columnDefs: [
            {
                "targets": 0,
                "visible": false
            },
            {
                "targets": [4, 5, 6],
                "className": "col-moneda"
            },
        ]
    });
    //Enmascarar formato de moneda
    $('tbody .col-moneda').mask('$ 000.000.000.000.000,00');
    //Desplegar mapa
    var mapplic = $('#mapplic').mapplic({
        source: base_url + 'ajax/get_mapa',	// Using mall.json file as map data
        sidebar: true, 			// Enable sidebar
        minimap: false, 			// Enable minimap
        markers: false, 		// Disable markers
        mapfill: true,
        fillcolor: '',
        fullscreen: false, 		// Enable fullscreen
        developer: true,
        zoom: false,
        maxscale: 0.65, 			// Setting maxscale to 3
    });
    //Herramienta para capturar las coordenadas del mapa
    mapplic.on('locationopened', function (e, location) {
        var manzana = (location.category.replace("mz", ""));
        var lote = (location.title.replace("Lote número ", ""));
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
    });

    //Formularios
    //Formulario de manzana
    $('#frm-add-manzanas').on('submit', function (e) {
        e.preventDefault();
        var data = $(this).serializeArray(); //Serializar formulario
        var that = this; //Almacenar el formulario donde sucedio el evento submit
        //Llamada ajax
        $.ajax({
            url: base_url + "ajax/add_manzana",
            type: "post",
            data: data,
            beforeSend: function (xhr) {
                $("input[type='submit']").attr("disabled", true).next().css('visibility', 'visible');
                //Hacer que el boton tenga efeto de load
            }
        })
        .done(function(response) {
            that.reset();
            console.log(response);
            $('.modal-body').find('.container-icons.success').fadeIn(1000).delay(1000).fadeOut(1000);
            // $('.modal-body').removeClass('after');
            $("input[type='submit']").attr("disabled", false).next().css('visibility','hidden');
            // alert("Datos insertados correctamente");
            // $('#add-manzana').modal('hide');
            manzanas_table.ajax.reload(null, false ); // user paging is not reset on reload
            manzanas_table.order( [ 0, 'desc' ] ).draw();
        })
        .fail(function(response) {
            console.log(response)
            $('.modal-body').find('.container-icons.error').fadeIn(1000).delay(1000).fadeOut(1000);
            // $('.modal-body').find('.container-icons.error').show(0).delay(1000).hide(0);
            $("input[type='submit']").attr("disabled", false).next().css('visibility','hidden');
            var mensaje  = "Mensaje de error: " + response.responseText;
            mensaje     += "\nVerificar los datos ingresados con los registros existentes.";
            mensaje     += "\nCódigo de error: " + response.status + "."; 
            mensaje     += "\nMensaje de código error: " + response.statusText + ".";
            // alert(mensaje);
        });
    });
});