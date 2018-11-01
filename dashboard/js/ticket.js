$(document).ready(function () {

	$(document).keypress(function(e) {
	    if(e.which == 13){
	    	return false;
	    }
	});

	$("#status").change(function(){
		var statusSelected = $("#status option:selected").val();
		if(statusSelected != "aberto"){
			$("button[name='finishTicket']").hide();
		} else{
			$("button[name='finishTicket']").show();
		}
	});

	$("button[type=submit]").click(function(){
		var error = false;

		if(!error){
			var val = $("#module").val();
			if(val.indexOf("/") == -1){
				$("#module").addClass("border-danger");
				error = true;
			}
		}

		if(!error){
			$('select').each(function() {
				if( $(this).val() == ""){
					$(this).addClass("border-danger");
					error = true;
				}
			});
		}

		if(error){ 
			return false;
		}
	});
});