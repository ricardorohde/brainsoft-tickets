<?php 

include_once __DIR__.'/../../common/config.php';

session_start();

$connection = new ConfigDatabase();
$chat = $_POST["id_chat"];
$logged = $_SESSION['login'];

$sql = $connection->getConnection()->prepare("INSERT INTO `log` (`id`, `area`, `content`, `time`, `who_did`) VALUES (NULL, ?, ?, NULL, ?)");

$result = $sql->execute(array("buscaChat", $chat, $logged));


?>