$(document).ready(function(){
    
	$("#internal-row > table > tbody > tr:nth-child(8) > td").each(function(){
		var column = $(this).attr("id");
		var soma = 0;
		
		$("#internal-row > #report > tbody > tr").each(function(index){
			if(index > 0 && index < 7){ 
				soma += parseInt($(this).children().eq(column).html());
			}
		});

		if($.isNumeric(soma)){
			$(this).html(soma);
		}
	});

});