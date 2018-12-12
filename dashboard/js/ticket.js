$(document).ready(function () {

	$(document).keypress(function(e) {
	    if(e.which == 13){
	    	return false;
	    }
	});

	$("#status").change(function() {
		var statusSelected = $("#status option:selected").val();
		if(statusSelected != "aberto"){
			$("button[name='finishTicket']").hide();
		} else{
			$("button[name='finishTicket']").show();
		}
	});

	$("button[type=submit]").click(function() {
		var error = false;

		if(!error){
			var val = $("#module").val();
			if(val.indexOf("/") == -1){
				$("#module").addClass("border-danger");
				error = true;
			} else {
				$("#module").removeClass("border-danger");
			}
		}

		if(!error) {
			$('select').each(function() {
				if($(this).val() == ""){
					$(this).addClass("border-danger");
					error = true;
				} else {
					$(this).removeClass("border-danger");
				}
			});
		}

		if(!error) {
			var selectedAttendant = $("#attendant option:selected").html();
			var targetAttendant = $("#target-attendant").val();
		
			if(selectedAttendant != targetAttendant) {
				$("#attendant").addClass("border-danger");
				$("#span-attendant").addClass("text-danger");
				$("#span-attendant").html("Alerta! Selecione o atendente correto (neste caso, o " + targetAttendant + ").");
				error = true;
			} else {
				$("#attendant").removeClass("border-danger");
				$("#span-attendant").removeClass("text-danger");
				$("#span-attendant").html("");
			}
		}

		if(error){ 
			return false;
		}
	});
});