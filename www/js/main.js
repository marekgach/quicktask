$(document).ready(function(){
    $.nette.ext('reload', {
   	 	complete: function () {
        	$('input').iCheck({
    			checkboxClass: 'icheckbox_square-grey'
    		});
    	}
	});

	$.nette.init();

    $('#frm-insertTaskForm-insertTaskForm').on('submit', function (e) {
    	e.preventDefault();

	    $(this).netteAjax(e);
	});
    
    $('input').iCheck({
    	checkboxClass: 'icheckbox_square-grey'
    });
	
    $('.datepicker').datepicker({
        orientation: 'left top'
    });

	$('.iCheck-helper').on('click', function(){
		var stav = 0;
		var id = $(this).parent().children( ".taskCheck" ).attr('id');
		if($(this).parent().children( ".taskCheck" ).is(':checked')) {
			stav = 1;
		}
		else {
			stav = 0;
		}

		$.ajax({
  			url: "/task/task-group/"+id+"?stav="+stav+"&idTaskGroup=1&do=changeTaskState"
		});
	});

});

$(document).on("click", ".katRight", function () {
     var myId = $(this).data('id');
     var ids = myId.split('-');

     $('.modal-body #frm-changeTaskGroupModal-changeTaskGroupForm-category').val(ids[0]);
     $('.modal-body .taskId').val(ids[1]);
     // $(".modal-body #bookId").val( myBookId );
     
});