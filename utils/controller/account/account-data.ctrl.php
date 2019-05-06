<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../client/client.ctrl.php";
include_once __DIR__ . "/../credential/credential.ctrl.php";

new AccountDataController();

class AccountDataController
{
	private $prepareInstance;

	private $navBarController;
	private $clientController;
	private $credentialController;

	private $postReceived;

	public function getPostReceived()
	{
		return $this->postReceived;
	}

	public function setPostReceived($postReceived)
	{
		$this->postReceived = $postReceived;
	}

	function __construct()
	{
		$this->navBarController = NavBarController::getInstance();
		$this->prepareInstance = $this->navBarController->getPrepareInstance();

		$this->credentialController = CredentialController::getInstance();
        $this->clientController = ClientController::getInstance();
        
        $this->postReceived = $_POST;
        $this->verifyPost();
	}

	public function verifyPost()
	{
		if (isset($this->postReceived['actual-password']) and isset($this->postReceived['new-password']) and isset($this->postReceived['confirmation-new-password'])) {
			$idUser = $_SESSION['login'];
			$actualPass = $this->postReceived['actual-password'];
			$newPass = $this->postReceived['new-password'];
			$confirmPass = $this->postReceived['confirmation-new-password'];

			$this->credentialController->change($actualPass, $newPass, $confirmPass, $idUser);
		}

		if (isset($this->postReceived['registry']) and isset($this->postReceived['client']) and isset($this->postReceived['new-password-user']) and isset($this->postReceived['confirmation-new-password-user'])) {
			$idUser = $this->postReceived['client'];
			$newPass = $this->postReceived['new-password-user'];
			$confirmPass = $this->postReceived['confirmation-new-password-user'];

			$credential = $this->clientController->findById($idUser);
			$this->credentialController->changeToClient($newPass, $confirmPass, $credential['id_credential']);
		}
	}
}
