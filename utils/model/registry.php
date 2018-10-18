<?php

	class Registry {
		protected $id;
		protected $name;
		protected $idCity;

		protected $connection;
		protected $myController;

		public function getId(){
		    return $this->id;
		}
		public function setId($id){
		    $this->id = $id;
		}
		public function getName(){
		    return $this->name;
		}
		public function setName($name){
		    $this->name = $name;
		}
		public function getIdCity(){
		    return $this->idCity;
		}
		public function setIdCity($idCity){
		    $this->idCity = $idCity;
		}
		public function getConn(){
		    return $this->connection;
		}
		public function setConn($conn){
		    $this->connection = $conn;
		}
		public function getController(){
		    return $this->myController;
		}
		public function setController($controller){
		    $this->myController = $controller;
		}

		function __construct($myController) {
			$this->setConn($this->getConnection());
			$this->setController($myController);
		}

		public function findRegistries(){
			$sql = $this->getConn()->prepare("SELECT id, name FROM registry WHERE id_city = ? ORDER BY name");
	    	$sql->bindValue(1, $this->getIdCity());
	    	$sql->execute();

	   		return $sql->fetchAll();
		}

		public function register(){
			$sql = $this->connection->prepare("INSERT INTO registry (id, name, id_city) VALUES (NULL, ?, ?)");
		    $sql->bindValue(1, $this->getName());
		    $sql->bindValue(2, $this->getIdCity());
		    $result = $sql->execute();
		
		    return $result;
		}

		public function update(){
			$sql = $this->connection->prepare("UPDATE registry SET name = ?, id_city = ? WHERE id = ?");
		    $sql->bindValue(1, $this->getName());
		    $sql->bindValue(2, $this->getIdCity());
		    $sql->bindValue(3, $this->getId());
	        $result = $sql->execute();

	        return $result;
		}

		public function findIdByName(){
			$name = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $this->getName()));

			$sql = $this->getConn()->prepare("SELECT id FROM registry WHERE name LIKE ?");
			$sql->bindValue(1, $name);
		    $sql->execute();

		    return $sql->fetchAll();
		}

		public function findFiles(){
			$sql = $this->getConn()->prepare("SELECT * FROM administrative_file WHERE id_registry = ?");
	    	$sql->bindValue(1, $this->getId());
	    	$sql->execute();

	   		return $sql->fetchAll();
		}

		public function verifyIfExists(){
			$name = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $this->getName()));

			$sql = $this->getConn()->prepare("SELECT COUNT(*) as total FROM registry WHERE name LIKE ?");
			$sql->bindValue(1, "%".$name."%");
	    	$sql->execute();

	   		while($row = $sql->fetch()){
  				$total = $row['total'];
			}
			
			return $total;
		}

		public function getConnection(){
			$host = "localhost"; // Hostname
			$port = "3306"; // MySQL Port : Default : 3306
			$user = "root"; // Username Here
			$pass = "brain123"; //Password Here
			$db   = "brain"; // Database Name

			$dbh  = new PDO('mysql:dbname='.$db.';host='.$host.';port='.$port,$user,$pass);

			return $dbh;
		}
	}

?>