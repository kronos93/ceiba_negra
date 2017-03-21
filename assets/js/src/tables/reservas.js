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
    ],
    columnDefs: [ //
        {
            //Añadir boton dinamicamente, para esta columna*
            "targets": -1,
            "data": null,
            "defaultContent": "",
        },
    ]
});