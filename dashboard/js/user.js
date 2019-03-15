$(document).ready(function(){
	$("input[name=login]").change(function(){
        $("input[name=login]").val($(this).val().toLowerCase());
    });

    setTimeout(function() {
        $("#example_filter > label > input").focus();
        $("#ticketListOfClient_filter > label > input").focus();
    }, 1000);
});