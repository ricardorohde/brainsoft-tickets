<?php
include_once __DIR__ . "/../../pct-chat/api.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
require_once __DIR__ . "/../../model/ticket.php";

class TicketController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $apiPct;
    private $openedAt;
    private $transferedAt;
    private $lastMessageByReceptionAt;
    private $afterTransferStartAt;

    public function getApiPct()
    {
        return $this->apiPct;
    }
    
    public function setApiPct($apiPct)
    {
        $this->apiPct = $apiPct;
    }

    public function getOpenedAt() 
    {
        return $this->openedAt;
    }
    
    public function setOpenedAt($openedAt) 
    {
        $this->openedAt = $openedAt;
    }

    public function getTransferedAt()
    {
      return $this->transferedAt;
    }
    
    public function setTransferedAt($transferedAt)
    {
      $this->transferedAt = $transferedAt;
    }

    public function getLastMessageByReceptionAt() 
    {
        return $this->lastMessageByReceptionAt;
    }
    
    public function setLastMessageByReceptionAt($lastMessageByReceptionAt) 
    {
        $this->lastMessageByReceptionAt = $lastMessageByReceptionAt;
    }

    public function getAfterTransferStartAt() 
    {
        return $this->afterTransferStartAt;
    }
    
    public function setAfterTransferStartAt($afterTransferStartAt) 
    {
        $this->afterTransferStartAt = $afterTransferStartAt;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();

        $this->setApiPct(new ApiPct());
    }

    public function getConn()
    {
        return $this->navBarController->getConnection();
    }

    function setTimezone()
    {
        date_default_timezone_set('America/Sao_Paulo');
    }

    function setIdChat($id)
    {   
        $this->apiPct->assignIdChatInSession($id);
    }

    function getHistoryOfChat()
    {
        $this->apiPct->consultAllChats();
        $this->apiPct->putFeaturesOfChatInVariables();
        return $this->apiPct->getDataOfEspecificChat();
    }

    function getAttendant()
    {
        return $this->apiPct->getAttendant();
    }

    function getClient()
    {
        return $this->apiPct->getClient();
    }

    function getIpClient()
    {
        return $this->apiPct->getIpClient();
    }

    function getStart()
    {
        return $this->apiPct->getStart();
    }

    function getFinal()
    {
        return $this->apiPct->getFinal();
    }

    function getRating()
    {
        return $this->apiPct->getRating();
    }

    public function findTotalTickets()
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        return $ticket->findTotalTickets();
    }

    public function findOpenTickets()
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setStatus("aberto");
        return $ticket->findOpenTickets();
    }

    public function findPendingTickets()
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setStatus("pendente");
        return $ticket->findPendingTickets();
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Ticket'.'_page_'.$_SESSION['login']])) {
            header("Location:../dashboard");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new TicketController();
        }
        return self::$instance;
    }
}
