<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/credential.php";

class CredentialController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function findById($id)
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setId($id);
        return $credential->findById();
    }

    function new($login, $password){
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setLogin($login);
        $credential->setPassword($password);
        return $credential->register();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CredentialController();
        }
        return self::$instance;
    }
}