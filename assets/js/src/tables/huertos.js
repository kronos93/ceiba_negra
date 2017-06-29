import { base_url, ajax_msg, multiplicar } from '../utils/util';
import GenericFrm from '../GenericFrm';
import '../configs/datatables';
var huertos_table = $('#huertos-table').DataTable({
    dom: '<"container-fluid" <"row" B> >lfrtip',
    buttons: [{
            extend: 'copy',
            text: 'Copiar al portapapeles',
            /*className: 'btn btn-default',*/
        },
        {
            extend: 'excel',
            text: 'Descargar en formato excel'
        },
        {
            extend: 'pdf',
            text: 'Descargar en formato PDF'
        },
        {
            extend: 'print',
            text: 'Imprimir área visible'
        },

    ],
    "ajax": base_url() + 'ajax/get_huertos_pmz',
    "columns": [ //Atributos para la tabla
        {
            "data": "manzana",
            "render": $.fn.dataTable.render.number(',', '.', 0, 'Mz. ', ''),
            "type": "num-fmt",
        }, {
            "data": "huerto",
            "render": $.fn.dataTable.render.number(',', '.', 0, 'Ht. ', ''),
            "type": "num-fmt",
        }, {
            "data": "superficie",
            "render": $.fn.dataTable.render.number(',', '.', 2, '', ' m<sup>2</sup>'),
            "type": "num-fmt",
        }, {
            "data": "precio_x_m2",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        }, {
            "data": "precio",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        }, {
            "data": "enganche",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        }, {
            "data": "abono",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        }, {
            "data": "vendido", //Supa kawaiesko funcion para el render
            "render": function(data, type, full, meta) {
                if (!parseInt(data)) {
                    return '<span class="label label-primary">No vendido</span>';
                } else {
                    return '<span class="label label-success">Vendido</span>';
                }

            }
        },
        { "data": "col_norte" },
        { "data": "col_noreste" },
        { "data": "col_este" },
        { "data": "col_sureste" },
        { "data": "col_sur" },
        { "data": "col_suroeste" },
        { "data": "col_oeste" },
        { "data": "col_noroeste" },
        { "data": "" } //Espacio extra para el boton o botones de opciones
    ],
    columnDefs: [ //
        {
            //Añadir boton dinamicamente, para esta columna*
            "targets": -1,
            "data": null,
            "defaultContent": '<button data-toggle="modal" data-title="Editar huerto" data-btn-type="edit" data-target="#huertoModal" class="btn btn-info btn-sm pull-right"><i class="fa fa-fw fa-pencil"></i></button>',
        }, {
            //Quitar ordenamiento para estas columnas
            "sortable": false,
            "targets": [-1, -2, -3, -4, -5, -6, -7, -8, -9]
        }, {
            //Quitar busqueda para esta columna
            "targets": [-1, -2, -3, -4, -5],
            "searchable": false,
        }
    ],
    "order": [
        [0, "asc"]
    ],
});
$('#huertoModal').on('show.bs.modal', function(e) {
    //Ocultar mensajes de la caja AJAX
    ajax_msg.hidden();
    var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable)
    var title = button.data('title'); // Extraer informacipon desde atributos data-* 
    var btnType = button.data('btnType');
    var modal = $(this);
    modal.find('.model-title').html(title);

    var config = {
        'frm': '#frm-huerto',
        'urls': { 'edit': 'ajax/update_huerto', 'add': 'ajax/add_huerto' },
        'msgs': { 'edit': 'Huerto actualizado correctamente.', 'add': 'Huerto agregado correctamente.' },
        'autoNumeric': ['superficie', 'precio_x_m2', 'precio'], //A que campos quitarle las comas y signos.
        //'readonly': { 'inputs': '#id_manzana' }, //Que campos son de lectura para agregar y quitar
        'append': ["id_huerto"], //Que campo anexar de dtRow al data a enviar por AJAX
        'btn': button, //Boton que disparó el evento de abrir modal
        'dtTable': huertos_table, //Data table que se parseará
    };
    var genericFrm = new GenericFrm(config);
    genericFrm[btnType]();
});
$('.multiplicar').on('keyup', function() {
    let resultado = multiplicar();
    $('#precio').autoNumeric('set', resultado);
});