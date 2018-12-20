<?php

include_once __DIR__.'/ctrl-password.php';

class AccountController {

	private static $instance;
	private $prepareInstance;

	private $idUser;
	private $actualPass;
	private $newPass;
	private $confirmPass;

	private $controllerPass;

	public function getIdUser() {
	  return $this->idUser;
	}
	public function setIdUser($idUser) {
	  $this->idUser = $idUser;
	}
	public function getActualPass() {
	  return $this->actualPass;
	}
	public function setActualPass($actualPass) {
	  $this->actualPass = $actualPass;
	}
	public function getNewPass() {
	  return $this->newPass;
	}
	public function setNewPass($newPass) {
	  $this->newPass = $newPass;
	}
	public function getConfirmPass() {
	  return $this->confirmPass;
	}
	public function setConfirmPass($confirmPass) {
	  $this->confirmPass = $confirmPass;
	}
	public function setPrepareInstance($prepareInstance) {
		$this->prepareInstance = $prepareInstance;
	}
	public function setControllerPass($controllerPass) {
	  $this->controllerPass = $controllerPass;
	}

	function __construct() {
		$this->controllerPass = PasswordController::getInstance();
	}

	public function change() {
		$this->controllerPass->setPrepareInstance($this->prepareInstance);
		$this->controllerPass->setIdUser($this->idUser);
		$this->controllerPass->setActualPass($this->actualPass);
		$this->controllerPass->setNewPass($this->newPass);
		$this->controllerPass->setConfirmPass($this->confirmPass);

		return $this->controllerPass->change();
	}

	public function changeToUser() {
		$this->controllerPass->setPrepareInstance($this->prepareInstance);
		$this->controllerPass->setIdUser($this->idUser);
		$this->controllerPass->setNewPass($this->newPass);
		$this->controllerPass->setConfirmPass($this->confirmPass);

		return $this->controllerPass->changeToUser();
	}

	public static function getInstance() {
		if (!self::$instance)
      self::$instance = new AccountController();

    return self::$instance;
	}
}

?>