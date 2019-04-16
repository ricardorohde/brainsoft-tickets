<?php
include_once __DIR__ . "/../employee/employee.ctrl.php";

class headerController
{
    private static $instance;

    private $employeeController;

    function __construct()
    {
        $this->employeeController = EmployeeController::getInstance();
    }

    public function checkAttendantStatus($idCredential, $checkBox)
    {
        $statusRaw = $this->employeeController->verifyOnChatToSetSession($idCredential);
        $status = $statusRaw['on_chat'];

        if ($status == $checkBox) {
            return "checked";
        } else {
            return "";
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new headerController();
        }
        return self::$instance;
    }
}
