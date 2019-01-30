<?php  

include_once("../common/config.php");
$connection = new ConfigDatabase();

$text1 = $_GET['term'];

$text = utf8_decode($text1);

$sql = $connection->getConnection()->prepare("SELECT description FROM category_module WHERE description LIKE '%$text%' ORDER BY description ASC");
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
  	$json .= '{"value":"'.$row['description'].'"}';
}
$json .= ']';
 
echo $json;
?>

