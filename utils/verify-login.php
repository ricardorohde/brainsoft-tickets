<?php
  session_start();
  if(!isset($_SESSION['login'])){
    if(isset($_SESSION['errorLogin'])){unset($_SESSION['errorLogin']);};
    $_SESSION['withoutLogin'] = "<strong>Informação!</strong> Informe seus dados para acessar o sistema.";
    header("Location:/utils/do-login.php");
  }else{}
?>