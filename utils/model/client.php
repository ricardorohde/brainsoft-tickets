<?php

	class Client{
		protected $id;
		protected $name;
		protected $email;
		protected $idCredential;
		protected $idRegistry;
		protected $idRole;

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
		public function getEmail(){
		    return $this->email;
		}
		public function setEmail($email){
		    $this->email = $email;
		}
		public function getIdCredential(){
		    return $this->idCredential;
		}
		public function setIdCredential($idCredential){
		    $this->idCredential = $idCredential;
		}
		public function getIdRegistry(){
		    return $this->idRegistry;
		}
		public function setIdRegistry($idRegistry){
		    $this->idRegistry = $idRegistry;
		}
		public function getIdRole(){
		    return $this->idRole;
		}
		public function setIdRole($idRole){
		    $this->idRole = $idRole;
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

	    public function register(){
	      	$sql = $this->getConn()->prepare("INSERT INTO client (`id`, `name`, `email`, `id_credential`, `id_registry`, `id_role`) VALUES (NULL, ?, ?, ?, ?, ?)");
	      	$sql->bindValue(1, $this->getName());
			$sql->bindValue(2, $this->getEmail());
			$sql->bindValue(3, $this->getIdCredential());
			$sql->bindValue(4, $this->getIdRegistry());
			$sql->bindValue(5, $this->getIdRole());
	      	$result = $sql->execute();
	    
	    	$this->getController()->verifyResult("register", $result);		
	    }

	    public function update(){
	    	$sql = $this->getConn()->prepare("UPDATE client SET name = ?, email = ?, id_registry = ?, id_role = ? WHERE id = ?");
	    	$sql->bindValue(1, $this->getName());
			$sql->bindValue(2, $this->getEmail());
			$sql->bindValue(3, $this->getIdRegistry());
			$sql->bindValue(4, $this->getIdRole());
			$sql->bindValue(5, $this->getId());
      		$result = $sql->execute();
	    
	    	$this->getController()->verifyResult("update", $result);
	    }

	    public function findClients(){
			$sql = $this->getConn()->prepare("SELECT id, name FROM client WHERE id_registry = ? ORDER BY name");
			$sql->bindValue(1, $this->getIdRegistry());
	    	$sql->execute();

	   		return $sql->fetchAll();
		}

		public function findIdRegistry(){
			$sql = $this->getConn()->prepare("SELECT id_registry FROM client WHERE id = ?");
			$sql->bindValue(1, $this->getId());
	    	$sql->execute();

	   		while($row = $sql->fetch()){
  				$id = $row['id_registry'];
			}

			return $id;
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