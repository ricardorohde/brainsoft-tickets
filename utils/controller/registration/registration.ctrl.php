<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../module/module.ctrl.php";
include_once __DIR__ . "/../role/role.ctrl.php";
include_once __DIR__ . "/../category/category.ctrl.php";

class RegistrationController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $moduleController;
    private $roleController;
    private $categoryController;

    private $allModules;
    private $allRoles;
    private $category;

    public function getAllModules() 
    {
        return $this->allModules;
    }
    
    public function setAllModules($allModules) 
    {
        $this->allModules = $allModules;
    }

    public function getAllRoles() 
    {
        return $this->allRoles;
    }
    
    public function setAllRoles($allRoles) 
    {
        $this->allRoles = $allRoles;
    }

    public function getCategory() 
    {
        return $this->category;
    }
    
    public function setCategory($category) 
    {
        $this->category = $category;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->moduleController = ModuleController::getInstance();
        $this->roleController = RoleController::getInstance();
        $this->categoryController = CategoryController::getInstance();
    }

    public function findAllModules()
    {
        $this->allModules = $this->moduleController->findAll();
    }

    public function findAllRoles()
    {
        $this->allRoles = $this->roleController->findAll();
    }

    public function findCategoryByIdAndOrder($id)
    {
        $this->category = $this->categoryController->findCategoryByIdAndOrder($id);
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Module'.'_page_'.$_SESSION['login']])) {
            header("Location:../dashboard");
        }
    }
    
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RegistrationController();
        }
        return self::$instance;
    }
}
