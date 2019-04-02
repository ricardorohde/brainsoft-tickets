<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../ticket/ticket.ctrl.php";
include_once __DIR__ . "/../employee/employee.ctrl.php";

class DashboardController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $ticketController;
    private $employeeController;

    private $totalTickets;
    private $openTickets;
    private $pendingTickets;
    private $solvedTickets;

    private $labelsToBarGraph;

    public function getTotalTickets()
    {
        return $this->totalTickets;
    }

    public function setTotalTickets($totalTickets)
    {
        $this->totalTickets = $totalTickets;
    }

    public function getOpenTickets()
    {
        return $this->openTickets;
    }

    public function setOpenTickets($openTickets)
    {
        $this->openTickets = $openTickets;
    }

    public function getPendingTickets()
    {
        return $this->pendingTickets;
    }

    public function setPendingTickets($pendingTickets)
    {
        $this->pendingTickets = $pendingTickets;
    }

    public function getSolvedTickets()
    {
        return $this->solvedTickets;
    }

    public function setSolvedTickets($solvedTickets)
    {
        $this->solvedTickets = $solvedTickets;
    }

    public function getLabelsToBarGraph()
    {
        return $this->labelsToBarGraph;
    }

    public function setLabelsToBarGraph($labelsToBarGraph)
    {
        $this->labelsToBarGraph = $labelsToBarGraph;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->ticketController = TicketController::getInstance();
        $this->employeeController = EmployeeController::getInstance();

        define('OPEN', '[');
        define('CLOSE', ']');
        define('DELIMITER', ',');

        $this->findTotalTickets();
        $this->findOpenTickets();
        $this->findPendingTickets();
        $this->findSolvedTickets();
        $this->makeLabelsToBarGraph();
    }

    public function findTotalTickets()
    {
        $this->totalTickets = $this->ticketController->findTotalTickets();
    }

    public function findOpenTickets()
    {
        $this->openTickets = $this->ticketController->findOpenTickets();
    }

    public function findPendingTickets()
    {
        $this->pendingTickets = $this->ticketController->findPendingTickets();
    }

    public function findSolvedTickets()
    {
        $this->solvedTickets = $this->totalTickets['total'] - $this->pendingTickets['total'] - $this->openTickets['total'];
    }

    public function makeLabelsToBarGraph()
    {
        $labels = OPEN;
        for ($i = 0; $i < 7; $i++) {
            if ($i == 0) {
                $labels = $labels . '"Hoje"' . DELIMITER;
            } else {
                $labels = $labels . '"' . date('d/m', strtotime('-' . $i . ' days')) . '"' . DELIMITER;
            }
        }
        $labels = $labels . CLOSE;

        $this->labelsToBarGraph = $labels;
    }

    public function makeDataSets()
    {
        $data = OPEN;
        $data = $data . $this->makeElements();
        $data = $data . CLOSE;

        return $data;
    }

    public function makeElements()
    {
        $element = "";

        for ($i = 0; $i < 7; $i++) {
            $actualAttendant = $this->findAttendant($i);
            $element = $element . '{
                label: "' . explode(" ", $actualAttendant)[0] . '",
                data:' . $this->makeData($actualAttendant) . ',
                backgroundColor: [' .
                $this->makeBackgroundColor($i) .
                '],
                borderColor: [' .
                $this->makeBorderColor($i) .
                '],
                borderWidth: 1
            }';

            if ($i < 7) {
                $element = $element . DELIMITER;
            }
        }

        return $element;
    }

    public function findAttendant($attendant)
    {
        $attendants = $this->employeeController->findAllByGroupAndName();

        return $attendants[$attendant]['name'];
    }

    public function makeData($name)
    {
        $attendantId = $this->employeeController->findByName($name)['id'];
        $data = OPEN;

        for ($i = 0; $i < 7; $i++) {
            $actualDate = date('Y-m-d', strtotime('-' . $i . ' days'));
            if ($i < 7) {
                $data = $data . $this->ticketController->countByAttendantAndDate($attendantId, $actualDate)['total'] . DELIMITER;
            } else {
                $data = $data . $this->ticketController->countByAttendantAndDate($attendantId, $actualDate)['total'];
            }
        }

        $data = $data . CLOSE;
        return $data;
    }

    public function makeBackgroundColor($color)
    {
        $colorsList = ['"rgba(255, 68, 68, 0.3)"', '"rgba(44, 177, 99, 0.3)"', '"rgba(255, 131, 43, 0.3)"', '"rgba(35, 209, 209, 0.3)"', '"rgba(255, 177, 43, 0.3)"', '"rgba(39, 95, 234, 0.3)"', '"rgba(0, 107, 164, 0.3)"'];
        $colors = "";

        for ($i = 0; $i < 7; $i++) {
            if ($i < 7) {
                $colors = $colors . $colorsList[$color] . DELIMITER;
            } else {
                $colors = $colors . $colorsList[$color];
            }
        }

        return $colors;
    }

    public function makeBorderColor($color)
    {
        $colorsList = ['"rgba(255, 68, 68, 1)"', '"rgba(44, 177, 99, 1)"', '"rgba(255, 131, 43, 1)"', '"rgba(35, 209, 209, 1)"', '"rgba(255, 177, 43, 1)"', '"rgba(39, 95, 234, 1)"', '"rgba(0, 107, 164, 1)"'];
        $colors = "";

        for ($i = 0; $i < 7; $i++) {
            if ($i < 7) {
                $colors = $colors . $colorsList[$color] . DELIMITER;
            } else {
                $colors = $colors . $colorsList[$color];
            }
        }

        return $colors;
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION["Index" . "_page_" . $_SESSION['login']])) {
            header("Location:/painel/conta");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new DashboardController();
        }
        return self::$instance;
    }
}
