<?php
include_once __DIR__ . "/../../../common/session.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../client/client.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../credential/credential.ctrl.php";

new NewUserDataController();

class NewUserDataController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $clientController;
    private $employeeController;
    private $credentialController;
    private $sessionController;

    private $dataReceived;

    function __construct()
    {
        $this->sessionController = Session::getInstance();
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->clientController = ClientController::getInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->credentialController = CredentialController::getInstance();
        $this->dataReceived = $_POST;
        $this->verifyDataReceived();
    }

    public function verifyDataReceived()
    {
        if (isset($this->dataReceived['submit'])) {
            foreach ($this->dataReceived as $key => $value) {
                if ((!isset($this->dataReceived[$key]) || empty($this->dataReceived[$key])) /*&& $this->data['typeUser'] != "employee"*/) {
                    //$this->setHeader(NULL, NULL, '400');
                }
            }

            if ($this->dataReceived['submit'] == 'newUser') {
                $this->new();
            }

            if ($this->dataReceived['submit'] == 'alterUser') {
                $this->update();
            }
        }
    }

    public function new()
    {
        $lastCredentialId = $this->credentialController->new($this->dataReceived['login'], $this->dataReceived['password'])[0]["last"];
        array_push($this->dataReceived, $lastCredentialId);

        if ($this->dataReceived['typeUser'] == 'client') {
            $result = $this->clientController->new($this->dataReceived);
        } else {
            $result = $this->employeeController->new($this->dataReceived);
        }

        $this->setSession($result, "new", "registrado", "registrar");
    }

    public function update()
    {
        if ($this->dataReceived['typeUser'] == 'client') {
            $result = $this->clientController->update($this->dataReceived);
        } else {
            $result = $this->employeeController->update($this->dataReceived);
        }

        $this->setSession($result, "update", "atualizado", "atualizar");
    }

    public function setSession($result, $sender, $verbOk, $verbNo)
    {
        if ($result == 1) {
            $this->sessionController->setSession($sender . "UserOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Usuário " . $verbOk . " com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession($sender . "UserNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao " . $verbNo . " o usuário.");
            $this->sessionController->set();
        }

        header("Location:../../../dashboard/usuarios");
    }
    
    public function verifyPermission()
    {
        if (!isset($_SESSION['User'.'_page_'.$_SESSION['login']])) {
            header("Location:../dashboard");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new NewUserDataController();
        }
        return self::$instance;
    }
}
