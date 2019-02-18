<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../client/client.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../registry/registry.ctrl.php";
include_once __DIR__ . "/../city/city.ctrl.php";
include_once __DIR__ . "/../state/state.ctrl.php";
include_once __DIR__ . "/../role/role.ctrl.php";
include_once __DIR__ . "/../credential/credential.ctrl.php";

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
    }

    public function verifyGet($get)
    {
        $this->thereIsProblem = false;
        if ((isset($get['type']) && $get['type'] != "") && (isset($get['id']) && $get['id'] != "")) {
          $this->currentType = $get['type'];
          $this->currentId = $get['id'];

          $this->verifyCurrentType();
        } else {
          $this->thereIsProblem = true;
        }
    }

    public function verifyCurrentType()
    {
        if($this->currentType == "client") {
            /*$sql_user = $connection->getConnection()->prepare("SELECT name, email, id_credential, id_registry, id_role FROM client WHERE id = ?");
            $sql_user->execute(array($id));
            $row_sql_user = $sql_user->fetch();*/
            $this->findClientById();

            /*$sql_registry = $connection->getConnection()->prepare("SELECT * FROM registry WHERE id = ?");
            $sql_registry->execute(array($row_sql_user['id_registry']));
            $row_sql_registry = $sql_registry->fetch();*/
            $this->findRegistryById();

            /*$sql_city = $connection->getConnection()->prepare("SELECT * FROM city WHERE id = ?");
            $sql_city->execute(array($row_sql_registry['id_city']));
            $row_sql_city = $sql_city->fetch();*/
            $this->findCityById();

            /*$sql_state = $connection->getConnection()->prepare("SELECT * FROM state WHERE id = ?");
            $sql_state->execute(array($row_sql_city['id_state']));
            $row_sql_state = $sql_state->fetch();*/
            $this->findStateById();

            /*$sql_role = $connection->getConnection()->prepare("SELECT * FROM role WHERE status = ? AND id = ?");
            $sql_role->execute(array("ativo", $row_sql_user['id_role']));
            $row_sql_role = $sql_role->fetch();*/
            $this->findRoleByStatusAndId();

            $this->findAllRoles("1");
        } else if ($this->currentType == "employee") {
            $this->inputHasEmployee = "<input type='hidden' id='hasEmployee'>";

            /*$sql_user = $connection->getConnection()->prepare("SELECT name, email, t_group, id_credential, id_role FROM employee WHERE id = ?");
            $sql_user->execute(array($id)); 
            $row_sql_user = $sql_user->fetch();*/
            $this->findEmployeeById();

            /*$sql_role = $connection->getConnection()->prepare("SELECT * FROM role WHERE status = ? AND id = ?"); 
            $sql_role->execute(array("ativo", $row_sql_user['id_role'])); 
            $row_sql_role = $sql_role->fetch();*/
            $this->findRoleByStatusAndId();

            $this->findAllRoles("0");
        } else {
            $this->thereIsProblem = true;
        }

        /*$sql_credential = $connection->getConnection()->prepare("SELECT login FROM credential WHERE id = ?"); 
        $sql_credential->execute(array(@$row_sql_user['id_credential'])); 
        $row_sql_credential = $sql_credential->fetch();*/
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
    
    public function verifyPermission()
    {
        if (!isset($_SESSION['User'.'_page_'.$_SESSION['login']])) {
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
