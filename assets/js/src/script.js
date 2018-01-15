import 'datatables.net-dt/css/jquery.dataTables.css';
import 'datatables.net-responsive-dt/css/responsive.dataTables.css';
import 'datatables.net-buttons-dt/css/buttons.dataTables.css';
import 'sweetalert/dist/sweetalert.css';
import './configs/serializeObject';
//Implementación del carrito
//Se contempla que el carrito tiene format_init como primera función
if ($('#shopCartSale').length > 0) {
  import(/* webpackChunkName: "shop_cart" */ './Cart')
    .then((Cart) => {
      let cart = new Cart.default();
      cart.get(); //Siempre que se encuentre el carrito llamar a la función get()
    })
    .catch(err => { console.log(`Sucedio un error al importar el módulo de Carrito: ${err}`); });
}
if ($('#mapplic').length > 0) {
  import( /* webpackChunkName: "mapa" */ './mapa/mapplic').catch(err => { console.log(`Sucedio un error al importar el módulo de Mapplic: ${err}`); });
  import( /* webpackChunkName: "fullscreen-mapa" */ './mapa/fullscreen').catch(err => { console.log(`Sucedio un error al importar el módulo de fullscreen mapa: ${err}`); });
}
import './tables/datatables';

if ($('#frm-venta').length > 0) {
  import( /* webpackChunkName: "frm_venta" */ './venta').catch(err => { console.log(`Sucedio un error al importar el módulo de Venta: ${err}`); });
}
if ($('#frm-reserva').length > 0) {
  import( /* webpackChunkName: "frm_reserva" */ './reserva').catch(err => { console.log(`Sucedio un error al importar el módulo de Reservas: ${err}`); });
}
