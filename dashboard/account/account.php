<?php 
  include_once __DIR__.'/../../utils/controller/account/account.ctrl.php';
  $accountController = AccountController::getInstance();
  $accountController->verifyPermission();

  $accountController->setPostReceived($_POST);
  $accountController->verifyPost();
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Minha Conta</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">

    <link rel="stylesheet" href="css/jquery-ui.css">
  </head>

  <body>
    <?php include ("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php"); ?>

      <section class="forms">
        <div class="container-fluid">
          <?php if ($accountController->getResult() != "") : ?>
            <?php @$type = $accountController->getResult()[0]; $message = $accountController->getResult()[1];?>
              <div class="alert alert-<?= $type ?> mt-3" role="alert">
                <?= $message ?>
              </div>
          <?php endif; ?>
          <div class="row">
            <div class="col-lg-12 mt-3">
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h2 class="h5 display">Redefinir Senha</h2>
                </div>
                <div class="card-body">
                  <form method="POST">
                    <div class="col-lg-6 form-group">
                      <label for="actual-password">Senha Atual</label>
                      <input type="password" name="actual-password" id="actual-password" class="form-control">
                      <small id="actual-password-help" class="form-text text-muted">Informe a sua senha atual.</small>
                    </div>
                    <div class="row" style="margin-left: 1px;">
                      <div class="col-lg-5">
                        <label for="new-password">Nova Senha</label>
                        <input type="password" name="new-password" id="new-password" class="form-control">
                        <small id="new-password-help" class="form-text text-muted">Informe a sua nova senha.</small>
                      </div>
                      <div class="col-lg-5">
                        <label for="confirmation-new-password">Confirmação da Nova Senha</label>
                        <input type="password" name="confirmation-new-password" id="confirmation-new-password" class="form-control">
                        <small id="confirmation-new-password-help" class="form-text text-muted">Confirme sua nova senha.</small>
                      </div>
                      <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary" style="margin-top: 32px; width: 90%;">Redefinir!</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>

              <?php if($accountController->findRole()['role'] == "adm") : ?>
                <div class="card mt-5">
                  <div class="card-header d-flex align-items-center">
                    <h2 class="h5 display">Redefinir senha de usuário</h2>
                  </div>
                  <div class="card-body">
                    <form method="POST">
                      <div class="col-lg-6 form-group">
                        <label for="actual-password">Cartório</label>
                        <input type="text" name="registry" id="registry" class="form-control" required>
                        <small id="registry-help" class="form-text text-muted">Informe o cartório do cliente.</small>
                      </div>
                      <div class="col-lg-6 form-group">
                        <label for="actual-password">Usuário</label>
                        <select name="client" id="client" class="form-control">
                          <option >Primeiramente, informe o cartório...</option>
                        </select>
                        <small id="client-help" class="form-text text-muted">Informe o cliente.</small>
                      </div>
                      <div class="row" style="margin-left: 1px;">
                        <div class="col-lg-5">
                          <label for="new-password">Nova Senha</label>
                          <input type="password" name="new-password-user" id="new-password-user" class="form-control">
                          <small id="new-password-help" class="form-text text-muted">Informe a nova senha.</small>
                        </div>
                        <div class="col-lg-5">
                          <label for="confirmation-new-password">Confirmação da Nova Senha</label>
                          <input type="password" name="confirmation-new-password-user" id="confirmation-new-password-user" class="form-control">
                          <small id="confirmation-new-password-help" class="form-text text-muted">Confirme a nova senha.</small>
                        </div>
                        <div class="col-lg-2">
                          <button type="submit" class="btn btn-primary" style="margin-top: 32px; width: 90%;">Redefinir!</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              <?php endif; ?>
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