<?php
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../ticket/ticket.ctrl.php";
include_once __DIR__ . "/../../../common/session.php";

new LogoutController();

class LogoutController
{
	private static $instance;
    private $navBarController;
	private $employeeController;
	private $ticketController;
	private $sessionInstance;

	function __construct()
	{
		$this->employeeController = new EmployeeController();
		$this->ticketController = new TicketController();
		$this->sessionInstance = new Session("");
		$this->logout();
	}

	function logout()
	{
		$id = $this->employeeController->findByCredential($_SESSION['login'])['id'];
		if ($this->ticketController->checkIfHasOpenTicketByEmployee($id)['total'] < 1) {
			$this->employeeController->statusOnChat($_SESSION['login'], "off");
			$this->sessionInstance->destroy();
			header("Location:/");
		} else {
			header("Location:/painel/fila-interna");
		}
	}

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new LogoutController();
        }
        return self::$instance;
    }
}
