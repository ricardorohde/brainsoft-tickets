<?php 
  session_start();
  if (!isset($_SESSION['Authorization'.'_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
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

  </head>
  <body>
    <?php include ("../navs/navbar.php");?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php");?>

      <?php
        $sql_type_user = $connection->getConnection()->prepare("SELECT role.description FROM role, client, employee WHERE (client.id_credential = ? AND client.id_role = role.id) OR (employee.id_credential = ? AND employee.id_role = role.id) LIMIT 1");
        $sql_type_user->execute(array($id, $id)); $type_user = $sql_type_user->fetch();

        if ($type_user['description'] == "adm" || $type_user['description'] == "supportBrain"){
          $sql_list_employee = $connection->getConnection()->prepare("SELECT id_credential, name FROM employee");
          $sql_list_employee->execute(array($id)); $list_user = $sql_list_employee->fetchAll();
        }

        if ($type_user['description'] == "Oficial"){
          $sql_list_user_registry = $connection->getConnection()->prepare("SELECT id_credential, name FROM client WHERE id_registry = (SELECT id_registry FROM client WHERE id = ?)");
          $sql_list_user_registry->execute(array($id)); $list_user = $sql_list_user_registry->fetchAll();
        }
      ?>

      <section class="forms">
        <div class="container-fluid">
          <header>
            <div class="row">
              <div class="col-sm-6 title">
                <h1 class="h3 display">Controle de Acesso</h1>
              </div>
            </div>
          </header>

          <hr>

          <div id="conteudo">
            <div class="form-group row">
              <label class="col-sm-2 form-control-label">Usuário</label>
              <div class="col-sm-10 select">
                <select name="listUser" class="form-control" id="listUser">
                  <option value="">Selecione um usuário...</option>
                  <?php if (@$list_user != NULL): ?>
                    <?php foreach ($list_user as $user) : ?>
                      <option value=<?php echo '"'.$user['id_credential'].'"';?>><?php echo $user['name'];?></option>
                    <?php endforeach ?>
                  <?php endif; ?>
                </select>
              </div>
            </div>

          	<div style="margin-left: 45%;">
    					<div class="row" id="internal-row" style="margin-top: 15px;">
    						<div class="auth">
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
              <p>Brainsoft &copy; 2017-2019</p>
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
    <script src="./js/front.js"></script>
    <script src="./jquery-ui.js"></script>
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