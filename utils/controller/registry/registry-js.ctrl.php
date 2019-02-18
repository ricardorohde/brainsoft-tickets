<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/registry.php";

new RegistryJsController();

class RegistryJsController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;

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
        $this->dataReceived = $_POST;
        $this->verifyDataReceived();
    }

    public function verifyDataReceived()
    {
    	$city = $this->dataReceived['valor'];

		$registry = new Registry($this, $this->prepareInstance);
		$registry->setIdCity($city);
		$registries = $registry->findRegistries();
		$qtd_registries = count($registries);

		if ($qtd_registries > 0) {
			$info = utf8_encode("<option value=''>Selecione um cartorio...</option>");
		} else {
			$info = utf8_encode("<option value='' disabled selected>Nenhum cartorio registrado para esta cidade!</option>");
		}

    	echo $info;

		for ($i=0; $i < $qtd_registries; $i++) { 
    		$id = $registries[$i]['id'];
    		$name = $registries[$i]['name'];
    		$option = "<option value='$id'>$name</option>";
    		echo $option;
		}
    }

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RegistryJsController();
        }
        return self::$instance;
    }
}
