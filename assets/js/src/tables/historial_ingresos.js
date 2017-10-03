/* import '../configs/datatables'; */
import 'jquery-mask-plugin/dist/jquery.mask';
import 'jquery-ui/ui/widgets/datepicker';
import '../configs/datepicker';
import moment from 'moment';
import { datepicker } from '../components/components';
datepicker();
$.fn.dataTable.moment('DD-MM-YYYY');
$('#historial-ingresos-table').DataTable({
    columnDefs: [ //
        {
            //Quitar ordenamiento para estas columnas
            "sortable": false,
            "targets": [0]
        },
        {
            "type": "html-num-fmt",
            "targets": [2, 3, 4, 5]
        },
    ],
    "order": [
        [1, "asc"]
    ],
});
$('#frm-filter-ingreso').on('submit', function(e) {
    e.preventDefault();
    let init_date = $('#init_date').val();
    let end_date = $('#end_date').val();
    let url = this.action;
    window.location.href = url + '/' + init_date + '/' + end_date;

});
$('a[data-toggle=popover]').popover({
    'html': true,
    trigger: 'manual'
});
$('a[data-toggle=popover]').on('click', function(e) {

    $(this).popover('toggle');
    $('.popover').on('click', function(e) {
        e.stopPropagation();
    });
    e.stopPropagation();
});
$('a[data-toggle=popover]').on('shown.bs.popover', function(e) {
    console.log("show" + this);
    $('a[data-toggle=popover]').not(this).popover('hide');
});
