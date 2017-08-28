import { base_url, ajax_msg } from '../utils/util';
import GenericFrm from '../GenericFrm';
let opciones_de_pago = $('#opciones-pago-table').DataTable({
    "ajax": base_url() + 'ajax/get_op_pago',
    "columns": [ //Atributos para la tabla
        { "data": "id_precio" },
        {
            "data": "enganche",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        },
        {
            "data": "abono",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        },
    ]
});
$('#opPagoModal').on('show.bs.modal', function(e) {
    //Ocultar mensajes de la caja AJAX
    ajax_msg.hidden();
    var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable)
    var title = button.data('title'); // Extraer informacipon desde atributos data-* 
    var btnType = button.data('btnType');
    var modal = $(this);
    modal.find('.model-title').html(title);
    //console.log(btnType);
    var config = {
        'frm': '#frm-op-pago',
        'urls': {
            /*'edit': 'ajax/update_manzana',*/
            'add': 'ajax/add_op_pago'
        },
        'msgs': {
            /*'edit': 'Manzana actualizada correctamente.',*/
            'add': 'Opción de pago agregada correctamente.'
        },
        'autoNumeric': ["abono", "enganche"], //A que campos quitarle las comas y signos.
        /*'readonly': { 'inputs': '#manzana' }, */ //Que campos son de lectura para agregar y quitar
        /*'append': ["id_manzana"],*/ //Que campo anexar de dtRow al data a enviar por AJAX
        'btn': button, //Boton que disparó el evento de abrir modal
        'dtTable': opciones_de_pago, //Data table que se parseará
    };
    var genericFrm = new GenericFrm(config);
    genericFrm[btnType]();
});