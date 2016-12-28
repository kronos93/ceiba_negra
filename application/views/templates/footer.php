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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/autonumeric/1.9.46/autoNumeric.js"></script>
	<script id="template-venta" type="text/html">
		{{#huertos}}
		<li><a href="#">{{.}}</a></li>
		{{/huertos}}
		<li role="separator" class="divider"></li>
		<li><a>Total: {{total}}</a></li>
		<li><a>Enganche: {{enganche}}</a></li>
		<li><a>Abonos: {{abono}}</a></li>
	</script>
	<script type="text/javascript" src="<?= base_url() ?>assets/js/script.js?v=<?= date("Y-m-dH:i:s") ?>"></script>
    </body>
</html>