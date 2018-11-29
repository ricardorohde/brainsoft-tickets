$(document).ready(function () {
  $("#divCarregando").fadeOut("slow");
  $("#txtBusca").removeAttr("disabled");
  $("#txtBusca").focus();

	$("#filter-by-period").click(function(){
		var date_required = $("#final-actual-date-filter").val();
        
    setCookie("date_to_filter", 1);

    function setCookie(name, exdays){    //função universal para criar cookie
      var expires;
      var date; 

      date = new Date(); //  criando o COOKIE com a data atual
      date.setHours(date.getHours() - 3);
      date.setTime(date.getTime() + (exdays*24*60*60*1000));
      expires = date.toUTCString();

      document.cookie = name+"="+date_required+"; expires="+expires+"; path=/";
    }
	});

	countElements();
	$("#txtBusca").keyup(function(){
		str = $(this).val();
  	$(this).val(str.substr(0,1).toUpperCase()+str.substr(1));

		countElements();
	});
});

function countElements(){
	var count = 0;

	$("myElement").each(function(){
		count++;
	});

	$("#qtd-tickets > p").empty();
	$("#qtd-tickets > p").append("Quantidade de tickets: " + count);
	$("#qtd-tickets").fadeIn("slow");

	$("#actual-filter").fadeIn("slow");
}