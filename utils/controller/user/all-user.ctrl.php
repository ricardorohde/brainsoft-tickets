<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../client/client.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../registry/registry.ctrl.php";
include_once __DIR__ . "/../city/city.ctrl.php";

class AllUserController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $clientController;
    private $employeeController;
    private $registryController;
    private $cityController;

    private $sqlClientIds;
    private $sqlEmployeeIds;

    public function getSqlClientIds() 
    {
        return $this->sqlClientIds;
    }
    
    public function setSqlClientIds($sqlClientIds) 
    {
        $this->sqlClientIds = $sqlClientIds;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->clientController = ClientController::getInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->registryController = RegistryController::getInstance();
        $this->cityController = CityController::getInstance();
    }

    public function findAllClients()
    {
        $clients = $this->clientController->findAll();
        $clientIds = array_column($clients, 'id');
        $this->sqlClientIds = implode(',', $clientIds);
        return $clients;
    }

    public function findDataOfClients()
    {
        return $this->clientController->findDataOfClients($this->sqlClientIds);
    }

    public function findAllEmployees()
    {
        $employees = $this->employeeController->findAll();
        $employeeIds = array_column($employees, 'id');
        $this->sqlEmployeeIds = implode(',', $employeeIds);
        return $employees;
    }

    public function findDataOfEmployees()
    {
        return $this->employeeController->findDataOfEmployees($this->sqlEmployeeIds);
    }
    
    function verifyPermission()
    {
        if (!isset($_SESSION['User'.'_page_'.$_SESSION['login']])) {
            header("Location:../painel");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new AllUserController();
        }
        return self::$instance;
    }
}
