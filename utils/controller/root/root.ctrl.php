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

		if ($this->data['action'] == "getAllClients") {
			echo $controller->findAllClients();
		} else if ($this->data['action'] == "getAllStates") {
			echo $controller->findAllStates();
		} else if ($this->data['action'] == "getAllClientsOfState") {
			echo $controller->findAllClientsOfState($this->data['value']);
		} else if ($this->data['action'] == "getAllClientsOfRegistry") {
			echo $controller->findAllClientsOfRegistry($this->data['value']);
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
