var base_url = 'http://' + window.location.hostname + '';
var lang_esp_datatables = "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json";
$(document).ready(function() {
    var manzanas_table = $('#manzanas').DataTable({
    	"ajax":  base_url + 'ajax/get_manzanas',
    	"language": {
    					"url": lang_esp_datatables
				    },
    	columnDefs: [ 	
						{
                			"targets": 0,
                			"visible": false
            			},
						{
							"targets": 4,
							"data": null,
							"defaultContent": '<button class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>'
						}
					]
    });
	$('#manzanas tbody').on( 'click', 'button', function () {
        var manzana = manzanas_table.row( $(this).parents('tr') ).data();
        var id_manzana = manzana[0];
		console.log(id_manzana);
    });

    $('#lotes').DataTable({
        "ajax":  base_url + 'ajax/get_lotes_pmz',
        "language": {
                        "url": lang_esp_datatables
                    },
        columnDefs: [   
                        {
                            "targets": 0,
                            "visible": false
                        },
                        {
                            "targets": [4,5,6],
                            "className": "moneda"
                        },
                        /*{
                            "targets": 4,
                            "data": null,
                            "defaultContent": '<button class="btn btn-info btn-sm"><i class="fa fa-fw fa-pencil"></i></button>'
                        }*/
                    ]
    });
    /*$('#lotes_table tbody').on( 'click', 'button', function () {
        var manzana = manzanas_table.row( $(this).parents('tr') ).data();
        var id_manzana = manzana[0];
        console.log(id_manzana);
    });*/



    $('tbody .moneda').mask('$ 000.000.000.000.000,00');
});