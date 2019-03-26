<?php
include_once __DIR__ . "/../../../common/session.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/role.php";

$roleController = new RoleDataController();
$roleController->setPostReceived($_POST);
$roleController->setGetReceived($_GET);
$roleController->verifyData();

class RoleDataController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $sessionController;

    private $postReceived;
    private $getReceived;

    public function getPostReceived() 
    {
        return $this->postReceived;
    }

    public function setPostReceived($postReceived) 
    {
        $this->postReceived = $postReceived;
    }

    public function getGetReceived() 
    {
        return $this->getReceived;
    }
    
    public function setGetReceived($getReceived) 
    {
        $this->getReceived = $getReceived;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->sessionController = Session::getInstance();
    }

    public function verifyData()
    {
        if (isset($this->getReceived['id'])) {
            if (isset($this->getReceived['type'])) {
                $this->active();
            } else {
                $this->delete();
            }
        }

        if (isset($this->postReceived['desc_role']) && isset($this->postReceived['type_role'])) {
            $this->new();
        }
    }

    public function new()
    {
        $role = new Role($this, $this->prepareInstance);
        $role->setDescription($this->postReceived['desc_role']);
        
        if ($this->postReceived['type_role'] == "employee") {
            $role->setType(0);
        } else {
            $role->setType(1);  
        }

        $result = $role->register();
        $this->setSession($result, "new", "criado", "criar");
    }

    function active()
    {
        $role = new Role($this, $this->prepareInstance);
        $role->setId($this->getReceived['id']);
        $result = $role->active();
        $this->setSession($result, "active", "ativado", "ativar");
    }

    function delete()
    {
        $role = new Role($this, $this->prepareInstance);
        $role->setId($this->getReceived['id']);
        $result = $role->delete();
        $this->setSession($result, "delete", "desativado", "desativar");
    }

    function setSession($result, $sender, $verbOk, $verbNo)
    {
        if ($result == 1) {
            $this->sessionController->setSession($sender . "RoleOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Módulo " . $verbOk . " com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession($sender . "RoleNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao " . $verbNo . " módulo.");
            $this->sessionController->set();
        }

        header("Location:../../../painel/cargos");
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RoleDataController();
        }
        return self::$instance;
    }
}
