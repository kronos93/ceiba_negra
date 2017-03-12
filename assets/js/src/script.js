import $ from 'jquery';
import { base_url } from './utils/util';
import './config';

import Cart from './Cart';


import './mapplic';

import './tables/manzanas';
import './tables/huertos';
import './tables/opciones_ingreso';
import './tables/historial_ventas';
import './tables/pagos';
import './tables/usuarios';

var cart = new Cart();
cart.get();
if ($('#frm-venta').length) {
    require.ensure([], function(require) {
        require("./venta.js");
    });
}






if (module.hot) {
    module.hot.accept();
}