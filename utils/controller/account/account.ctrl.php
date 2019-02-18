<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../client/client.ctrl.php";

class AccountController
{
	private static $instance;
	private $prepareInstance;
	private $navBarController;
	private $clientController;

	private $idUser;
	private $email;
	private $actualPass;
	private $newPass;
	private $confirmPass;
	private $salt;

	private $postReceived;
	private $result;

	public function getIdUser()
	{
	  	return $this->idUser;
	}

	public function setIdUser($idUser)
	{
	  	$this->idUser = $idUser;
	}

	public function getEmail() 
	{
		return $this->email;
	}
	
	public function setEmail($email) 
	{
		$this->email = $email;
	}

	public function getActualPass()
	{
	  	return $this->actualPass;
	}

	public function setActualPass($actualPass)
	{
	  	$this->actualPass = $actualPass;
	}

	public function getNewPass()
	{
	  	return $this->newPass;
	}

	public function setNewPass($newPass)
	{
	  	$this->newPass = $newPass;
	}

	public function getConfirmPass()
	{
	  	return $this->confirmPass;
	}

	public function setConfirmPass($confirmPass)
	{
	  	$this->confirmPass = $confirmPass;
	}

	public function getSalt() 
	{
		return $this->salt;
	}
	
	public function setSalt($salt) 
	{
		$this->salt = $salt;
	}

	public function getPostReceived() 
	{
		return $this->postReceived;
	}
	
	public function setPostReceived($postReceived) 
	{
		$this->postReceived = $postReceived;
	}

	public function getResult() 
	{
		return $this->result;
	}
	
	public function setResult($result) 
	{
		$this->result = $result;
	}

	function __construct()
	{
		$this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->clientController = ClientController::getInstance();
		$this->salt = "ftosniarbsistemas";
	}

	public function verifyPost()
	{
		if (isset($this->postReceived['actual-password']) AND isset($this->postReceived['new-password']) AND isset($this->postReceived['confirmation-new-password'])) {
			$actualPass = $this->postReceived['actual-password'];
			$newPass = $this->postReceived['new-password'];
			$confirmPass = $this->postReceived['confirmation-new-password'];

			$this->idUser = $_SESSION['login'];
			$this->actualPass = $actualPass;
			$this->newPass = $newPass;
			$this->confirmPass = $confirmPass;
			$this->change();
        }

        if (isset($this->postReceived['registry']) AND isset($this->postReceived['client']) AND isset($this->postReceived['new-password-user']) AND isset($this->postReceived['confirmation-new-password-user'])) {
			$id = $this->postReceived['client'];
			$newPass = $this->postReceived['new-password-user'];
			$confirmPass = $this->postReceived['confirmation-new-password-user'];

			$this->idUser = $_SESSION['login'];
			$this->newPass = $newPass;
			$this->confirmPass = $confirmPass;
			$this->changeToUser();
        }
	}

	public function change()
    {
        if ($this->checkActualPassword()) {
            if ($this->newPass == $this->confirmPass) {
                $bSalt = $this->makeRandString(20);
                $saltedHash = hash('sha256', $this->newPass . $this->salt . $bSalt);

                $elements = [$saltedHash, $bSalt, $this->idUser];
                $query = "UPDATE credential SET password = ?, b_salt = ? WHERE id = ?";
                $credential = $this->prepareInstance->prepare($query, $elements, "");

                $this->result = ['success', 'Sucesso! Senha alterada com êxito.'];
            } else {
                $this->result = ['danger', "Erro! Senhas necessitam ser iguais."];
            }
        } else {
            $this->result = ['danger', "Erro! Senha atual não confere."];
        }
    }

    public function changeToUser()
    {
        if ($this->newPass == $this->confirmPass) {
            $element = $this->idUser;
            $query = "SELECT id_credential as id FROM client WHERE id = ?";
            $idCredential = $this->prepareInstance->prepare($query, $element, "");

            $bSalt = $this->makeRandString(20);
            $saltedHash = hash('sha256', $this->newPass . $this->salt . $bSalt);

            $elements = [$saltedHash, $bSalt, $idCredential['id']];
            $query = "UPDATE credential SET password = ?, b_salt = ? WHERE id = ?";
            $credential = $this->prepareInstance->prepare($query, $elements, "");

            $this->result = ['success', 'Sucesso! Senha alterada com êxito.'];
        } else {
            $this->result = ['danger', "Erro! Senhas necessitam ser iguais."];
        }
    }

    private function checkActualPassword() 
    {
        $element = $this->idUser;
        $query = "SELECT password, b_salt FROM credential WHERE id = ?";
        $credential = $this->prepareInstance->prepare($query, $element, "");
        $saltedHash = hash('sha256', $this->actualPass . $this->salt . $credential['b_salt']);

        if ($credential['password'] == $saltedHash) {
            return true;
        } else {
            return false;
        }
    }

    private function checkIfEmailExists()
    {
        $element = $this->email;
        $query = "SELECT id FROM client WHERE email = ?";
        $client = $this->prepareInstance->prepare($query, $element, "");

        if (is_null($client) || $client == "") {
            $query = "SELECT id FROM employee WHERE email = ?";
            $employee = $this->prepareInstance->prepare($query, $element, "");

            if (is_null($employee) || $employee == "") {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    function makeRandString($length)
    {
        $str = "";
        $chars = "ftosniarbabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0,$size-1)];
        }
        return $str;
    }

    public function findRole()
    {
    	return $this->clientController->findByIdCredential($_SESSION['login']);
    }

    function verifyPermission()
    {
        if (!isset($_SESSION['login'])) {
            header("Location:/");
        }
    }

	public static function getInstance()
	{
		if (!self::$instance)
      		self::$instance = new AccountController();

		return self::$instance;
	}
}
