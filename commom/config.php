<?php

class ConfigDatabase
{
	private static $instance;

	public function getConnection()
	{
		$host = "127.0.0.1"; 
		$port = "3306"; 
		$user = "root"; 
		$pass = "brain123"; 
		$db = "brain"; 

		try {
			$dbh = new PDO('mysql:host='.$host.';dbname='.$db.';port='.$port, $user, $pass);
			$dbh->query("SET NAMES 'utf8'");
			$dbh->query('SET character_set_connection=utf8');
			$dbh->query('SET character_set_client=utf8');
			$dbh->query('SET character_set_results=utf8');
			return $dbh;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new ConfigDatabase();
		}
    	return self::$instance;
	}
}
