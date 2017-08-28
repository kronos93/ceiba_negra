/* import '../configs/datatables'; */
import 'sweetalert';
import { base_url, ajax_msg } from '../utils/util';
import GenericFrm from '../GenericFrm';
import Cart from '../Cart';
import 'jquery-mask-plugin/dist/jquery.mask';
import 'jquery-ui/ui/widgets/datepicker';
import '../configs/datepicker';
import moment from 'moment';
moment.locale('es');

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
                var btn_extender = '<button class="btn btn-warning btn-extender-reserva">Prorroga</button>';
                if (full.is_admin == 1) {
                    return btn_eliminar + ' ' + btn_vender + ' ' + btn_extender;
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
let dtRow;
$('#reservas-table').on('click', '.btn-extender-reserva', function() {
    $('#modalEditarReserva').modal('show');
    dtRow = $(this).closest('tr').hasClass('child') ? $(this).closest('tr').prev('tr.parent') : $(this).parents('tr');

    let data = reservas_table.row(dtRow).data();

    var datepicker = $('#expira');

    datepicker.mask('00-00-0000');
    let expira = moment(data.expira, "YYYY-MM-DD");
    console.log(data.expira);
    console.log(expira);
    datepicker.datepicker({
        dateFormat: "dd-mm-yy",
        "minDate": expira.format('DD-MM-YYYY')
    });
    datepicker.val('').val(expira.format('DD-MM-YYYY'));
    $('#id_reserva').val(data.id_reserva);

});

$('#frm-extender-reserva').on('submit', function(e) {
    e.preventDefault();
    swal({
            title: "¿Esta seguro?",
            text: "Extenderá la fecha de reserva de los huertos",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, continuar",
            cancelButtonText: 'Cancelar',
            closeOnConfirm: false
        },
        function() {
            //swal("Hecho", "Datos actualizados correctamente", "success");
            $.ajax({
                    data: {
                        id_reserva: $('#id_reserva').val(),
                        expira: $('#expira').val(),
                    },
                    url: base_url() + 'ajax/update_reserva',
                    method: 'post',
                })
                .done(function(response) {
                    let newData = reservas_table.row(dtRow).data(response.data).draw().node();
                    $(newData).css({ backgroundColor: 'yellow' });
                    swal("Hecho", "Datos actualizados correctamente", "success");
                    $('#modalEditarReserva').modal('hide');
                })
                .fail(function() {
                    swal("Error", "Ha sucedido un error insperado, contacte al administrador", "error");
                });
        });
});