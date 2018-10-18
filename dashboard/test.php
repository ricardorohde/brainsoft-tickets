<?php
	include_once "../utils/api-chat/api-reception-queue.php"; 
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='refresh' content='30;url=test.php'>
	<title></title>

	<!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom icon font-->
    <link rel="stylesheet" href="css/fontastic.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="css/grasp_mobile_progress_circle-1.0.0.min.css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">
</head>
<body style="background-color: transparent;">
	<h3>Recepção | 
  		<?php if (isset($customers_at_reception)){
      		if($customers_at_reception == 0){
      			echo "Nenhum Cliente";
      		} else if($customers_at_reception == 1){
      			echo $customers_at_reception . " Cliente";
      		} else{
      			echo $customers_at_reception . " Clientes";
      		}
      	} else{
      		echo "<span>Erro! Problema ao buscar dados.</span>";
      	}?>
    </h3>  
</body>
</html>