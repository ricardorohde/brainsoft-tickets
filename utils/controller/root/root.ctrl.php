<?php
include_once __DIR__ . "/../marketing/email.ctrl.php";

new PostController();

class PostController
{
	private static $instance;

	private $data;

	function __construct()
	{
		$this->data = $_POST;
		$this->verifyData();
	}

	function verifyData()
	{
		if ($this->data['toController'] == "EmailController") {
			$this->toEmailController();
		}
	}

	function toEmailController()
	{
		$controller = new EmailController();

		switch ($this->data['action']) {
			case 'getAllClients':
				echo $controller->findAllClients();
				break;
			case 'getAllStates':
				echo $controller->findAllStates();
				break;
			case 'getAllClientsOfState':
				echo $controller->findAllClientsOfState($this->data['value']);
				break;
			case 'getAllClientsOfRegistry':
				echo $controller->findAllClientsOfRegistry($this->data['value']);
				break;
			case 'getAllClientsWithFilter':
				echo $controller->findAllClientsWithFilter($this->data['filters']);
				break;
			case 'getAllClientsOfStateWithFilter':
				echo $controller->findAllClientsOfStateWithFilter($this->data['value'], $this->data['filters']);
				break;
			case 'getAllClientsOfRegistryWithFilter':
				echo $controller->findAllClientsOfRegistryWithFilter($this->data['value'], $this->data['filters']);
				break;
			default:
				break;
		}
	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new PostController();
		}
		return self::$instance;
	}
}
