<?php
class Chat
{
    private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $idChat;
	private $openingTime;
	private $finalTime;
	private $durationInMinutes;
	private $idClient;

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

    public function getOpeningTime() 
    {
        return $this->openingTime;
    }
    
    public function setOpeningTime($openingTime) 
    {
        $this->openingTime = $openingTime;
    }

    public function getFinalTime() 
    {
        return $this->finalTime;
    }
    
    public function setFinalTime($finalTime) 
    {
        $this->finalTime = $finalTime;
    }

    public function getDurationInMinutes() 
    {
        return $this->durationInMinutes;
    }
    
    public function setDurationInMinutes($durationInMinutes) 
    {
        $this->durationInMinutes = $durationInMinutes;
    }

    public function getIdClient() 
    {
        return $this->idClient;
    }
    
    public function setIdClient($idClient) 
    {
        $this->idClient = $idClient;
    }

    function __construct($controller, $prepareInstance)
    {
        $this->setMyController($controller);
        $this->prepareInstance = $prepareInstance;
    }

    public function findById() //NEW
    {
        $element = $this->getId();
        $query = "SELECT * FROM chat WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

	function register()
	{
        //REGISTRANDO O ACESSO DO USUÁRIO
        $elements = [$this->getId(), $this->getOpeningTime(), $this->getFinalTime(), $this->getDurationInMinutes()];
        $query = "INSERT INTO `chat` (`id`, `id_chat`, `opening_time`, `final_time`, `duration_in_minutes`) VALUES (NULL, ?, ?, ?, ?)";
        $this->prepareInstance->prepare($query, $elements, "");

        //RECEBENDO O ID DO ULTIMO REGISTRO FEITO EM 'CREDENTIAL'
        $query = "SELECT MAX(ID) as last FROM chat";
        return $this->prepareInstance->prepare($query, "", "all");
    }

    function findByIdAndIdAttendant($idAttendant)
    {
        $elements = [$this->getId(), $idAttendant];
        $query = "SELECT chat.id FROM chat, ticket WHERE chat.id_chat = ? AND ticket.id_attendant = ? AND chat.id = ticket.id_chat";
        $result = $this->prepareInstance->prepare($query, $elements, "");
        return $result['id'];
    }

    function searchChatId($id)
    {
    	$sql = $this->connection->getConnection()->prepare("SELECT id_chat FROM chat WHERE id = ?");
    	$sql->execute(array($id));

   		while ($row = $sql->fetch()) {
			$idFinded = $row['id_chat'];

			if ($idFinded == NULL) {
				return 0;
			} else {
				return $idFinded;
			}
		}		
    }

    function update()
    {
        $elements = [$this->getIdChat(), $this->getFinalTime(), $this->getDurationInMinutes()];
        $query = "UPDATE chat SET final_time = ?, duration_in_minutes = ? WHERE id_chat = ?";
        return $this->prepareInstance->prepare($query, $elements, "");
    }
}
