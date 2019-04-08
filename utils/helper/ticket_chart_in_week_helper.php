<?php
include_once __DIR__ . "/../controller/ticket/ticket.ctrl.php";
include_once __DIR__ . "/../controller/employee/employee.ctrl.php";

class TicketChartInWeekHelper
{
    private static $instance;
    private $ticketController;

    private $labels;
    private $options;
    private $dayInWeekFlag;

    private $initialDate;

    public function getLabels()
    {
        return $this->labels;
    }
    public function setLabels($labels)
    {
        $this->labels = $labels;
    }
    public function getOptions()
    {
        return $this->options;
    }
    public function setOptions($options)
    {
        $this->options = $options;
    }
    public function getinitialDate()
    {
        return $this->initialDate;
    }
    public function setinitialDate($initialDate)
    {
        $this->initialDate = $initialDate;
    }

    function __construct()
    {
        $this->ticketController = TicketController::getInstance();
        $this->employeeController = EmployeeController::getInstance();

        define('OPEN', '[');
        define('CLOSE', ']');
        define('DELIMITER', ',');
        $this->dayInWeekFlag = false;

        $this->makeOptions();
    }

    public function makeLabels()
    {
        $labels = OPEN;
        for ($i = 0; $i < 7; $i++) {
            $labels = $labels . '"' . date('d/m', strtotime($this->dayInWeek($i))) . '"' . DELIMITER;
        }
        $labels = $labels . CLOSE;
        $this->labels = $labels;
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
        $this->dayInWeekFlag = false;
        $attendantId = $this->employeeController->findByName($name)['id'];
        $data = OPEN;
        for ($i = 0; $i < 7; $i++) {
            $actualDate = $this->dayInWeek($i);
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
    public function dayInWeek($decrement)
    {       
        $actualDate = date('Y-m-d', strtotime('-' . $decrement . ' days', strtotime($this->initialDate)));
        $dayInWeek = date('w', strtotime($actualDate));
        if ($dayInWeek == 0 || $dayInWeek == 6 || $this->dayInWeekFlag) {
            $this->dayInWeekFlag = true;
            $decrement = $decrement + 2;
            $actualDate = date('Y-m-d', strtotime('-' . $decrement . ' days', strtotime($this->initialDate)));
        }
        return $actualDate;
    }

    public function makeOptions()
    {
        $options = '{
            responsive: true,
            title: {
                display: true,
                position: "top",
                text: "Atendimentos na semana",
                fontSize: 18,
                fontColor: "#111"
            },
            legend: {
                display: true,
                position: "bottom",
                labels: {
                    fontColor: "#333",
                    fontSize: 12
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        min: 0
                    }
                }]
            }
        }';

        $this->options = $options;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new TicketChartInWeekHelper();
        }
        return self::$instance;
    }
}
