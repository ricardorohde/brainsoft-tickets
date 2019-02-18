<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/role.php";

class RoleController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function findAll()
    {
        $role = new Role($this, $this->prepareInstance);
        return $role->findAll();
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

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new RoleController();
        }
        return self::$instance;
    }
}
