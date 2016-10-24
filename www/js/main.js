$(document).ready(function(){
    $.nette.init();

    function bindICheck(){
        var input = $('input');
        input.iCheck('destroy');
        input.iCheck({
            handle: 'checkbox',
            checkboxClass: 'icheckbox_flat-blue'
        });
    }

    function bindHandleCheckbox() {
        $('input[type=checkbox].handle').on('ifChecked', function (e) {
            var link = $(this).data('link');
            var bound = $(this).data('handle-bound');
            if(!bound){
                $.nette.ajax(link);
                $(this).data('handle-bound', 1);
            }
        });
    }

    function bindDatepicker() {
        var datepicker = $('.datepicker');
        datepicker.datepicker('destroy');
        datepicker.datepicker({
            orientation: 'left bottom'
        });
    }

    function initBinds(){
        bindICheck();
        bindHandleCheckbox();
        bindDatepicker();
    }

    /* init */
    initBinds();

    /* after ajax */
    $.nette.ext({
        load: function () {
            initBinds();
        }
    });

});