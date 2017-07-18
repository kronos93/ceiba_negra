import 'datatables.net-dt/css/jquery.dataTables.css';
import 'datatables.net-responsive-dt/css/responsive.dataTables.css';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';
import 'sweetalert/dist/sweetalert.css';

import $ from 'jquery';
import { base_url } from './utils/util';
import './config';
import { format_numeric } from './components/components'; //Temporal
format_numeric('init'); //Temporal
if ($('#mapplic').length > 0) {
    //Mapplic funciona con JQuery > v. 3.0
    require.ensure([], function(require) {
        require('./mapplic');
    });
}
//Import script para dt de manzanas
if ($('#manzanas-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/manzanas');
    });
}
//Import script para dt de huertos
if ($('#huertos-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/huertos');
    });
}
//Import script para dt de opciones de pago
if ($('#opciones-pago-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/opciones_de_pago');
    });
}
//Import script para dt de reservas eliminadas
if ($('#reservas-eliminadas-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/reservas_eliminadas');
    });
}
//Import script para dt de reservas reservas
if ($('#reservas-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/reservas');
    });
}
//Import script para dt de ingresos /bancos/caja
if ($('#historial-ingresos-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/historial_ingresos');
    });
}
if ($('#pagos-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/pagos');
    });
}
if ($('#users-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/usuarios');
    });
}

if ($('#historial-ventas-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/historial_ventas');
    });
}
if ($('#opciones-de-ingreso-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/opciones_ingreso');
    });
}
if ($('#comisiones-per-lider-table').length > 0) {
    require.ensure([], function(require) {
        require('./tables/comisiones_per_lider.js');
    });
}
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


if (module.hot) {
    module.hot.accept();
}