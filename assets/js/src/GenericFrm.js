import { base_url, ajax_msg } from './utils/util';
class GenericFrm {
    constructor(config) {
        this.data = {};
        this.urls = config.urls;
        this.url = "";
        this.msgs = config.msgs;
        this.msg = "";
        this.frm = $(config.frm);
        this.btn = config.btn;

        this.append = config.append;
        if (config.readonly !== undefined) {
            this.readonly = config.readonly;
        }

        this.dtTable = config.dtTable;
        this.autoNumeric = config.autoNumeric;
        this.dtRow =
            (this.btn.closest('tr').hasClass('child')) ?
            this.btn.closest('tr').prev('tr.parent') :
            this.btn.parents('tr');

        this.parseDtRow = this.dtTable.row(this.dtRow).data();
        this.response;
        this.fnOnDone;

        this.on_submit();
    }
    add() {
        this.frm[0].reset();
        this.data = {};
        if (this.readonly !== undefined) {
            this.readonly.status = false;
            this.fnReadonly();
        }
        this.url = this.urls.add;
        this.msg = this.msgs.add;
        this.fnOnDone = this.ajaxAddDone;
    }
    edit() {

        this.data = {};
        if (this.readonly !== undefined) {
            this.readonly.status = true;
            this.fnReadonly();
        }
        this.url = this.urls.edit;
        this.msg = this.msgs.edit;
        this.fnOnDone = this.ajaxEditDone;
        for (var data in this.parseDtRow) {
            //Sí existe el elemento con id
            if ($("#" + data).length) {
                var input = $("#" + data);
                if (input.hasClass('autoNumeric')) {
                    input.autoNumeric('set', this.parseDtRow[data]);
                } else {
                    input.val(this.parseDtRow[data]);
                }
            }
        }
        for (var data in this.append) {
            this.data[this.append[data]] = this.parseDtRow[this.append[data]];
        }
    }
    fnReadonly() {
        $(this.readonly.inputs).attr('readonly', this.readonly.status);
    };
    submit() {
        var self = this;
        $.ajax({
                url: base_url() + self.url,
                type: "post",
                data: self.data,
                beforeSend: function(xhr) {
                    $("input[type='submit']").next().css('visibility', 'visible');
                }
            })
            .done(function(response) {
                self.response = response;
                self.fnOnDone.apply(self);
            })
            .fail(function(response) {
                ajax_msg.show_error(response);
            });
    }
    ajaxAddDone() {
        this.frm[0].reset();
        console.log(this.response[0]);
        var newData = this.dtTable.row.add(this.response[0]).draw(false).node();
        console.log(newData);
        $(newData).css({ backgroundColor: 'yellow' }); //Animación para MAX
        this.dtTable.order([0, 'desc']).draw(); //Ordenar por id
        ajax_msg.show_success(this.msg);
    }
    ajaxEditDone() {
        for (let data in this.response[0]) {
            this.parseDtRow[data] = this.response[0][data];
        }
        var newData = this.dtTable.row(this.dtRow).data(this.parseDtRow).draw(false).node(); //
        $(newData).css({ backgroundColor: 'yellow' }); //Animación para MAX
        ajax_msg.show_success(this.msg);
    }
    on_submit() {
        var self = this;
        this.frm.off('submit').on('submit', function(e) {
            e.preventDefault();
            Object.assign(self.data, $(this).serializeObject());
            for (let data in self.autoNumeric) { //Convertir de númerico a número
                if ($('#' + self.autoNumeric[data]).length > 0) {
                    self.data[self.autoNumeric[data]] = $('#' + self.autoNumeric[data]).autoNumeric('get');
                }
            }
            self.submit();
        });
    }
}

export default GenericFrm;
