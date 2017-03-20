import $ from 'jquery';
import { base_url } from './utils/util';
import './config';

import './mapplic';

import './tables/manzanas';
import './tables/huertos';
import './tables/opciones_ingreso';

import './tables/historial_ventas';
//Reacomodar
import 'jquery-mask-plugin/dist/jquery.mask';
import { phone } from './components/components.js';
phone();
import './tables/pagos';
import './tables/usuarios';

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


if (module.hot) {
    module.hot.accept();
}