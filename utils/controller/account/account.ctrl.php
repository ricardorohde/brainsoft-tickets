<?php
include_once __DIR__ . "/../client/client.ctrl.php";

class AccountController
{
	private static $instance;

	private $clientController;

	function __construct()
	{
        $this->clientController = ClientController::getInstance();
	}

	public function findRole()
	{
		return $this->clientController->findByIdCredential($_SESSION['login']);
	}

	function verifyPermission()
	{
		if (!isset($_SESSION['login'])) {
			header("Location:/painel");
		}
	}

	public static function getInstance()
	{
		if (!self::$instance)
			self::$instance = new AccountController();

		return self::$instance;
	}
}
