<?php
class Ticket
{
    private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $idChat;
	private $idRegistry;
	private $idClient;
	private $priority;
	private $status;
	private $source;
	private $type;
	private $tGroup;
	private $idModule;
	private $idAttendant;
	private $resolution;
	private $historic;
	private $isRepeated;
	private $finalizedAt;
	private $idWhoOpened;
	private $idWhoClosed;

    public function getPrepareInstance()
    {
      return $this->prepareInstance;
    }
    
    public function setPrepareInstance($prepareInstance)
    {
      $this->prepareInstance = $prepareInstance;
    }

    public function getMyController()
    {
      return $this->myController;
    }
    
    public function setMyController($myController)
    {
      $this->myController = $myController;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getIdChat()
    {
        return $this->idChat;
    }

    public function setIdChat($idChat)
    {
        $this->idChat = $idChat;
    }

    public function getIdRegistry()
    {
        return $this->idRegistry;
    }

    public function setIdRegistry($idRegistry)
    {
        $this->idRegistry = $idRegistry;
    }

    public function getIdClient()
    {
        return $this->idClient;
    }

    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function setSource($source)
    {
        $this->source = $source;
    }
    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getGroup()
    {
        return $this->tGroup;
    }

    public function setGroup($group)
    {
        $this->tGroup = $group;
    }

    public function getIdModule()
    {
        return $this->idModule;
    }

    public function setIdModule($idModule)
    {
        $this->idModule = $idModule;
    }

    public function getIdAttendant()
    {
        return $this->idAttendant;
    }

    public function setIdAttendant($idAttendant)
    {
        $this->idAttendant = $idAttendant;
    }

    public function getResolution()
    {
        return $this->resolution;
    }

    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    public function getHistoric()
    {
        return $this->historic;
    }

    public function setHistoric($historic)
    {
        $this->historic = $historic;
    }

    public function getIsRepeated()
    {
        return $this->isRepeated;
    }

    public function setIsRepeated($isRepeated)
    {
        $this->isRepeated = $isRepeated;
    }

    public function getFinalizedAt()
    {
        return $this->finalizedAt;
    }

    public function setFinalizedAt($finalizedAt)
    {
        $this->finalizedAt = $finalizedAt;
    }

    public function getIdWhoOpened()
    {
        return $this->idWhoOpened;
    }

    public function setIdWhoOpened($idWhoOpened)
    {
        $this->idWhoOpened = $idWhoOpened;
    }

    public function getIdWhoClosed()
    {
        return $this->idWhoClosed;
    }

    public function setIdWhoClosed($idWhoClosed)
    {
        $this->idWhoClosed = $idWhoClosed;
    }

	function __construct($controller, $prepareInstance)
    {
    	$this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
   	}

   	public function register()
    {
        $elements = [$this->getIdRegistry(), $this->getIdClient(), $this->getPriority(), $this->getStatus(), $this->getSource(), $this->getType(),
                    $this->getGroup(),$this->getIdModule(), $this->getIdAttendant(), $this->getResolution(), $this->getIsRepeated(), $this->getIdWhoOpened(), $this->getIdChat()];
        $query = "INSERT INTO ticket (`id`, `id_registry`, `id_client`, `priority`, `t_status`, `source`, `type`, `t_group`, `id_module`, `id_attendant`, `resolution`, `is_repeated`, `id_who_opened`, `id_who_closed`, `id_chat`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?);";
        return $this->prepareInstance->prepare($query, $elements, "");

        /*echo $this->getIdRegistry();
        echo "<br>";
        echo $this->getIdClient();
        echo "<br>";
        echo $this->getPriority();
        echo "<br>";
        echo $this->getStatus();
        echo "<br>";
        echo $this->getSource();
        echo "<br>";
        echo $this->getType();
        echo "<br>";
        echo $this->getGroup();
        echo "<br>";
        echo $this->getIdModule();
        echo "<br>";
        echo $this->getIdAttendant();
        echo "<br>";
        echo $this->getResolution();
        echo "<br>";
        echo $this->getIdWhoOpened();
        echo "<br>";
        echo $this->getIdChat();*/
   	}

   	public function update()
    {
        $elements = [$this->getIdRegistry(), $this->getIdClient(), $this->getPriority(), $this->getStatus(), $this->getSource(), $this->getType(),
                    $this->getGroup(),$this->getIdModule(), $this->getIdAttendant(), $this->getResolution(), $this->getIsRepeated(), $this->getIdChat()];
        $query = "UPDATE ticket SET id_registry = ?, id_client = ?, priority = ?, t_status = ?, source = ?, type = ?, t_group = ?, id_module = ?, id_attendant = ?, resolution = ?, is_repeated = ? WHERE id_chat = ?";
        return $this->prepareInstance->prepare($query, $elements, "");
   	}

   	public function finish()
    {
        $elements = [$this->getIdRegistry(), $this->getIdClient(), $this->getPriority(), $this->getStatus(), $this->getSource(), $this->getType(),
                    $this->getGroup(),$this->getIdModule(), $this->getIdAttendant(), $this->getResolution(), $this->getIsRepeated(), $this->getIdWhoClosed(), 
                    $this->getFinalizedAt(), $this->getIdChat()];
        $query = "UPDATE ticket SET id_registry = ?, id_client = ?, priority = ?, t_status = ?, source = ?, type = ?, t_group = ?, id_module = ?, id_attendant = ?, resolution = ?, is_repeated = ?, id_who_closed = ?, finalized_at = ? WHERE id_chat = ?";
        return $this->prepareInstance->prepare($query, $elements, "");
   	}

    //TO CTRL-SHOW-ALL
    public function filterByPeriod($initialDate, $actualDate)
    {
        $elements = [$initialDate."%", $actualDate."%"];
        $query = "SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
            WHERE registered_at BETWEEN ? AND ? ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, $elements, "all");
    }

    public function filterByPeriodAndAttendant($initialDate, $actualDate, $attedantId, $status)
    {
        $elements = [$initialDate."%", $actualDate."%", $attedantId, $status];
        $query = "SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
            WHERE (registered_at BETWEEN ? AND ?) AND id_attendant = ? AND t_status = ? ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, $elements, "all");
    }

    public function filterByActualDate($actualDate)
    {
        $element = $actualDate."%";
        $query = "SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
            WHERE registered_at LIKE ? ORDER BY t_status ASC, id DESC";
        return $this->prepareInstance->prepare($query, $element, "all");
    }

    public function findBySource()
    {
        $element = $this->getSource();
        $query = "SELECT * FROM ticket WHERE source = ? ORDER BY id DESC LIMIT 5";
        return $this->prepareInstance->prepare($query, $element, "all");
    }

    //
    public function findTotalTickets()
    {
        $query = "SELECT COUNT(*) as total FROM ticket";
        return $this->prepareInstance->prepare($query, "", "");
    }

    public function findOpenTickets()
    {
        $element = $this->getStatus();
        $query = "SELECT COUNT(*) as total FROM ticket WHERE t_status = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function findPendingTickets()
    {
        $element = $this->getStatus();
        $query = "SELECT COUNT(*) as total FROM ticket WHERE t_status = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function findDataBySqlIds($sqlIds)
    {
        $query = sprintf("SELECT ticket_module.description as module, employee.name, chat.id_chat as chat, ticket.t_status, ticket.registered_at FROM ticket, ticket_module, employee, chat WHERE ticket.id IN (%s) AND ticket.id_module = ticket_module.id AND ticket.id_attendant = employee.id AND ticket.id_chat = chat.id ORDER BY ticket.id DESC", $sqlIds);
        return $this->prepareInstance->prepare($query, "", "all");
    }

    public function findOpenedTicketsByAttendant()
    {
        $elements = [$this->getIdAttendant(), "aberto"];
        $query = "SELECT COUNT(*) as total FROM ticket WHERE id_attendant = ? AND t_status = ?";
        return $this->prepareInstance->prepare($query, $elements, "");
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Ticket();
        }
        return self::$instance;
    }
}
