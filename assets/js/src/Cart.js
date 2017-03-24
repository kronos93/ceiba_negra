import { base_url } from './utils/util';
import { format_numeric } from './components/components';
class Cart {
    constructor() {
        var that = this;
        $('#shopCartSale').off('click').on('click', function(e) {
            $(this).find('.my-dropdown').slideToggle('3500');
        });
        $('#shopCartSale').find('nav').off('click').on('click', function(e) {
            e.stopPropagation();
        });
        $(document).off('mouseup').on('mouseup', function(e) {
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

        if (response.is_reserva) {
            localStorage.setItem("id_reserva", response.id_reserva);
            localStorage.setItem("id_lider", response.id_lider);
            localStorage.setItem("nombre_lider", response.nombre_lider);
            localStorage.setItem("nombre_cliente", response.nombre_cliente);
            localStorage.setItem("apellidos_cliente", response.apellidos_cliente);
            localStorage.setItem("email_cliente", response.email_cliente);
            localStorage.setItem("phone_cliente", response.phone_cliente);
            localStorage.setItem("comment", response.comment);
            localStorage.setItem("is_reserva", true);
        } else {
            localStorage.removeItem("id_reserva");
            localStorage.removeItem("id_lider");
            localStorage.removeItem("nombre_lider");
            localStorage.removeItem("nombre_cliente");
            localStorage.removeItem("apellidos_cliente");
            localStorage.removeItem("email_cliente");
            localStorage.removeItem("phone_cliente");
            localStorage.removeItem("comment");
            localStorage.setItem("is_reserva", false);
        }

        if ($('#frm-venta #precio').length && $('#frm-venta #enganche').length && $('#frm-venta #abono').length && $('#comision').length) {
            //Esto esta aquí por si se cambia el carrito dinamicamente se actualizen los datos
            //Dinamicamente,
            /////////////////////////////////////////////////////////////////////////
            $('#frm-venta #precio').autoNumeric('set', localStorage.getItem("precio"));
            $('#frm-venta #enganche').autoNumeric('set', localStorage.getItem("enganche"));
            $('#frm-venta #abono').autoNumeric('set', localStorage.getItem("abono"));

            var porcentaje_comision = $('#porcentaje_comision').val();
            $('#frm-venta #comision').autoNumeric('set', (porcentaje_comision / 100) * $('#precio').autoNumeric('get'));
            ///////////////////////////////////////////////////////////////////////////////
            if (response.is_reserva) {
                ///////////////////////////////////////////////////////////////////////////
                $('#first_name').val(localStorage.getItem("nombre_cliente"));
                $('#last_name').val(localStorage.getItem("apellidos_cliente"));
                $('#id_lider').val(localStorage.getItem("id_lider"));
                $('#lideres_autocomplete').val(localStorage.getItem("nombre_lider"));
                $('#email').val(localStorage.getItem("email_cliente"));
                $('#phone').val(localStorage.getItem("phone_cliente"));
                if (localStorage.getItem("comment")) {
                    $('#comments').show().html(localStorage.getItem("comment"));
                } else {
                    $('#comments').hide().html("");
                }
                $('#id_reserva').val(localStorage.getItem("id_reserva"));
                ///////////////////////////////////////////////////////////////////////////////
            } else {
                $('#first_name').val("");
                $('#last_name').val("");
                $('#id_lider').val("");
                $('#lideres_autocomplete').val("");
                $('#email').val("");
                $('#phone').val("");
                $('#comments').hide().empty();
                $('#id_reserva').val("");
            }
        } else if ($('#frm-reserva #precio').length && $('#frm-reserva #enganche').length && $('#frm-reserva #abono').length) {
            $('#frm-reserva #precio').autoNumeric('set', localStorage.getItem("precio"));
            $('#frm-reserva #enganche').autoNumeric('set', localStorage.getItem("enganche"));
            $('#frm-reserva #abono').autoNumeric('set', localStorage.getItem("abono"));
        }



        //Validar cuando se vacie el carrito
        if (!response.count) {
            var uri = window.location.pathname;
            var uri_split = uri.split('/');
            var name_uri = uri_split[uri_split.length - 1];
            if (name_uri == 'venta' || name_uri == 'reserva') {
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