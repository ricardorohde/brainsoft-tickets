<?php
include_once 'config.php';

class PrepareQuery
{
	private static $instance;
	private $connectionToDatabase;

	function __construct()
	{
		$this->connectionToDatabase = ConfigDatabase::getInstance();
	}

	public function getConnToDatabase() 
	{
		return $this->connectionToDatabase;
	}
	
	function prepare($query, $elements, $typeReturn)
	{
    	$openConnection = $this->connectionToDatabase->getConnection()->prepare($query);
    	$result = "";

    	if (gettype($elements) == "string") {
      		$openConnection->execute(array($elements)); 
    	} else { 
    		$openConnection->execute($elements);
    	}

    	if ($typeReturn == "all") {
    		$result = $openConnection->fetchAll();
    		$openConnection = null;
    		return $result;
    	} else {
    		$result = $openConnection->fetch();
    		$openConnection = null;
    		return $result;
    	}
	}

	function prepareBind($query, $elements, $typeReturn)
	{
		$openConnection = $this->connectionToDatabase->getConnection()->prepare($query);
		$result = "";

		if (count($elements) == 3) {
			$openConnection->bindValue(':status', $elements[0], PDO::PARAM_STR);
			$openConnection->bindValue(':group', $elements[1], PDO::PARAM_STR);
			$openConnection->bindValue(':count', $elements[2], PDO::PARAM_INT);
		} else {
			$openConnection->bindValue(':status', $elements[0], PDO::PARAM_STR);
			$openConnection->bindValue(':group', $elements[1], PDO::PARAM_STR);
			$openConnection->bindValue(':active', $elements[2], PDO::PARAM_STR);
			$openConnection->bindValue(':count', $elements[3], PDO::PARAM_INT);
		}

		$openConnection->execute();

		if ($typeReturn == "all") {
			$result = json_encode($openConnection->fetchAll());
			$openConnection = null;
			return $result;
		} else {
			$result = json_encode($openConnection->fetch());
			$openConnection = null;
			return $result;
		}
	}

	public static function getInstance()
	{
		if (!self::$instance) {
      		self::$instance = new PrepareQuery();
		}
    	return self::$instance;
	}
}
