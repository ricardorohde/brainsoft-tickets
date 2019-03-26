<?php
include_once "navbar/navbar.ctrl.php";
include_once __DIR__ . "/client/client.ctrl.php";
include_once __DIR__ . "/registry/registry.ctrl.php";
include_once __DIR__ . "/module/module.ctrl.php";
include_once __DIR__ . "/category/category.ctrl.php";

class QueueController
{
	private static $instance;
	private $prepareInstance;
	private $allOpenChats;

	private $allAttendantsOfGroup1OnChat;
	private $initializedQueueOfGroup1;
	private $finalizedQueueOfGroup1;

	private $allAttendantsOfGroup2OnChat;
	private $initializedQueueOfGroup2;
	private $finalizedQueueOfGroup2;

	private $navBarController;
	private $clientController;
	private $registryController;
	private $moduleController;
	private $categoryController;

	private $groupOne;
	private $groupTwo;
	private $countGroupOne;
	private $countGroupTwo;
	
	public function setNavBarController($navBarController)
	{
	  	$this->navBarController = $navBarController;
	}

	public function getGroupOne() 
	{
		return $this->groupOne;
	}
	
	public function setGroupOne($groupOne) 
	{
		$this->groupOne = $groupOne;
	}

	public function getGroupTwo() 
	{
		return $this->groupTwo;
	}
	
	public function setGroupTwo($groupTwo) 
	{
		$this->groupTwo = $groupTwo;
	}

	public function getCountGroupOne() 
	{
		return $this->countGroupOne;
	}
	
	public function setCountGroupOne($countGroupOne) 
	{
		$this->countGroupOne = $countGroupOne;
	}

	public function getCountGroupTwo() 
	{
		return $this->countGroupTwo;
	}
	
	public function setCountGroupTwo($countGroupTwo) 
	{
		$this->countGroupTwo = $countGroupTwo;
	}

	function __construct()
	{
		$this->setNavBarController(new NavBarController());
		$this->setPrepareInstance($this->navBarController->getPrepareInstance());
		$this->clientController = ClientController::getInstance();
		$this->registryController = RegistryController::getInstance();
		$this->moduleController = ModuleController::getInstance();
		$this->categoryController = CategoryController::getInstance();

		$this->groupOne = $this->attendantsOnGroup("nivel1");
		$this->groupTwo = $this->attendantsOnGroup("nivel2");
		$this->countGroupOne = count($this->groupOne);
		$this->countGroupTwo = count($this->groupTwo);
		$this->cleanDataInTable();
	}

	function setPrepareInstance($prepareInstance)
	{
		$this->prepareInstance = $prepareInstance;
	}

	function getAllOpenChats()
	{
		return $this->allOpenChats;
	}

	function orderByQuantity($contagem)
	{
		$queue = array();
		foreach ($contagem as $numero => $vezes) {
			if ($vezes == 2) {
				array_unshift($queue, $numero);
			}
		}

		foreach ($contagem as $numero => $vezes) {
			if ($vezes == 1) {
				array_unshift($queue, $numero);
			}
		}
		return $queue;
	}

	function orderByDate($id_finalized_queue)
	{
		$queue_according_data = array();
		$dates = array();
		$position = 0;

		foreach ($id_finalized_queue as $id_attendant) {
			$elements = $id_attendant['id_attendant'];
    		$query = "SELECT registered_at FROM ticket WHERE id_attendant = ? ORDER BY id DESC LIMIT 1";
    		$row_finalized_queue_2 = $this->prepareInstance->prepare($query, $elements, "");

			$last_date = $row_finalized_queue_2['registered_at'];
			$final_date = strtotime($last_date);

			array_push($dates, $final_date);
		}

		asort($dates);

		foreach ($dates as $key => $date) {
			$dateString = date('Y-m-d H:i:s', $date);

			$elements = $dateString;
    		$query = "SELECT id_attendant FROM ticket WHERE registered_at = ? ORDER BY id DESC LIMIT 1";
    		$row_finalized_queue_2 = $this->prepareInstance->prepare($query, $elements, "");

			$queue_according_data[$position] = $row_finalized_queue_2['id_attendant'];

			$position++;
		}
		return $queue_according_data;
	}

	function findUserInQueue($qtd_attendants, $new_queue_group, $queue_according_date, $plus)
	{
		$finalized_queue_group_1 = array();

		for ($i = 0; $i <= $qtd_attendants + 7; $i++) { 
			if (!in_array($i + $plus, $new_queue_group)) { 
				$finded = 0;
				foreach ($queue_according_date as $key => $value) {
					if ($value == $i + $plus && $finded == 0) {
						$finalized_queue_group_1[$key] = $i + $plus;
						$finded = 1;
					}
				}
			} 
		}

		ksort($finalized_queue_group_1);
		return $finalized_queue_group_1;
	}

	function openChats()
	{
	    $elements = "aberto";
	    $query = "SELECT id_registry as registry, id_client as client, source, id_module, t_group, id_attendant as id, registered_at, chat.id_chat FROM ticket, chat WHERE t_status = ? AND ticket.id_chat = chat.id ORDER BY ticket.id_chat DESC";
	    $this->allOpenChats = $this->prepareInstance->prepare($query, $elements, "all");
	}

	function attendantsAbleOnGroup1()
	{
	    $elements = ["nivel1", "yes"];
	    $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?";
	    $row_attendants_1_on_chat = $this->prepareInstance->prepare($query, $elements, "all");
	    $this->allAttendantsOfGroup1OnChat = $row_attendants_1_on_chat[0]['total'];
	}

	function makeQueueToGroup1()
	{
	    $limit_initialize_1 = $this->allAttendantsOfGroup1OnChat * 2;
	    $elements = ["aberto", "nivel1", $limit_initialize_1];
	    $query = "SELECT id_attendant FROM ticket WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :count";
	    $this->initializedQueueOfGroup1 = json_decode($this->prepareInstance->prepareBind($query, $elements, "all"), true);

	    $limit_finalized_1 = $this->allAttendantsOfGroup1OnChat;
	    $elements = ["aberto", "nivel1", "yes", $limit_finalized_1];
	    $query = "SELECT DISTINCT id_attendant FROM ticket, employee WHERE 
			t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :count";
		$this->finalizedQueueOfGroup1 = json_decode($this->prepareInstance->prepareBind($query, $elements, "all"), true);
	}

	function attendantsAbleOnGroup2()
	{
	    $elements = ["nivel2", "yes"];
	    $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?";
	    $row_attendants_1_on_chat = $this->prepareInstance->prepare($query, $elements, "all");
	    $this->allAttendantsOfGroup2OnChat = $row_attendants_1_on_chat[0]['total'];
	}

	function makeQueueToGroup2()
	{
		$limit_initialize_2 = $this->allAttendantsOfGroup2OnChat * 2;
		$elements_to_initialize_queue_2 = ["aberto", "nivel2", $limit_initialize_2];
		$query = "SELECT id_attendant FROM ticket WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :count";
		$this->initializedQueueOfGroup2 = json_decode($this->prepareInstance->prepareBind($query, $elements_to_initialize_queue_2, "all"), true);

		$limit_finalized_2 = $this->allAttendantsOfGroup2OnChat;
		$elements_to_finalized_queue_2 = ["aberto", "nivel2", "yes", $limit_finalized_2];
		$query = "SELECT DISTINCT id_attendant FROM ticket, employee WHERE 
			t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :count";
		$this->finalizedQueueOfGroup2 = json_decode($this->prepareInstance->prepareBind($query, $elements_to_finalized_queue_2, "all"), true);
	}

	function getOrderedQueue($group)
	{
		$updatedQueue = array();	
		$stepQueue = array(); 

		if ($group == 1) {
			foreach ($this->initializedQueueOfGroup1 as $row) {
				array_push($stepQueue, $row['id_attendant']);
			}

			$count = array_count_values($stepQueue); 
			$updatedQueue = $this->orderByQuantity($count); 
			$queueAccordingDate = $this->orderByDate($this->finalizedQueueOfGroup1);							
			$finalQueue = $this->findUserInQueue($this->allAttendantsOfGroup1OnChat, $updatedQueue, $queueAccordingDate, 2);
		} else {
			foreach ($this->initializedQueueOfGroup2 as $row) {
				array_push($stepQueue, $row['id_attendant']);
			} 

			$count = array_count_values($stepQueue);
			$updatedQueue = $this->orderByQuantity($count);
			$queueAccordingDate = $this->orderByDate($this->finalizedQueueOfGroup2);
			$finalQueue = $this->findUserInQueue($this->allAttendantsOfGroup2OnChat, $updatedQueue, $queueAccordingDate, 5);
		}
		return array_merge($finalQueue, $updatedQueue);
	}

	function progressBar($registeredAt)
	{
		$initial_time = new DateTime(date('Y/m/d H:i:s', strtotime($registeredAt)));
		$actual_time = new DateTime();
		$diff = $actual_time->diff($initial_time);	
		return ($diff->h*60) + $diff->i + ($diff->s/60) + ($diff->days*24*60);
	}

	function limitTimeToFinish($IdModule)
	{
		$element = $IdModule;
		$query = "SELECT limit_time FROM ticket_module WHERE id = ?";
		return $this->prepareInstance->prepare($query, $element, "all"); 
	}

	function attendantsOnGroup($group)
	{
		$elements = [$group, "yes"];
		$query = "SELECT id, name FROM employee WHERE t_group = ? AND on_chat = ?";
		$data = $this->prepareInstance->prepare($query, $elements, "all"); 
	
		$group = array();
		foreach ($data as $g) {
			$name = explode(" ", $g['name']);
			$group[$g['id']] = $name[0];
		}
		return $group;
	}

	function dataToTable()
	{
		$elements = ["nivel1", "nivel2"];
		$query = "SELECT id, name, on_chat FROM employee WHERE t_group = ? OR t_group = ? ORDER BY t_group, name";
		return $this->prepareInstance->prepare($query, $elements, "all");
	}

	function totalCalls($attendant, $actualDate)
	{
		$elements = [$attendant['id'], $actualDate."%", "pendente", $actualDate."%"];
		$query = "SELECT COUNT(*) as total FROM ticket WHERE id_attendant = ? AND (finalized_at LIKE ? OR (t_status = ? AND registered_at LIKE ?))";
		return $this->prepareInstance->prepare($query, $elements, "");
	}

	function cleanDataInTable()
	{
		$this->navBarController->cleanDataOfCall();
	}

	public function findClientOfTicketById($id)
	{
		return $this->clientController->findById($id)['name'];
	}

	public function findRegistryOfTicketById($id)
	{
		return $this->registryController->findById($id)['name'];
	}

	public function findModuleOfTicketById($id)
	{
		$module = $this->moduleController->findById($id);
		$category = $this->categoryController->findById($module['id_category']);
		return $category['description'] . "/" . $module['description'];
	}

	function verifyPermission()
	{
  		if (!isset($_SESSION['Queue'.'_page_'.$_SESSION['login']])) {
    		header("Location:/painel/conta");
  		}
	}

	public static function getInstance()
	{
		if (!self::$instance) {
      		self::$instance = new QueueController();
		}
    	return self::$instance;
	}
}
