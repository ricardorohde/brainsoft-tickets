<?php
include_once "navbar/navbar.ctrl.php";
include_once "ctrl_credential.php";
include_once "ctrl_client.php";
include_once "ctrl_employee.php";

$controller = new UserController();
$controller->verifyData();

class UserController
{
    private static $instance;
    private $prepareInstance;

    private $credentialController;
    private $clientController;
    private $employeeController;
    private $navBarController;

    private $clients;
    private $employees;
    private $city;

    private $data;

    public function setNavBarController($navBarController)
    {
      $this->navBarController = $navBarController;
    }

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    public function getCredentialController()
    {
        return $this->credentialController;
    }

    public function setCredentialController($credentialController)
    {
        $this->credentialController = $credentialController;
    }

    public function getClientController()
    {
        return $this->clientController;
    }

    public function setClientController($clientController)
    {
        $this->clientController = $clientController;
    }

    public function getEmployeeController()
    {
        return $this->employeeController;
    }

    public function setEmployeeController($employeeController)
    {
        $this->employeeController = $employeeController;
    }

    public function getClients()
    {
      return $this->clients;
    }
    
    public function setClients($Clients)
    {
      $this->clients = $clients;
    }

    public function getEmployees()
    {
      return $this->employees;
    }
    
    public function setEmployees($Employees)
    {
      $this->employees = $employees;
    }

    public function getCity()
    {
      return $this->city;
    }
    
    public function setCity($city)
    {
      $this->city = $city;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    function __construct()
    {
        $this->setCredentialController(new CredentialController());
        $this->setClientController(new ClientController());
        $this->setEmployeeController(new EmployeeController());
        $this->setNavBarController(new NavBarController());
        $this->setPrepareInstance($this->navBarController->getPrepareInstance());
    }

    function verifyData()
    {
        $this->setData($_POST);
        $data = $this->getData();

        if (isset($data['submit'])) {
            foreach ($data as $key => $value) {
                if ((!isset($data[$key]) || empty($data[$key])) /*&& $this->data['typeUser'] != "employee"*/) {
                    //$this->setHeader(NULL, NULL, '400');
                }
            }

            if ($data['submit'] == 'newUser') {
                $this->registerCtrl();
            }

            if ($data['submit'] == 'alterUser'){
                $this->updateCtrl();
            }
        }
    }

    function registerCtrl()
    {
        $data = $this->getData();

        $lastCredentialId = $this->getCredentialController()->registerCtrl($data['login'], $data['password'])[0]["last"];
        array_push($data, $lastCredentialId);

        if ($data['typeUser'] == 'client') {
            $clientController = $this->getClientController();
            $clientController->registerCtrl($data);
        } else {
            $employeeController = $this->getEmployeeController();
            $employeeController->registerCtrl($data);
        }
    }

    function updateCtrl()
    {
        $data = $this->getData();

        if ($data['typeUser'] == 'client') {
            $clientController = $this->getClientController();
            $clientController->updateCtrl($data);
        } else {
            $employeeController = $this->getEmployeeController();
            $employeeController->updateCtrl($data);
        }
    }    

    function cityOfClient($id_registry)
    {
        $element = $id_registry;
        $query = "SELECT id_city FROM registry WHERE id = ?";
        $registry = $this->prepareInstance->prepare($query, $element, "");

        $element = $registry['id_city'];
        $query = "SELECT description FROM city WHERE id = ?";
        $city = $this->prepareInstance->prepare($query, $element, "");
        $this->city = $city['description'];
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
            self::$instance = new UserController();
        }
        return self::$instance;
    }
}
