<?php 

include_once __DIR__.'/../../utils/controller/ctrl-target.php';

class QueueController extends TargetController {

	private static $instance;
	private $prepareInstance;
	private $allOpenChats;                //chats

	private $allAttendantsOnGroup1;       //qtd_attendant_1
	private $allAttendantsOfGroup1OnChat; //qtd_attendant_1_on_chat
	private $initializedQueueOfGroup1;    //row_initialized_queue_1
	private $finalizedQueueOfGroup1;      //row_id_finalized_queue_1

	private $allAttendantsOnGroup2;       //$qtd_attendant_2
	private $allAttendantsOfGroup2OnChat; //qtd_attendant_2_on_chat
	private $initializedQueueOfGroup2;    //row_initialized_queue_2
	private $finalizedQueueOfGroup2;      //row_id_finalized_queue_2

	function setPrepareInstance($prepareInstance) {
		$this->prepareInstance = $prepareInstance;
	}

	function getAllOpenChats() {
		return $this->allOpenChats;
	}

	function orderByQuantity($contagem) {
		$queue = array();
		foreach($contagem as $numero => $vezes){
			if($vezes == 2){
				array_unshift($queue, $numero);
			}
		}

		foreach($contagem as $numero => $vezes){
			if($vezes == 1){
				array_unshift($queue, $numero);
			}
		}

		return $queue;
	}

	function orderByDate($id_finalized_queue) {
		$queue_according_data = array();
		$dates = array();
		$position = 0;

		foreach ($id_finalized_queue as $id_attendant){
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

	function findUserInQueue($qtd_attendants, $new_queue_group, $queue_according_date) {
		$qtd_attendants = $qtd_attendants + 1;
		$finalized_queue_group_1 = array();

		for ($i = 0; $i <= $qtd_attendants; $i++){ 
			if (!in_array($i+$qtd_attendants, $new_queue_group)) { 
				$finded = 0;
				foreach ($queue_according_date as $key => $value){
					if($value[0] == $i+$qtd_attendants && $finded == 0){
						$finalized_queue_group_1[$key] = $i+$qtd_attendants;
						$finded = 1;
					}
				}
			} 
		}

		ksort($finalized_queue_group_1);

		return $finalized_queue_group_1;
	}

	function openChats(){
    $elements = "aberto";
    $query = "SELECT id_module, t_group, id_chat, id_attendant as id, registered_at FROM ticket WHERE t_status = ? ORDER BY id_chat desc";
    $this->allOpenChats = $this->prepareInstance->prepare($query, $elements, "all");
	}

	function attendantsOnGroup1(){
    $elements = "nivel1";
    $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ?";
    $row_attendants_1 = $this->prepareInstance->prepare($query, $elements, "all");
    $this->allAttendantsOnGroup1 = (int) $row_attendants_1[0]['total'];
	}

	function attendantsAbleOnGroup1() {
    $elements = ["nivel1", "yes"];
    $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?";
    $row_attendants_1_on_chat = $this->prepareInstance->prepare($query, $elements, "all");
    $this->allAttendantsOfGroup1OnChat = $row_attendants_1_on_chat[0]['total'];
	}

	function makeQueueToGroup1() {
    $limit_initialize_1 = $this->allAttendantsOnGroup1 * 2;
    $elements = ["aberto", "nivel1", $limit_initialize_1];
    $query = "SELECT id_attendant FROM ticket WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :count";
    $this->initializedQueueOfGroup1 = $this->prepareInstance->prepareBind($query, $elements, "all");

    $limit_finalized_1 = $this->allAttendantsOnGroup1;
    $elements = ["aberto", "nivel1", "yes", $limit_finalized_1];
    $query = "SELECT DISTINCT id_attendant FROM ticket, employee WHERE 
			t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :count";
		$this->finalizedQueueOfGroup1 = $this->prepareInstance->prepareBind($query, $elements, "all");
	}

	function attendantsOnGroup2() {
    $elements = "nivel2";
		$query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ?";
		$row_attendants_2 = $this->prepareInstance->prepare($query, $elements, "all");
		$this->allAttendantsOnGroup2 = (int) $row_attendants_2[0]['total'];
	}

	function attendantsAbleOnGroup2() {
    $elements = ["nivel2", "yes"];
    $query = "SELECT COUNT(*) as total FROM employee WHERE t_group = ? AND on_chat = ?";
    $row_attendants_1_on_chat = $this->prepareInstance->prepare($query, $elements, "all");
    $this->allAttendantsOfGroup2OnChat = $row_attendants_1_on_chat[0]['total'];
	}

	function makeQueueToGroup2() {
		$limit_initialize_2 = $this->allAttendantsOnGroup2 * 2;
		$elements_to_initialize_queue_2 = ["aberto", "nivel2", $limit_initialize_2];
		$query = "SELECT id_attendant FROM ticket WHERE t_status = :status AND t_group = :group ORDER BY registered_at DESC LIMIT :count";
		$this->initializedQueueOfGroup2 = $this->prepareInstance->prepareBind($query, $elements_to_initialize_queue_2, "all");

		$limit_finalized_2 = $this->allAttendantsOnGroup2;
		$elements_to_finalized_queue_2 = ["aberto", "nivel2", "yes", $limit_finalized_2];
		$query = "SELECT DISTINCT id_attendant FROM ticket, employee 
			WHERE t_status != :status AND ticket.t_group = :group AND employee.on_chat = :active AND ticket.id_attendant = employee.id ORDER BY registered_at DESC LIMIT :count";
		$this->finalizedQueueOfGroup2 = $this->prepareInstance->prepareBind($query, $elements_to_finalized_queue_2, "all");
	}

	function getOrderedQueue($group) {
		$updatedQueue = array();	
		$stepQueue = array(); 

		if ($group == 1) {
			foreach ($this->initializedQueueOfGroup1 as $row) {
				array_push($stepQueue, $row['id_attendant']);
			} 

			$count = array_count_values($stepQueue); 
			$updatedQueue = $this->orderByQuantity($count); 
			$queueAccordingDate = $this->orderByDate($this->finalizedQueueOfGroup1);							
			$finalQueue = $this->findUserInQueue($this->allAttendantsOfGroup1OnChat, $updatedQueue, $queueAccordingDate);
		} else {
			foreach ($this->initializedQueueOfGroup2 as $row) {
				array_push($stepQueue, $row['id_attendant']);
			} 

			$count = array_count_values($stepQueue); 
			$updatedQueue = $this->orderByQuantity($count); 
			$queueAccordingDate = $this->orderByDate($this->finalizedQueueOfGroup2);							
			$finalQueue = $this->findUserInQueue($this->allAttendantsOfGroup2OnChat, $updatedQueue, $queueAccordingDate);
		}
		
		return array_merge($finalQueue, $updatedQueue);
	} 

	function chatNumberToUseInLink($idChat) {
		$element = $idChat;
		$query = "SELECT id_chat FROM chat WHERE id = ?";
		
		return $this->prepareInstance->prepare($query, $element, "all");
	}

	function timeOfTicket($idChat) {
		$element = $idChat;
		$query = "SELECT registered_at FROM ticket WHERE id_chat = ?"; 
		
		return $this->prepareInstance->prepare($query, $element, "all");
	}

	function progressBar($registeredAt) {
		$initial_time = new DateTime(date('Y/m/d H:i:s', strtotime($registeredAt)));
		$actual_time = new DateTime();
		$diff = $actual_time->diff($initial_time);
		
		return ($diff->h*60) + $diff->i + ($diff->s/60) + ($diff->days*24*60);
	}
	

	function limitTimeToFinish($IdModule) {
		$element = $IdModule;
		$query = "SELECT limit_time FROM ticket_module WHERE id = ?";

		return $this->prepareInstance->prepare($query, $element, "all"); 
	}

	function verifyPermission() {
		session_start();
  	if (!isset($_SESSION['Queue'.'_page_'.$_SESSION['login']])) {
    	header("Location:../dashboard");
  	}
	}

	function getTargets(){
		return $this->targetRoot();
	}

	public static function getInstance() {
		if ( !self::$instance )
      self::$instance = new QueueController();

    return self::$instance;
	}
}

?>