import { base_url } from '../utils/util';
var historial_ventas_table = $('#historial-ventas-table').DataTable({
    responsive: {
        /*details: {
            display: $.fn.dataTable.Responsive.display.childRowImmediate,
        }*/
    },
    "columns": [ //Atributos para la tabla
        { "data": "id_venta" },
        { "data": "nombre_cliente" },
        { "data": "descripcion" },
        { "data": "retraso" },
        { "data": "detalles" }, {
            "data": "precio",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        }, {
            "data": "comision",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        }, {
            "data": "pagado",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        }, {
            "data": "comisionado",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        },
        { "data": "nombre_lider" },
        { "data": "nombre_user" }, {
            "data": "",
            "render": function(data, type, full, meta) {
                if (full.estado != null && full.estado != undefined && full.estado != "") {
                    var contrato = '<a href="' + base_url() + 'reportes/contrato/' + full.id_venta + '" class="btn btn-default" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> contrato</a>';
                    var pagare_recibo = '<a href="' + base_url() + 'reportes/' + ((full.version == 2) ? 'pagares' : 'recibos') + '/' + full.id_venta + '" class="btn btn-primary" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> ' + ((full.version == 2) ? 'pagares' : 'recibos') + '</a>';
                    var pagos = '<a href="' + base_url() + 'registros/pagos/' + full.id_venta + '" target="_blank" class="btn btn-info"><i class="fa fa-fw fa-eye"></i>pagos</a>';
                    var cancelar = '';
                    var restablecer = '';
                    var eliminar = '';
                    var recuperar = ''; //Activo
                    if (full.estado == 0) {
                        cancelar = '<button title="Cancelar Contrato" class="btn btn-warning cancelar-venta"><span class="fa fa-ban fa-lg"></span> Cancelar</button>';
                        eliminar = '<button title="Eliminar Contrato" class="btn btn-danger eliminar-venta"><span class="fa fa-trash fa-lg"></span> Eliminar</button>';
                    } else if (full.estado == 2) { //Cancelado
                        /* pagos = '';*/
                        restablecer = '<button class="btn btn-success activar-venta"> <span class="fa fa-check"></span>Restablecer</button>';
                        eliminar = '<button title="Eliminar Contrato" class="btn btn-danger eliminar-venta"><span class="fa fa-trash fa-lg"></span> Eliminar</button>';
                    } else if (full.estado == 3) { //Eliminado
                        recuperar = '<button class="btn recuperar-venta"> <span class="fa fa-undo"></span> Recuperar</button>';
                        contrato = '';
                        pagare_recibo = '';
                        pagos = '';

                    }
                    return contrato + ' ' + pagare_recibo + ' ' + pagos + ' ' + cancelar + ' ' + restablecer + ' ' + eliminar + ' ' + recuperar;
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
        }, {
            //Quitar busqueda para esta columna
            "targets": [],
            "searchable": false,
        }
    ],
    "order": [
        [1, "asc"]
    ],
});
var ventaDtRow;
$('#historial-ventas-table').on('click', '.cancelar-venta', function() {
    ventaDtRow = $(this).closest('tr').hasClass('child') ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
    var parseDtRow = historial_ventas_table.row(ventaDtRow).data();
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
                    var newData = historial_ventas_table.row(ventaDtRow).data(response).draw(false).node(); //
                    $(newData).css({ backgroundColor: 'yellow' }); //Animación para MAX
                    swal("Confirmación", "¡Venta cancelada!", "success");
                })
                .fail(function(response) {
                    swal("Error:", "¡Ha ocurrido un error, contactar al administrador sí el problema persiste!", "error");
                });
        });
});
$('#historial-ventas-table').on('click', '.activar-venta', function() {
    ventaDtRow = ($(this).closest('tr').hasClass('parent') || $(this).closest('tr').hasClass('child')) ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
    var parseDtRow = historial_ventas_table.row(ventaDtRow).data();
    var data = {
        id_venta: parseDtRow.id_venta
    };
    swal({
            title: "Restablecer venta",
            text: "Esta a punto de restablecer una venta, ¿Desea continuar?",
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
                    if (response.status == 200) {
                        var newData = historial_ventas_table.row(ventaDtRow).data(response).draw(false).node(); //
                        $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX
                        swal("Confirmación", "¡Venta restablecida!", 'success');
                    } else {
                        swal("Error:", "¡Ha ocurrido un error, contactar al administrador sí el problema persiste! \n" + response.msg_status, "error");
                    }

                })
                .fail(function(response) {
                    swal("Error:", "¡Ha ocurrido un error, contactar al administrador sí el problema persiste! ", "error");
                });
        });
});
$('#historial-ventas-table').on('click', '.eliminar-venta', function() {
    ventaDtRow = $(this).closest('tr').hasClass('child') ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
    var parseDtRow = historial_ventas_table.row(ventaDtRow).data();
    var data = {
        id_venta: parseDtRow.id_venta
    };
    swal({
            title: "Eliminar venta",
            text: "Esta a punto de eliminar una venta, ¿Desea continuar?",
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
                    var newData = historial_ventas_table.row(ventaDtRow).data(response).draw(false).node(); //
                    $(newData).css({ backgroundColor: 'yellow' }); //Animación para MAX
                    swal("Confirmación", "¡Venta eliminada!", "success");
                })
                .fail(function(response) {
                    swal("Error:", "¡Ha ocurrido un error, contactar al administrador sí el problema persiste!", "error");
                });
        });
});
$('#historial-ventas-table').on('click', '.recuperar-venta', function() {
    ventaDtRow = $(this).closest('tr').hasClass('child') ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
    var parseDtRow = historial_ventas_table.row(ventaDtRow).data();
    var data = {
        id_venta: parseDtRow.id_venta
    };
    swal({
            title: "Recuperar venta",
            text: "Esta a punto de recuperar una venta, ¿Desea continuar?",
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
                    var newData = historial_ventas_table.row(ventaDtRow).data(response).draw(false).node(); //
                    $(newData).animate({ backgroundColor: 'yellow' }); //Animación para MAX*/
                    swal("Confirmación", "¡Venta recuperada!", 'success');
                })
                .fail(function(response) {
                    swal("Error:", "¡Ha ocurrido un error, contactar al administrador sí el problema persiste!", "error");
                });
        });
});

$('a[data-toggle=popover]').popover({
    'html': true,
    trigger: 'manual'
});
$('a[data-toggle=popover]').on('click', function(e) {

    $(this).popover('toggle');
    $('.popover').on('click', function(e) {
        e.stopPropagation();
    });
    e.stopPropagation();
});

$('a[data-toggle=popover]').on('shown.bs.popover', function(e) {
    console.log("show" + this);
    $('a[data-toggle=popover]').not(this).popover('hide');
});