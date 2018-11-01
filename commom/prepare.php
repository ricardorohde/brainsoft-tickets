<?php
	include_once 'config.php';
?>

<?php
	class PrepareQuery{

		private $connectionToDatabase;

		function getConnToDatabase() {
        return $this->connectionToDatabase;
    }

		function __construct(){
			$this->connectionToDatabase = new ConfigDatabase();
		}
		
		function prepare($query, $elements, $typeReturn){
	    $open_connection = $this->connectionToDatabase->getConnection()->prepare($query);
	    
	    if(gettype($elements) == "string")
	      $open_connection->execute(array($elements)); 
	    else 
	      $open_connection->execute($elements);

	    if($typeReturn == "all")
	    	return $open_connection->fetchAll();
	    else
	    	return $open_connection->fetch();
	  }
	}
?>