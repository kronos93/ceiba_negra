var base_url = 'http://' + window.location.hostname + '/ceiba_negra/';
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


    var mapplic = $('#mapplic').mapplic({
        source: base_url + 'ajax/get_mapa',	// Using mall.json file as map data
        sidebar: true, 			// Enable sidebar
        minimap: false, 			// Enable minimap
        markers: false, 		// Disable markers
        mapfill:true,
        fillcolor:'',
        fullscreen: false, 		// Enable fullscreen
        developer:true,
        zoom:false
        // maxscale: 3, 			// Setting maxscale to 3
    });
    /*mapplic.on('mapready',function(){
        console.log("Listo");
        $('.mapplic-layer').on('click',function(){
            console.log(this);
            console.log($(".mapplic-coordinates-x")[0].innerHTML);
            console.log($(".mapplic-coordinates-y")[0].innerHTML);
        })    
    });*/

    mapplic.on('locationopened',function(e,location){
        /*console.log(JSON.stringify(location));
        console.log(location.id);
        console.log(location.x);
        console.log(location.y);*/
       var manzana = (location.category.replace("mz",""));
        var lote = (location.title.replace("Lote número ",""));
        
        var data = {
            manzana : manzana,
            lote : lote,
            x : ($(".mapplic-coordinates-x")[0].innerHTML),
            y : $(".mapplic-coordinates-y")[0].innerHTML
        };
        console.log(data);
        $.ajax({
            url: base_url+"ajax/guardar_coordenadas/",
            type: 'post',
            asyn: true,
            data: data
        });
        
    });
	
});