tinymce.init({
    selector: '#contrato_html',
    mode: 'specifics_textareas',
    editor_selector: 'mceEditor',
    height: '600px',
    plugins: [
        'advlist autolink lists charmap print preview hr anchor pagebreak',
        'searchreplace wordcount visualblocks visualchars code fullscreen',
        'nonbreaking save table directionality',
        'template paste textcolor colorpicker textpattern'
    ],
    toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
    content_css: base_url() + '/assets/css/tinymce.css',
    setup: function(ed) {
        ed.on('init', function(args) {
            //console.log(this);
            // this ==  tinymce.activeEditor;
            //tinyMCE.activeEditor.dom.select('.fecha_init'); Trae datos por clase probar después

        });
    },
    init_instance_callback: function(editor) {
        editor.on('SetContent', function(e) {
            console.log("Asignando contenido dinamico");
            /*console.log(this.dom.select('.fecha_init'));
            console.log(tinymce.activeEditor.dom.select('.fecha_init'));*/
            var fechas = ['fecha_primer_pago', 'fecha_ultimo_pago', 'fecha_init_1', 'fecha_init_2', 'fecha_init_3', 'fecha_init_4', 'fecha_init_5'];
            for (var fecha in fechas) {
                var fecha_tiny = this.dom.get(fechas[fecha]);
                var fecha_val = $(fecha_tiny).html();
                var fecha_moment = moment(fecha_val, 'DD-MM-YYYY');
                this.dom.setHTML(fecha_tiny, fecha_moment.format("[el día ] dddd, DD [de] MMMM [del] [año] YYYY"));
            }

            var currencies = ['precio_1', 'precio_2', 'enganche', 'abono_1', 'abono_2', 'porcentaje'];
            for (var currency in currencies) {
                var currency_tiny = this.dom.get(currencies[currency]);
                var currency_val = $(currency_tiny).html();
                if (currencies[currency] == 'porcentaje') {
                    var currency_format = NumeroALetras(currency_val).replace(/\b00\/100 MN\b/, '').replace(/\bPeso\b/, '').replace(/\bCON\b/g, 'PUNTO').replace(/\bPESOS\b/g, '').replace(/\bCENTAVOS\b/g, '').replace(/\s{2,}/g, " ");
                } else {
                    var currency_format = NumeroALetras(currency_val).replace(/\s{2,}/g, " ");
                }
                this.dom.setHTML(currency_tiny, currency_format);
            }
        });
    }
});