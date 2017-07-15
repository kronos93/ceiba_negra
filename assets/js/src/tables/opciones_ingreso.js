import '../configs/datatables';
/*import AutoNumeric from 'AutoNumeric/dist/AutoNumeric';*/
import { base_url, ajax_msg } from '../utils/util';
import GenericFrm from '../GenericFrm';
import Inputmask from 'inputmask';
Inputmask({ "mask": "999-9999-9999", 'autoUnmask': true }).mask('.cuenta-banco');
Inputmask({ "mask": "9999-9999-9999-9999", 'autoUnmask': true }).mask('.tarjeta');
//OPCIONES DE INGRESO
var opciones_de_ingreso_table = $('#opciones-de-ingreso-table').DataTable({
    "ajax": base_url() + 'ajax/get_opciones_de_ingreso',
    "columns": [ //Atributos para la tabla
        { "data": "id_opcion_ingreso" },
        { "data": "nombre" },
        {
            "data": "cuenta",
            "render": function(data, type, full, meta) {
                if (parseInt(data) === 0) {
                    return '';
                } else {
                    return Inputmask.format(data, { alias: "999-9999-9999" });
                }
            },
            className: "cuenta-banco"
        },
        {
            "data": "tarjeta",
            "render": function(data, type, full, meta) {
                if (parseInt(data) === 0) {
                    return '';
                } else {
                    return Inputmask.format(data, { alias: "9999-9999-9999-9999" });
                }
            }
        },
        {
            "data": "ingreso",
            "render": $.fn.dataTable.render.number(',', '.', 2, '$'),
            "type": "num-fmt",
        },
        {
            "data": "",
            "render": function(data, type, full, meta) {
                //full.id_opcion_ingreso
                var btnEditar = '<button data-toggle="modal" data-title="Editar opción de ingreso" data-btn-type="edit" data-target="#opcionDeIngresoModal" class="btn btn-info btn-sm pull-right"><i class="fa fa-fw fa-pencil"></i> Editar</button>';
                var btnShowIngresos = '<a href="' + base_url() + 'registros/ingresos/' + full.nombre.toLowerCase() + '/' + full.id_opcion_ingreso + '" data-index="' + full.id_opcion_ingreso + '" class="btn btn-success btn-sm go-to-ingreso"><i class="fa fa-fw fa-search"></i>Ver detalles de ingreso</a>';
                if (full.id_opcion_ingreso === 1 || full.nombre === 'CAJA') {
                    return btnShowIngresos;
                } else {
                    return btnEditar + ' ' + btnShowIngresos;
                }
            }
        },
    ],
    columnDefs: [ //
        {
            //Quitar ordenamiento para estas columnas
            "sortable": false,
            "targets": [1, 5]
        },
    ],
    initComplete: function() {
        let api = this.api();
        let total = api.column(4).data().sum();
        $('#total-op-ingresos').text(total).autoNumeric('init', { currencySymbol: "$" });
    },
});
/*$('#opciones-de-ingreso-table').on('click', '.go-to-ingreso', function(e) {
    e.preventDefault();
    console.log(this);
});*/

$('#opcionDeIngresoModal').on('show.bs.modal', function(e) {
    //Ocultar mensajes de la caja AJAX
    ajax_msg.hidden();
    var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable)
    var title = button.data('title'); // Extraer informacipon desde atributos data-* 
    var btnType = button.data('btnType');
    var modal = $(this);
    modal.find('.model-title').html(title);
    var config = {
        'frm': '#frm-opcion-ingreso',
        'urls': { 'edit': 'ajax/update_opcion_ingreso', 'add': 'ajax/add_opcion_ingreso' },
        'msgs': { 'edit': 'Opción de ingreso actualizada correctamente.', 'add': 'Opción de ingreso agregada correctamente.' },
        'autoNumeric': [], //A que campos quitarle las comas y signos.
        //'readonly': { 'inputs': '#id_manzana' }, //Que campos son de lectura para agregar y quitar
        'append': ["id_opcion_ingreso"], //Que campo anexar de dtRow al data a enviar por AJAX
        'btn': button, //Boton que disparó el evento de abrir modal
        'dtTable': opciones_de_ingreso_table, //Data table que se parseará
    };
    var genericFrm = new GenericFrm(config);
    genericFrm[btnType]();
});