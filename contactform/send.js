// evitando que os dados sejam submetidos pelo modo tradicional
jQuery(document).ready(function(){
	var ipOfClient = "";

	jQuery("#form_contato").submit(function(){
		return false;
	});
        
	// carregando a função para o envio
	jQuery("#submit").click(function(){
		getIp();
	});
	
	function getIp(){
		$.getJSON("http://jsonip.com/?callback=?", function (data) {
        	useIp(data.ip);
    	});
	}

	function useIp(ip){
		ipOfClient = ip;
		envia_form();
	}

	// limpando a div antes de um novo envio
	function envia_form() {
		jQuery("#statusOfSend").empty();

		// pegando os campos do formulário
		var subject = jQuery("#subject").val();
		var name = jQuery("#name").val();
	 	var email = jQuery("#email").val();
	 	var phone = jQuery("#phone").val();
	 	var message = jQuery("#message").val();

		// tipo dos dados, url do documento, tipo de dados, campos enviados     
		// para GET mude o type para GET  
		var settings = {
		  "async": true,
		  "crossDomain": true,
		  "url": "http://10.1.1.101:5050/Email/Enviar",
		  "method": "POST",
		  "headers": {
		    "content-type": "application/x-www-form-urlencoded",
		    "cache-control": "no-cache"
		  },
		  "data": {
		    "Name": ""+name+"",
		    "Email": ""+email+"",
		    "Telefone": ""+phone+"",
		    "Subject": ""+subject+"",
		    "Message": ""+message+"",
		    "ipOfClient": ""+ipOfClient+""
		  }
		}

		$.ajax(settings).done(function (response) {

			if(response.indexOf('SUCESSO') != -1){
				jQuery("#statusOfSend").slideDown();
				jQuery("#statusOfSend").addClass('alert-success');
				jQuery("#statusOfSend").append("<strong>Sucesso!</strong> Seu email foi enviado com sucesso. Logo entraremos em contato!");
			
				jQuery("#name").val("");
				jQuery("#email").val("");
				jQuery("#phone").val("");
				jQuery("#message").val("");
			} else {
				jQuery("#statusOfSend").slideDown();
				jQuery("#statusOfSend").addClass('alert-danger');
				jQuery("#statusOfSend").append("<strong>Erro!</strong> Ocorreu um problema ao enviar seu email. Por gentileza tente novamente!");
			}
		}); 
	}
});
