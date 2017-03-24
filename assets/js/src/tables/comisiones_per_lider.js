import { base_url } from '../utils/util';
var huertos_table = $('#comisiones-per-lider-table').DataTable({
    "ajax": base_url() + 'ajax/get_comision_per_lider',
    "columns": [ //Atributos para la tabla
        {
            "data": "nombre",
        },
        {
            "data": "comision",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        },
        {
            "data": "comisiones_pendientes",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        },
        {
            "data": "comisiones_pagadas",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        },
    ]
});