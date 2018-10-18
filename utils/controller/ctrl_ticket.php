<?php

	date_default_timezone_set('America/Sao_Paulo');

	include_once "ctrl_client.php";
	include_once "ctrl-chat.php";
	include_once "ctrl-category.php";
	include_once "ctrl-module.php";
	include_once "../model/ticket.php";

	$controller = new TicketController();
	$controller->verifyData();

	class TicketController{

		protected $clientController;
		protected $chatController;
		protected $categoryModuleController;
		protected $moduleController;

		public function getClientController(){
		    return $this->clientController;
		}
		public function setClientController($clientController){
		    $this->clientController = $clientController;
		}
		public function getChatController(){
		    return $this->chatController;
		}
		public function setChatController($chatController){
		    $this->chatController = $chatController;
		}
		public function getCategoryModuleController(){
		    return $this->categoryModuleController;
		}
		public function setCategoryModuleController($categoryModuleController){
		    $this->categoryModuleController = $categoryModuleController;
		}
		public function getModuleController(){
		    return $this->moduleController;
		}
		public function setModuleController($moduleController){
		    $this->moduleController = $moduleController;
		}

		function __construct(){
			$this->setClientController(new ClientController());
			$this->setChatController(new ChatController());
			$this->setCategoryModuleController(new CategoryModuleController());
			$this->setModuleController(new ModuleController());
		}

		function verifyData(){
			if (isset($_POST['finishTicket'])){
				$data = $_POST;

				$this->registerCtrl($data, 1);
			} else if (isset($_POST['submit'])){
				foreach ($_POST as $key => $value){
					if ((!isset($_POST[$key]) || empty($_POST[$key])) && $key != 'submit' && $key != 'module' && $key != 'attendant' && $key != 'resolution' && $key != 'historic') {
						$this->thereIsInputEmpty();
					}
				}
				$data = $_POST;

				$this->registerCtrl($data, 0);
			} else{
				header("Location:/dashboard/tickets");
			}
		}

		function registerCtrl($data, $finish){
			session_start();

			$id_who_opened = $_SESSION['login'];

			if (isset($data['is_repeated'])){$is_repeated = 1;} else{$is_repeated = 0;};

			$id_registry = $this->clientController->findIdRegistryByIdClient($data['client']);

			$id_chat_found = $this->chatController->searchIdCtrl($data['id_chat']);

			$id_category = $this->categoryModuleController->findIdByName($data['selected_category']);

			$id_module = $this->moduleController->findIdByNameAndCategory($data['selected_module'], $id_category);

			if ($id_chat_found == 0){
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
			} else if ($finish == 1){
				$this->finishTicket($id_chat_found, $id_registry, $data['client'], $data['priority'], $data['status'], $data['source'], $data['type'], $data['group'], $id_module, $data['attendant'], $data['resolution'], $is_repeated, date("Y/m/d H:i:s"));
			} else{
				$this->chatController->updateCtrl($data['id_chat'], $data['final_time'], $data['duration_in_minutes']);

				$this->updateCtrl($id_chat_found, $id_registry, $data['client'], $data['priority'], $data['status'], $data['source'], $data['type'], $data['group'], $id_module, $data['attendant'], $data['resolution'], $is_repeated);
			}
		}

		function updateCtrl($id_chat_found, $id_registry, $client, $priority, $status, $source, $type, $group, $module, $attendant, $resolution, $is_repeated){
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

		function finishTicket($id_chat_found, $id_registry, $client, $priority, $status, $source, $type, $group, $module, $attendant, $resolution, $is_repeated, $finalized_at){
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

		function setSession($action){
			session_start();

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

		function thereIsInputEmpty(){
			session_start();

			$_SESSION['thereIsProblemInTicket'] = "<strong>Erro!</strong> Preencha todos os campos para registrar.";
			header("Location:/novo-site/dashboard/view_ticket.php");
			die();
		}

	}

?>