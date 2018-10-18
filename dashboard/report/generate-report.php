<?php 
  session_start();
  if (!isset($_SESSION['report_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }

  date_default_timezone_set('America/Sao_Paulo');
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Relatórios</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom icon font-->
    <link rel="stylesheet" href="css/fontastic.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">

    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <?php 
    $target_adm = "administrativo";
    $target_ticket = "tickets"; 
    $target_user = "usuarios";
    $target_registry = "cartorios";
    $target_registration_forms = "cadastros";
    $target_internal_queue = "fila-interna";
    $target_authorization = "autorizacoes";
    $target_report = "relatorios";

    $target_logout = "logout";
  ?>
  <body>
    <?php include ("../navs/navbar.php");?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php");?>
      <section class="forms">
        <div class="container-fluid">
          <header>
            <div class="row">
              <div class="col-sm-6">
                <h1 class="h3 display title-report">Relatórios</h1>
              </div>
            </div>
          </header>
           
          <div class="row">
            <div class="col-lg-12">
              <div class="col-lg-4" style="display: flex;">
                <div id="div-initial-date">
                  <input type="date" name="initial_date" id="initial_date" class="form-control" min="2018-01-01">
                  <span class="span_date">Data inicial</span>
                </div>
                <div id="div-final-date">
                  <input type="date" name="final_date" id="final_date" class="form-control" max="<?php echo date("Y-m-d");?>">
                  <span class="span_date">Data final</span>
                </div>
              </div>
              <button id="btn-generate-report" class="btn btn-primary generate-report" value="<?php echo $id?>" disabled>Gerar Relatório</button>
            </div>
          </div>
          
          <div class="row rowForm">    
            <div class="col-lg-8 report">
            </div>
          </div>
      </section>
      <footer class="main-footer">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <p>Brainsoft Sistemas - IMOB</p>
            </div>
            <div class="col-sm-6 text-right">
              <p class="generate-report">Design by <a href="https://bootstrapious.com" class="external">Bootstrapious</a></p>
              <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
            </div>
          </div>
        </div>
      </footer>
    </div>
    <!-- Javascript files-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="./js/front.js"></script>
    <script src="./jquery-ui.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
  </body>
</html>