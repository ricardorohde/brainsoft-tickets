<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/credential.php";

new CredentialDataController();

class CredentialDataController
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
        
        $this->redirectDataReceived($_POST);
    }

    public function redirectDataReceived($post)
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->verifyDataReceived($post);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CredentialDataController();
        }
        return self::$instance;
    }
}
