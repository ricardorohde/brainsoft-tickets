<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
require_once __DIR__ . "/../../model/category.php";

class CategoryController{

    private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function findById($idCategory)
    {
        $category = new CategoryModule($this, $this->prepareInstance);
        $category->setId($idCategory);
        return $category->findById();
    }

    public function findIdByDescription($description)
    {
        $category = new CategoryModule($this, $this->prepareInstance);
        $category->setDescription($description);
        return $category->findIdByDescription();
    }

    public function findCategoryByIdAndOrder($id)
    {
        $category = new CategoryModule($this, $this->prepareInstance);
        $category->setId($id);
        return $category->findCategoryByIdAndOrder();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CategoryController();
        }
        return self::$instance;
    }
}
