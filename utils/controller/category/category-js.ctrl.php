<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/category.ctrl.php";
include_once __DIR__ . "/../../model/category.php";

$controller = new CategoryJsController();
$controller->setPostReceived($_POST);
$controller->verifyData();

class CategoryJsController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $categoryController;

    private $postReceived;

    public function getCategoryController() 
    {
        return $this->categoryController;
    }
    
    public function setCategoryController($categoryController) 
    {
        $this->categoryController = $categoryController;
    }

    public function getPostReceived() 
    {
        return $this->postReceived;
    }
    
    public function setPostReceived($postReceived) 
    {
        $this->postReceived = $postReceived;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->categoryController = CategoryController::getInstance();
    }

    function verifyData()
    {
        if (isset($this->postReceived['fromCategory'])) {
            echo $this->categoryController->findIdByDescription($this->postReceived['fromCategory']);
        }
    }
}
