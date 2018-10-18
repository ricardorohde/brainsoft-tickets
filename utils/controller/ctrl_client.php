<?php
  include_once "../model/client.php";
  include_once "ctrl_registry.php";

  if (isset($_POST['registry']) && !empty($_POST['registry'])) {
    $controller = new ClientController();
    $controller->findClient();
  }

  class ClientController{

    protected $registryController;

    public function getRegistryController(){
        return $this->registryController;
    }
    public function setRegistryController($registryController){
        $this->registryController = $registryController;
    }

    function __construct(){
      $this->setRegistryController(new RegistryController());
    }

    function registerCtrl($data){
      include_once("../model/client.php");
      $name = utf8_decode($data['name']);

      $client = new Client($this->getInstance());
      $client->setName($name);
      $client->setEmail($data['email']);
      $client->setIdCredential($data[0]);
      $client->setIdRegistry($data['registry']);
      $client->setIdRole($data['role']);
      
      $client->register();
    }

    function updateCtrl($data){
      include_once("../model/client.php");
      $name = utf8_decode($data['name']);

      $client = new Client($this->getInstance());
      $client->setName($name);
      $client->setEmail($data['email']);
      $client->setIdRegistry($data['registry']);
      $client->setIdRole($data['role']);
      $client->setId($data['id_user']);

      $client->update();
    }

    function findClient(){
      $nameRegistry = $_POST['registry'];

      $rawId = $this->getRegistryController()->findIdByName($nameRegistry);
      $id = $rawId[0]['id'];

      $client = new Client($this->getInstance());
      $client->setIdRegistry($id);
      $clients = $client->findClients();

      for ($i = 0; $i < count($clients); $i++) { 
        $id = $clients[$i]['id'];
        $name = utf8_encode($clients[$i]['name']);

        $option = "<option value='$id'>$name</option>";
        echo $option;
      }
    }

    function findIdRegistryByIdClient($id_client){
      include_once("../model/client.php");
      
      $client = new Client($this->getInstance());
      $client->setId($id_client);

      return $client->findIdRegistry();
    }

    function verifyResult($action, $result){
      if ($result == 1){
        if ($action == "register"){
          $this->setSession('newOk');  
        } else {
          $this->setSession('updateOk');
        }
      } else{
        if ($action == "register"){
          $this->setSession('newNo');  
        } else {
          $this->setSession('updateNo');
        }
      }
    }

    function setSession($status){
      session_start();

      unset($_SESSION['userOk']);
      unset($_SESSION['userNo']);

      switch ($status) {
        case "newOk":
          $_SESSION['userOk'] = "<strong>Sucesso!</strong> Cliente registrado com êxito.";
          header("Location:../../dashboard/usuarios");
          break;
        case "updateOk":
          $_SESSION['userOk'] = "<strong>Sucesso!</strong> Cliente atualizado com êxito.";
          header("Location:../../dashboard/usuarios");
          break;
        case "newNo":
          $_SESSION['userNo'] = "<strong>Erro!</strong> Houve um problema ao registrar este cliente.";
          header("Location:../../dashboard/usuarios");
          break;
        case "updateNo":
          $_SESSION['userNo'] = "<strong>Erro!</strong> Houve um problema ao atualizar este cliente.";
          header("Location:../../dashboard/usuarios");
          break;
        default:
          break;
      }
    }

    function getInstance(){
      return new ClientController();
    }
  }
?>