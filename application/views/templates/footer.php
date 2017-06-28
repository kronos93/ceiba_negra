<footer>
    <div class="container-fluid container">
        <div class="row">
            <div class="col-xs-12">
                <p>&#x24B8; Derechos Reservados Huertos La Ceiba 2017</p>
            </div>
        </div>
    </div>
</footer>
<!--<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>-->
<script src="https://code.jquery.com/jquery-2.2.4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mustache.js/2.3.0/mustache.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<!--<script type="text/javascript" src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.1.0/js/dataTables.responsive.min.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.devbridge-autocomplete/1.3.0/jquery.autocomplete.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/autonumeric/2.0.12/autoNumeric.min.js"></script>
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
