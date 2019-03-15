<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../client/client.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../registry/registry.ctrl.php";
include_once __DIR__ . "/../city/city.ctrl.php";
include_once __DIR__ . "/../state/state.ctrl.php";
include_once __DIR__ . "/../role/role.ctrl.php";
include_once __DIR__ . "/../credential/credential.ctrl.php";
include_once __DIR__ . "/../ticket/ticket.ctrl.php";

class NewUserController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $clientController;
    private $employeeController;
    private $registryController;
    private $cityController;
    private $stateController;
    private $roleController;
    private $credentialController;
    private $ticketController;

    private $thereIsProblem;
    private $currentType;
    private $currentId;
    private $user;
    private $registry;
    private $city;
    private $state;
    private $role;
    private $inputHasEmployee;
    private $credential;

    private $allStates;
    private $allRoles;

    private $allTickets;

    public function getThereIsProblem()
    {
        return $this->thereIsProblem;
    }

    public function setThereIsProblem($thereIsProblem)
    {
        $this->thereIsProblem = $thereIsProblem;
    }

    public function getCurrentType()
    {
        return $this->currentType;
    }

    public function setCurrentType($currentType)
    {
        $this->currentType = $currentType;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getRegistry()
    {
        return $this->registry;
    }

    public function setRegistry($registry)
    {
        $this->registry = $registry;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getInputHasEmployee()
    {
        return $this->inputHasEmployee;
    }

    public function setInputHasEmployee($inputHasEmployee)
    {
        $this->inputHasEmployee = $inputHasEmployee;
    }

    public function getCredential()
    {
        return $this->credential;
    }

    public function setCredential($credential)
    {
        $this->credential = $credential;
    }

    public function getAllStates()
    {
        return $this->allStates;
    }

    public function setAllStates($allStates)
    {
        $this->allStates = $allStates;
    }

    public function getAllRoles()
    {
        return $this->allRoles;
    }

    public function setAllRoles($allRoles)
    {
        $this->allRoles = $allRoles;
    }

    public function getAllTickets()
    {
        return $this->allTickets;
    }

    public function setAllTickets($allTickets)
    {
        $this->allTickets = $allTickets;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->clientController = ClientController::getInstance();
        $this->employeeController = EmployeeController::getInstance();
        $this->registryController = RegistryController::getInstance();
        $this->cityController = CityController::getInstance();
        $this->stateController = StateController::getInstance();
        $this->roleController = RoleController::getInstance();
        $this->credentialController = CredentialController::getInstance();
        $this->ticketController = TicketController::getInstance();
    }

    public function verifyGet($get)
    {
        $this->thereIsProblem = false;
        if ((isset($get['type']) && $get['type'] != "") && (isset($get['id']) && $get['id'] != "")) {
            $this->currentType = $get['type'];
            $this->currentId = $get['id'];

            $this->verifyCurrentType();
            $this->getTickets();
        } else {
            $this->thereIsProblem = true;
        }
    }

    public function verifyCurrentType()
    {
        if ($this->currentType == "client") {
            $this->findClientById();
            $this->findRegistryById();
            $this->findCityById();
            $this->findStateById();
            $this->findRoleByStatusAndId();
            $this->findAllRoles("1");
        } else if ($this->currentType == "employee") {
            $this->inputHasEmployee = "<input type='hidden' id='hasEmployee'>";

            $this->findEmployeeById();
            $this->findRoleByStatusAndId();
            $this->findAllRoles("0");
        } else {
            $this->thereIsProblem = true;
        }

        $this->findCredentialBydId();
    }

    public function findClientById()
    {
        $this->user = $this->clientController->findById($this->currentId);
    }

    public function findRegistryById()
    {
        $this->registry = $this->registryController->findById($this->user['id_registry']);
    }

    public function findCityById()
    {
        $this->city = $this->cityController->findById($this->registry['id_city']);
    }

    public function findStateById()
    {
        $this->state = $this->stateController->findById($this->city['id_state']);
    }

    public function findRoleByStatusAndId()
    {
        $this->role = $this->roleController->findRoleByStatusAndId("ativo", $this->user['id_role']);
    }

    public function findEmployeeById()
    {
        $this->user = $this->employeeController->findById($this->currentId);
    }

    public function findCredentialBydId()
    {
        $this->credential = $this->credentialController->findById($this->user['id_credential']);
    }

    public function findAllStates()
    {
        $this->allStates = $this->stateController->findAllStates();
    }

    public function findAllRoles($type)
    {
        $this->allRoles = $this->roleController->findAllByType($type);
    }

    public function getTickets()
    {
        $this->allTickets = $this->ticketController->findByClient($this->currentId);
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['User' . '_page_' . $_SESSION['login']])) {
            header("Location:../dashboard");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new NewUserController();
        }
        return self::$instance;
    }
}
