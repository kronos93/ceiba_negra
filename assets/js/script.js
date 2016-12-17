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
//Preconfiguración de los datatable
$.extend( true, $.fn.dataTable.defaults, {
    "pagingType": "full_numbers",
    "language": {
        "url": lang_esp_datatables
    },
} );
//Al cargar la página
$(document).ready(function () {
    //MANZANAS
    //Estructura de Datatable para las Manzanas (La tabla de vista)
    var manzanas_table = $('#manzanas').DataTable({
        "ajax": base_url + 'ajax/get_manzanas', //URL de datos
        "columns": [    //Atributos para la tabla
            { "data": "id_manzana" },
            { "data": "manzana" },
            { "data": "calle" },
            { "data": "disponibilidad" },
            { "data": "col_norte" },
            { "data": "col_sur" },
            { "data": "col_este" },
            { "data": "col_oeste" },
            { "data": "" } //Espacio extra para el boton o botones de opciones
        ],
        columnDefs: [   //Configuracion de la tabla de manzanas
            {
                //Ocultar columna*
                "targets": 0,
                "visible": false
            },
            {
                //Añadir super clase kawai, para esta columna*
                "targets": 1,
                "className": "col-mz"
            },
            {
                //Añadir boton dinamicamente, para esta columna*
                "targets": -1,
                "data": null,
                "defaultContent": '<button data-toggle="modal" data-target="#edit-manzana" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>'
            },
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [-1, -3]
            },
            {
                //Quitar busqueda para esta columna
                "searchable": false,
                "targets": [-2]
            }
        ]
    });
    //Obtener id del datatable de manzanas
    var manzana;
    var fila;
    $('#manzanas tbody').on('click', 'button', function () {
        fila = $(this).parents('tr');
        manzana = manzanas_table.row(fila).data();
        
        //{manzana: 8907,calle:"Ola ke ace",disponibilidad:"0"}     
        
        //Añadimos los valores al modal, inidicando el formulario debido a que los id se repiten (Eliminarlos del insert ya que ahí no se usan)
        $("#frm-edit-manzanas #manzana").val(manzana.manzana);
        $("#frm-edit-manzanas #calle").val(manzana.calle);
        $("#frm-edit-manzanas #calle").val(manzana.calle);
        $("#frm-edit-manzanas #disponibilidad").val(manzana.disponibilidad);
        
    });
    //manzanas_table.fnUpdate( , 1 ); // Row
    //Formulario para agregar manzana
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

            $('.container-icons').removeClass().addClass('container-icons showicon ok').find('i').removeClass().addClass('fa fa-check-circle-o');
            // $('.modal-body').find('.container-icons').fadeOut('slow');
            // $('.modal-body').removeClass('after');
            $("input[type='submit']").attr("disabled", false).next().css('visibility','hidden');
            // alert("Datos insertados correctamente");
            // $('#add-manzana').modal('hide');
            manzanas_table.ajax.reload(null, false ); // user paging is not reset on reload
            manzanas_table.order( [ 0, 'desc' ] ).draw();
        })
        .fail(function(response) {
            console.log(response)
            $('.container-icons').removeClass().addClass('container-icons showicon error').find('i').removeClass().addClass('fa fa-times-circle-o');
            $('.modal-body').find('.container-icons.error').fadeIn();
            // $('.modal-body').find('.container-icons.error').show(0).delay(1000).hide(0);
            $("input[type='submit']").attr("disabled", false).next().css('visibility','hidden');
            var mensaje  = "Mensaje de error: " + response.responseText;
            mensaje     += "\nVerificar los datos ingresados con los registros existentes.";
            mensaje     += "\nCódigo de error: " + response.status + "."; 
            mensaje     += "\nMensaje de código error: " + response.statusText + ".";
            // alert(mensaje);
        });
    });
    //Formulario para editar manzanas
    $("#frm-edit-manzanas").on('submit',function(e){
        e.preventDefault();
        var data = $(this).serializeArray(); //Serializar formulario
        var that = this; //Almacenar el formulario donde sucedio el evento submit
        //Llamada ajax
        $.ajax({
            url: base_url + "ajax/update_manzana",
            type: "post",
            data: data,
            beforeSend: function (xhr) {
               
            }
        })
        .done(function(response) {
            
        })
        .fail(function(response) {
            
            
        });

    }); 
    //LOTES
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
    // Datatables de Usuarios
    $('#tableUsers').DataTable();
    //DETALLES EXTRA
    //Enmascarar formato de moneda, dentro de una tabla*
    $('tbody .col-moneda').mask('$ 000.000.000.000.000,00');
    //MAPA
    //Desplegar mapa
    var mapplic = $('#mapplic').mapplic({
        source: base_url + 'ajax/get_mapa',	// Using mall.json file as map data
        sidebar: true, 			// hahilita Panel izquierdo
        minimap: false, 		// Enable minimap
        markers: false, 		// Deshabilita Marcadores
        hovertip: false,        //Activa o desactiba tooltip en hover
        mapfill: true,
        fillcolor: '',
        fullscreen: false, 		// Enable fullscreen
        developer: true,
        zoom: false,
        maxscale: 0.65, 			// Setting maxscale to 3
        smartip: false,
        deeplinking: false      //inhabilita nombres en uri
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
});