<?php
require_once __DIR__ . "/../navbar/navbar.ctrl.php";
require_once __DIR__ . "/../employee/employee.ctrl.php";
require_once __DIR__ . "/../module/module.ctrl.php";
require_once __DIR__ . "/../category/category.ctrl.php";
require_once __DIR__ . "/../registry/registry.ctrl.php";
require_once __DIR__ . "/../client/client.ctrl.php";
require_once __DIR__ . "/../chat/chat.ctrl.php";
require_once __DIR__ . "/../../model/ticket.php";

class AllTicketController
{
	private static $instance;
	private $prepareInstance;
	private $ticketInstance;
	private $navBarController;
	private $employeeController;
	private $moduleController;
	private $categoryController;
	private $registryController;
	private $clientController;
	private $chatController;

	private $actualDateToFind;
	private $initialDateToFind;
	private $filterToShow;
	private $hasFilter;
	private $tickets;
	private $attendants;
	private $statusOfFilter;
	private $attendantIdOfFilter;
	private $attendantsToForward;
	private $moduleOfTicket;
	private $categoryModule;
	private $registry;
	private $client;
	private $employee;
	private $chat;

	public function setNavBarController($navBarController)
	{
	  	$this->navBarController = $navBarController;
	}

	public function setPrepareInstance($prepareInstance)
	{
		$this->prepareInstance = $prepareInstance;
	}

	public function getActualDateToFind()
	{
	  	return $this->actualDateToFind;
	}
	
	public function setActualDateToFind($actualDateToFind)
	{
	  	$this->actualDateToFind = $actualDateToFind;
	}

	public function getInitialDateToFind()
	{
	  	return $this->initialDateToFind;
	}
	
	public function setInitialDateToFind($initialDateToFind)
	{
	  	$this->initialDateToFind = $initialDateToFind;
	}

	public function getFilterToShow()
	{
	  	return $this->filterToShow;
	}
	
	public function setFilterToShow($filterToShow)
	{
	  	$this->filterToShow = $filterToShow;
	}

	public function getHasFilter()
	{
	  	return $this->hasFilter;
	}
	
	public function setHasFilter($hasFilter)
	{
	  	$this->hasFilter = $hasFilter;
	}

	public function getTickets()
	{
	  	return $this->tickets;
	}
	
	public function setTickets($tickets)
	{
	  	$this->tickets = $tickets;
	}

	public function getAttendants()
	{
	  	return $this->attendants;
	}
	
	public function setAttendants($attendants)
	{
	 	$this->attendants = $attendants;
	}

	public function getStatusOfFilter()
	{
	  	return $this->statusOfFilter;
	}
	
	public function setStatusOfFilter($statusOfFilter)
	{
	  	$this->statusOfFilter = $statusOfFilter;
	}

	public function getAttendantIdOfFilter()
	{
	  	return $this->attendantIdOfFilter;
	}
	
	public function setAttendantIdOfFilter($attendantIdOfFilter)
	{
	  	$this->attendantIdOfFilter = $attendantIdOfFilter;
	}

	public function getAttendantsToForward()
	{
	  	return $this->attendantsToForward;
	}
	
	public function setAttendantsToForward($attendantsToForward)
	{
	  	$this->attendantsToForward = $attendantsToForward;
	}

	public function getModuleOfTicket()
	{
	  	return $this->moduleOfTicket;
	}
	
	public function setModuleOfTicket($moduleOfTicket)
	{
	 	$this->moduleOfTicket = $moduleOfTicket;
	}

	public function getCategoryModule()
	{
	  	return $this->categoryModule;
	}
	
	public function setCategoryModule($categoryModule)
	{
	  	$this->categoryModule = $categoryModule;
	}

	public function getRegistry()
	{
	  	return $this->registry;
	}
	
	public function setRegistry($registry)
	{
	  	$this->registry = $registry;
	}

	public function getClient()
	{
	  return $this->client;
	}
	
	public function setClient($client)
	{
	  $this->client = $client;
	}

	public function getEmployee()
	{
	  return $this->employee;
	}
	
	public function setEmployee($employee)
	{
	  $this->employee = $employee;
	}

	public function getChat()
	{
	  return $this->chat;
	}
	
	public function setChat($chat)
	{
	  $this->chat = $chat;
	}

	function __construct()
	{
		$this->setTimezone();
		$this->navBarController = NavBarController::getInstance();
		$this->prepareInstance = $this->navBarController->getPrepareInstance();
		$this->ticketInstance = new Ticket(self::$instance, $this->prepareInstance);
		$this->employeeController = EmployeeController::getInstance();
		$this->moduleController = ModuleController::getInstance();
		$this->categoryController = CategoryController::getInstance();
		$this->registryController = RegistryController::getInstance();
		$this->clientController = ClientController::getInstance();
		$this->chatController = ChatController::getInstance();
	}

	function setTimezone()
	{
		date_default_timezone_set('America/Sao_Paulo');
	}

	function verifyCookie($cookie)
	{
		if (isset($cookie['date_to_filter'])) {
			$this->actualDateToFind = $cookie['date_to_filter'];
		} else {
			date_default_timezone_set('America/Sao_Paulo');
			$day   = date('d');
			$month = date('m');
			$year  = date('Y');
			$this->actualDateToFind = $year . "-" . $month . "-" . $day; 
		}

		$this->initialDateToFind = date('Y-m-d', strtotime("-15 day", strtotime($this->actualDateToFind)));
	}

	function verifyPost($post)
	{
		if (isset($post['initial-date-filter'])) {
          	$this->hasFilter = true;
          	$this->initialDateToFind = date('Y-m-d', strtotime($post['initial-date-filter']));
          	$this->actualDateToFind = date('Y-m-d', strtotime("+1 day", strtotime($this->actualDateToFind)));
          
          	if (isset($post['filter-by-period'])) {
        		$this->tickets = $this->ticketInstance->filterByPeriod($this->initialDateToFind, $this->actualDateToFind);

            	$this->actualDateToFind = date('Y-m-d', strtotime("-1 day", strtotime($this->actualDateToFind)));
            	$this->filterToShow = "de " . date('d/m/Y', strtotime($this->initialDateToFind)) . " até " . date('d/m/Y', strtotime($this->actualDateToFind));
          	} else if (isset($post['filter-by-attendant'])) {
            	$this->attendantIdOfFilter = $post['attendant'];
           		$this->statusOfFilter = $post['status'];
           		$this->tickets = $this->ticketInstance->filterByPeriodAndAttendant($this->initialDateToFind, $this->actualDateToFind, $this->attendantIdOfFilter, $this->statusOfFilter);
            	$this->actualDateToFind = date('Y-m-d', strtotime("-1 day", strtotime($this->actualDateToFind)));
            	$this->filterToShow = "de " . date('d/m/Y', strtotime($this->initialDateToFind)) . " até " . date('d/m/Y', strtotime($this->actualDateToFind));
          	}
        } else {
          	$this->hasFilter = false;
          	$this->tickets = $this->ticketInstance->filterByActualDate($this->actualDateToFind);

          	$this->filterToShow = date('d/m/Y', strtotime($this->actualDateToFind));
        }

        $this->attendants = $this->employeeController->filterAttendantsByTwoGroups();
	}

	public function forwardTo()
	{
		$this->attendantsToForward = $this->employeeController->findToForward();
	}

	public function findModule($idModule)
	{
        $this->moduleOfTicket = $this->moduleController->findById($idModule);
	}

	public function findCategoryModule($idCategoryModule)
	{
		$this->categoryModule = $this->categoryController->findById($idCategoryModule);
	}

	public function findRegistry($idRegistry)
	{
		$this->registry = $this->registryController->findById($idRegistry);
	}

	public function findClient($idClient)
	{
		$this->client = $this->clientController->findById($idClient);
	}

	public function findEmployee($idEmployee)
	{
		$this->employee = $this->employeeController->findById($idEmployee);
	}

	public function findChat($idChat)
	{
		$this->chat = $this->chatController->findById($idChat);
	}

	public function verifyPermission()
	{
  		if (!isset($_SESSION['Ticket'.'_page_'.$_SESSION['login']])) {
    		header("Location:../dashboard");
  		}
	}

	public static function getInstance()
	{
		if (!self::$instance) {
      		self::$instance = new AllTicketController();
		}
    	return self::$instance;
	}
}
