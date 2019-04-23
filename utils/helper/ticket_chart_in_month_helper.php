<?php
include_once __DIR__ . "/../controller/ticket/ticket.ctrl.php";
include_once __DIR__ . "/../controller/module/module.ctrl.php";
include_once __DIR__ . "/../controller/category/category.ctrl.php";

class TicketChartInMonthHelper
{
    private static $instance;
    private $ticketController;
    private $moduleController;
    private $categoryController;

    private $labels;
    private $data;
    private $dataGroup1;
    private $dataGroup2;
    private $monthsToCount;

    private $initialDate;

    public function getLabels()
    {
        return $this->labels;
    }

    public function setLabels($labels)
    {
        $this->labels = $labels;
    }

    public function getData()
    {
        return $this->data;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function getTopFiveModules()
    {
        return $this->topFiveModules;
    }

    public function setTopFiveModules($topFiveModules)
    {
        $this->topFiveModules = $topFiveModules;
    }

    public function getInitialDate()
    {
        return $this->initialDate;
    }

    public function setInitialDate($initialDate)
    {
        $this->initialDate = $initialDate;
    }

    function __construct()
    {
        $this->ticketController = TicketController::getInstance();
        $this->moduleController = ModuleController::getInstance();
        $this->categoryController = CategoryController::getInstance();

        @define('OPEN', '[');
        @define('CLOSE', ']');
        @define('DELIMITER', ',');
    }

    public function makeLabels()
    {
        $this->monthsToCount = array();
        $months = array();
        $labels = OPEN;
        for ($i = 0; $i < 12; $i++) {
            $monthToCount = date("Y-m", strtotime('-' . $i . ' Month', strtotime($this->initialDate)));
            $actualMonth = '"' . date("m/Y", strtotime('-' . $i . ' Month', strtotime($this->initialDate))) . '"';
            
            array_unshift($this->monthsToCount, $monthToCount);
            array_unshift($months, $actualMonth);
        }
        $labels = $labels . implode(",", $months) . CLOSE;
        $this->labels = $labels;
    }

    public function makeData()
    {
        $data = OPEN;
        for ($i = 0; $i < 12; $i++) {
            if ($i < 12) {
                $data = $data . $this->ticketController->countByMonth($this->monthsToCount[$i])['total'] . DELIMITER;
            } else {
                $data = $data . $this->ticketController->countByMonth($this->monthsToCount[$i])['total'];
            }
        }
        $data = $data . CLOSE;
        $this->data = $data;
    }

    public function makeDataByGroup($group)
    {
        $data = OPEN;
        for ($i = 0; $i < 12; $i++) {
            if ($i < 12) {
                $data = $data . $this->ticketController->countByGroupAndMonth($group, $this->monthsToCount[$i])['total'] . DELIMITER;
            } else {
                $data = $data . $this->ticketController->countByGroupAndMonth($group, $this->monthsToCount[$i])['total'];
            }
        }
        $data = $data . CLOSE;
        return $data;
    }

    public function makeElement()
    {
        $this->makeLabels();
        $this->makeData();
        $this->dataGroup1 = $this->makeDataByGroup("nivel1");
        $this->dataGroup2 = $this->makeDataByGroup("nivel2");
        
        $element = '{
            "type": "line",
            "data": {
                "labels": ' . $this->labels . ',
                "datasets": [
                    {
                        "label": "Total no mês",
                        "data": ' . $this->data . ',
                        "fill": false,
                        "borderColor": "rgb(75, 192, 192)",
                        "lineTension": 0.1
                    },
                    {
                        "label": "Nível 1",
                        "data": ' . $this->dataGroup1 . ',
                        "fill": false,
                        "borderColor": "rgb(133, 191, 66)",
                        "lineTension": 0.1
                    },
                    {
                        "label": "Nível 2",
                        "data": ' . $this->dataGroup2 . ',
                        "fill": false,
                        "borderColor": "rgb(188, 66, 66)",
                        "lineTension": 0.1
                    }
                ]
            }
        }';

        return $element;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ticketChartInMonthHelper();
        }
        return self::$instance;
    }
}
