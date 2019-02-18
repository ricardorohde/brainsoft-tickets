<?php
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "../../../commmon/session.php";

new LogoutController();

class LogoutController
{
	private static $instance;
    private $navBarController;
	private $employeeController;
	private $sessionInstance;

	function __construct()
	{
		$this->employeeController = new EmployeeController();
		$this->sessionInstance = new Session("");
		$this->logout();
	}

	function logout()
	{ 
		$this->employeeController->isOnChat($_SESSION['login'], "no");
		$this->sessionInstance->destroy();
		header("Location:/");
	}

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new LogoutController();
        }
        return self::$instance;
    }
}
