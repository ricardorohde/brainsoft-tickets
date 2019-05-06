<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/credential.php";

new CredentialJsController();

class CredentialJsController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $dataReceived;

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
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
        if (isset($this->dataReceived['userToVerify'])){
            echo $this->verifyIfExists(); //ECHO TO PRINT IN JS
        }
    }

    public function verifyIfExists()
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setLogin($this->dataReceived['userToVerify']);
        $result = $credential->verifyIfExists();
        return $result['total'];
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CredentialJsController();
        }
        return self::$instance;
    }
}