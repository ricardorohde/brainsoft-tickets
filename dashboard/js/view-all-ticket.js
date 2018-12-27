$(document).ready(function () {
  $("#divCarregando").fadeOut("slow");
  $("#txtBusca").removeAttr("disabled");
  $("#txtBusca").focus();

	$("#filter-by-period").click(function() {
		var dateRequired = $("#final-actual-date-filter").val();
        
    setCookie("date_to_filter", 1, dateRequired);
	});

	$("#filter-by-attendant").click(function() {
		var dateRequired = $("#final-actual-date-filter").val();
        
    setCookie("date_to_filter", 1, dateRequired);
	});

	$("#show-hide-filters").click(function() {
		var html = $.trim($(this).html());

		if (html == "Exibir Filtros") {
			$(this).html("Esconder Filtros");
		} else {
			$(this).html("Exibir Filtros");
		}
	});

	countElements();
	$("#txtBusca").keyup(function() {
		str = $(this).val();
  	$(this).val(str.substr(0,1).toUpperCase()+str.substr(1));

		countElements();
	});
});

function setCookie(name, exdays, dateRequired) {
  var expires;
  var date;

  date = new Date();
  date.setHours(date.getHours() - 3);
  date.setTime(date.getTime() + (exdays*24*60*60*1000));
  expires = date.toUTCString();

  document.cookie = name+"="+dateRequired+"; expires="+expires+"; path=/";
}

function countElements() {
	var count = 0;

	$(".card-in-ticket-list").each(function() {
		count++;
	});

	$("#qtd-tickets > p").empty();
	$("#qtd-tickets > p").append("Quantidade de tickets: " + count);
	$("#qtd-tickets").fadeIn("slow");

	$("#actual-filter").fadeIn("slow");
}