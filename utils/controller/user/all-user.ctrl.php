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

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->clientController = ClientController::getInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->registryController = RegistryController::getInstance();
        $this->cityController = CityController::getInstance();
    }

    function findAllClients()
    {
        return $this->clientController->findAll();
    }

    function findAllEmployees()
    {
        return $this->employeeController->findAll();
    }

    function cityOfClient($id_registry)
    {
        $registry = $this->registryController->findById($id_registry);
        $city = $this->cityController->findById($registry['id_city']);

        return $city['description'];
    }
    
    function verifyPermission()
    {
        if (!isset($_SESSION['User'.'_page_'.$_SESSION['login']])) {
            header("Location:../dashboard");
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
