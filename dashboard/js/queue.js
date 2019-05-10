$(document).ready(function(){
    
	$("#internal-row > table > tbody > tr:nth-child(9) > td").each(function(){
		var column = $(this).attr("id");
		var soma = 0;
		
		$("#internal-row > #report > tbody > tr").each(function(index){
			if(index > 0 && index < 8){ 
				soma += parseInt($(this).children().eq(column).html());
			}
		});

		if($.isNumeric(soma)){
			$(this).html(soma);
		}
	});

	$('[data-toggle="tooltip"]').tooltip();
});