<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../ticket/ticket.ctrl.php";
include_once __DIR__ . "/../../helper/ticket_chart_in_week_helper.php";
include_once __DIR__ . "/../../helper/module_chart_helper.php";
include_once __DIR__ . "/../../helper/ticket_chart_in_month_helper.php";
include_once __DIR__ . "/../../helper/ticket_chart_by_registry_helper.php";

class DashboardController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $ticketController;

    private $ticketChartHelper;
    private $moduleChartHelper;
    private $ticketChartInMonthHelper;
    private $ticketChartByRegistryHelper;

    private $totalTickets;
    private $openTickets;
    private $pendingTickets;
    private $solvedTickets;

    private $actualDate;

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

    public function getActualDate()
    {
        return $this->actualDate;
    }

    public function setActualDate($actualDate)
    {
        $this->actualDate = $actualDate;
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

        $this->ticketChartHelper = TicketChartInWeekHelper::getInstance();
        $this->moduleChartHelper = ModuleChartHelper::getInstance();
        $this->ticketChartInMonthHelper = TicketChartInMonthHelper::getInstance();
        $this->ticketChartByRegistryHelper = TicketChartByRegistryHelper::getInstance();

        $this->actualDate = date("Y-m-d");

        $this->findTotalTickets();
        $this->findOpenTickets();
        $this->findPendingTickets();
        $this->findSolvedTickets();
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

    public function makeBarChart($post)
    {
        if (isset($post['filter-to-tickets-in-week']) && isset($post['bar-tickets-in-week-filter'])) {
            $date = $post['bar-tickets-in-week-filter'];
        } else {
            $date = date("Y-m-d");
        }

        $this->ticketChartHelper->setInitialDate($date);
        $this->ticketChartHelper->makeLabels();
    }

    public function makePolarChart($post)
    {
        if (isset($post['filter-to-modules'])) {
            $initialDate = $post['polar-modules-initial-filter'];
            $finalDate = $post['polar-modules-final-filter'];
        } else {
            $initialDate = date("Y-m-d", strtotime("2018-10-10"));
            $finalDate = date("Y-m-d");
        }

        $this->moduleChartHelper->setInitialDate($initialDate);
        $this->moduleChartHelper->setFinalDate($finalDate);
    }

    public function makeLineChart($post)
    {
        if (isset($post['filter-to-tickets-in-month']) && isset($post['line-tickets-in-month-filter'])) {
            $date = $post['line-tickets-in-month-filter'];
        } else {
            $date = date("Y-m-d");
        }
        $this->ticketChartInMonthHelper->setInitialDate($date);
    }

    public function makeHorizontalBarChart($post)
    {
        if (isset($post['filter-to-bar-tickets-by-registry'])) {
            $initialDate = $post['bar-tickets-of-registry-initial-filter'];
            $finalDate = $post['bar-tickets-of-registry-final-filter'];
        } else {
            $initialDate = date("Y-m-d", strtotime("2018-10-10"));
            $finalDate = date("Y-m-d");
        }

        $this->ticketChartByRegistryHelper->setInitialDate($initialDate);
        $this->ticketChartByRegistryHelper->setFinalDate($finalDate);
    }

    public function getLabelsToBarChart()
    {
        return $this->ticketChartHelper->getLabels();
    }

    public function getDataSetsToBarChart()
    {
        return $this->ticketChartHelper->makeDataSets();
    }

    public function getOptionsToBarChart()
    {
        return $this->ticketChartHelper->getOptions();
    }

    public function getElementToPolarChart($group)
    {
        return $this->moduleChartHelper->makeElement($group);
    }

    public function getElementToLineChart()
    {
        return $this->ticketChartInMonthHelper->makeElement();
    }

    public function getElementToHorizontalBarChart()
    {
        return $this->ticketChartByRegistryHelper->makeElement();
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
