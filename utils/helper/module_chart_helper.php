<?php
include_once __DIR__ . "/../controller/ticket/ticket.ctrl.php";
include_once __DIR__ . "/../controller/module/module.ctrl.php";
include_once __DIR__ . "/../controller/category/category.ctrl.php";

class ModuleChartHelper
{
    private static $instance;
    private $ticketController;
    private $moduleController;
    private $categoryController;

    private $labels;
    private $data;
    private $topFiveModules;
    private $backgroundColors;

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

    function __construct()
    {
        $this->ticketController = TicketController::getInstance();
        $this->moduleController = ModuleController::getInstance();
        $this->categoryController = CategoryController::getInstance();

        @define('OPEN', '[');
        @define('CLOSE', ']');
        @define('DELIMITER', ',');
    }

    public function topFiveModules($group)
    {
        $this->topFiveModules = $this->ticketController->findTopFiveModules($group);
    }

    public function makeLabelsAndData()
    {
        $i = 0;
        $count = count($this->topFiveModules) - 1;
        $labels = OPEN;
        $data = OPEN;

        foreach ($this->topFiveModules as $module) {
            $actualModule = $this->moduleController->findById($module[1]);
            $actualCategory = $this->categoryController->findById($actualModule['id_category']);

            if ($i != $count) {
                $labels = $labels . '"' . $actualCategory['description'] . "/" . $actualModule['description'] . '"' . DELIMITER;
                $data = $data . $module[0] . DELIMITER;
            } else {
                $labels = $labels . '"' . $actualCategory['description'] . "/" . $actualModule['description'] . '"';
                $data = $data . $module[0];
            }
            $i++;
        }

        $labels = $labels . CLOSE;
        $data = $data . CLOSE;
        $this->labels = $labels;
        $this->data = $data;
    }

    public function suffleColors()
    {
        $colors = ["rgb(201, 203, 207)", "rgb(255, 205, 86)", "rgb(255, 99, 132)", "rgb(54, 162, 235)", "rgb(75, 192, 192)"];
        shuffle($colors);

        $newSequenceColors = OPEN;
        $i = 0;
        $count = count($colors) - 1;
        
        foreach ($colors as $color) {
            if ($i != $count) {
                $newSequenceColors = $newSequenceColors . '"' . $color . '"' . DELIMITER;
            } else {
                $newSequenceColors = $newSequenceColors . '"' . $color . '"';
            }
            $i++;
        }

        $newSequenceColors = $newSequenceColors . CLOSE;
        $this->backgroundColors = $newSequenceColors;
    }

    public function makeElement($group)
    {
        $this->topFiveModules($group);
        $this->makeLabelsAndData();
        $this->suffleColors();

        $element = '{
            "type": "polarArea",
            "data": {
                "labels":' . $this->labels . ',
                "datasets": [{
                    "label": "",
                    "data":' . $this->data . ',
                    "backgroundColor":' . $this->backgroundColors . '
                }]
            }
        }';

        return $element;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ModuleChartHelper();
        }
        return self::$instance;
    }
}
