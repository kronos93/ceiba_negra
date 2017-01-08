<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script type="text/javascript" src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/es.js"></script>	
    <script type="text/javascript">
        tinymce.init({
            selector: '#contrato',
        });
    </script>
</head>
<body>
    <textarea id="contrato"><?= $output ?></textarea>
    <script type="text/javascript">
        /*console.log(tinymce.activeEditor.getContent());
        //console.log(tinymce.editors[$('#fecha_primer_pago')].getContent());
        console.log(tinymce.get('contrato').getContent());
*/
        console.log(tinymce);
        moment.locale('es');
		var fecha = moment();
		console.log(fecha.format('YYYY-MM-DD'));
		
		console.log("el día " + fecha.format("dddd, DD \\d\\e MMMM \\d\\e\\l \\a\\ñ\\o YYYY"));
    </script>
</body>
</html>