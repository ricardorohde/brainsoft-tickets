<?php

  class PasswordController {

    private static $instance;

    private $idUser;
    private $email;
    private $actualPass;
    private $newPass;
    private $confirmPass;
    private $token;
    private $salt;

    private $prepareInstance;
    
    public function getIdUser() {
      return $this->idUser;
    } 
    public function setIdUser($idUser) {
      $this->idUser = $idUser;
    }
    public function getEmail() {
      return $this->email;
    }
    public function setEmail($email) {
      $this->email = $email;
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
    public function getToken() {
      return $this->token;
    }
    public function setToken($token) {
      $this->token = $token;
    }
    public function getPrepareInstance() {
      return $this->prepareInstance;
    }
    public function setPrepareInstance($prepareInstance) {
      $this->prepareInstance = $prepareInstance;
    }

    function __construct(){
      $this->salt = "ftosniarbsistemas";
    }
    
    public function change() {
      if ($this->checkActualPassword()){
        if ($this->newPass == $this->confirmPass) {
          $bSalt      = $this->makeRandString(20);
          $saltedHash = hash('sha256', $this->newPass . $this->salt . $bSalt);

          $elements    = [$saltedHash, $bSalt, $this->idUser];
          $query       = "UPDATE credential SET password = ?, b_salt = ? WHERE id = ?";
          $credential  = $this->prepareInstance->prepare($query, $elements, "");
          
          return ['success', 'Sucesso! Senha alterada com êxito.'];
        } else {
          return ['danger', "Erro! Senhas necessitam ser iguais."];
        }
      } else {
        return ['danger', "Erro! Senha atual não confere."];
      }
    }

    public function changeToUser() {
      if ($this->newPass == $this->confirmPass) {
        $element       = $this->idUser;
        $query         = "SELECT id_credential as id FROM client WHERE id = ?";
        $idCredential  = $this->prepareInstance->prepare($query, $element, "");

        $bSalt      = $this->makeRandString(20);
        $saltedHash = hash('sha256', $this->newPass . $this->salt . $bSalt);

        $elements    = [$saltedHash, $bSalt, $idCredential['id']];
        $query       = "UPDATE credential SET password = ?, b_salt = ? WHERE id = ?";
        $credential  = $this->prepareInstance->prepare($query, $elements, "");
        
        return ['success', 'Sucesso! Senha alterada com êxito.'];
      } else {
        return ['danger', "Erro! Senhas necessitam ser iguais."];
      }
    }

    private function checkActualPassword() {
      $element    = $this->idUser;
      $query      = "SELECT password, b_salt FROM credential WHERE id = ?";
      $credential = $this->prepareInstance->prepare($query, $element, "");
      
      $saltedHash = hash('sha256', $this->actualPass . $this->salt . $credential['b_salt']);

      if ($credential['password'] == $saltedHash) {
        return true;
      } else {
        return false;
      }
    }

    private function checkIfEmailExists() {
      $element = $this->email;

      $query  = "SELECT id FROM client WHERE email = ?";
      $client = $this->prepareInstance->prepare($query, $element, "");

      if (is_null($client) || $client == "") {
        $query    = "SELECT id FROM employee WHERE email = ?";
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

    function makeRandString($length) {
      $str = "";
      $chars = "ftosniarbabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      $size = strlen($chars);

      for ($i = 0; $i < $length; $i++) {
        $str .= $chars[rand(0,$size-1)];
      }
      
      return $str;
    }

    public static function getInstance() {
      if (!self::$instance)
        self::$instance = new PasswordController();

      return self::$instance;
    }
  }
?>