<?php 
    include_once "../../utils/controller/registry/registry.ctrl.php";
    $registryController = RegistryController::getInstance();
    $registryController->verifyPermission();

    $registryController->findAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Listagem de Tickets</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <!-- Custom icon font-->
    <link rel="stylesheet" href="./css/fontastic.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- jQuery Circle-->
    <link rel="stylesheet" href="./css/grasp_mobile_progress_circle-1.0.0.min.css">
    <!-- Custom Scrollbar-->
    <link rel="stylesheet" href="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="./css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="./css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

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
            <div class="row">
              <div class="col-sm-6">
                <h1 class="h3 display">Cartórios</h1>
              </div>
              <div class="col-sm-6 text-right h2">
                  <a class="btn btn-primary" href="cartorio/novo"><i class="fa fa-plus"></i> Novo Cartório</a>
                  <a class="btn btn-default" href="cartorios"><i class="fa fa-refresh"></i> Atualizar</a>
                </div>
            </div>
            <div id="statusLogin" class="alert alert-success" 
              <?php echo (isset($_SESSION['registryOk'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
              <?php echo $_SESSION['registryOk']; unset($_SESSION['registryOk']);?>
            </div>
            <div id="statusLogin" class="alert alert-danger" 
              <?php echo (isset($_SESSION['registryExists'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
              <?php echo $_SESSION['registryExists']; unset($_SESSION['registryExists']);?>
            </div>
          </header>

          <hr>
          <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Cod.</th>
                    <th>Nome</th>      
                    <th>Cidade</th>
                    <th></th>
                 </tr>
            </thead>
            <tbody>
            <?php if (!empty($registryController->getAllRegistries())) : ?>
                <?php foreach ($registryController->findDataOfRegistries() as $registry) : ?>
                    <tr>
                        <td>00<?= $registry['id'] ?></td>
                        <td><?= $registry['name'] ?></td>
                        <td><?= $registry['city'] ?></td>
                        <td class="actions text-right">
                            <a href="registry/view-new-registry.php?id=<?= $registry['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Visualizar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="6">Nenhum cartório encontrado.</td>
              </tr>
            <?php endif; ?>
            </tbody>
            </table>
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
    <script src="./js/jquery-3.2.1.min.js"></script>
    <script src="./../js/jquery.mask.js"></script>
    <script src="./jquery-ui.js"></script>
    <script src="./js/front.js" charset="UTF-8"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="./js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="./vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
  </body>
</html>