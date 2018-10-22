$(document).ready(function(){	
	$(".chat-code").on('click', function(){
		var chat = $(this).html();
		$("#id_chat").val(chat);
		$("#id_chat").focus();
	});
});