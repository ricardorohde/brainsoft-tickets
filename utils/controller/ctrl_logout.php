<?php
include_once "ctrl_employee.php";
include_once "../../commmon/session.php";

$controllerLogout = new Logout();
$controllerLogout->logout();

class Logout
{
	private $controllerEmployee;
	private $sessionInstance;

	function __construct()
	{
		session_start();
		$this->controllerEmployee = new EmployeeController();
		$this->sessionInstance = new Session("");
	}

	function logout()
	{ 
		$this->controllerEmployee->isOnChat($_SESSION['login'], "no");
		$this->sessionInstance->destroy();
		header("Location:/");
	}
}
