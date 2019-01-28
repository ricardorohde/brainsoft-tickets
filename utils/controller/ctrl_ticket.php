<?php
include_once "../../utils/pct-chat/api.php";

if (isset($_POST['client'])) { 
	include_once "ctrl_client.php";
	include_once "ctrl-chat.php";
	include_once "ctrl-category.php";
	include_once "ctrl-module.php";
	include_once "../model/ticket.php";

	$controller = TicketController::getInstance();
	$controller->verifyData();
}

class TicketController
{
	private static $instance;

	private $clientController;
	private $chatController;
	private $categoryModuleController;
	private $moduleController;
	private $apiPct;

	private $transferedAt;

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

	public function getApiPct() {
	 	return $this->apiPct;
	}
	
	public function setApiPct($apiPct) {
	 	$this->apiPct = $apiPct;
	}

	public function getTransferedAt() {
	  return $this->transferedAt;
	}
	
	public function setTransferedAt($transferedAt) {
	  $this->transferedAt = $transferedAt;
	}

	function __construct()
	{
		$this->setTimezone();
		$this->launchSession();
		$this->setApiPct(new ApiPct());

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

	function launchSession()
	{
		session_start();
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
		} else if ($finish == 1) {
			$this->finishTicket($id_chat_found, $id_registry, $data['client'], $data['priority'], $data['status'], $data['source'], $data['type'], $data['group'], $id_module, $data['attendant'], $data['resolution'], $is_repeated, date("Y/m/d H:i:s"));
		} else {
			$this->chatController->updateCtrl($data['id_chat'], $data['final_time'], $data['duration_in_minutes']);

			$this->updateCtrl($id_chat_found, $id_registry, $data['client'], $data['priority'], $data['status'], $data['source'], $data['type'], $data['group'], $id_module, $data['attendant'], $data['resolution'], $is_repeated);
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
