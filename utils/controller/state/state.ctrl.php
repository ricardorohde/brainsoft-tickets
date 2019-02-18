<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/state.php";

class StateController
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

    public function findAllStates()
    {
    	$state = new State($this, $this->prepareInstance);
    	return $state->findAll();
    }

    public function findById($id)
    {
        $state = new State($this, $this->prepareInstance);
        $state->setId($id);
        return $state->findById();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new StateController();
        }
        return self::$instance;
    }
}
