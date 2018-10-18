<?php

  include_once("ctrl_credential.php");
  include_once("ctrl_client.php");
  include_once("ctrl_employee.php");

  $controller = new UserController();
  $controller->verifyData();

  class UserController{

    protected $credentialController;
    protected $clientController;
    protected $employeeController;

    protected $data;

    public function getCredentialController(){
        return $this->credentialController;
    }
    public function setCredentialController($credentialController){
        $this->credentialController = $credentialController;
    }
    public function getClientController(){
        return $this->clientController;
    }
    public function setClientController($clientController){
        $this->clientController = $clientController;
    }
    public function getEmployeeController(){
        return $this->employeeController;
    }
    public function setEmployeeController($employeeController){
        $this->employeeController = $employeeController;
    }
    public function getData(){
        return $this->data;
    }
    public function setData($data){
        $this->data = $data;
    }

    function __construct(){
      $this->setCredentialController(new CredentialController());
      $this->setClientController(new ClientController());
      $this->setEmployeeController(new EmployeeController());
    }

    function verifyData(){
      $this->setData($_POST);
      $data = $this->getData();

      if (isset($data['submit'])){
        foreach ($data as $key => $value){
          if ((!isset($data[$key]) || empty($data[$key])) /*&& $this->data['typeUser'] != "employee"*/) {
            //$this->setHeader(NULL, NULL, '400');
          }
        }
        if ($data['submit'] == 'newUser'){
          $this->registerCtrl();
        } 
        if ($data['submit'] == 'alterUser'){
          $this->updateCtrl();
        }
      }
    }

    function registerCtrl(){
      $data = $this->getData();

      $lastCredentialId = $this->getCredentialController()->registerCtrl($data['login'], $data['password'])[0]["last"];
      array_push($data, $lastCredentialId);

      if ($data['typeUser'] == 'client'){
        $clientController = $this->getClientController();
        $clientController->registerCtrl($data);
      } else{
        $employeeController = $this->getEmployeeController();
        $employeeController->registerCtrl($data);
      }
    }

    function updateCtrl(){
      $data = $this->getData();

      if ($data['typeUser'] == 'client') {
        $clientController = $this->getClientController();
        $clientController->updateCtrl($data);
      } else{
        $employeeController = $this->getEmployeeController();
        $employeeController->updateCtrl($data);
      }
    }
  }

?>