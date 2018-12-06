<?php

class ConfigDatabase{

	private static $instance;

	public function getConnection(){
		$host = "127.0.0.1"; // Hostname
		$port = "3306"; // MySQL Port : Default : 3306
		$user = "root"; // Username Here
		$pass = "brain123"; //Password Here
		$db   = "brain"; // Database Name

		try {
			$dbh  = new PDO('mysql:dbname='.$db.';host='.$host.';port='.$port,$user,$pass);

			$dbh->query("SET NAMES 'utf8'");
			$dbh->query('SET character_set_connection=utf8');
			$dbh->query('SET character_set_client=utf8');
			$dbh->query('SET character_set_results=utf8');

			return $dbh;
		} catch (PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
	}

	public static function getInstance() {
		if ( !self::$instance )
      self::$instance = new ConfigDatabase();

    return self::$instance;
	}
}

?>
