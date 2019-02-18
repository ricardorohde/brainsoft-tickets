<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Administrativo</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="../dashboard/vendor/font-awesome/css/font-awesome.min.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="../dashboard/css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="../dashboard/css/custom.css">
    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <div class="root-page login-page">
      <div class="container">
        <div class="form-outer text-center d-flex align-items-center">
          <div class="form-inner">
            <div id="statusLogin" class="alert alert-info" 
              <?php echo (isset($_SESSION['withoutLogin'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
              <?php echo $_SESSION['withoutLogin'];?>
            </div>
            <div id="statusLogin" class="alert alert-danger" 
              <?php echo (isset($_SESSION['errorLogin'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
              <?php echo $_SESSION['errorLogin'];?>
            </div>
            <div class="logo text-uppercase">
              <span>Cliente</span><strong class="text-primary">Brainsoft</strong>
            </div>
            <p>Informe seu usuário e senha para ter acesso a área administrativa destinada para a exibição de informações financeiras.</p>
            <form id="login-form" method="post" action="controller/credential/credential-data.ctrl.php">
              <div class="form-group">
                <input id="login" type="text" name="login" required="" autofocus="" placeholder="Usuário...">
              </div>
              <div class="form-group">
                <input id="password" type="password" name="password" required="" placeholder="Senha...">
              </div>
              <button type="submit" id="submit" name="submit" value="fromDoLogin" class="btn btn-primary">Entrar!</button>
            </form>
            <a href="#" class="forgot-pass">Esqueceu sua senha?</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Javascript files-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../dashboard/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../dashboard/js/front.js"></script>
    <script src="../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
    
  </body>
</html>