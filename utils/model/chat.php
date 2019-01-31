<?php
include __DIR__.'/../../common/config.php';

class Chat
{
	protected $id;
	protected $id_chat;
	protected $opening_time;
	protected $final_time;
	protected $duration_in_minutes;
	protected $id_client;

	protected $connection;
	protected $myController;

    function __construct($myControllerInstance)
    {
    	$this->connection = new ConfigDatabase();
    	$this->myController = $myControllerInstance;
	}

	function register($id_chat, $opening_time, $final_time, $duration_in_minutes)
	{
        //REGISTRANDO O ACESSO DO USUÁRIO
        $sql = $this->connection->getConnection()->prepare("INSERT INTO `chat` (`id`, `id_chat`, `opening_time`, `final_time`, `duration_in_minutes`) VALUES (NULL, ?, ?, ?, ?)");
        $sql->execute(array($id_chat, $opening_time, $final_time, $duration_in_minutes));

        //RECEBENDO O ID DO ULTIMO REGISTRO FEITO EM 'CREDENTIAL'
    	$sql = $this->connection->getConnection()->prepare("SELECT MAX(ID) as last FROM chat");
    	$sql->execute();
    	return $sql->fetchAll();
    }

    function searchId($id_chat, $id_attendant)
    {
    	$id = 0;

    	$sql = $this->connection->getConnection()->prepare("SELECT chat.id FROM chat, ticket WHERE chat.id_chat = ? AND ticket.id_attendant = ? AND chat.id = ticket.id_chat");
    	$sql->execute(array($id_chat, $id_attendant));

   		while ($row = $sql->fetch()) {
			$id = $row['id'];
		}
		return $id;
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

    function update($id_chat, $final_time, $duration_in_minutes)
    {
        $sql = $this->connection->getConnection()->prepare("UPDATE chat SET final_time = ?, duration_in_minutes = ? WHERE id_chat = ?");
        
        $result = $sql->execute(array($final_time, $duration_in_minutes, $id_chat));
    }
}
