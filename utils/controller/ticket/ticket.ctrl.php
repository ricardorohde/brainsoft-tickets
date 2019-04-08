<?php
include_once __DIR__ . "/../../pct-chat/api.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../category/category.ctrl.php";
include_once __DIR__ . "/../module/module.ctrl.php";
require_once __DIR__ . "/../../model/ticket.php";

class TicketController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;
    private $categoryController;
    private $moduleController;

    private $apiPct;
    private $openedAt;
    private $transferedAt;
    private $lastMessageByReceptionAt;
    private $afterTransferStartAt;

    private $categoriesGroup1;
    private $categoriesGroup2;
    private $sqlCategoriesGroup1;
    private $sqlCategoriesGroup2;

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

    public function getCategoriesGroup1() 
    {
        return $this->categoriesGroup1;
    }
    
    public function setCategoriesGroup1($categoriesGroup1) 
    {
        $this->categoriesGroup1 = $categoriesGroup1;
    }

    public function getCategoriesGroup2() 
    {
        return $this->categoriesGroup2;
    }
    
    public function setCategoriesGroup2($categoriesGroup2) 
    {
        $this->categoriesGroup2 = $categoriesGroup2;
    }

    public function getSqlCategoriesGroup1() 
    {
        return $this->sqlCategoriesGroup1;
    }
    
    public function setSqlCategoriesGroup1($sqlCategoriesGroup1) 
    {
        $this->sqlCategoriesGroup1 = $sqlCategoriesGroup1;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->categoryController = CategoryController::getInstance();
        $this->moduleController = ModuleController::getInstance();

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

    function getHistoryOfChat($date)
    {
        $registered_at = date("Y/m/d", strtotime($date));

        $this->apiPct->consultAllChats($registered_at);
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

    public function checkIfHasOpenTicketByEmployee($idEmployee)
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setIdAttendant($idEmployee);
        return $ticket->findOpenedTicketsByAttendant();
    }

    public function findAllCategoryModule()
    {
        $rawCategoriesGroup1 = $this->categoryController->findByGroup("nivel1");
        $rawCategoriesGroup2 = $this->categoryController->findByGroup("nivel2");

        $encodeCategoriesGroup1 = json_encode($rawCategoriesGroup1);
        $encodeCategoriesGroup2 = json_encode($rawCategoriesGroup2);
        $this->categoriesGroup1 = json_decode($encodeCategoriesGroup1);
        $this->categoriesGroup2 = json_decode($encodeCategoriesGroup2);

        $categoriesIdsGroup1 = array_column($rawCategoriesGroup1, 'id');
        $categoriesIdsGroup2 = array_column($rawCategoriesGroup2, 'id');
        $this->sqlCategoriesGroup1 = implode(',', $categoriesIdsGroup1);
        $this->sqlCategoriesGroup2 = implode(',', $categoriesIdsGroup2);
    }

    public function findDataOfCategoriesGroup1()
    {
        $enconde = json_encode($this->moduleController->findDataOfCategories($this->sqlCategoriesGroup1));
        return json_decode($enconde);
    }

    public function findDataOfCategoriesGroup2()
    {
        $encode = json_encode($this->moduleController->findDataOfCategories($this->sqlCategoriesGroup2));
        return json_decode($encode);
    }

    public function findByClient($id)
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setIdClient($id);
        return $ticket->findByClient();
    }

    public function countByAttendantAndDate($attendantId, $actualDate)
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setIdAttendant($attendantId);
        $ticket->setRegisteredAt($actualDate);
        $ticket->setFinalizedAt($actualDate);
        return $ticket->countByAttendantAndDate();
    }

    public function findTopFiveModules($group, $initialDate, $finalDate)
    {
        $ticket = new Ticket($this, $this->prepareInstance);
        $ticket->setGroup($group);
        $ticket->setRegisteredAt($initialDate);
        $ticket->setFinalizedAt($finalDate);
        return $ticket->topFiveModules();
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['Ticket'.'_page_'.$_SESSION['login']])) {
            header("Location:/painel/conta");
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
