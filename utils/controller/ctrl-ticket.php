<?php
include_once "../../utils/pct-chat/api.php";
include_once "navbar/navbar.ctrl.php";

if (isset($_POST['client'])) { 
	include_once "ctrl_client.php";
	include_once "ctrl-chat.php";
	include_once "ctrl-category.php";
	include_once "ctrl-module.php";
	include_once "../model/ticket.php";

	$controller = TicketController::getInstance();
	$controller->verifyData();
}

class TicketControllerOld
{
	private static $instance;
	private $prepareInstance;

	private $clientController;
	private $chatController;
	private $categoryModuleController;
	private $moduleController;
	private $apiPct;

	private $transferedAt;
	private $navBarController;

	//TO VIEW-ALL-TICKET PAGE
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

	public function getClientController()
	{
	    return $this->clientController;
	}

	public function setClientController($clientController)
	{
	    $this->clientController = $clientController;
	}

	public function getChatController()
	{
	    return $this->chatController;
	}

	public function setChatController($chatController)
	{
	    $this->chatController = $chatController;
	}

	public function getCategoryModuleController()
	{
	    return $this->categoryModuleController;
	}

	public function setCategoryModuleController($categoryModuleController)
	{
	    $this->categoryModuleController = $categoryModuleController;
	}

	public function getModuleController()
	{
	    return $this->moduleController;
	}

	public function setModuleController($moduleController)
	{
	    $this->moduleController = $moduleController;
	}

	public function getApiPct()
	{
	 	return $this->apiPct;
	}
	
	public function setApiPct($apiPct)
	{
	 	$this->apiPct = $apiPct;
	}

	public function getTransferedAt()
	{
	  	return $this->transferedAt;
	}
	
	public function setTransferedAt($transferedAt)
	{
	  	$this->transferedAt = $transferedAt;
	}

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

	function __construct()
	{
		$this->setTimezone();
		$this->setApiPct(new ApiPct());
		$this->setNavBarController(NavBarController::getInstance());
		$this->setPrepareInstance($this->navBarController->getPrepareInstance());

		if (isset($_POST['client'])) {
			$this->setClientController(new ClientController());
			$this->setChatController(new ChatController());
			$this->setCategoryModuleController(new CategoryModuleController());
			$this->setModuleController(new ModuleController());
		}
	}

	function setTimezone()
	{
		date_default_timezone_set('America/Sao_Paulo');
	}

	function verifyData()
	{
		if (isset($_POST['finishTicket'])) {
			$data = $_POST;

			$this->registerCtrl($data, 1);
		} else if (isset($_POST['submit'])) {
			foreach ($_POST as $key => $value) {
				if ((!isset($_POST[$key]) || empty($_POST[$key])) && $key != 'submit' && $key != 'module' && $key != 'attendant' && $key != 'resolution' && $key != 'historic') {
					$this->thereIsInputEmpty();
				}
			}

			$data = $_POST;
			$this->registerCtrl($data, 0);
		} else {
			header("Location:/dashboard/tickets");
		}
	}

	function registerCtrl($data, $finish)
	{
		$id_who_opened = $_SESSION['login'];

		if (isset($data['is_repeated'])) {
			$is_repeated = 1;
		} else {
			$is_repeated = 0;
		};

		$id_registry = $this->clientController->findIdRegistryByIdClient($data['client']);
		$id_chat_found = $this->chatController->searchIdCtrl($data['id_chat'], $data['attendant']);
		$id_category = $this->categoryModuleController->findIdByName($data['selected_category']);
		$id_module = $this->moduleController->findIdByNameAndCategory($data['selected_module'], $id_category);

		if ($id_chat_found == 0) {
			$id_chat = $this->chatController->registerCtrl($data['id_chat'], $data['opening_time'], $data['final_time'], $data['duration_in_minutes'])[0]['last'];

			$ticket = new Ticket();
			$ticket->setIdRegistry($id_registry);
			$ticket->setIdClient($data['client']);				
			$ticket->setPriority($data['priority']);
			$ticket->setStatus($data['status']);
			$ticket->setSource($data['source']);
			$ticket->setType($data['type']);
			$ticket->setGroup($data['group']);
			$ticket->setIdModule($id_module);
			$ticket->setIdAttendant($data['attendant']);
			$ticket->setResolution($data['resolution']);
			$ticket->setIsRepeated($is_repeated);
			$ticket->setidWhoOpened($id_who_opened);
			$ticket->setIdChat($id_chat);
			$ticket->register();

			$this->setSession("new");
		} elseif ($finish == 1) {
			$this->finishTicket($id_chat_found, $id_registry, $data['client'], $data['priority'], $data['status'], $data['source'],
				$data['type'], $data['group'], $id_module, $data['attendant'], $data['resolution'], $is_repeated, date("Y/m/d H:i:s"));
		} else {
			$this->chatController->updateCtrl($data['id_chat'], $data['final_time'], $data['duration_in_minutes']);

			$this->updateCtrl($id_chat_found, $id_registry, $data['client'], $data['priority'], $data['status'], $data['source'], $data['type'],
				$data['group'], $id_module, $data['attendant'], $data['resolution'], $is_repeated);
		}
	}

	function updateCtrl($id_chat_found, $id_registry, $client, $priority, $status, $source, $type, $group, $module, $attendant, $resolution, $is_repeated)
	{
		$ticket = new Ticket();
		$ticket->setIdChat($id_chat_found);
		$ticket->setIdRegistry($id_registry);
		$ticket->setIdClient($client);
		$ticket->setPriority($priority);
		$ticket->setStatus($status);
		$ticket->setSource($source);
		$ticket->setType($type);
		$ticket->setGroup($group);
		$ticket->setIdModule($module);
		$ticket->setIdAttendant($attendant);
		$ticket->setResolution($resolution);
		$ticket->setIsRepeated($is_repeated);
		$result = $ticket->update();

		$this->setSession("update");
		die();
	}

	function finishTicket($id_chat_found, $id_registry, $client, $priority, $status, $source, $type, $group, $module, $attendant, $resolution, $is_repeated, $finalized_at)
	{
		$ticket = new Ticket();
		$ticket->setIdChat($id_chat_found);
		$ticket->setIdRegistry($id_registry);
		$ticket->setIdClient($client);
		$ticket->setPriority($priority);
		$ticket->setStatus("solucionado");
		$ticket->setSource($source);
		$ticket->setType($type);
		$ticket->setGroup($group);
		$ticket->setIdModule($module);
		$ticket->setIdAttendant($attendant);
		$ticket->setResolution($resolution);
		$ticket->setIsRepeated($is_repeated);
		$ticket->setFinalizedAt($finalized_at);
		$ticket->setIdWhoClosed($_SESSION['login']);
		$result = $ticket->finish();

		$this->setSession("finish");
		die();
	}

	function setSession($action)
	{
		switch ($action) {
			case "new":
	      		unset($_SESSION['ticketStatus']);
	      		$_SESSION['ticketStatus'] = "<strong>Sucesso!</strong> Ticket cadastrado com êxito.";
	      		header("Location:../../dashboard/tickets");
				break;
			case "update":
				unset($_SESSION['ticketStatus']);
	      		$_SESSION['ticketStatus'] = "<strong>Sucesso!</strong> Ticket alterado com êxito.";
	      		header("Location:../../dashboard/tickets");
		    	break;
			case 'finish':
				unset($_SESSION['ticketStatus']);
	      		$_SESSION['ticketStatus'] = "<strong>Sucesso!</strong> Ticket finalizado com êxito.";
	      		header("Location:../../dashboard/tickets");
				break;
			default:
				break;
		}
	}

	function thereIsInputEmpty()
	{
		$_SESSION['thereIsProblemInTicket'] = "<strong>Erro!</strong> Preencha todos os campos para registrar.";
		header("Location:../dashboard/view_ticket.php");
		die();
	}

	function setIdChat($id)
	{	
		$this->apiPct->assignIdChatInSession($id);
	}

	function getHistoryOfChat()
	{
		$this->apiPct->consultAllChats();
		$this->apiPct->putFeaturesOfChatInVariables();
		return $this->apiPct->getDataOfEspecificChat();
	}

	function getAttendant()
	{
		return $this->apiPct->getAttendant();
	}

	function getClient()
	{
		return $this->apiPct->getClient();
	}

	function getIpClient()
	{
		return $this->apiPct->getIpClient();
	}

	function getStart()
	{
		return $this->apiPct->getStart();
	}

	function getFinal()
	{
		return $this->apiPct->getFinal();
	}

	function getRating()
	{
		return $this->apiPct->getRating();
	}

	function getOpenedAt()
	{
		return $this->apiPct->getOpenedAt();
	}

	function getDay()
	{
		return $this->apiPct->getDay();
	}

	function getMonth()
	{
		return $this->apiPct->getMonth();
	}

	function getYear()
	{
		return $this->apiPct->getYear();
	}

	function getCustomersAtReception()
	{
		$this->apiPct->consultCustomersAtReception();
		return $this->apiPct->getDataCustomersAtReception();
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
            	$elements = [$initial_date_to_find."%", $this->actual_date_to_find."%"];
            	$query = "SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
              		WHERE registered_at BETWEEN ? AND ? ORDER BY id DESC";
            	$this->tickets = $this->prepareInstance->prepare($query, $elements, "all");

            	$this->actualDateToFind = date('Y-m-d', strtotime("-1 day", strtotime($this->actualDateToFind)));
            	$this->filterToShow = "de " . date('d/m/Y', strtotime($this->initialDateToFind)) . " até " . date('d/m/Y', strtotime($this->actualDateToFind));
          	} else if (isset($post['filter-by-attendant'])) {
            	$attedantId = $post['attendant'];
           		$this->statusOfFilter = $post['status'];

            	$elements = [$initial_date_to_find."%", $this->actual_date_to_find."%", $attedantId, $this->statusOfFilter];
            	$query = "SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
              		WHERE (registered_at BETWEEN ? AND ?) AND id_attendant = ? AND t_status = ? ORDER BY id DESC";
            	$this->tickets = $this->prepareInstance->prepare($query, $elements, "all");

            	$this->actualDateToFind = date('Y-m-d', strtotime("-1 day", strtotime($this->actualDateToFind)));
            	$this->filterToShow = "de " . date('d/m/Y', strtotime($this->initialDateToFind)) . " até " . date('d/m/Y', strtotime($this->actualDateToFind));
          	}
        } else {
          	$this->hasFilter = false;
          	$element = $this->actualDateToFind."%";
          	$query = "SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
            WHERE registered_at LIKE ? ORDER BY t_status ASC, id DESC";
          	$this->tickets = $this->prepareInstance->prepare($query, $element, "all");

          	$this->filterToShow = date('d/m/Y', strtotime($this->actualDateToFind));
        }

        $element = ["nivel1", "nivel2"];
        $query = "SELECT id, name FROM employee WHERE t_group = ? OR t_group = ?";
        $this->attendants = $this->prepareInstance->prepare($query, $element, "all");
	}

	function forwardTo()
	{
		$elements = ["nivel1", "nivel2", "yes", "yes"];
		$query = "SELECT id, name FROM employee WHERE (t_group = ? OR t_group = ?) AND on_chat = ? AND (SELECT COUNT(*) FROM ticket WHERE id_attendant = employee.id AND t_status = ?) < 2 ORDER BY t_group, name";
		$this->attendantsToForward = $this->prepareInstance->prepare($query, $elements, "all");
	}

	function findModuleOfTicket()
	{
		
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
      		self::$instance = new TicketController();
		}
    	return self::$instance;
	}
}
