$(document).ready(function() {

	$('.registerUser').click(function() {

		var haveProblem = 0;
		var statusOfEmail = 0;

		var typeUser = document.formAdd.typeUser.value;
		var name = document.formAdd.name.value;
		var state = document.formAdd.state.value;
		var city = document.formAdd.city.value;
		var registry = document.formAdd.registry.value;
		var role = document.formAdd.role.value;
		var login = document.formAdd.login.value;
		var password = document.formAdd.password.value;

		if(name == ""){
			document.formAdd.name.style.boxShadow = "0 0 5px #ff0000"; 
			document.formAdd.name.style.border = "1px solid #ff0000";
			haveProblem = 1;
		} else{
			document.formAdd.name.style.boxShadow = ""; 
			document.formAdd.name.style.border = "";
		}

		statusOfEmail = validateEmail();

		if (typeUser == "client") {
			if(state == ""){
				document.formAdd.state.style.boxShadow = "0 0 5px #ff0000"; 
				document.formAdd.state.style.border = "1px solid #ff0000";
				haveProblem = 1;
			} else{
				document.formAdd.state.style.boxShadow = ""; 
				document.formAdd.state.style.border = "";
			}

			if(city == ""){
				document.formAdd.city.style.boxShadow = "0 0 5px #ff0000"; 
				document.formAdd.city.style.border = "1px solid #ff0000";
				haveProblem = 1;
			} else{
				document.formAdd.city.style.boxShadow = ""; 
				document.formAdd.city.style.border = "";
			}

			if(registry == ""){
				document.formAdd.registry.style.boxShadow = "0 0 5px #ff0000"; 
				document.formAdd.registry.style.border = "1px solid #ff0000";
				haveProblem = 1;
			} else{
				document.formAdd.registry.style.boxShadow = ""; 
				document.formAdd.registry.style.border = "";
			}
		}

		if(role == ""){
			document.formAdd.role.style.boxShadow = "0 0 5px #ff0000"; 
			document.formAdd.role.style.border = "1px solid #ff0000";
			haveProblem = 1;
		} else{
			document.formAdd.role.style.boxShadow = ""; 
			document.formAdd.role.style.border = "";
		}

		if(login == ""){
			document.formAdd.login.style.boxShadow = "0 0 5px #ff0000"; 
			document.formAdd.login.style.border = "1px solid #ff0000";
			haveProblem = 1;
		} else{
			document.formAdd.login.style.boxShadow = ""; 
			document.formAdd.login.style.border = "";
		}

		if(password == ""){
			document.formAdd.password.style.boxShadow = "0 0 5px #ff0000"; 
			document.formAdd.password.style.border = "1px solid #ff0000";
			haveProblem = 1;
		} else{
			document.formAdd.password.style.boxShadow = ""; 
			document.formAdd.password.style.border = "";
		}

		if (haveProblem > 0 || statusOfEmail > 0){
			return false;
		} else{
			return true;
		}

		function validateEmail(){
			var emailID = document.formAdd.email.value;
			atpos = emailID.indexOf("@");
			dotpos = emailID.lastIndexOf(".");

			if (emailID == "" || (atpos < 1 || ( dotpos - atpos < 2 ))){
				document.formAdd.email.style.boxShadow = "0 0 5px #ff0000"; 
				document.formAdd.email.style.border = "1px solid #ff0000";
				document.formAdd.email.focus();
				return 1;
			} else{
				document.formAdd.email.style.boxShadow = ""; 
				document.formAdd.email.style.border = "";
				return 0;
			}
		}

	});
});