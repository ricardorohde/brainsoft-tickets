<?php
include_once __DIR__ . "/../employee/employee.ctrl.php";
include_once __DIR__ . "/../ticket/ticket.ctrl.php";

class headerController
{
    private static $instance;

    private $employeeController;
    private $ticketController;

    function __construct()
    {
        $this->employeeController = EmployeeController::getInstance();
        $this->ticketController = TicketController::getInstance();
    }

    public function checkAttendantStatus($idCredential, $checkBox)
    {
        $statusRaw = $this->employeeController->verifyOnChatToSetSession($idCredential);
        $status = $statusRaw['on_chat'];

        if ($status == $checkBox) {
            return "checked";
        }
    }

    public function checkTicketsInQueue()
    {
        $id = $this->employeeController->findByCredential($_SESSION['login'])['id'];
		if ($this->ticketController->checkIfHasOpenTicketByEmployee($id)['total'] > 0) {
			return "hide";
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
