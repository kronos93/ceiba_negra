var historial_ventas_table = $('#historial-ventas-table').DataTable({
    responsive: {
        details: {
            display: $.fn.dataTable.Responsive.display.childRowImmediate,
        }
    },
    "columns": [ //Atributos para la tabla
        { "data": "id_venta" },
        { "data": "nombre_cliente" },
        /*{ "data": "detalles" },*/
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
        /*{
            //Quitar ordenamiento para estas columnas
            "sortable": false,
            "targets": [0, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13],
        },*/
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
    pagoDtRow = ($(this).closest('tr').hasClass('parent') || $(this).closest('tr').hasClass('child')) ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
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
                    url: base_url() + "ajax/cancelar_venta/",
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
    pagoDtRow = ($(this).closest('tr').hasClass('parent') || $(this).closest('tr').hasClass('child')) ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
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
                    url: base_url() + "ajax/activar_venta/",
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
    pagoDtRow = ($(this).closest('tr').hasClass('parent') || $(this).closest('tr').hasClass('child')) ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
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
                    url: base_url() + "ajax/eliminar_venta/",
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
    pagoDtRow = (this.btn.closest('tr').hasClass('parent') || this.btn.closest('tr').hasClass('child')) ?
        this.btn.closest('tr').prev('tr.parent') :
        this.btn.parents('tr')
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
                    url: base_url() + "ajax/recuperar_venta/",
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
$('[data-toggle=popover]').on('click', function(e) {
    console.log(e);
    var event = e;
    $('[data-toggle=popover]').popover('hide');
    $(this).popover({
            'html': true,
        }).popover('show')
        .on('shown.bs.popover', function() {
            event.stopPropagation();
            $('.popover').attr('tabindex', "10").on('click', function(e) {
                e.stopPropagation();
            });
        }).on('hidden.bs.popover', function() {
            event.stopPropagation();
        });
    event.stopPropagation();
});