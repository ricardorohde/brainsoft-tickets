<?php
include_once __DIR__.'/../../common/config.php';
include_once('../controller/ctrl_ticket.php');

class Ticket
{
	protected $id;
	protected $idChat;
	protected $idRegistry;
	protected $idClient;
	protected $priority;
	protected $status;
	protected $source;
	protected $type;
	protected $tGroup;
	protected $idModule;
	protected $idAttendant;
	protected $resolution;
	protected $historic;
	protected $isRepeated;
	protected $finalizedAt;
	protected $idWhoOpened;
	protected $idWhoClosed;

	protected $connection;
    protected $myController;

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

    public function getResolution(){
        return $this->resolution;
    }

    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
    }

    public function getHistoric(){
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

    public function getConn()
    {
        return $this->connection;
    }

    public function setConn($connection)
    {
        $this->connection = $connection;
    }

    public function getController()
    {
        return $this->mycontroller;
    }

    public function setController($controller)
    {
        $this->controller = $controller;
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

	function __construct()
    {
    	$this->setConn(new ConfigDatabase());
    	$this->setController(new TicketController());
   	}

   	function register()
    {
    	$sql = $this->getConn()->getConnection()->prepare("INSERT INTO ticket (`id`, `id_registry`, `id_client`, `priority`, `t_status`, `source`, `type`, `t_group`, `id_module`, `id_attendant`, `resolution`, 
            `is_repeated`, `id_who_opened`, `id_who_closed`, `id_chat`) 
            VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?);");

    	$sql->bindValue(1, $this->getIdRegistry());
		$sql->bindValue(2, $this->getIdClient());
		$sql->bindValue(3, $this->getPriority());
		$sql->bindValue(4, $this->getStatus());
		$sql->bindValue(5, $this->getSource());
		$sql->bindValue(6, $this->getType());
		$sql->bindValue(7, $this->getGroup());
		$sql->bindValue(8, $this->getIdModule());
		$sql->bindValue(9, $this->getIdAttendant());
		$sql->bindValue(10, $this->getResolution());
        $sql->bindValue(11, $this->getIsRepeated());
		$sql->bindValue(12, $this->getIdWhoOpened());
		$sql->bindValue(13, $this->getIdChat());

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
    	return $sql->execute();
   	}

   	function update()
    {
   		$sql = $this->getConn()->getConnection()->prepare("UPDATE ticket SET id_registry = ?, id_client = ?, priority = ?, t_status = ?, source = ?, type = ?, t_group = ?, id_module = ?, id_attendant = ?, resolution = ?, is_repeated = ? WHERE id_chat = ?");

   		$sql->bindValue(1, $this->getIdRegistry());
		$sql->bindValue(2, $this->getIdClient());
		$sql->bindValue(3, $this->getPriority());
		$sql->bindValue(4, $this->getStatus());
		$sql->bindValue(5, $this->getSource());
		$sql->bindValue(6, $this->getType());
		$sql->bindValue(7, $this->getGroup());
		$sql->bindValue(8, $this->getIdModule());
		$sql->bindValue(9, $this->getIdAttendant());
		$sql->bindValue(10, $this->getResolution());
		$sql->bindValue(11, $this->getIsRepeated());
		$sql->bindValue(12, $this->getIdChat());
        return $sql->execute();
   	}

   	function finish()
    {
   		$sql = $this->connection->getConnection()->prepare("UPDATE ticket SET id_registry = ?, id_client = ?, priority = ?, t_status = ?, source = ?, type = ?, t_group = ?, id_module = ?, id_attendant = ?, resolution = ?, is_repeated = ?, id_who_closed = ?, finalized_at = ? WHERE id_chat = ?");

   		$sql->bindValue(1, $this->getIdRegistry());
		$sql->bindValue(2, $this->getIdClient());
		$sql->bindValue(3, $this->getPriority());
		$sql->bindValue(4, $this->getStatus());
		$sql->bindValue(5, $this->getSource());
		$sql->bindValue(6, $this->getType());
		$sql->bindValue(7, $this->getGroup());
		$sql->bindValue(8, $this->getIdModule());
		$sql->bindValue(9, $this->getIdAttendant());
		$sql->bindValue(10, $this->getResolution());
		$sql->bindValue(11, $this->getIsRepeated());
		$sql->bindValue(12, $this->getIdWhoClosed());
		$sql->bindValue(13, $this->getFinalizedAt());
		$sql->bindValue(14, $this->getIdChat());
        return $sql->execute();
   	}
}
