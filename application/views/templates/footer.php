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
	<script type="text/javascript" src="http://cdn.jsdelivr.net/jquery.validation/1.15.0/jquery.validate.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/es.js"></script>	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/1.9.46/autoNumeric.js"></script>
	<script src="http://code.jquery.com/ui/1.12.0/jquery-ui.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.2.27/jquery.autocomplete.min.js"></script>
	<script src="<?= base_url() ?>assets/js/datepicker-es.js"></script>
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
	jQuery.extend(jQuery.validator.messages, {
		required: "Este campo es obligatorio.",
		remote: "Por favor, rellena este campo.",
		email: "Por favor, escribe una dirección de correo válida",
		url: "Por favor, escribe una URL válida.",
		date: "Por favor, escribe una fecha válida.",
		dateISO: "Por favor, escribe una fecha (ISO) válida.",
		number: "Por favor, escribe un número entero válido.",
		digits: "Por favor, escribe sólo dígitos.",
		creditcard: "Por favor, escribe un número de tarjeta válido.",
		equalTo: "Por favor, escribe el mismo valor de nuevo.",
		accept: "Por favor, escribe un valor con una extensión aceptada.",
		maxlength: jQuery.validator.format("Por favor, no escribas más de {0} caracteres."),
		minlength: jQuery.validator.format("Por favor, no escribas menos de {0} caracteres."),
		rangelength: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1} caracteres."),
		range: jQuery.validator.format("Por favor, escribe un valor entre {0} y {1}."),
		max: jQuery.validator.format("Por favor, escribe un valor menor o igual a {0}."),
		min: jQuery.validator.format("Por favor, escribe un valor mayor o igual a {0}.")
	});
	var form = $("#example-basic");
	form.validate({
		errorPlacement: function errorPlacement(error, element) { element.before(error); },
		lang: 'es'
	});
	form.steps({
		headerTag: "h3",
		bodyTag: "div",
		transitionEffect: "none",
		autoFocus: true,
		onStepChanging: function (event, currentIndex, newIndex)
		{
			form.validate().settings.ignore = ":disabled,:hidden";
			return form.valid();
		},
		onFinishing: function (event, currentIndex)
		{
			form.validate().settings.ignore = ":disabled";
			return form.valid();
		},
		onFinished: function (event, currentIndex)
		{
			alert("Submitted!");
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
	</script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/numToWord.js?v=<?= date("Y-m-dH:i:s") ?>"></script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/script.js?v=<?= date("Y-m-dH:i:s") ?>"></script>
	</body>
</html>