/*import Inputmask from 'inputmask';*/
/**/
module.exports = {
    autocompleteClientes: function(base_url) {
        var base = {
            "first_name": "Default",
            "last_name": "Default Default",
            "email": "default@huertoslaceiba.com",
            "phone": "99821234567",
            "calle": "Default",
            "no_ext": "100",
            "no_int": "100",
            "colonia": "Default",
            "municipio": "Default",
            "estado": "Default",
            "ciudad": "Default",
            "cp": "77777",
            "lugar_nacimiento": "Default",
            "fecha_nacimiento": "01-01-1999",

        };
        var extra = {
            "ciudad_expedicion": "Playa del Carmen",
            "testigo_1": "XXXX XXXX XXXX",
            "testigo_2": "XXXX XXXX XXXX",
        };
        $('#clientes_autocomplete').autocomplete({
            serviceUrl: base_url() + 'ajax/autocomplete_clientes',
            noCache: true,
            autoSelectFirst: true,
            onSelect: function(suggestion) {
                console.log(suggestion);
                for (var data in suggestion) {
                    if (suggestion[data] == "" || suggestion[data] == null || suggestion[data] == 0) {
                        suggestion[data] = base[data];
                    }
                    $('#' + data).val(suggestion[data]).trigger('input');
                }
                for (var data in extra) {
                    $('#' + data).val(extra[data]);
                }
                $('#id_cliente').val(suggestion.id_cliente);
            },
            onSearchError: function(query, jqXHR, textStatus, errorThrown) {
                console.log("Ha ocurrido un error xhr.");
            },
            onInvalidateSelection: function() {
                console.log("Ha ocurrido un error en la selección.");
                for (var data in base) {
                    $('#' + data).val("");
                }
                for (var data in extra) {
                    $('#' + data).val("");
                }
                $('#id_cliente').val("");
            },
        });
        $('#clientes_autocomplete').on('keyup', function() {
            if (this.value == "" || this.value == null || this.value == undefined) {
                console.log("Ha ocurrido un error en la selección.");
                for (var data in base) {
                    $('#' + data).val("");
                }
                for (var data in extra) {
                    $('#' + data).val("");
                }
                $('#id_cliente').val("");
            }
        });
    },
    autocompleteLideres: function(base_url) {
        $('#lideres_autocomplete').autocomplete({
            serviceUrl: base_url() + 'ajax/autocomplete_lideres',
            noCache: true,
            autoSelectFirst: true,
            onSelect: function(suggestion) {
                $("#id_lider").val(suggestion.id);
            },
            onSearchError: function(query, jqXHR, textStatus, errorThrown) {
                console.log("Ha ocurrido un error.");
            },
            onInvalidateSelection: function() {
                console.log("Ha ocurrido un error en la selección.");
            }
        });
    },
    autocompleteSaldosClientes: function(base_url) {
        var base = {
            "first_name": "Default",
            "last_name": "Default Default",
            "email": "default@huertoslaceiba.com",
            "phone": "99821234567",
            "calle": "Default",
            "no_ext": "100",
            "no_int": "100",
            "colonia": "Default",
            "municipio": "Default",
            "estado": "Default",
            "ciudad": "Default",
            "cp": "77777",
            "lugar_nacimiento": "Default",
            "fecha_nacimiento": "01-01-1999",
            "ciudad_expedicion": "Playa del Carmen",
            "testigo_1": "XXXX XXXX XXXX",
            "testigo_2": "XXXX XXXX XXXX",

        }
        $('#saldos_clientes_autocomplete').autocomplete({
            serviceUrl: base_url() + 'ajax/autocomplete_saldos_clientes',
            noCache: true,
            autoSelectFirst: true,
            onSelect: function(suggestion) {
                $('#saldos_clientes_autocomplete').val(suggestion.data);
                console.log(suggestion);
                for (var data in suggestion) {
                    if (suggestion[data] == "" || suggestion[data] == null || suggestion[data] == 0) {
                        suggestion[data] = base[data];
                    }
                    $('#' + data).val(suggestion[data]).trigger('input');
                }
                if (suggestion.pagado) {
                    $('#enganche').autoNumeric('set', suggestion.pagado);
                }
                $('#id_cliente').val(suggestion.id_cliente);
                $('#id_venta').val(suggestion.id_venta);
            },
            onSearchError: function(query, jqXHR, textStatus, errorThrown) {
                console.log("Ha ocurrido un error xhr.");
            },
            onInvalidateSelection: function() {
                console.log("Ha ocurrido un error en la selección.");
            },
            onSearchStart: function(query) {
                console.log(query);
            }
        });
        $('#saldos_clientes_autocomplete').on('keyup', function() {
            if (this.value == "" || this.value == null || this.value == undefined) {
                for (var data in base) {
                    $('#' + data).val("").trigger('input');
                }
                $('#id_cliente').val("");
                $('#id_venta').val("");
            }
        });
    },
    datepicker: function(moment) {
        var datepicker = $('.datepicker');
        datepicker.mask('00-00-0000');
        datepicker.datepicker({
            dateFormat: "dd-mm-yy"
        });

        if (datepicker.val() === "") {
            datepicker.datepicker("setDate", new Date());
        }
    },
    format_numeric: function(action) {
        if ($('.superficie').length > 0) {
            $('.superficie').autoNumeric(action, {
                currencySymbol: ' m\u00B2',
                currencySymbolPlacement: 's'
            }); //Averiguar más del plugin para evitar menores a 0
        }
        if ($(".currency").length > 0) {
            $(".currency").autoNumeric(action, {
                currencySymbol: "$"
            });
        }
    },
    phone: function() {
        var options = {
            onComplete: function(cep) {

            },
            onKeyPress: function(cep, event, currentField, options) {

            },
            onChange: function(cep) {

            },
            onInvalid: function(val, e, f, invalid, options) {
                var error = invalid[0];
                console.log("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
            },
            reverse: false
        };
        $('.phone').mask('(000) 000-0000', options);
    },
    number: function() {
        var options = {
            onComplete: function(cep) {

            },
            onKeyPress: function(cep, event, currentField, options) {

            },
            onChange: function(cep) {

            },
            onInvalid: function(val, e, f, invalid, options) {
                var error = invalid[0];
                console.log("Digit: ", error.v, " is invalid for the position: ", error.p, ". We expect something like: ", error.e);
            },
            reverse: false
        };
        $('.number').mask('0#', options);
    },
    tabs: function() {
        $('.tabgroup > div').hide();
        $('.tabgroup > div:first-of-type').show();
        $('.tabs a').click(function(e) {
            console.log('tab');
            e.preventDefault();
            var $this = $(this),
                tabgroup = '#' + $this.parents('.tabs').data('tabgroup'),
                others = $this.closest('li').siblings().children('a'),
                target = $this.attr('href');
            others.removeClass('active');
            $this.addClass('active');
            $(tabgroup).children('div').hide();
            $(target).show();

        });
    },
    tarjeta: function() {
        Inputmask('0000-0000-0000-0000').mask('.tarjeta');
    }
};