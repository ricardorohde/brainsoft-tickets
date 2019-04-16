<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/employee.php";
include_once __DIR__ . "/employee.ctrl.php";

$employeeController = new EmployeeJsController();
$employeeController->verifyData();

class EmployeeJsController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $employeeController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->employeeController = EmployeeController::getInstance();
    }

    public function verifyData()
    {
        if (isset($_POST['group']) && !empty($_POST['group'])) {
            $this->findAttendantsByGroup($_POST['group']);
        }

        if (isset($_POST['status']) && !empty($_POST['status'])) {
            $this->changeStatusOnChat($_POST['status']);
        }
    }

    public function findAttendantsByGroup($group)
    {
        $employee = new Employee($this, $this->prepareInstance);

        $employee->setTGroup($group);
        $employees = $employee->findAttendants();
  
        $qtd_attendants = count($employees);

        for ($i = 0; $i < $qtd_attendants; $i++) { 
            $id = $employees[$i]['id'];
            $name = $employees[$i]['name'];
            $option = utf8_encode("<option value='$id'>$name</option>");

            echo $option;
        }
    }

    private function changeStatusOnChat($status)
    {
        $this->employeeController->statusOnChat($_SESSION['login'], $status);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new EmployeeJsController();
        }
        return self::$instance;
    }
}