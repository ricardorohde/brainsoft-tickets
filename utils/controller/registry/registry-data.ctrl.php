<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../city/city.ctrl.php";
include_once __DIR__ . "/../../model/registry.php";

if (isset($_POST['registryToAdm'])) {
    $registryController = new RegistryController();
    $registryController->findFilesOfRegistry();
}

if (isset($_POST['fromFile'])) {
    $registryController = new RegistryController();
    $registryController->findIdByNameFromFile();
}

if (isset($_POST['registryToVerify'])) {
    $registryToVerify = $_POST['registryToVerify'];
    $registryController = new RegistryController();
    echo $registryController->verifyIfExists($registryToVerify);
}

class RegistryDataController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $cityController;

    private $dataReceived;

    public function getDataReceived() 
    {
        return $this->dataReceived;
    }
    
    public function setDataReceived($dataReceived) 
    {
        $this->dataReceived = $dataReceived;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->cityController = CityController::getInstance();
    }

	public function findIdByName($name)
    {
		$registry = new Registry($this, $this->prepareInstance);
		$registry->setName($name);
		return $registry->findIdByName();
	}

	public function findIdByNameFromFile()
    {
		$registry = new Registry($this, $this->prepareInstance);
		$registry->setName($_POST['fromFile']);
		echo $registry->findIdByName()[0]['id'];	
	}

    function findFilesOfRegistry()
    {
        $name_registry = $_POST['registryToAdm'];
        $id_registry = $this->findIdByName($name_registry);

        $registry = new Registry($this->getInstance());
        $registry->setId($id_registry[0]['id']);

        $files = $registry->findFiles();

        if (count($files) > 0) {
            for ($i = 0; $i < count($files); $i++) { 
                $id = $files[$i]['id'];
                $desc = $files[$i]['path_to_file'];
                $path = 'administrative-files/' . $files[$i]['path_to_file'];
                $exp_date = $files[$i]['expiration_date'];
                $paid_date = $files[$i]['paid_date'];
                $status = $files[$i]['status'];

                //VERIFICANDO DATA DE VENCIMENTO E ATRIBUINDO VARIAVEIS
                if ($exp_date == '0000-00-00') {
                    $title_modal = "Informar Data de Vencimento"; 
                    $btn_change_date = "'btn btn-sm btn-info'"; 
                    $fa = "'fa fa-calendar-plus-o'";
                } else {
                    $title_modal = "Alterar Data de Vencimento"; 
                    $btn_change_date = "'btn btn-sm btn-success'"; 
                    $fa = "'fa fa-calendar-check-o'";
                }

                //VERIFICANDO A DATA EM QUE FOI PAGA, SE FOI ATRASADO OU NÃO
                if ($paid_date < $exp_date) {
                    $btn = "'btn btn-sm btn-success disabled'";
                } else {
                    $btn = "'btn btn-sm btn-warning disabled'";
                }

                //TRANSFORMANDO DATA DE VENCIMENTO PARA PADRÃO BRASILEIRO
                if ($exp_date != '0000-00-00') {
                    $exp_date_create = date_create($exp_date); 
                    $exp_date = date_format($exp_date_create, 'd/m/Y');
                } else {
                    $exp_date = "";
                }
        
                //TRANSFORMANDO DATA DE PAGAMENTO PARA PADRÃO BRASILEIRO        
                if ($paid_date != '0000-00-00') {
                    $paid_date_create = date_create($paid_date); 
                    $paid_date = date_format($paid_date_create, 'd/m/Y');
                } else {
                    $paid_date = "";
                }

                //VERIFICANDO STATUS E ATRIBUINDO VALORES PARA A TABELA
                if ($status == "ativo") {
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

	public function verifyIfExists($registryToVerify)
    {	      
      	$registry = new Registry($this, $this->prepareInstance);
      	$registry->setName($registryToVerify);
      	return $registry->verifyIfExists();
    }

    public function setSession($result, $sender, $verbOk, $verbNo)
    {
        if ($result == 1) {
            $this->sessionController->setSession($sender . "RegistryOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Cartório " . $verbOk . " com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession($sender . "RegistryNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao " . $verbNo . " cartório.");
            $this->sessionController->set();
        }

        header("Location:../../../dashboard/cartorios");
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Registry'.'_page_'.$_SESSION['login']])) {
            header("Location:../dashboard");
        }
    }

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RegistryDataController();
        }
        return self::$instance;
    }
}
