$(document).ready(function(){	
	$("input[name=login]").change(function(){
        $("input[name=login]").val($(this).val().toLowerCase());
    });
});