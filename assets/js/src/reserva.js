import { base_url } from './utils/util';
import 'jquery-validation/dist/jquery.validate'
import 'jquery-mask-plugin/dist/jquery.mask';
import './configs/validator';
var form_reserva = $("#frm-reserva");
form_reserva.validate({
    errorPlacement: function errorPlacement(error, element) { element.before(error); },
    lang: 'es'
});
form_reserva.on('submit', function(e) {
    e.preventDefault();
    var data = $(this).serializeObject();
    data.phone = $('#phone').cleanVal();
    data.precio = $('#precio').autoNumeric('get');
    data.enganche = $('#enganche').autoNumeric('get');
    data.abono = $('#abono').autoNumeric('get');
    form_reserva.validate().settings.ignore = ":disabled,:hidden";
    if (form_reserva.valid()) {
        swal({
                title: "¿Desea guardar los datos?",
                text: "Reservar huertos",
                type: "info",
                showCancelButton: true,
                cancelButtonText: "CANCELAR",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            },
            function() {
                $.ajax({
                        data: data,
                        url: base_url() + "reserva/guardar/",
                        async: true,
                        type: 'post',
                        beforeSend: function() {}
                    }).done(function(response) {
                        localStorage.removeItem("precio");
                        localStorage.removeItem("enganche");
                        localStorage.removeItem("abono");
                        if (response.status != null && response.status != undefined != "" && response.status == 200) {
                            swal({
                                    title: "¡Huertos reservados!",
                                    text: "Pulsar el boton continuar.",
                                    type: "success",
                                    /*confirmButtonColor: "#DD6B55",*/
                                    confirmButtonText: "¡CONTINUAR!",
                                    closeOnConfirm: false
                                },
                                function() {
                                    window.location.href = base_url() + 'registros/reservas/';
                                });
                        } else {
                            sweetAlert("¡Error!", "Algo salió mal, contactar al administrador sí el problema persiste.<p>Error:</p>" + response.responseText, "error");
                        }

                    })
                    .fail(function(response) {
                        swal({
                            title: "¡Error!",
                            text: "Algo salió mal, contactar al administrador sí el problema persiste.<p>Error:</p>" + response.responseText,
                            type: "error",
                            html: true
                        });
                    });
            });
    } else {
        console.log('No Enviar');
    }
});