import { base_url, ajax_msg } from './utils/util';
import GenericFrm from './GenericFrm';
//MANZANAS
//Estructura de Datatable para las Manzanas (La tabla de vista)
var manzanas_table = $('#manzanas-table').DataTable({
    "ajax": base_url() + 'ajax/get_manzanas', //URL de datos
    "columns": [ //Atributos para la tabla
        {
            "data": "id_manzana",
            "type": "num",
        }, {
            "data": "manzana",
            "render": $.fn.dataTable.render.number(',', '.', 0, 'Mz. ', ''),
            "type": "num-fmt",
        },
        { "data": "calle" }, {
            "data": "superficie",
            "render": $.fn.dataTable.render.number(',', '.', 2, '', ' m<sup>2</sup>'),
            "type": "num-fmt",
        }, {
            "data": "disponibilidad",
            //Supa kawaiesko funcion para el render
            "render": function(data, type, full, meta) {
                if (!parseInt(data)) {
                    return '<span class="label label-primary">No disponible</span>';
                } else {
                    return '<span class="label label-success">Disponible</span>';
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
    columnDefs: [ //Configuracion de la tabla de manzanas
        {
            //Añadir boton dinamicamente, para esta columna*
            "targets": -1,
            "data": null,
            "defaultContent": '<button data-toggle="modal" data-target="#manzanaModal" data-title="Editar manzana" data-btn-type="edit" class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>',
        }, {
            //Quitar ordenamiento para estas columnas
            "sortable": false,
            "targets": [-1, -2, -3, -4, -5, -6, -7, -8, -9, -12]
        }, {
            //Quitar busqueda para esta columna
            "searchable": false,
            "targets": [-1]
        }
    ],
    "order": [
        [1, "asc"]
    ],
});
$('#manzanaModal').on('show.bs.modal', function(e) {
    //Ocultar mensajes de la caja AJAX
    ajax_msg.hidden();
    var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable)
    var title = button.data('title'); // Extraer informacipon desde atributos data-* 
    var btnType = button.data('btnType');
    var modal = $(this);
    modal.find('.model-title').html(title);

    //console.log(btnType);
    var config = {
        'frm': '#frm-manzana',
        'urls': { 'edit': 'ajax/update_manzana', 'add': 'ajax/add_manzana' },
        'msgs': { 'edit': 'Manzana actualizada correctamente.', 'add': 'Manzana agregada correctamente.' },
        'autoNumeric': ["superficie"], //A que campos quitarle las comas y signos.
        'readonly': { 'inputs': '#manzana' }, //Que campos son de lectura para agregar y quitar
        'append': ["id_manzana"], //Que campo anexar de dtRow al data a enviar por AJAX
        'btn': button, //Boton que disparó el evento de abrir modal
        'dtTable': manzanas_table, //Data table que se parseará
    };
    var genericFrm = new GenericFrm(config);
    genericFrm[btnType]();
});