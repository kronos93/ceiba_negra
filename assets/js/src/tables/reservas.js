import { base_url, ajax_msg } from '../utils/util';
import GenericFrm from '../GenericFrm';
var reservas_table = $('#reservas-table').DataTable({
    "ajax": base_url() + 'ajax/get_reservas',
    "columns": [ //Atributos para la tabla
        { "data": "id_reserva" },
        { "data": "nombre_lider" },
        { "data": "descripcion" },
        { "data": "detalles" },
        { "data": "expira" },
        {
            "data": "",
            "render": function(data, type, full, meta) {
                var btn_eliminar = '<button class="btn btn-danger cancelar-venta">Eliminar</button>';
                var btn_vender = '<button class="btn btn-primary">Vender</button>';
                if (full.is_admin == 1) {
                    return btn_eliminar + ' ' + btn_vender;
                } else {
                    return btn_eliminar;
                }
            }
        }
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
            "targets": [-1],
        },
    ]
});
var reservaDtRow;
$('#reservas-table').on('click', '.cancelar-venta', function() {
    reservaDtRow = $(this).closest('tr').hasClass('child') ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
    var parseDtRow = reservas_table.row(reservaDtRow).data();
    var data = {
        id_reserva: parseDtRow.id_reserva
    };
    swal({
            title: "Eliminar reserva",
            text: "Esta a punto de eliminar una reserva, ¿Desea continuar?",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: 'CANCELAR',
        },
        function() {
            $.ajax({
                    url: base_url() + "reserva/eliminar",
                    data: data,
                    type: "post",
                })
                .done(function(response) {
                    reservas_table
                        .row(reservaDtRow)
                        .remove()
                        .draw();
                    var n_reservas = $('#reservas-badge').html();
                    $('#reservas-badge').html(n_reservas - 1);
                    swal("Hechó", "¡Reserva eliminada!", "success");
                })
                .fail(function(response) {
                    swal("¡Error!", "La operación que intentó realizar ha fallado, contactar al administador sí el error persiste", "error");
                });
        });
});