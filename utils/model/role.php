<?php

	include_once __DIR__.'/../../common/config.php';

	class Role {

		protected $id;
		protected $description;
		protected $type;
		protected $status;

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
		public function getType(){
		    return $this->type;
		}
		public function setType($type){
		    $this->type = $type;
		}
		public function getStatus(){
		    return $this->status;
		}
		public function setStatus($status){
		    $this->status = $status;
		}

		function __construct(){
	    	$this->connection = new ConfigDatabase();
		}

		public function show(){
			$sql = $this->connection->getConnection()->prepare("SELECT id, description FROM `role` WHERE status = ? AND type = ? ORDER BY `description`");
    		$sql->execute(array("ativo", $this->getType()));

   			return $sql->fetchAll();
		}

		public function register(){
			$sql = $this->connection->getConnection()->prepare("INSERT INTO role (id, description, type, status) VALUES (NULL, ?, ?, ?)");
			$sql->bindValue(1, $this->getDescription());
			$sql->bindValue(2, $this->getType());
			$sql->bindValue(3, "ativo");
		        
		    $result = $sql->execute();
		
		    return $result;
		}

		public function active(){
			$sql = $this->connection->getConnection()->prepare("UPDATE role SET status = ? WHERE id = ?");
		    $sql->bindValue(1, "ativo");
			$sql->bindValue(2, $this->getId()); 

		    $result = $sql->execute();
		
		    return $result;
		}

		public function delete(){
			$sql = $this->connection->getConnection()->prepare("UPDATE role SET status = ? WHERE id = ?");
		    $sql->bindValue(1, "inativo");
			$sql->bindValue(2, $this->getId()); 

		    $result = $sql->execute();
		
		    return $result;
		}
	}

?>
