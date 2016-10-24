$(document).ready(function(){
    $.nette.init();

    function bindICheck(){
        $('input').iCheck({
            handle: 'checkbox',
            checkboxClass: 'icheckbox_flat-blue'
        });
    }

    function bindHandleCheckbox() {
        $('input[type=checkbox].handle').on('ifChecked', function (e) {
            var link = $(this).data('link');
            $.nette.ajax(link);
        });
    }

    /* init */
    bindICheck();
    bindHandleCheckbox();

    $('.datepicker').datepicker({
        orientation: 'left bottom'
    });

    /* after ajax */
    $.nette.ext({
        load: function () {
            bindICheck();
            bindHandleCheckbox();
        }
    });

});