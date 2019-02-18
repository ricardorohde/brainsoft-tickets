<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../registry/registry.ctrl.php";
require_once __DIR__ . "/../../model/client.php";

if (isset($_POST['registry']) && !empty($_POST['registry'])) {
    $controller = ClientJsController::getInstance();
    $controller->setPostReceived($_POST);
    $controller->findClient();
}

class ClientJsController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $registryController;

    private $postReceived;

    public function getPostReceived() 
    {
        return $this->postReceived;
    }
    
    public function setPostReceived($postReceived) 
    {
        $this->postReceived = $postReceived;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->registryController = RegistryController::getInstance();
    }

    function findClient()
    {
        $nameRegistry = $this->postReceived['registry'];

        $rawId = $this->registryController->findIdByName($nameRegistry);
        $id = $rawId[0]['id'];

        $client = new Client($this, $this->prepareInstance);
        $client->setIdRegistry($id);
        $clients = $client->findClients();

        for ($i = 0; $i < count($clients); $i++) { 
            $id = $clients[$i]['id'];
            $name = utf8_encode($clients[$i]['name']);

            $option = "<option value='$id'>$name</option>";
            echo $option;
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ClientJsController();
        }
        return self::$instance;
    }
}
