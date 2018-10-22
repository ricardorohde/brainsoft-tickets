$(document).ready(function () {
	$("input[name=nameRegistry]").blur(function(){
        $.post("../../utils/controller/ctrl_registry.php", {registryToVerify:$(this).val().trim()},
          function(result){
            if (result == 1){
                document.formAddRegistry.nameRegistry.style.boxShadow = "0 0 5px #ff0000"; 
                document.formAddRegistry.nameRegistry.style.border = "1px solid #ff0000";
                $("button[type=submit]").hide();
            } else if (result == 0){
                document.formAddRegistry.nameRegistry.style.boxShadow = "0 0 5px #28a745"; 
                document.formAddRegistry.nameRegistry.style.border = "1px solid #28a745";
                $("button[type=submit]").show();
            }
          }
        )
    });
});
