module.exports = {
    autocompleteClientes: function(base_url) {
        $('#clientes_autocomplete').autocomplete({
            serviceUrl: base_url() + 'ajax/autocomplete_clientes',
            noCache: true,
            autoSelectFirst: true,
            onSelect: function(suggestion) {
                console.log(suggestion);
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

                }
                var extra = {
                    "ciudad_expedicion": "Playa del Carmen",
                    "testigo_1": "XXXX XXXX XXXX",
                    "testigo_2": "XXXX XXXX XXXX",
                };
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
                    "cp": "77777s",
                    "lugar_nacimiento": "Default",
                    "fecha_nacimiento": "01-01-1999",

                }
                var extra = {
                    "ciudad_expedicion": "Playa del Carmen",
                    "testigo_1": "XXXX XXXX XXXX",
                    "testigo_2": "XXXX XXXX XXXX",
                };
                for (var data in base) {
                    $('#' + data).val("");
                }
                for (var data in extra) {
                    $('#' + data).val("");
                }
                $('#id_cliente').val("");
            },
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
    datepicker: function() {
        var datepicker = $('.datepicker');
        datepicker.mask('00-00-0000');
        datepicker.datepicker({
            dateFormat: "dd-mm-yy"
        });
        if (datepicker.val() == "") {
            datepicker.datepicker("setDate", new Date());
        }

    },
    format_numeric: function(action) {
        if ($('.superficie').length) {
            $('.superficie').autoNumeric(action, {
                currencySymbol: ' m\u00B2',
                currencySymbolPlacement: 's'
            }); //Averiguar más del plugin para evitar menores a 0
        }
        if ($(".currency").length) {
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

};