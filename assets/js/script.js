var base_url = 'http://' + window.location.hostname + '/ceiba_negra/';;

$(document).ready(function() {
    $('#example').DataTable({
    	"ajax":  base_url + 'ajax/get_manzanas',
    	"language": {
    					"url": "https://cdn.datatables.net/plug-ins/1.10.12/i18n/Spanish.json"
				        /*"lengthMenu": "Mostrar _MENU_ lotes por página",
				        "zeroRecords": "Sin resultados - Lo sentimos",
				        "info": "Mostrando _PAGE_ paginas de _PAGES_",
				        "infoEmpty": "No records available",
				        "infoFiltered": "(filtered from _MAX_ total records)",
				        "search": "_INPUT_",
				    	"searchPlaceholder": "Buscar...",
				    	paginate: {
				            previous: 'Anterior',
				            next:     'Siguiente'
						   }*/
				    },
    	columnDefs: [ { orderable: false, targets: [4]}]
    });
    $('#example_filter input').addClass('form-control');

    // $('.open-popup-link').modal('show');
} );