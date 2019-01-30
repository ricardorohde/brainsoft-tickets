<?php

include_once __DIR__.'/../../common/config.php';

class CategoryModule {

	protected $id;
	protected $description;
	protected $tGroup;

	public function getId(){
	    return $this->id;
	}
	public function setId($id){
	    $this->id = $id;
	}
	public function getDescription(){
	    return $this->description;
	}
	public function setDescription($description){
	    $this->description = $description;
	}
	public function getTGroup(){
	    return $this->tGroup;
	}
	public function setTGroup($tGroup){
	    $this->tGroup = $tGroup;
	}

	function __construct() {
    	$this->connection = new ConfigDatabase();
	}

	public function register(){
		$sql = $this->connection->getConnection()->prepare("INSERT INTO category_module (id, description, t_group) VALUES (NULL, ?, ?)");
	        
	    $result = $sql->execute(array($this->description, $this->tGroup));
	
	    return $result;
	}

	public function findIdByName($name){
		$sql = $this->connection->getConnection()->prepare("SELECT id FROM category_module WHERE description LIKE ?");
    	$sql->execute(array($name));

   		while($row = $sql->fetch()){
  			$id = $row['id'];
		}

		if (isset($id)) {
			return $id;
		} else {
			return $id = "";
		}
	}
}
