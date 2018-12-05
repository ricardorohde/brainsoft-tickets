<?php 
  session_start();
  if (!isset($_SESSION['Billet'.'_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }
?>

<?php date_default_timezone_set('America/Sao_Paulo'); $actual_date = date('Y-m-d');?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bootstrap Dashboard by Bootstrapious.com</title>
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
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="css/grasp_mobile_progress_circle-1.0.0.min.css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="css/custom.css">

    <link type="text/css" href="css/jquery-ui.css" rel="stylesheet"/>

    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">

    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>

  <?php 
    $targets = array(
      "Billet" => "administrativo",
      "Ticket" => "tickets",
      "User" => "usuarios",
      "Registry" => "cartorios",
      "Register" => "cadastros",
      "Queue" => "fila-interna",
      "Authorization" => "autorizacoes",
      "Report" => "relatorios",
      "Logout" => "logout"
    );
  ?>

  <body>
    <?php include ("navs/navbar.php");?>
    <div class="root-page forms-page">
      <?php include ("navs/header.php");?>
      <section class="forms">
        <div class="container-fluid">
          <header> 
            <h1 class="h3 display">Boletos</h1>
          </header>

          <div id="statusLogin" class="alert alert-success" 
            <?php echo (isset($_SESSION['resultSaveFiles'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
            <?php echo $_SESSION['resultSaveFiles']; unset($_SESSION['resultSaveFiles']);?>
          </div>
          <div id="statusLogin" class="alert alert-danger" 
            <?php echo (isset($_SESSION['thereIsProblemInSaveFiles'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
            <?php echo $_SESSION['thereIsProblemInSaveFiles']; unset($_SESSION['thereIsProblemInSaveFiles']);?>
          </div>
          
          <div class="row">    
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h2 class="h5 display">Inserção de Boletos</h2>
                </div>
                <div class="card-body">
                  <div class="modal fade" id="expiration_date" role="dialog" data-backdrop="static">
                     <div class="modal-dialog modal-date">
                     
                       <!-- Modal content no 1-->
                       <div class="modal-content">
                         <div class="modal-header">
                           <h4 class="modal-title" id="myModalLabel titleModal"></h4>
                           <button type="button" class="close" data-target="#expiration_date" data-toggle="modal">&times;</button>
                         </div>
                         <div class="modal-body padtrbl">
                           <form class="form-horizontal" method="POST" action="../utils/controller/ctrl-file.php">
                              <div class="form-group">
                                 <label class="col-sm-4 form-control-label">Boleto</label>
                                 <div class="col-sm-12 select ui-widget">
                                    <input type="text" name="desc_of_file" id="desc_of_file" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-6 form-control-label">Data de Vencimento</label>
                                 <div class="col-sm-12 select ui-widget">
                                    <input type="date" name="date_when_expire" id="date_when_expire" class="form-control">
                                    <input type="hidden" name="id_of_file" id="id_of_file">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-12">  
                                    <input type="submit" class="btn btn-primary col-sm-12" id="submit_date" value="Salvar">
                                 </div>
                              </div>                                    
                           </form>  
                         </div>
                       </div>
                     </div>
                  </div>

                  <div class="modal fade" id="paid_date" role="dialog" data-backdrop="static">
                     <div class="modal-dialog modal-date">
                     
                       <!-- Modal content no 1-->
                       <div class="modal-content">
                         <div class="modal-header">
                           <h4 class="modal-title" id="myModalLabel">Pagar Boleto</h4>
                           <button type="button" class="close" data-target="#paid_date" data-toggle="modal">&times;</button>
                         </div>
                         <div class="modal-body padtrbl">
                           <form class="form-horizontal" method="POST" action="../utils/controller/ctrl-file.php">
                              <div class="form-group">
                                 <label class="col-sm-4 form-control-label">Boleto</label>
                                 <div class="col-sm-12 select ui-widget">
                                    <input type="text" name="desc_of_file" id="desc_of_file" class="form-control" disabled>
                                 </div>
                              </div>
                              <div class="form-group">
                                 <label class="col-sm-6 form-control-label">Data de Pagamento</label>
                                 <div class="col-sm-12 select ui-widget">
                                    <input type="date" name="paid_date" id="paid_date" <?php echo "max='$actual_date'" ?> class="form-control">
                                    <input type="hidden" name="id_of_file" id="id_of_file">
                                 </div>
                              </div>
                              <div class="form-group">
                                 <div class="col-sm-12">  
                                    <input type="submit" class="btn btn-danger col-sm-12" value="Pagar">
                                 </div>
                              </div>                                    
                           </form>  
                         </div>
                       </div>
                     </div>
                  </div>

                  <form class="form-horizontal" action="../utils/controller/ctrl-file.php" method="POST" enctype="multipart/form-data">
                     <div class="form-group row">
                        <label class="col-sm-2 form-control-label">Cartório</label>
                        <div class="col-sm-10 select ui-widget">
                           <input type="text" name="registryListToAdm" id="registryListToAdm" class="form-control" required><span class="help-block-none">Informe o cartório.</span>
                           <input type="hidden" name="id_registry" id="id_registry">
                        </div>
                     </div>
                     <div class="line"></div>
                     <div class="form-group row">
                        <label class="col-sm-2 form-control-label">Boletos Cadastrados</label>
                        <div class="col-sm-10 select ui-widget table-files">
                           <table class="table table-clicked">
                              <thead>
                              <tr>      
                                 <th>Descrição</th>
                                 <th>Vence em</th>
                                 <th>Pago em</th>
                                 <th></th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Inserir Boletos</label>
                      <div class="col-sm-10 select ui-widget">
                        <input type="file" name="fileUpload[]" multiple name="file" id="file" class="form-control" required><span class="help-block-none">Selecione os boletos referentes ao cartório.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <div class="col-sm-4 offset-sm-2">
                        <button type="reset" class="btn btn-secondary">Limpar</button>
                        <button type="submit" class="btn btn-primary">Cadastrar!</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
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
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="jquery-ui.js"></script>
    <script src="js/front.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
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
