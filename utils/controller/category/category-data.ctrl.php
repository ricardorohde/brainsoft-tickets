<?php
include_once __DIR__ . "/../../../common/session.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/category.ctrl.php";
include_once __DIR__ . "/../../model/category.php";

$controller = new CategoryDataController();
$controller->setPostReceived($_POST);
$controller->verifyData();

class CategoryDataController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $categoryController;
    private $sessionController;

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
        $this->sessionController = Session::getInstance();
    }

    function verifyData()
    {
        if (isset($this->postReceived['desc_category']) && isset($this->postReceived['group'])) {
            $this->new();
        }
    }

   function new()
   {
        $category = new CategoryModule($this, $this->prepareInstance);
        $category->setDescription($this->postReceived['desc_category']);
        $category->setTGroup($this->postReceived['group']);
        $result = $category->register();

        $this->setSession($result, "new", "criada", "criar");
    }

    function setSession($result, $sender, $verbOk, $verbNo)
    {
        if ($result == 1) {
            $this->sessionController->setSession($sender . "CategoryOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Categoria " . $verbOk . " com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession($sender . "CategoryNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao " . $verbNo . " categoria.");
            $this->sessionController->set();
        }

        header("Location:../../../painel/modulos");
    }

   function getInstance(){
      return new CategoryDataController();
   }

}
