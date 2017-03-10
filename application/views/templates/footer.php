	<footer>
		<div class="container-fluid container">
			<div class="row">
				<div class="col-xs-12">
					<p>&#x24B8; Derechos Reservados Huertos La Ceiba 2017</p>
				</div>
			</div>
		</div>
	</footer>
	<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.3/jquery.mask.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
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
		<li>
			<ul class="totales">
				<li><a>Total: <label class="currency">{{total}}</label></a></li>
				<li><a>Enganche: <label class="currency">{{enganche}}</label></a></li>
				<li><a>Abonos: <label class="currency">{{abono}}</label></a></li>
			</ul>
		</li>
		{{{link}}}
	</script>
	<!--<script type="text/javascript" src="<?= base_url() ?>assets/js/dist/bundle.js"></script>-->
	<script type="text/javascript" src="http://localhost:3030/dist/bundle.js"></script>
	</body>
</html>