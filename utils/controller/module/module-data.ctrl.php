<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/module.php";
include_once __DIR__ . "/../../../common/session.php";

$controller = new ModuleDataController();
$controller->setPostReceived($_POST);
$controller->setGetReceived($_GET);
$controller->verifyData();

class ModuleDataController
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
        if(isset($this->getReceived['id'])) {
            if (isset($this->getReceived['type'])) {
                $this->active();
            } else{
                $this->delete();
            }
        }

        if (isset($this->postReceived['id']) && isset($this->postReceived['valor'])) {
            $this->update();
        }

        if (isset($this->postReceived['newModule'])) {
            $this->new();
        }
    }

    public function new()
    {
        $module = new Module($this, $this->prepareInstance);
        $module->setDescription($this->postReceived['module_desc']);
        $module->setIdCategory($this->postReceived['id_category']);
        $module->setLimitTime($this->postReceived['module_limit_time']);
        $result = $module->register();

        if ($result == 1) {
            $this->sessionController->setSession("moduleOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Módulo cadastrado com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession("moduleNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao cadastrar módulo.");
            $this->sessionController->set();
        }

        //header("Location:../../../dashboard/cadastros");
    }

    public function active()
    {
        $module = new Module($this, $this->prepareInstance);
        $module->setId($this->getReceived['id']);
        $result = $module->active();
        
        if ($result == 1) {
            $this->sessionController->setSession("activeModuleOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Módulo ativado com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession("activeModuleNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao ativar módulo.");
            $this->sessionController->set();
        }

        header("Location:../../../painel/modulos");
    }

    public function delete()
    {
        $module = new Module($this, $this->prepareInstance);
        $module->setId($this->getReceived['id']);
        $result = $module->delete();
        
        if ($result == 1) {
            $this->sessionController->setSession("deleteModuleOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Módulo desativado com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession("deleteModuleNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao desativar módulo.");
            $this->sessionController->set();
        }

        header("Location:../../../painel/modulos");
    }

    public function update()
    {
        $module = new Module($this, $this->prepareInstance);
        $module->setId($this->postReceived['id']);
        $module->setLimitTime($this->postReceived['valor']);
        $result = $module->update();

        if ($result == 1) {
            $this->sessionController->setSession("updateModuleOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Módulo alterado com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession("updateModuleNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao alterar módulo.");
            $this->sessionController->set();
        }

        header("Location:../../../painel/modulos");
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ModuleDataController();
        }
        return self::$instance;
    }
}
