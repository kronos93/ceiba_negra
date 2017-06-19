import { base_url } from './utils/util.js';
import $ from 'jquery';
import '../mapplic/mapplic.js';
import { format_numeric } from './components/components';
let mapplic = $('#mapplic').mapplic({
    source: base_url() + 'ajax/get_mapa', // Using mall.json file as map data
    sidebar: true, // hahilita Panel izquierdo
    minimap: false, // Enable minimap
    markers: false, // Deshabilita Marcadores
    mapfill: true,
    fillcolor: '',
    fullscreen: true, // Enable fullscreen
    zoom: false,
    maxscale: 2, // Setting maxscale to 3
    smartip: false,
    deeplinking: false, //inhabilita nombres en uri,
});
//Cuando se abra un popout, asignar función de formato a números y superifice
$(mapplic).on('locationopened', function(e, location) {
    format_numeric('init');
});