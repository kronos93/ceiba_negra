import { base_url } from './utils/util';
import { format_numeric } from './components/components';
class Cart {
    constructor() {
        var that = this;
        $('#shopCartSale').off('click').on('click', function(e) {
            $(this).find('.my-dropdown').slideToggle('3500');
        });
        $('#shopCartSale').find('nav').on('click', function(e) {
            e.stopPropagation();
        });
        $(document).mouseup(function(e) {
            var container = $(".my-dropdown");
            if (!container.is(e.target) // if the target of the click isn't the container...
                &&
                container.has(e.target).length === 0) // ... nor a descendant of the container
            {
                container.hide();
            }
        });
    }
    get() {
        var that = this;
        $.get(base_url() + "ajax/add_cart", function(response) {
            that.templateCart(response);
        });
    }
    delete(rowid) {
        var that = this;
        $.ajax({
                url: base_url() + "ajax/delete_cart/",
                data: { rowid: rowid },
                type: "post",
            })
            .done(function(response) {
                that.templateCart(response);
            })
            .fail(function(response) {

            });
    }
    templateCart(response) {
        var that = this;
        $("#shopCartSale")
            .find('span')
            .attr("data-venta", response.count);
        var template = document.getElementById('template-venta').innerHTML;
        var output = Mustache.render(template, response);
        document.getElementById("listaVenta").innerHTML = output;
        format_numeric('init');
        localStorage.setItem("precio", response.total);
        localStorage.setItem("enganche", response.enganche);
        localStorage.setItem("abono", response.abono);


        if ($('#frm-venta #precio').length && $('#frm-venta #enganche').length && $('#frm-venta #abono').length && $('#comision').length) {

            $('#frm-venta #precio').autoNumeric('set', localStorage.getItem("precio"));
            $('#frm-venta #enganche').autoNumeric('set', localStorage.getItem("enganche"));
            $('#frm-venta #abono').autoNumeric('set', localStorage.getItem("abono"));
            ///////////////////////////////////////////////////////////////////////////
            var porcentaje_comision = $('#porcentaje_comision').val();
            $('#comision').autoNumeric('set', (porcentaje_comision / 100) * $('#precio').autoNumeric('get'));

        }



        //Validar cuando se vacie el carrito
        if (!response.count) {
            var uri = window.location.pathname;
            var uri_split = uri.split('/');
            var name_uri = uri_split[uri_split.length - 1];
            if (name_uri == 'venta') {
                window.location.href = base_url();
            }
        };
        $('.itemCartDelete').off('click').on('click', function(e) {
            var rowid = $(this).val();
            that.delete(rowid)
            e.stopPropagation();
        });
    }
}
export default Cart;