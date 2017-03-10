import { base_url, ajax_msg } from '../utils/util';
import GenericFrm from '../GenericFrm';
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
var id_historial = 0;
var pagoDtRow;
$('#pagoModal').on('shown.bs.modal', function(e) {
    var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable
    pagoDtRow = $(button).parents('tr');
    var parseDtRow = pagos_table.row(pagoDtRow).data();
    id_historial = parseDtRow.id_historial;
    $.ajax({
            url: base_url() + "ajax/get_pagos/",
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
            url: base_url() + "ajax/pagar/",
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
            url: base_url() + "ajax/get_comision/",
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
            url: base_url() + "ajax/pagar_comision/",
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
                    url: base_url() + "ajax/remover_pago/",
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