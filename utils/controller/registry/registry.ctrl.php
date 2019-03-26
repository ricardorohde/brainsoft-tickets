<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../city/city.ctrl.php";
include_once __DIR__ . "/../../model/registry.php";

class RegistryController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $cityController;

    private $allRegistries;
    private $sqlRegistryIds;

    public function getAllRegistries() 
    {
    	return $this->allRegistries;
    }
    
    public function setAllRegistries($allRegistries) 
    {
    	$this->allRegistries = $allRegistries;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->cityController = CityController::getInstance();
    }

	public function findAll()
	{
		$registry = new Registry($this, $this->prepareInstance);

		$registries = $registry->findAll();
        $registryIds = array_column($registries, 'id');
        $this->sqlRegistryIds = implode(',', $registryIds);
        $this->allRegistries = $registries;
	}

    public function findById($id){
        $registry = new Registry($this, $this->prepareInstance);
        $registry->setId($id);
        return $registry->findById();
    }

    public function findIdByName($name)
    {
        $registry = new Registry($this, $this->prepareInstance);
        $registry->setName($name);
        return $registry->findIdByName();
    }

    public function findDataOfRegistries()
    {
        $registry = new Registry($this, $this->prepareInstance);
        return $registry->findDataBySqlIds($this->sqlRegistryIds);
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Registry'.'_page_'.$_SESSION['login']])) {
            header("Location:/painel/conta");
        }
    }

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RegistryController();
        }
        return self::$instance;
    }
}
