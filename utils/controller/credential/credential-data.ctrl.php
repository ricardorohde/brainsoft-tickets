<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../authorization/authorization.ctrl.php";
include_once __DIR__ . "/../../model/credential.php";

new CredentialDataController();

class CredentialDataController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $employeeController;
    private $authorizationController;

    private $dataReceived;

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->dataReceived = $_POST;
        $this->verifyDataReceived();
    }

    public function verifyDataReceived()
    {
        if (isset($this->dataReceived['submit'])) {
            foreach ($this->dataReceived as $key => $value) {
                if ((!isset($this->dataReceived[$key]) || empty($this->dataReceived[$key]))) {
                    //$this->setHeader(null, null, '400');
                }
            }

            if ($this->dataReceived['submit'] == 'fromDoLogin' || $this->dataReceived['submit'] == 'submitFromIndex') {
                $this->checkLogin();
            } 

            if ($this->dataReceived['submit'] == 'submitFromChangePass' ){
                $this->changePassword();
            }
        }
    }

    function checkLogin(){
        $credential = new Credential($this, $this->prepareInstance);
        
        $credential->setLogin($this->dataReceived['login']);
        $credential->setPassword($this->dataReceived['password']);
        $credential->checkLogin();
    }       

    function changePassword(){
        if ($this->dataReceived['newPass'] == $this->dataReceived['confirmPass']) {
            $credential = new Credential($this, $this->prepareInstance);

            $credential->setId($_SESSION['login']);
            $credential->setPassword($this->dataReceived['newPass']);
            $credential->changePassword();          
        } else {
            $this->setHeader(NULL, NULL, '304');
        }
    }

    public function verifyChangePass($result)
    {
        if ($result == 1) {
            $this->setHeader(NULL, NULL, '202');
        } else{
            $this->setHeader(NULL, NULL, '409');
        }
    }

    public function setHeader($id, $password, $status)
    {
        switch ($status) {
            case '200':
                $this->employeeController->isOnChat($id, "yes"); //DEIXANDO O USUÁRIO ONLINE NA FILA
                $_SESSION['login'] = $id;

                $this->authorizationController = AuthorizationController::getInstance();
                $this->authorizationController->setIdInSession($id);
                $authToUser = $this->authorizationController->findAuthorizationsById();
                $this->authorizationController->setAuthorizations($authToUser);

                $this->authorizationController->authorizePage();       

                unset($_SESSION['withoutLogin']);
                unset($_SESSION['errorLogin']);
                unset($_SESSION['thereIsProblemInLogin']);
                header("Location:../../../painel");
                break;
            case '404':
                unset($_SESSION['withoutLogin']);
                $_SESSION['errorLogin'] = "<strong>Erro!</strong> Usuário e/ou Senha incorretos.";
                header("Location:/utils/do-login.php");
                break;
            case '202':
                unset($_SESSION['passDefault']);
                unset($_SESSION['needSamePass']);
                unset($_SESSION['passNotChanged']);
                $_SESSION['passChanged'] = "<strong>Sucesso!</strong> Senha alterada com êxito.";
                header("Location:../../painel");
                break;
            case '409':
                $_SESSION['passNotChanged'] = "<strong>Erro!</strong> Ocorreu um problema ao alterar a sua senha.";
                header("Location:../../painel");
                break;
            case '304':
                $_SESSION['needSamePass'] = "<strong>Erro!</strong> As senhas necessitam ser iguais.";
                header("Location:../../painel");
                break;
            case '400':
                $_SESSION['thereIsProblemInLogin'] = "<strong>Erro!</strong> Preencha todos os campos para entrar.";
                header("Location:/utils/do-login.php");
                break;
            default:
                break;
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CredentialDataController();
        }
        return self::$instance;
    }
}