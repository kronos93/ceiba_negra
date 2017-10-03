import { base_url } from './utils/util';
import NumeroALetras from './utils/NumeroALetras.js';
import 'jquery-mask-plugin';
import 'jquery-ui/ui/widgets/datepicker';
import './configs/datepicker';
import {
  autocompleteSaldosClientes,
  autocompleteClientes,
  autocompleteLideres,
  datepicker,
  phone,
  number,
  format_numeric,
  tabs
} from './components/components.js';

import "jquery-validation";
import { validate_es } from "jquery-validation/dist/localization/messages_es";
import "jquery-steps/build/jquery.steps.min";
import "./configs/validator";

// Core - these two are required :-)
import tinymce from 'tinymce';
import 'tinymce/plugins/advlist/plugin';
import 'tinymce/plugins/autolink/plugin';
import 'tinymce/plugins/lists/plugin';
import 'tinymce/plugins/charmap/plugin';
import 'tinymce/plugins/print/plugin';
import 'tinymce/plugins/preview/plugin';
import 'tinymce/plugins/hr/plugin';
import 'tinymce/plugins/anchor/plugin';
import 'tinymce/plugins/pagebreak/plugin';
import 'tinymce/plugins/searchreplace/plugin';
import 'tinymce/plugins/wordcount/plugin';
import 'tinymce/plugins/visualblocks/plugin';
import 'tinymce/plugins/visualchars/plugin';
import 'tinymce/plugins/code/plugin';
import 'tinymce/plugins/fullscreen/plugin';
import 'tinymce/plugins/nonbreaking/plugin';
import 'tinymce/plugins/save/plugin';
import 'tinymce/plugins/table/plugin';
import 'tinymce/plugins/directionality/plugin';
import 'tinymce/plugins/template/plugin';
import 'tinymce/plugins/paste/plugin';
import 'tinymce/plugins/textcolor/plugin';
import 'tinymce/plugins/colorpicker/plugin';
import 'tinymce/plugins/textpattern/plugin';
import 'tinymce/plugins/noneditable/plugin';
import './configs/es_MX';
import 'tinymce/themes/modern/theme';
import 'tinymce/skins/lightgray/skin.min.css';
//import 'tinymce/skins/lightgray/content.min.css';
import moment from 'moment';
moment.locale('es');
import swal from 'sweetalert';

let form_venta = $("#frm-venta");
form_venta.validate({
  errorPlacement: function errorPlacement(error, element) {
    element.before(error);
  },
  lang: validate_es
});

form_venta.steps({
  headerTag: "h3",
  bodyTag: "div",
  transitionEffect: "slide",
  autoFocus: true,
  saveState: true,
  labels: {
    cancel: "Cancelar",
    current: "Paso actual:",
    pagination: "Pagination",
    finish: "Finalizar",
    next: "Siguiente",
    previous: "Anterior",
    loading: "Cargando ..."
  },
  onInit: function() {
    autocompleteClientes(base_url);
    autocompleteSaldosClientes(base_url);

    phone();
    number();
    datepicker();
    autocompleteLideres(base_url);
    tabs();
    tinymce.init({
      selector: '#contrato_html',
      skin: false,
      mode: 'specifics_textareas',
      //language: 'es_MX',
      editor_selector: 'mceEditor',
      height: '600px',
      plugins: [
          'noneditable',
          'advlist autolink lists charmap print preview hr anchor pagebreak',
          'searchreplace wordcount visualblocks visualchars code fullscreen',
          'nonbreaking save table directionality',
          'template paste textcolor colorpicker textpattern',
      ],
      noneditable_noneditable_class: "mceNonEditable",
      toolbar1: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
      content_css: base_url() + '/assets/css/tinymce.css',
      setup: function(ed) {
          ed.on('init', function(args) {});
      },
      init_instance_callback: function(editor) {
        editor.on('SetContent', function(e) {
          var fechas = tinymce.activeEditor.dom.select('.fecha_txt');
          //var fechas = ['fecha_primer_pago', 'fecha_ultimo_pago', 'fecha_init_1', 'fecha_init_2', 'fecha_init_3', 'fecha_init_4', 'fecha_init_5'];
          fechas.map((fecha) => {
              let fecha_tiny = tinymce.activeEditor.dom.get(fecha);
              let fecha_val = $(fecha_tiny).html();
              let fecha_moment = moment(fecha_val, 'DD-MM-YYYY');
              tinymce.activeEditor.dom.setHTML(fecha_tiny, fecha_moment.format("[el día ] dddd, DD [de] MMMM [del] [año] YYYY"));
              fecha_tiny.classList.remove('fecha_txt');
          });
         //var currencies = ['precio_1', 'precio_2', 'enganche', 'abono_1', 'abono_2', 'porcentaje'];
          var currencies = tinymce.activeEditor.dom.select('.currency_txt');
          currencies.map((currency) => {
              let currency_tiny = tinymce.activeEditor.dom.get(currency);
              let currency_val = $(currency_tiny).html();
              let currency_format = "";
              if (currency_tiny.id != 'porcentaje') {
                  currency_format = new NumeroALetras(currency_val).data.txt.replace(/\s{2,}/g, " ");
              } else {
                  currency_format = new NumeroALetras(currency_val).data.txt.replace(/\b00\/100 MN\b/, '').replace(/\bPeso\b/, '').replace(/\bCON\b/g, 'PUNTO').replace(/\bPESOS\b/g, '').replace(/\bCENTAVOS\b/g, '').replace(/\s{2,}/g, " ");
              }
              tinymce.activeEditor.dom.setHTML(currency_tiny, currency_format);
              currency_tiny.classList.remove('currency_txt');
          });
        });
      }
    });
    format_numeric('init');
    $('#frm-venta #precio').autoNumeric('set', localStorage.getItem("precio"));
    $('#frm-venta #enganche').autoNumeric('set', localStorage.getItem("enganche"));
    $('#frm-venta #abono').autoNumeric('set', localStorage.getItem("abono"));
    if (localStorage.getItem("is_reserva")) {
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
    //////////////////////////////////////////////////////////////////////////////////////
    var porcentaje_comision = $('#porcentaje_comision').val();
    $('#comision').autoNumeric('set', (porcentaje_comision / 100) * $('#precio').autoNumeric('get'));
    ///////////////////////////////////////////////////////////////////////////////////////
    $("#precio").on('change keyup', function() {
      var precio = parseFloat($(this).autoNumeric('get'));
      var porcentaje = parseFloat($('#porcentaje_comision').val());
      var enganche = parseFloat($('#enganche').autoNumeric('get'));
      $('#comision').autoNumeric('set', (porcentaje / 100) * precio);

      if (precio == enganche) {
          console.log('iguales');
          $('.select-periodos').slideUp(300);
          $('#abono').autoNumeric('set', 0);
      } else if (precio < enganche) {
          console.log('menor');
          $('#enganche').autoNumeric('set', precio);
          $('#abono').autoNumeric('set', 0);
          $('.select-periodos').slideUp(300);
      } else {
          console.log('default');
          $('.select-periodos').slideDown(300);
      }
    });
    $("#enganche").on('change keyup', function() {
        var precio = parseFloat($('#precio').autoNumeric('get'));
        var enganche = parseFloat($(this).autoNumeric('get'));
        if (enganche === precio) {
            $('.select-periodos').slideUp(300);
            $('#abono').autoNumeric('set', 0);
        } else if (enganche > precio) {
            $(this).autoNumeric('set', precio);
            $('.select-periodos').slideUp(300);
            $('#abono').autoNumeric('set', 0);
        } else {
            $('.select-periodos').slideDown(300);
        }
    });
    $('#porcentaje_comision').on('change keyup', function() {
        var porcentaje = this.value;
        var precio = $('#precio').autoNumeric('get');
        $('#comision').autoNumeric('set', (porcentaje / 100) * precio);
    });
    $('#comision').on('change keyup', function() {
        var comision = $(this).autoNumeric('get');
        var precio = $('#precio').autoNumeric('get');
        $('#porcentaje_comision').val((100 * (comision / precio)).toFixed(2));
    });
    $("#tipo_historial").on('change', function() {
        var op = $(this).val();
        if (op == 'ini-mes' || op == 'quincena-mes' || op == '1-15') {
            $('#empezar_pago').show();
        } else {
            $('#empezar_pago').hide();
        }
    });
  },
  onStepChanging: function(event, currentIndex, newIndex) {
    var xhr_response = true;
    if (newIndex == 2) {
      tinymce.activeEditor.setContent("");
      var data = {
          'first_name': '',
          'last_name': '',
          'email': '',
          'phone': '',
          'lugar_nacimiento': '',
          'fecha_nacimiento': '',
          'calle': '',
          'no_ext': '',
          'no_int': '',
          'colonia': '',
          'municipio': '',
          'estado': '',
          'ciudad': '',
          'cp': '',
          'testigo_1': '',
          'testigo_2': '',
          'ciudad_expedicion': '',
          'tipo_historial': '',
          'confirmyes': '',
          'confirmno': '',
          'id_lider': '',
          'fecha_init': '',
          'precio': 0,
          'enganche': 0,
          'abono': 0,
          'porcentaje_penalizacion': 0,
          'maximo_retrasos_permitidos': 0
      };
      var op = $('#tipo_historial').val();
      if (op == '1-15' || op == 'quincena-mes' || op == 'fin-mes') {
          var n_pago = $('#n_pago').val();
          data.n_pago = n_pago;
      }

      for (var campo in data) {
          var input = $('#' + campo + '');
          if (!input.hasClass('currency')) {
              data[campo] = input.val();
          } else {
              data[campo] = input.autoNumeric('get');
          }
      }
      $.ajax({
              data: data,
              url: base_url() + "venta/generar_contrato/",
              async: false,
              type: 'post',
              beforeSend: function() {
                  tinymce.activeEditor.setContent("");
              },
              success: function(response) {

              },
          }).done(function(response) {
              //console.log(response.html);
              tinymce.activeEditor.setContent(response.html);
              xhr_response = true;
          })
          .fail(function(response) {
              console.log('fail');
              sweetAlert("¡Error!", "Algo salió mal, contactar al administrador sí el problema persiste.", "error");
              xhr_response = false;
          });

    }
    form_venta.validate().settings.ignore = ":disabled,:hidden";
    if (form_venta.valid()) {
      if (xhr_response) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
    //return true; //Para debug
  },
  onFinishing: function(event, currentIndex) {
    form_venta.validate().settings.ignore = ":disabled";
    return form_venta.valid();
  },
  onFinished: function(event, currentIndex) {
    var data = $(this).serializeObject();
    data.phone = $('#phone').cleanVal();
    data.contrato_html = tinymce.activeEditor.getContent();
    data.precio = $('#precio').autoNumeric('get');
    data.enganche = $('#enganche').autoNumeric('get');
    data.abono = $('#abono').autoNumeric('get');
    swal({
            title: "¿Desea guardar los datos?",
            text: "Generar contrato",
            type: "info",
            showCancelButton: true,
            cancelButtonText: "CANCELAR",
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        },
        function() {
            $.ajax({
                  data: data,
                  url: base_url() + "venta/guardar_contrato/",
                  async: true,
                  type: 'post',
                  beforeSend: function() {}
                })
                .done(function(response) {
                  localStorage.removeItem("precio");
                  localStorage.removeItem("enganche");
                  localStorage.removeItem("abono");
                  if (response.status != null && (response.status != undefined) != "" && response.status == 200) {
                    swal(
                      {
                        title: "¡Contrato realizado!",
                        text:
                          "Pulsar el boton continuar para ver el historial.",
                        type: "success",
                        /*confirmButtonColor: "#DD6B55",*/
                        confirmButtonText: "¡CONTINUAR!",
                        closeOnConfirm: false
                      },
                      function() {
                        window.location.href =
                          base_url() +
                          "venta/historial_de_ventas";
                      }
                    );
                  } else {
                    sweetAlert("¡Error!", "Algo salió mal, contactar al administrador sí el problema persiste.", "error");
                  }

                })
                .fail(function(response) {
                    sweetAlert("¡Error!", "Algo salió mal, contactar al administrador sí el problema persiste.", "error");
                });
        });


  },
});
