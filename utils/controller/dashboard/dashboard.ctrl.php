<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../ticket/ticket.ctrl.php";

class DashboardController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $ticketController;

    private $totalTickets;
    private $openTickets;
    private $pendingTickets;
    private $solvedTickets;

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

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->ticketController = TicketController::getInstance();
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

    public function verifyPermission()
    {
        if (!isset($_SESSION['login'])) {
            if (isset($_SESSION['errorLogin'])) {
                unset($_SESSION['errorLogin']);
            };

            $_SESSION['withoutLogin'] = "<strong>Informação!</strong> Informe seus dados para acessar o sistema.";
            header("Location:../utils/do-login.php");
        }

        if (!isset($_SESSION["Index"."_page_".$_SESSION['login']])) {
            header("Location:conta");
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
