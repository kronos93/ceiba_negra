<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <script src="https://code.jquery.com/jquery-2.2.4.js"></script>
    <script type="text/javascript" src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/locale/es.js"></script>
    <script src="http://localhost/ceiba_negra/assets/js/numToWord.js" type="text/javascript"></script>	
    <script type="text/javascript">
        tinymce.init({
            selector: '#contrato',
            mode: 'specifics_textareas',
            editor_selector : 'mceEditor',
            height: '600px',
            plugins: [
			    'advlist autolink lists charmap print preview hr anchor pagebreak',
			    'searchreplace wordcount visualblocks visualchars code fullscreen',
			    'nonbreaking save table directionality',
			    'template paste textcolor colorpicker textpattern'
			  ],
            toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
            content_css: '../assets/css/tinymce.css',
            setup: function (ed) {
                ed.on('init', function(args) {
                    moment.locale('es');
                    var fechas = ['fecha_primer_pago','fecha_ultimo_pago','fecha_init_1','fecha_init_2','fecha_init_3','fecha_init_4'];
                    for(var fecha in fechas){
                        var fecha_tiny = tinymce.activeEditor.dom.get(fechas[fecha]);

                        var fecha_val = $(tinymce.activeEditor.dom.get(fechas[fecha])).html();
                        var fecha_moment = moment(fecha_val,'DD-MM-YYYY');                        

                        this.dom.setHTML(fecha_tiny,fecha_moment.format("[el día ] dddd, DD [de] MMMM [del] [año] YYYY"));
                    }

                    var currencies = ['precio_1','precio_2','enganche','abono_1','abono_2','porcentaje'];
                    for(var currency in currencies){
                        var currency_tiny = tinymce.activeEditor.dom.get(currencies[currency]);
                        var currency_val = $(currency_tiny).html();
                        if(currencies[currency] == 'porcentaje'){
                            var currency_format = NumeroALetras(currency_val).replace(/\b00\/100 MN\b/,'').replace(/\bPeso\b/,'').replace(/\bCON\b/g,'PUNTO').replace(/\bPESOS\b/g,'').replace(/\bCENTAVOS\b/g,'').replace(/\s{2,}/g," ");
                        }else{
                            var currency_format = NumeroALetras(currency_val).replace(/\s{2,}/g," ");
                        }                        
                        this.dom.setHTML(currency_tiny,currency_format);
                    }
                });
            }
        });
        
    </script>
</head>
<body>
    <textarea id="contrato"><?= $output ?></textarea>
</body>
</html>