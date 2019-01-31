<?php
include_once __DIR__.'/../../common/config.php';

class City
{
	private $id;
	private $description;
	private $idState;
	private $connection;

	public function getId()
	{
	  return $this->id;
	}
	
	public function setId($id)
	{
	  $this->id = $id;
	}

	public function getIdState()
	{
	  return $this->idState;
	}
	
	public function setIdState($idState)
	{
	  $this->idState = $idState;
	}

	function __construct()
	{
    	$this->connection = new ConfigDatabase();
	}

	public function findCities($state)
	{
		$sql = $this->connection->getConnection()->prepare("SELECT id, description FROM city WHERE id_state = ? ORDER BY description");
    	$sql->execute(array($state));
   		return $sql->fetchAll();
	}
}
