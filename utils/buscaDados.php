<?php  

include_once("../commom/config.php");
$connection = new ConfigDatabase();

$text1 = $_GET['term'];

$text = utf8_decode($text1);

$sql = $connection->getConnection()->prepare("SELECT * FROM registry WHERE name LIKE '%$text%' ORDER BY name ASC");
$sql->execute();

//formata o resultado para JSON
$json = '[';
$first = true;
while($row = $sql->fetch()){
	if (!$first){ 
		$json .=  ','; 
	} else{ 
		$first = false; 
	}
  	$json .= '{"value":"'.$row['name'].'"}';
}
$json .= ']';
 
echo $json;
?>

