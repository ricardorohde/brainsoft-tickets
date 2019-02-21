$(document).ready(function () {

	$("#date-email").change(function () {
        $("#btn-get-history-email").prop("disabled", false);
    });

	$("#btn-get-history-email").click(function () {
		var date = $("#date-email").val();
		
		if (date != "") {
			$("table[name=tableHistoryEmail] > tbody").html("");

			$.post("../../../utils/controller/marketing/email-data.ctrl.php", {date:date},
				function (valor) {
					if (valor != "") {
						$("table[name=tableHistoryEmail] > tbody").append(valor);
						$("#resultHistory").remove();
						$(".divTableHistory").show();
						$("table[name=tableHistoryEmail]").show();
					} else {
						$(".divTableHistory").show();
						$("table[name=tableHistoryEmail]").hide();
						$(".divTableHistory").append("<div class='text-center mt-2' id='resultHistory'>Nenhum email enviado em " + convertDate(date) + "</div>");
					}
				}
	    	)
		}

		$("#btn-get-history-email").prop("disabled", true);
	});
});

function convertDate(userDate) {
	var date = new Date(userDate),
    yr = date.getFullYear(),
    month = date.getMonth() < 10 ? '0' + date.getMonth() : date.getMonth(),
    day = date.getDate()  < 10 ? '0' + date.getDate()  : date.getDate(),
    newDate = day + '/' + month + '/' + yr;
	
	return newDate;
}