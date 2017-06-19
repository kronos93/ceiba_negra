import '../configs/datatables';
import { base_url } from '../utils/util';
var reservas_table = $('#reservas-eliminadas-table').DataTable({
    "ajax": base_url() + 'ajax/get_reservas_eliminadas',
    "columns": [ //Atributos para la tabla
        { "data": "id_reserva_eliminada" },
        { "data": "nombre_lider" },
        { "data": "detalles" },
        { "data": "description" },
        {
            "data": "fecha",
            "render": $.fn.dataTable.render.moment('DD-MM-YYYY')
        },
    ],
    "columnDefs": [ //
        {
            //Quitar ordenamiento para estas columnas
            "sortable": false,
            "targets": [2, 3],
        },
    ],
    "order": [
        [4, "desc"]
    ],
});