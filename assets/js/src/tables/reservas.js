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
                var btn_eliminar = '<button class="btn btn-danger">Eliminar</button>';
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
    ]
});