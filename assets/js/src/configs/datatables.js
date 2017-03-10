//Preconfiguración de los datatable
$.extend(true, $.fn.dataTable.defaults, {
    "pagingType": "full_numbers",
    "language": {
        "url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json",
        "decimal": ".",
        "thousands": ","
    },
    /*"search": {
        "caseInsensitive": false
    },*/
    "responsive": true,
    "deferRender": true,
    "pageLength": 25,
});