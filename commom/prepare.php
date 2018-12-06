<?php
	include_once 'config.php';
?>

<?php
	class PrepareQuery{

		private $connectionToDatabase;

		function __construct(){
			$this->connectionToDatabase = ConfigDatabase::getInstance();
		}

		public function getConnToDatabase() {
   		return $this->connectionToDatabase;
  	}
		
		function prepare($query, $elements, $typeReturn){
	    $open_connection = $this->connectionToDatabase->getConnection()->prepare($query);
	    
	    if(gettype($elements) == "string") {
	      $open_connection->execute(array($elements)); 
	    } else { 
	      $open_connection->execute($elements);
	    }

	    if($typeReturn == "all")
	    	return $open_connection->fetchAll();
	    else
	    	return $open_connection->fetch();
  	}

  	function prepareBind($query, $elements, $typeReturn){
  		$open_connection = $this->connectionToDatabase->getConnection()->prepare($query);

  		if (count($elements) == 3) {
  			$open_connection->bindValue(':status', $elements[0], PDO::PARAM_STR);
      	$open_connection->bindValue(':group', $elements[1], PDO::PARAM_STR);
      	$open_connection->bindValue(':count', $elements[2], PDO::PARAM_INT);
  		} else {
  			$open_connection->bindValue(':status', $elements[0], PDO::PARAM_STR);
     		$open_connection->bindValue(':group', $elements[1], PDO::PARAM_STR);
      	$open_connection->bindValue(':active', $elements[2], PDO::PARAM_STR);
     		$open_connection->bindValue(':count', $elements[3], PDO::PARAM_INT);
  		}

      $open_connection->execute();

      if($typeReturn == "all")
	    	return $open_connection->fetchAll();
	    else
	    	return $open_connection->fetch();
  	}
	}
?>