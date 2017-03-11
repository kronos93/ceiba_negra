import { base_url } from './utils/util.js';
import { format_numeric } from './components/components';
(function() {
    if ($('#mapplic').length) {
        require.ensure([], function(require) {
            require("../mapplic/mapplic.js");
            console.log('dev');
            var mapplic = $('#mapplic').mapplic({
                source: base_url() + 'ajax/get_mapa', // Using mall.json file as map data
                sidebar: true, // hahilita Panel izquierdo
                minimap: false, // Enable minimap
                markers: false, // Deshabilita Marcadores
                // hovertip: false, //Activa o desactiba tooltip en hover
                mapfill: true,
                fillcolor: '',
                fullscreen: true, // Enable fullscreen
                developer: true,
                zoom: false,
                maxscale: 2, // Setting maxscale to 3
                smartip: false,
                deeplinking: false, //inhabilita nombres en uri,

            });
            $(mapplic).on('locationopened', function(e, location) {
                format_numeric('init');
            });
            //Herramienta para capturar las coordenadas del mapa
            /*$(mapplic).on('locationopened', function(e, location) {
                var manzana = (location.category.replace("mz", ""));
                var lote = (location.title.replace("Huerto ", ""));
                var data = {
                    manzana: manzana,
                    lote: lote,
                    x: ($(".mapplic-coordinates-x")[0].innerHTML),
                    y: $(".mapplic-coordinates-y")[0].innerHTML
                };
                console.log(data);
                $.ajax({
                    url: base_url() + "ajax/guardar_coordenadas/",
                    type: 'post',
                    asyn: true,
                    data: data
                });
            });
            });*/
        }, "custom-chunk-name");

    }

})();