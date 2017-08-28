
import 'jquery-mask-plugin/dist/jquery.mask';
import { phone } from '../components/components';
import { base_url, ajax_msg } from '../utils/util';
import GenericFrm from '../GenericFrm';
$(function() {
    //USUARIOS
    var users_table = $('#users-table').DataTable({
        "initComplete": function(settings, json) {
            phone();
        },
        "columns": [ //Atributos para la tabla
            {
                "data": "id",
                "type": "num",
            },
            { "data": "name" },
            { "data": "email" },
            { "data": "phone" },
            { "data": "groups" },
            { "data": "btn_activar_desactivar" },
            { "data": "btn_editar" },
        ],
        columnDefs: [ //
            {
                //Quitar ordenamiento para estas columnas
                "sortable": false,
                "targets": [-1, 2, 3, 4, 5],
            }, {
                //Quitar busqueda para esta columna
                "targets": [],
                "searchable": false,
            }
        ],
        "order": [
            [1, "asc"]
        ],
    });
    $('#userModal').on('shown.bs.modal', function(e) {
        console.log($("#frm-ion-user"));
        //Ocultar mensajes de la caja AJAX
        ajax_msg.hidden();
        var button = $(e.relatedTarget); // Boton que despliega el modal (Existe en el datatable
        var btnType = button.data('btnType');
        var config = {
            'frm': '#frm-ion-user',
            'urls': { 'edit': 'ajax/update_ion_user', 'add': 'ajax/add_ion_user' },
            'msgs': { 'edit': 'Usuario actualizado correctamente.', 'add': 'Usuario agregado correctamente.' },
            //'autoNumeric': ['superficie','precio_x_m2','precio'], //A que campos quitarle las comas y signos.
            //'readonly': { 'inputs': '#id_manzana' }, //Que campos son de lectura para agregar y quitar
            //'append': ["id_huerto"], //Que campo anexar de dtRow al data a enviar por AJAX
            'btn': button, //Boton que disparó el evento de abrir modal
            'dtTable': users_table, //Data table que se parseará
        };
        var genericFrm = new GenericFrm(config);
        if (btnType) {
            genericFrm.edit = function() {
                this.url = this.urls.edit;
                this.msg = this.msgs.edit;
                this.fnOnDone = this.ajaxEditDone;
            };
            genericFrm[btnType]();
        }
    }).on('hidden.bs.modal', function() {
        $(this).removeData('bs.modal');
    });
});