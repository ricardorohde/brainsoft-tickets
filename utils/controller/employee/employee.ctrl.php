<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/employee.php";

class EmployeeController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function findAll()
    {
        $employee = new Employee($this, $this->prepareInstance);
        return $employee->findAll();
    }

    public function findById($id)
    {
        $employee = new Employee($this, $this->prepareInstance);
        $employee->setId($id);
        return $employee->findById();
    }

    public function findByCredential($id)
    {
        $employee = new Employee($this, $this->prepareInstance);
        $employee->setIdCredential($id);
        return $employee->findByCredential();
    }

    public function findByName($name)
    {
        $employee = new Employee($this, $this->prepareInstance);
        $employee->setName($name);
        return $employee->findByName();
    }

    public function findAllByGroupAndName()
    {
        $employee = new Employee($this, $this->prepareInstance);
        return $employee->findAllByGroupAndName();
    }

    public function findDataOfEmployees($sqlIds)
    {
        $employee = new Employee($this, $this->prepareInstance);
        return $employee->findDataBySqlIds($sqlIds);
    }

    public function new($data)
    {  
        $employee = new Employee($this, $this->prepareInstance);
        $employee->setName($data['name']);
        $employee->setEmail($data['email']);
        $employee->setTGroup($data['group']);
        $employee->setIdCredential($data[0]);
        $employee->setIdRole($data['role']);
        return $employee->register();
    }

    public function update($data)
    {
        $employee = new Employee($this, $this->prepareInstance);
        $employee->setName($data['name']);
        $employee->setEmail($data['email']);
        $employee->setTGroup($data['group']);
        $employee->setIdRole($data['role']);
        $employee->setId($data['id_user']);
        return $employee->update();
    }

    public function statusOnChat($idCredential, $status)
    {
        $employee = new Employee($this, $this->prepareInstance);
        $employee->setOnChat($status);
        $employee->setIdCredential($idCredential);
        return $employee->turnOn();
    }

    public function filterAttendantsByTwoGroups()
    {
        $employee = new Employee($this, $this->prepareInstance);
        return $employee->filterByTwoGroups();
    }

    public function findToForward()
    {
        $employee = new Employee($this, $this->prepareInstance);
        return $employee->findToForward();
    }

    public function verifyOnChatToSetSession($idCredential)
    {
        $employee = new employee($this, $this->prepareInstance);
        $employee->setIdCredential($idCredential);
        return $employee->verifyOnChat();
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Employee'.'_page_'.$_SESSION['login']])) {
            header("Location:/painel/conta");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new EmployeeController();
        }
        return self::$instance;
    }
}