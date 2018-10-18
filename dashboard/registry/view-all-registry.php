<?php 
  session_start();
  if (!isset($_SESSION['registry_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }
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
    <link rel="stylesheet" href="./vendor/font-awesome/css/font-awesome.min.css">
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
      <?php 
        $sql_registry = $connection->getConnection()->prepare("SELECT * FROM registry ORDER BY id DESC");
        $sql_registry->execute(); $registries = $sql_registry->fetchAll();
      ?>
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
            <?php if (!empty($registries)) : ?>
              <?php foreach ($registries as $registry) : ?>

                <?php 
                  $sql_city = $connection->getConnection()->prepare("SELECT description FROM city WHERE id = ?"); $sql_city->execute(array($registry['id_city'])); 
                    $city = $sql_city->fetch();
                ?>

                <tr>
                  <td>00<?php echo $registry['id']; ?></td>
                  <td><?php echo $registry['name']; ?></td>
                  <td><?php echo $city['description']; ?></td>
                  <td class="actions text-right">
                    <a href="registry/view-new-registry.php?id=<?php echo $registry[0]; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Visualizar</a>
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