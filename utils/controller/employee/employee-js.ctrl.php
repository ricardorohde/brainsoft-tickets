<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/employee.php";

$employeeController = new EmployeeJsController();
$employeeController->verifyData();

class EmployeeJsController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function verifyData()
    {
        if (isset($_POST['group']) && !empty($_POST['group'])) {
            $this->findAttendantsByGroup($_POST['group']);
        } 
    }

    public function findAttendantsByGroup($group)
    {
        $employee = new Employee($this, $this->prepareInstance);

        $employee->setOnChat("yes");
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

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new EmployeeJsController();
        }
        return self::$instance;
    }
}