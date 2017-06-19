import moment from 'moment';
import '../configs/datatables';
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