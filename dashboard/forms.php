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
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">

    <!-- Tweaks for older IEs--><!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
  </head>
  <body>
    <?php include ("navs/navbar.php");?>
    <div class="page forms-page">
      <?php include ("navs/header.php");?>
      <div class="breadcrumb-holder">
        <div class="container-fluid">
          <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
            <li class="breadcrumb-item"><a href="#">Cadastro</a></li>
            <li class="breadcrumb-item active"><a href="../dashboard/forms.html">Usuário</a></li>
          </ul>
        </div>
      </div>
      <?php 
        $sql_state = $connection->getConnection()->prepare("SELECT id, description FROM `state` ORDER BY `initials`");
        $sql_state->execute();

        $sql_role = $connection->getConnection()->prepare("SELECT id, description FROM `role` ORDER BY `description`");
        $sql_role->execute();?>
      <section class="forms">
        <div class="container-fluid">
          <header> 
            <h1 class="h3 display">Cadastro de usuários</h1>
          </header>
          <div class="row">    
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h2 class="h5 display">Novo Usuário</h2>
                </div>
                <div class="card-body">
                  <form class="form-horizontal" id="formAdd" name="formAdd" action="../utils/controller/ctrl_user.php" method="POST">
                    <div id="statusLogin" class="alert alert-success" 
                      <?php echo (isset($_SESSION['userRegistered'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
                      <?php echo $_SESSION['userRegistered']; unset($_SESSION['userRegistered']);?>
                    </div>
                    <div id="statusLogin" class="alert alert-danger" 
                      <?php echo (isset($_SESSION['userExists'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
                      <?php echo $_SESSION['userExists']; unset($_SESSION['userExists']);?>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Tipo</label>
                      <div class="col-sm-10">
                        <div class="i-checks">
                          <input id="radioCustom1" type="radio" value="client" name="typeUser" class="form-control-custom radio-custom" checked="">
                          <label for="radioCustom1">Cliente</label>
                        </div>
                        <div class="i-checks">
                          <input id="radioCustom2" type="radio" value="employee" name="typeUser" class="form-control-custom radio-custom">
                          <label for="radioCustom2">Funcionário</label>
                        </div>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Nome Completo</label>
                      <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" ><span class="help-block-none">Informe o nome completo do usuário.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Email</label>
                      <div class="col-sm-10">
                        <input type="text" id="email" name="email" class="form-control" ><span class="help-block-none">Informe o email do usuário.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row dataOfClient">
                      <label class="col-sm-2 form-control-label">Estado</label>
                      <div class="col-sm-3 select">
                        <select name="state" class="form-control" id="state" >
                          <option value="">Selecione um estado...</option>
                          <?php while($row = $sql_state->fetch()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo utf8_encode($row['description']); ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <label class="col-sm-3 form-control-label text-center">Cidade</label>
                      <div class="col-sm-4 select">
                        <select name="city" class="form-control" id="city">
                          <option value="">Selecione um estado</option>
                        </select>
                      </div>
                    </div>
                    <div class="line dataOfClient"></div>
                    <div class="form-group row dataOfClient">
                      <label class="col-sm-2 form-control-label">Cartório</label>
                      <div class="col-sm-10 select">
                        <select name="registry" class="form-control">
                          <option value="">Selecione uma cidade</option>
                        </select>
                      </div>
                    </div>
                    <div class="line dataOfClient"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Cargo</label>
                      <div class="col-sm-10 select">
                        <select name="role" class="form-control">
                          <option value="">Selecione um cargo</option>
                          <?php while($row = $sql_role->fetch()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['description'] ?></option>
                          <?php } ?>
                        </select>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Nickname</label>
                      <div class="col-sm-10">
                        <input type="text" name="login" class="form-control" required><span class="help-block-none">Informe um nome para que o usuário acesse o sistema.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Senha</label>
                      <div class="col-sm-10">
                        <input type="text" name="password" class="form-control" required><span class="help-block-none">Crie uma senha de acesso para o usuário.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <div class="col-sm-4 offset-sm-2">
                        <button type="reset" class="btn btn-secondary">Limpar</button>
                        <button type="submit" name="submit" value="newUser" class="btn btn-primary registerUser">Cadastrar!</button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="jquery-ui.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/front.js"></script>
    <script src="js/brain.js"></script>
    
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
  </body>
</html>