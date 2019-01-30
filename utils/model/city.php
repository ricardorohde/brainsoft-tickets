<?php

include_once __DIR__.'/../../common/config.php';

class City {

	protected $id;
	protected $description;
	protected $id_state;
	protected $connection;

	function __construct() {
    	$this->connection = new ConfigDatabase();
	}

	public function findCities($state){
		$sql = $this->connection->getConnection()->prepare("SELECT id, description FROM city WHERE id_state = ? ORDER BY description");
    	$sql->execute(array($state));

   		return $sql->fetchAll();
	}
}

?>