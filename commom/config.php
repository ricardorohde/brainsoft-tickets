<?php

class ConfigDatabase{

	public function getConnection(){
		$host = "localhost"; // Hostname
		$port = "3306"; // MySQL Port : Default : 3306
		$user = "root"; // Username Here
		$pass = "brain123"; //Password Here
		$db   = "brain"; // Database Name

		$dbh  = new PDO('mysql:dbname='.$db.';host='.$host.';port='.$port,$user,$pass);

		$dbh->query("SET NAMES 'utf8'");
		$dbh->query('SET character_set_connection=utf8');
		$dbh->query('SET character_set_client=utf8');
		$dbh->query('SET character_set_results=utf8');

		return $dbh;
	}

	protected static function getDB() {
		return self::getConnection();
	}
}

?>
