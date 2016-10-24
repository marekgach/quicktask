$(document).ready(function(){
    $.nette.init();

    $('.datepicker').datepicker({
        orientation: 'left bottom'
    });

    $('input').iCheck({
        handle: 'checkbox',
        checkboxClass: 'icheckbox_flat-blue'
    });
});