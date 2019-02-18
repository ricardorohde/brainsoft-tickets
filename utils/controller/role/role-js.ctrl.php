<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/role.php";

$controller = new RoleJsController();
$controller->setPostReceived($_POST);
$controller->verifyData();

class RoleJsController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $postReceived;

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
    }

    public function verifyData()
    {
        if (isset($this->postReceived['typeUser']) && isset($this->postReceived['userInformed'])) {
            $this->display();
        }
    }

    public function display()
    {
        $role = new Role($this, $this->prepareInstance);

        if ($this->postReceived['typeUser'] == "employee") {
            $role->setType("0");
            $roles = $role->findAllByStatusAndType();
        } else {
            $role->setType("1");
            $roles = $role->findAllByStatusAndType();
        }

        echo "<option value=''>Selecione um cargo...</option>";

        for ($i = 0; $i < count($roles); $i++) { 
            $id = $roles[$i]['id'];
            $desc = $roles[$i]['description'] == "supportBrain" ? "Suporte" : $roles[$i]['description'];
            $status = $roles[$i]['description'] == $this->postReceived['userInformed'] ? "selected" : "";
            $option = "<option value='$id' $status>$desc</option>";
            echo $option;
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RoleJsController();
        }
        return self::$instance;
    }
}
