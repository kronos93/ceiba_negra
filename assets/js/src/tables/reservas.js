import '../configs/datatables';
import 'sweetalert';
import { base_url, ajax_msg } from '../utils/util';
import GenericFrm from '../GenericFrm';
import Cart from '../Cart';
var cart = new Cart();
var reservas_table = $('#reservas-table').DataTable({
    "ajax": base_url() + 'ajax/get_reservas',
    "columns": [ //Atributos para la tabla
        { "data": "id_reserva" },
        { "data": "nombre_lider" },
        { "data": "descripcion" },
        { "data": "detalles" },
        {
            "data": "expira",
            "render": $.fn.dataTable.render.moment('DD-MM-YYYY')
        },
        {
            "data": "",
            "render": function(data, type, full, meta) {
                var btn_eliminar = '<button class="btn btn-danger cancelar-reserva">Eliminar</button>';
                var btn_vender = '<button class="btn btn-primary aplicar-reserva">Aplicar</button>';
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
            "targets": [-1, 3],
        },
    ]
});
var reservaDtRow;
$('#reservas-table').on('click', '.cancelar-reserva', function() {
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

$('#reservas-table').on('click', '.aplicar-reserva', function() {
    reservaDtRow = $(this).closest('tr').hasClass('child') ?
        $(this).closest('tr').prev('tr.parent') :
        $(this).parents('tr');
    var parseDtRow = reservas_table.row(reservaDtRow).data();
    var data = {
        id_reserva: parseDtRow.id_reserva
    };

    swal({
            title: "Aplicar reserva",
            text: "Esta a punto de aplicar una reserva, se limpiará el carrito ¿Desea continuar?",
            type: "info",
            showCancelButton: true,
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            cancelButtonText: 'CANCELAR',
        },
        function() {
            $.ajax({
                    url: base_url() + "ajax/aplicar_reserva/",
                    data: data,
                    type: "post",
                })
                .done(function(response) {
                    var newData = reservas_table.row(reservaDtRow).data(parseDtRow).draw(false).node(); //
                    $(newData).css({ backgroundColor: 'yellow' }); //Animación para MAX
                    cart.get();
                    swal("Hechó", "¡Reserva aplicada!", "success");
                })
                .fail(function(response) {
                    swal("¡Error!", "La operación que intentó realizar ha fallado, contactar al administador sí el error persiste", "error");
                });
        });
});