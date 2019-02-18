<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/module.php";

class ModuleController
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

    public function findAll()
    {
        $module = new Module($this, $this->prepareInstance);
        return $module->findAll();
    }

    public function findById($idModule)
    {
        $module = new Module($this, $this->prepareInstance);
        $module->setId($idModule);
        return $module->findById();
    }

    function findIdByDescriptionAndCategory($description, $idCategory){
        $module = new Module($this, $this->prepareInstance);
        $module->setDescription($description);
        $module->setIdCategory($idCategory);
        return $module->findIdByDescriptionAndCategory();
    }
    
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ModuleController();
        }
        return self::$instance;
    }
}
