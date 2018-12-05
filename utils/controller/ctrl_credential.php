<?php

	include_once("../model/credential.php");
	include_once("ctrl_employee.php");
	include_once("ctrl-authorization.php");
	session_start();

	$controller = new CredentialController();
	$controller->verifyData();
	
	class CredentialController{

		protected $data;

		public function getData(){
		    return $this->data;
		}
		public function setData($data){
		    $this->data = $data;
		}

		function verifyData(){
			$this->data = $_POST;

			if (isset($this->data['submit'])) {
				foreach ($this->data as $key => $value) {
					if ((!isset($this->data[$key]) || empty($this->data[$key]))){
						//$this->setHeader(null, null, '400');
					}
				}

				if ($this->data['submit'] == 'fromDoLogin' || $this->data['submit'] == 'submitFromIndex'){
					$this->checkLoginCtrl();
				} 

				if($this->data['submit'] == 'submitFromChangePass'){
					$this->changePasswordCtrl();
				}
			} 

			if (isset($this->data['userToVerify'])){
				echo $this->verifyIfExistsCtrl();
			}
		}

		function checkLoginCtrl(){
			$credential = new Credential($this->getInstance());
			
			$credential->setLogin($this->data['login']);
			$credential->setPassword($this->data['password']);
			$credential->checkLogin();
		}		

		function changePasswordCtrl(){
			if ($this->data['newPass'] == $this->data['confirmPass']){
				$credential = new Credential($this->getInstance());

				$credential->setId($_SESSION['login']);
				$credential->setPassword($this->data['newPass']);
				$credential->changePassword();    		
			} else{
				$this->setHeader(NULL, NULL, '304');
			}
		}

		function registerCtrl($login, $password){
			$credential = new Credential($this->getInstance());

			$credential->setLogin($login);
			$credential->setPassword($password);

			return $credential->register();
		}

		function verifyIfExistsCtrl(){	      
			$credential = new Credential($this->getInstance());

			$credential->setLogin($this->data['userToVerify']);

			return $credential->verifyIfExists();
	    }

    function verifyChangePass($result){
			if ($result == 1) {
				$this->setHeader(NULL, NULL, '202');
			} else{
				$this->setHeader(NULL, NULL, '409');
			}
		}

    function setHeader($id, $password, $status){
			switch ($status) {
				case '200':
					$controllerEmployee = new EmployeeController();
					$controllerEmployee->isOnChat($id, "yes"); //DEIXANDO O USUÁRIO ONLINE NA FILA

					$_SESSION['login'] = $id;

					$controllerAuthorization = new AuthorizationController();
					$authorizations          = $controllerAuthorization->findAuthorizationsById();
					$controllerAuthorization->authorizePage($authorizations);

					if ($password == "sistemabrain"){
						$_SESSION['passDefault'] = "true";
					} 		

      		unset($_SESSION['withoutLogin']);
      		unset($_SESSION['errorLogin']);
      		unset($_SESSION['thereIsProblemInLogin']);
      		header("Location:../../dashboard");
					break;
				case '404':
					unset($_SESSION['withoutLogin']);
		   		$_SESSION['errorLogin'] = "<strong>Erro!</strong> Usuário e/ou Senha incorretos.";
		    	header("Location:/utils/do-login.php");
		    	break;
				case '202':
					unset($_SESSION['passDefault']);
					unset($_SESSION['needSamePass']);
					unset($_SESSION['passNotChanged']);
		  		$_SESSION['passChanged'] = "<strong>Sucesso!</strong> Senha alterada com êxito.";
 					header("Location:../../dashboard");
 					break;
 				case '409':
 					$_SESSION['passNotChanged'] = "<strong>Erro!</strong> Ocorreu um problema ao alterar a sua senha.";
 					header("Location:../../dashboard");
 					break;
 				case '304':
 					$_SESSION['needSamePass'] = "<strong>Erro!</strong> As senhas necessitam ser iguais.";
 					header("Location:../../dashboard");
 					break;
 				case '400':
 					$_SESSION['thereIsProblemInLogin'] = "<strong>Erro!</strong> Preencha todos os campos para entrar.";
					header("Location:/utils/do-login.php");
					break;
				default:
					break;
			}
		}

		function getInstance(){
      return new CredentialController();
    }
	}

?>