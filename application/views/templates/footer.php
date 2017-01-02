	<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/mapplic/dependencies/hammer.min.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/mapplic/dependencies/jquery.mousewheel.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/mapplic/dependencies/script.js"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/mapplic/mapplic.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/es.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.2.27/jquery.autocomplete.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/1.9.46/autoNumeric.js"></script>
	<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
	<script id="template-venta" type="text/html">
		{{#huertos}}
		<li><a href="#">{{descripcion}}{{{btn}}}</a></li>
		{{/huertos}}
		<!-- <li role="separator" class="divider"></li> -->
		<li>
			<ul class="totales">
				<li><a>Total: <label class="currency">{{total}}</label></a></li>
				<li><a>Enganche: <label class="currency">{{enganche}}</label></a></li>
				<li><a>Abonos: <label class="currency">{{abono}}</label></a></li>
			</ul>
		</li>
	</script>
	<script>
		$(document).mouseup(function (e)
		{
			var container = $(".my-dropdown");

			if (!container.is(e.target) // if the target of the click isn't the container...
				&& container.has(e.target).length === 0) // ... nor a descendant of the container
			{
				container.hide();
			}
		});
	</script>
	<script>
	$("#example-basic").steps({
		headerTag: "h3",
		bodyTag: "div",
		transitionEffect: "none",
		autoFocus: true,
		onStepChanging: function (event, currentIndex, newIndex) { 
			console.log('Hola',event)
			return true;//Si retorna Falso Marca error :v
		},
		labels: 
			{
		        cancel: "Cancelar",
		        current: "Paso Actual:",
		        pagination: "Pagination",
		        finish: "Finalizar",
		        next: "Siguiente",
		        previous: "Anterior",
		        loading: "Cargando ..."
		    }
	});
	$('#autocomplete').autocomplete({
	    lookup: function (query, done) {
	        // Do Ajax call or lookup locally, when done,
	        // call the callback and pass your results:
	        var result = {
	            suggestions: [
	                { "value": "United Arab Emirates", "data": "AE" },
	                { "value": "United Kingdom",       "data": "UK" },
	                { "value": "United States",        "data": "US" }
	            ]
	        };

	        done(result);
	    },
	    onSelect: function (suggestion) {
	        alert('You selected: ' + suggestion.value + ', ' + suggestion.data);
	    }
	});
	</script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/numToWord.js?v=<?= date("Y-m-dH:i:s") ?>"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/script.js?v=<?= date("Y-m-dH:i:s") ?>"></script>
	</body>
</html>