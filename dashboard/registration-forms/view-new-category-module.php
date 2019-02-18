<?php 
  session_start();
  if (!isset($_SESSION['Module'.'_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bootstrap Dashboard by Bootstrapious.com</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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

    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  
  <body>
    <?php include ("../navs/navbar.php");?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php");?>
      <section class="forms">
        <div class="container-fluid">
          <header> 
            <h1 class="h3 display">Cadastro de Categorias / Módulos</h1>
          </header>
          <div class="row">    
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h2 class="h5 display">Nova Categoria</h2>
                </div>
                <div class="card-body">
                  <form class="form-horizontal" action="../../utils/controller/category/category-data.ctrl.php" method="POST">
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Descrição</label>
                      <div class="col-sm-10">
                        <input type="text" name="desc_category" class="form-control"><span class="help-block-none">Informe o nome da categoria.</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Grupo</label>
                      <div class="col-sm-10 select">
                        <select name="group" class="form-control" id="group" required>
                          <option>Selecione um grupo...</option>
                          <option value='nivel1'>Nível 1</option>
                          <option value='nivel2'>Nível 2</option>
                        </select>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <div class="col-sm-4 offset-sm-2">
                        <button type="reset" class="btn btn-secondary">Limpar</button>
                        <button type="submit" id="newCategory" name="newCategory" class="btn btn-primary btnAction">Cadastrar!</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>

          <div class="row">    
            <div class="col-lg-12">
              <div class="card" style="margin-top: 50px;">
                <div class="card-header d-flex align-items-center">
                  <h2 class="h5 display">Novo Módulo</h2>
                </div>
                <div class="card-body">
                  <form class="form-horizontal" action="../../utils/controller/module/module-data.ctrl.php" method="POST">
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Categoria</label>
                      <div class="col-sm-10">
                        <input type="text" name="category" id="category" class="form-control" required><span class="help-block-none">Informe a categoria do módulo.</span>
                        <input type="hidden" name="id_category" id="id_category" class="form-control" required>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Descrição</label>
                      <div class="col-sm-10">
                        <input type="text" name="module_desc" class="form-control"><span class="help-block-none">Informe o nome do módulo.</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Tempo Limite</label>
                      <div class="col-sm-10">
                        <input type="text" name="module_limit_time" class="form-control justNumbers"><span class="help-block-none">Informe o tempo limite para a resolução deste módulo (em minutos).</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <div class="col-sm-4 offset-sm-2">
                        <button type="reset" class="btn btn-secondary">Limpar</button>
                        <button type="submit" id="newModule" name="newModule" class="btn btn-primary btnAction">Cadastrar!</button>
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
    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="../../js/jquery.mask.js"></script>
    <script src="../js/front.js"></script>
    <script src="../jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
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