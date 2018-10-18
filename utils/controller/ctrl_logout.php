<?php

include_once("ctrl_employee.php");
 
session_start(); //iniciamos a sessão que foi aberta

$controllerEmployee = new EmployeeController();
$controllerEmployee->isOnChat($_SESSION['login'], "no"); //DEIXANDO O USUÁRIO ONLINE NA FILA
 
session_destroy(); //destruimos a sessão ;)
session_unset(); //limpamos as variaveis globais das sessões
header("Location:/"); /*aqui você pode redirecionar para uma determinada página*/
 
?>