<?php
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . '/../../../common/session.php';

class IsOnChatController
{
    private static $instance;

    private $employeeController;
    private $sessionController;

    function __construct()
    {
        $this->employeeController = EmployeeController::getInstance();
        $this->sessionController = Session::getInstance();
    }

    public function checkOnChatToLogout($idCredential)
    {
        $statusRaw = $this->employeeController->verifyOnChatToSetSession($idCredential);
        $status = $statusRaw['on_chat'];

        if ($status == "no") {
            $this->sessionController->destroy();
            header("Location:/");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new IsOnChatController();
        }
        return self::$instance;
    }
}