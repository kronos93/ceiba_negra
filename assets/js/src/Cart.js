import { base_url, format_numeric } from './utils/util';
class Cart {
    constructor() {
        var that = this;
        $('#shopCartSale').off('click').on('click', function(e) {
            $(this).find('.my-dropdown').slideToggle('3500');

        });


    }
    get() {
        var that = this;
        $.get(base_url() + "ajax/add_cart", function(response) { that.templateCart(response) });
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