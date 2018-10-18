<?php
  if(isset($_POST['submit'])){
    session_start();
    $id = $_SESSION['login'];
    include("../commom/config.php");
    
    if(isset($_POST['newPass']) && isset($_POST['confirmPass'])){
      $new_pass = $_POST['newPass'];
      $confirm_pass = $_POST['confirmPass'];

      if ($new_pass == $confirm_pass) {

        function rand_string($length) {
          $str = "";
          $chars = "ftosniarbabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
          $size = strlen($chars);
          for($i = 0;$i < $length;$i++) {
            $str .= $chars[rand(0,$size-1)];
          }
          return $str; /* http://subinsb.com/php-generate-random-string */
        }

        //GERANDO A SENHA DO USUÁRIO COM O SALT 
        $b_salt = rand_string(20); /* http://subinsb.com/php-generate-random-string */
        $site_salt="ftosniarbsistemas"; /*Common Salt used for password storing on site.*/
        $salted_hash = hash('sha256', $new_pass.$site_salt.$b_salt);

        //REGISTRANDO O ACESSO DO USUÁRIO
        $sql = $dbh->prepare("UPDATE `credential` SET `password` = ?, `b_salt` = ? WHERE `id` = ?");
        $sql->execute(array($salted_hash, $b_salt, $id));
        
        //CRIANDO UMA SESSAO DE SUCESSO E REDIRECIONANDO
        $_SESSION['passChanged'] = "<strong>Sucesso!</strong> Senha alterada com êxito.";

        header("Location:../dashboard");
      }else {
        header("Location:../dashboard");
      }
    }
  }
?>