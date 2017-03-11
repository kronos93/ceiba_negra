import $ from 'jquery';
import { base_url } from './utils/util';
import './config';

import './mapplic';

import './tables/manzanas';
import './tables/huertos';
import './tables/opciones_ingreso';
import './tables/historial_ventas';
import './tables/pagos';
import './tables/usuarios';

import './venta';

import Cart from './Cart';

var cart = new Cart();
cart.get();

if (module.hot) {
    module.hot.accept();
}