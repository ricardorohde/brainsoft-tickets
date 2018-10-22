<?php

	if (isset($_POST['newRegistry'])) {
		$id = $_POST['id_registry'];

		$name = $_POST['nameRegistry'];
		$id_city = $_POST['city'];

		$registryController = new RegistryController();
		$registryController->newRegistry($id, $name, $id_city);
	}

	if (isset($_POST['valor'])){
		$city = $_POST['valor'];
		$registryController = new RegistryController();
		$registryController->findRegistriesByCity();
	}

	if (isset($_POST['registryToAdm'])){
		$registryController = new RegistryController();
		$registryController->findFilesOfRegistry();
	}

	if (isset($_POST['fromFile'])){
		$registryController = new RegistryController();
		$registryController->findIdByNameFromFile();
	}

	if (isset($_POST['registryToVerify'])){
		$registryToVerify = $_POST['registryToVerify'];
		$registryController = new RegistryController();
		echo $registryController->verifyIfExists($registryToVerify);
	}

	class RegistryController{

		function __construct(){
			include_once("../model/registry.php");
		}

		function newRegistry($id, $name, $id_city){
			session_start();

			$registry = new Registry($this->getInstance());

			if($id == NULL){
				$decodedName = utf8_decode($name);

				$registry->setName($decodedName);
				$registry->setIdCity($id_city);
				$registry->register();
			
				//CRIANDO UMA SESSAO DE SUCESSO E REDIRECIONANDO
	        	$_SESSION['registryOk'] = 
	        		"<strong>Sucesso!</strong> Cartório <b><u>$name</u></b> cadastrado com êxito.";
	       		header("Location:../../dashboard/cartorios");
	       	} else{
	       		$decodedName = utf8_decode($name);

				$registry->setId($id);
				$registry->setName($decodedName);
				$registry->setIdCity($id_city);
	       		$registry->update();

	       		//CRIANDO UMA SESSAO DE SUCESSO E REDIRECIONANDO
	        	$_SESSION['registryOk'] = 
	        		"<strong>Sucesso!</strong> Cartório <b><u>$name</u></b> atualizado com êxito.";
	       		header("Location:../../dashboard/cartorios");
	       	}
		}

		function findFilesOfRegistry(){
			$name_registry = $_POST['registryToAdm'];
			$id_registry = $this->findIdByName($name_registry);

			$registry = new Registry($this->getInstance());
			$registry->setId($id_registry[0]['id']);

			$files = $registry->findFiles();

			if (count($files) > 0){
				for ($i = 0; $i < count($files); $i++) { 
			        $id = $files[$i]['id'];
			        $desc = $files[$i]['path_to_file'];
			        $path = 'administrative-files/' . $files[$i]['path_to_file'];
			        $exp_date = $files[$i]['expiration_date'];
			        $paid_date = $files[$i]['paid_date'];
			        $status = $files[$i]['status'];

			        //VERIFICANDO DATA DE VENCIMENTO E ATRIBUINDO VARIAVEIS
			        if ($exp_date == '0000-00-00'){$title_modal = "Informar Data de Vencimento"; $btn_change_date = "'btn btn-sm btn-info'"; $fa = "'fa fa-calendar-plus-o'";
			    		} else {$title_modal = "Alterar Data de Vencimento"; $btn_change_date = "'btn btn-sm btn-success'"; $fa = "'fa fa-calendar-check-o'";}

			    	//VERIFICANDO A DATA EM QUE FOI PAGA, SE FOI ATRASADO OU NÃO
			    	if ($paid_date < $exp_date) {$btn = "'btn btn-sm btn-success disabled'";
			    		} else {$btn = "'btn btn-sm btn-warning disabled'"; }

			    	//TRANSFORMANDO DATA DE VENCIMENTO PARA PADRÃO BRASILEIRO
			    	if ($exp_date != '0000-00-00'){$exp_date_create = date_create($exp_date); $exp_date = date_format($exp_date_create, 'd/m/Y');
			    	} else {$exp_date = "";}
			
					//TRANSFORMANDO DATA DE PAGAMENTO PARA PADRÃO BRASILEIRO	    
				    if ($paid_date != '0000-00-00'){$paid_date_create = date_create($paid_date); $paid_date = date_format($paid_date_create, 'd/m/Y');
					} else {$paid_date = "";}

					//VERIFICANDO STATUS E ATRIBUINDO VALORES PARA A TABELA
			        if ($status == "ativo"){
			        	$option = utf8_encode("<tr><td>$desc</td><td>$exp_date</td><td>$paid_date</td>
				        	<td class='actions text-right'>
				        		<a href='#' class='btn btn-sm btn-danger' id='set_paid_date' data-id='$id|$desc' data-target='#paid_date' data-toggle='modal'><i class='fa fa-money'></i> Pagar!</a>
	                    		<a href='$path' download='Boleto' class='btn btn-sm btn-info'><i class='fa fa-eye'></i></a>
	                    		<a href='#' class=".$btn_change_date." id='set_expiration_date' data-id='$title_modal|$id|$desc|$exp_date' data-target='#expiration_date' data-toggle='modal'><i class=".$fa."></i></a>
	                  		</td></tr>");
			        } else {
			        	$option = utf8_encode("<tr><td>$desc</td><td>$exp_date</td><td>$paid_date</td>
				        	<td class='actions text-right'>
				        		<a href='#' class=".$btn."><i class='fa fa-check'></i> Pago!</a>
	                    		<a href='$path' download='Boleto' class='btn btn-sm btn-info'><i class='fa fa-eye'></i></a>
	                  		</td></tr>");
			        }

			        echo $option;
		      	}
	      	} else {
	      		$option = "<tr><td colspan='6'>Nenhum boleto encontrado.</td></tr>";
	      		echo $option;
	      	}
		}

		function findIdByName($name){
			$registry = new Registry($this->getInstance());
			$registry->setName($name);

			return $registry->findIdByName();
		}

		function findIdByNameFromFile(){
			$registry = new Registry($this->getInstance());
			$registry->setName($_POST['fromFile']);

			echo $registry->findIdByName()[0]['id'];	
		}

		function findRegistriesByCity(){
			$city = $_POST['valor'];

			$registry = new Registry($this->getInstance());
			$registry->setIdCity($city);

			$registries = $registry->findRegistries();

			$qtd_registries = count($registries);

			if ($qtd_registries > 0){
				$info = utf8_encode("<option value=''>Selecione um cartorio...</option>");
			} else{
				$info = utf8_encode("<option value='' disabled selected>Nenhum cartorio registrado para esta cidade!</option>");
			}

	    	echo $info;
			for ($i=0; $i < $qtd_registries; $i++){ 
	    		$id = $registries[$i]['id'];
	    		$name = $registries[$i]['name'];
	    		$option = utf8_encode("<option value='$id'>$name</option>");
	    		echo $option;
    		}
		}

		function verifyIfExists($registryToVerify){	      
	      $registry = new Registry($this->getInstance());

	      $registry->setName($registryToVerify);

	      return $registry->verifyIfExists();
	    }

		function getInstance(){
	      return new RegistryController();
	    }
	}

?>