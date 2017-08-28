import 'datatables.net-dt/css/jquery.dataTables.css';
import 'datatables.net-responsive-dt/css/responsive.dataTables.css';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';
import 'sweetalert/dist/sweetalert.css';

import './configs/serializeObject';

if ($('#mapplic').length > 0) {
    import( /* webpackChunkName: "mapa" */ './mapa/mapplic').catch(err => { console.log(`Sucedio un error al importar el módulo de Mapplic: ${err}`); });
}
import './tables/datatables';
/* 
//Reacomodar

import { phone } from './components/components.js';
phone();
import './tables/pagos';


if ($('#frm-venta').length) {
    require.ensure([], function(require) {
        require("./venta.js");
    });
}
if ($('#frm-reserva').length) {
    require.ensure([], function(require) {
        require('./reserva.js');
    });
}
if ($('#shopCartSale').length) {
    require.ensure([], function(require) {
        var Cart = require('./Cart');
        var cart = new Cart.default();
        cart.get();
    });
}
 */