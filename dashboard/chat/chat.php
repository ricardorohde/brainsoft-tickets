<?php
session_start();
if (!isset($_SESSION['ticket_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Buscar | Pesquisar Tickets</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="../vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom icon font-->
    <link rel="stylesheet" href="../css/fontastic.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="../css/grasp_mobile_progress_circle-1.0.0.min.css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="../css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../css/custom.css">

    <link type="text/css" href="../css/jquery-ui.css" rel="stylesheet"/>
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">
</head>
<body>
<?php include ("../navs/navbar.php");?>
<div class="root-page forms-page">
    <?php include ("../navs/header.php");?>

    <section class="forms">
        <div class="container-fluid">
            <header>
                <h1 class="h3 display">Fila de Atendimento</h1>
            </header>

            <div class="row mt-3">
                <div class="col-lg-5">
                    <div id="chats-in-reception" class="card"></div>
                </div>

                <div class="col-lg-7">
                    <iframe id="talk-in-chat" width='650px' height='600px' frameborder='0' SCROLLING="NO"></iframe>
                </div>
            </div>

        </div>
    </section>
    <footer class="main-footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <p>Your company &copy; 2017-2019</p>
                </div>
                <div class="col-sm-6 text-right">
                    <p>Design by <a href="https://bootstrapious.com" class="external">Bootstrapious</a></p>
                    <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                </div>
            </div>
        </div>
    </footer>
</div>
<!-- Javascript files-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
<script src="../js/jquery-3.2.1.min.js"></script>
<script src="../jquery-ui.js"></script>
<script src="../../js/jquery.mask.js"></script>
<script src="../js/chat.js"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/jquery.cookie/jquery.cookie.js"> </script>
<script src="../js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
<script src="../vendor/jquery-validation/jquery.validate.min.js"></script>
<script src="../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
<!---->
</body>
</html>