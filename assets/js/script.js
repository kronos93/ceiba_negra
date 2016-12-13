//Datos globales
var base_url = 'http://' + window.location.hostname + '/ceiba_negra/';
var lang_esp_datatables = "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json";
//Al cargar la página
$(document).ready(function () {
    //Datatable de manzanas
    var manzanas_table = $('#manzanas').DataTable({
        "ajax": base_url + 'ajax/get_manzanas',
        "language": {
            "url": lang_esp_datatables
        },
        columnDefs: [
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
                "defaultContent": '<button class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>'
            }
        ]
    });
    //Obtener id del datatable de manzanas
    $('#manzanas tbody').on('click', 'button', function () {
        var manzana = manzanas_table.row($(this).parents('tr')).data();
        var id_manzana = manzana[0];
        console.log(id_manzana);
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

    //Formulario
    $('#frm-add-manzanas').on('submit', function (e) {
        e.preventDefault();
        var data = $(this).serializeArray();
        var data1 = $(this).serialize();
    
        $.ajax({
            url: base_url + "ajax/add_manzana",
            type: "post",
            data : data 
        });
    });
    // swal({
    //       title: "Error!",
    //       text: "Here's my error message!",
    //       type: "error",
    //       confirmButtonText: "Cool"
    //     });
});