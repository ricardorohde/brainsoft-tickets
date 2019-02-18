<?php
include_once "../../utils/pct-chat/api.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../module/module.ctrl.php";
include_once __DIR__ . "/../chat/chat.ctrl.php";
include_once __DIR__ . "/../../model/ticket.php";

class NewFindTicketController
{
	private static $instance;
	private $prepareInstance;
	private $navBarController;
	private $employeeController;
	private $moduleController;
	private $chatController;

	private $apiPct;

	public function getApiPct()
	{
	 	return $this->apiPct;
	}
	
	public function setApiPct($apiPct)
	{
	 	$this->apiPct = $apiPct;
	}

	public function setNavBarController($navBarController)
	{
	  	$this->navBarController = $navBarController;
	}

	public function setPrepareInstance($prepareInstance)
	{
		$this->prepareInstance = $prepareInstance;
	}

	function __construct()
	{
		$this->setNavBarController(NavBarController::getInstance());
		$this->setPrepareInstance($this->navBarController->getPrepareInstance());
		$this->setApiPct(new ApiPct());
		$this->employeeController = EmployeeController::getInstance();
		$this->moduleController = ModuleController::getInstance();
		$this->chatController = ChatController::getInstance();
	}

	public function getDay()
	{
		return $this->apiPct->getDay();
	}

	public function getMonth()
	{
		return $this->apiPct->getMonth();
	}

	public function getYear()
	{
		return $this->apiPct->getYear();
	}

	public function getCustomersAtReception()
	{
		$this->apiPct->consultCustomersAtReception();
		return $this->apiPct->getDataCustomersAtReception();
	}

	public function findAttendantsInQueue()
	{
		return $this->employeeController->findToForward();
	}

	public function findCalls()
	{
		$ticket = new Ticket($this->getInstance(), $this->prepareInstance);
		$ticket->setSource("telefone");
		return $ticket->findBySource();
	}

	public function findModule($idModule)
	{
		return $this->moduleController->findById($idModule);
	}

	public function findEmployee($idEmployee)
	{	
		return $this->employeeController->findById($idEmployee);
	}

	public function findChat($idChat)
	{
		return $this->chatController->findById($idChat);
	}

	function verifyPermission()
	{
  		if (!isset($_SESSION['Ticket'.'_page_'.$_SESSION['login']])) {
    		header("Location:../dashboard");
  		}
	}

	public static function getInstance()
	{
		if (!self::$instance) {
      		self::$instance = new NewFindTicketController();
		}
    	return self::$instance;
	}
}
