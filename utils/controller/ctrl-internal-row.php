<?php 

include_once __DIR__.'/../../commom/config.php';

$connection = new ConfigDatabase();

$sql = $connection->getConnection()->prepare("SELECT DISTINCT id_attendant, registered_at FROM ticket WHERE t_status = ? ORDER BY id DESC LIMIT 3");
$sql->execute(array("aberto"));

if ($sql->fetch() == null){
	$sql = $connection->getConnection()->prepare("SELECT DISTINCT id_attendant FROM ticket ORDER BY id DESC LIMIT 3");
	$sql->execute(array("aberto"));

	while ($row = $sql->fetch()) {
		echo $row['id_attendant']." ";
	}
}

while ($row = $sql->fetch()) {
	echo $row['id_attendant']." ";
}

?>