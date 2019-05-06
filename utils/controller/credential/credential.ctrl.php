<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../authorization/authorization.ctrl.php";
include_once __DIR__ . "/../../model/credential.php";

class CredentialController
{
    private static $instance;
    private $prepareInstance;

    private $navBarController;
    private $employeeController;
    private $authorizationController;

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->authorizationController = AuthorizationController::getInstance();
    }

    public function findById($id)
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setId($id);
        return $credential->findById();
    }

    public function findByLogin($login)
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setLogin($login);
        return $credential->findByLogin()['id'];
    }

    public function new($login, $password)
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setLogin($login);
        $credential->setPassword($password);
        return $credential->register();
    }

    public function change($actualPass, $newPass, $confirmationPass, $idLogin)
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setId($idLogin);
        $credential->setPassword($actualPass);
        $credential->changePassword($newPass, $confirmationPass);
    }

    public function changeToClient($newPass, $confirmationPass, $idLogin)
    {
        $credential = new Credential($this, $this->prepareInstance);
        $credential->setId($idLogin);
        $credential->changePasswordToClient($newPass, $confirmationPass);
    }

    public function statusOnChat($id)
    {
        $this->employeeController->statusOnChat($id, "on");
        return $this->employeeController->findByCredential($id);
    }

    public function verifyAuthorizations($id)
    {
        $this->authorizationController->setIdInSession($id);
        $authToUser = $this->authorizationController->findAuthorizationsById();
        $this->authorizationController->setAuthorizations($authToUser);
        $this->authorizationController->authorizePage();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CredentialController();
        }
        return self::$instance;
    }
}

