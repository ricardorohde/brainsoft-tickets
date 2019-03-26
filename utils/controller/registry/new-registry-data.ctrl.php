<?php
include_once __DIR__ . "/../../../common/session.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../city/city.ctrl.php";
include_once __DIR__ . "/../state/state.ctrl.php";
include_once __DIR__ . "/../../model/registry.php";

$controller = new NewRegistryDataController();
$controller->verifyDataPostReceived();

class NewRegistryDataController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $cityController;
    private $stateController;
    private $sessionController;

    private $dataGetReceived;
    private $dataPostReceived;

    private $registry;
    private $city;
    private $state;
    private $allStates;

    public function getDataGetReceived() 
    {
        return $this->dataGetReceived;
    }
    
    public function setDataGetReceived($dataGetReceived) 
    {
        $this->dataGetReceived = $dataGetReceived;
    }

    public function getDataPostReceived() 
    {
        return $this->dataPostReceived;
    }
    
    public function setDataPostReceived($dataPostReceived) 
    {
        $this->dataPostReceived = $dataPostReceived;
    }

    public function getRegistry() 
    {
        return $this->registry;
    }
    
    public function setRegistry($registry) 
    {
        $this->registry = $registry;
    }

    public function getCity() 
    {
        return $this->city;
    }
    
    public function setCity($city) 
    {
        $this->city = $city;
    }

    public function getState() 
    {
        return $this->state;
    }
    
    public function setState($state) 
    {
        $this->state = $state;
    }

    public function getAllStates() 
    {
        return $this->allStates;
    }
    
    public function setAllStates($allStates) 
    {
        $this->allStates = $allStates;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->cityController = CityController::getInstance();
        $this->stateController = StateController::getInstance();
        $this->sessionController = Session::getInstance();

        $this->setDataGetReceived($_GET);
        $this->setDataPostReceived($_POST);
    }

    public function verifyDataGetReceived()
    {
        if (isset($this->dataGetReceived['id'])) {
            $this->findById($this->dataGetReceived['id']);
            $this->findCityById($this->registry['id_city']);
            $this->findStateById($this->city['id_state']);
        }
    }

    public function verifyDataPostReceived()
    {
        if (isset($this->dataPostReceived['newRegistry'])) {
            $this->new();
        }
    }

	public function new()
	{
		$registryInstance = new Registry($this, $this->prepareInstance);

		if ($this->dataPostReceived['id_registry'] == NULL) {
			$registryInstance->setName($this->dataPostReceived['nameRegistry']);
			$registryInstance->setIdCity($this->dataPostReceived['city']);
			$result = $registryInstance->register();

			$this->setSession($result, "new", "<b><u>$this->dataPostReceived['nameRegistry']</u></b> registrado", "registrar");
       	} else {
			$registryInstance->setId($this->dataPostReceived['id_registry']);
			$registryInstance->setName($this->dataPostReceived['nameRegistry']);
			$registryInstance->setIdCity($this->dataPostReceived['city']);
       		$result = $registryInstance->update();

       		$this->setSession($result, "update", "<b><u>$this->dataPostReceived['nameRegistry']</u></b> atualizado", "atualizar");
       	}
	}

    public function findById($id)
    {
        $registryInstance = new Registry($this, $this->prepareInstance);
        $registryInstance->setId($id);
        $this->registry = $registryInstance->findById();
    }

	public function findIdByName($name)
    {
		$registryInstance = new Registry($this, $this->prepareInstance);
		$registryInstance->setName($name);
		return $registryInstance->findIdByName();
	}

    public function findCityById($id)
    {
        $this->city = $this->cityController->findById($id);
    }

    public function findStateById($id)
    {
        $this->state = $this->stateController->findById($id);
    }

    public function findAllStates()
    {
        $this->allStates = $this->stateController->findAllStates();
    }

	public function verifyIfExists($registryToVerify)
    {	      
      	$registryInstance = new Registry($this, $this->prepareInstance);
      	$registryInstance->setName($registryToVerify);
      	return $registryInstance->verifyIfExists();
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

        header("Location:../../../painel/cartorios");
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Registry'.'_page_'.$_SESSION['login']])) {
            header("Location:../painel");
        }
    }

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new NewRegistryDataController();
        }
        return self::$instance;
    }
}
