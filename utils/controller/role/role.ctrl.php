<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/role.php";

class RoleController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $allRoles;

    public function getAllRoles()
    {
        return $this->allRoles;
    }

    public function setAllRoles($allRoles)
    {
        $this->allRoles = $allRoles;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function findAll()
    {
        $role = new Role($this, $this->prepareInstance);
        $this->allRoles = $role->findAll();
    }

    public function findAllByType($type)
    {
        $role = new Role($this, $this->prepareInstance);
        $role->setType($type);
        return $role->findAllByType();
    }

    public function findRoleByStatusAndId($status, $id)
    {
    	$role = new Role($this, $this->prepareInstance);
    	$role->setStatus($status);
        $role->setId($id);
    	return $role->findRoleByStatusAndId();
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Role'.'_page_'.$_SESSION['login']])) {
            header("Location:/painel/conta");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RoleController();
        }
        return self::$instance;
    }
}
