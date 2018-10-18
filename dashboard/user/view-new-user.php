<?php 
  session_start();
  if (!isset($_SESSION['user_page_'.$_SESSION['login']])) {
    header("Location:/dashboard");
  }
?>

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
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">

  </head>
  <?php 
    $target_adm = "../administrativo";
    $target_ticket = "../tickets"; 
    $target_user = "../usuarios";
    $target_registry = "../cartorios";
    $target_registration_forms = "../cadastros";
    $target_internal_queue = "../fila-interna";
    $target_authorization = "../autorizacoes"; 
    $target_report = "../relatorios";

    $target_logout = "../logout";
  ?>
  <body>
    <?php include ("../navs/navbar.php");?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php");?>
      
      <?php
        $thereIsProblem = false;
        if((isset($_GET['type']) && $_GET['type'] != "") && (isset($_GET['id']) && $_GET['id'] != "")){
          $type = $_GET['type'];
          $id = $_GET['id'];

          if($type == "client"){
            $sql_user = $connection->getConnection()->prepare("SELECT name, email, id_credential, id_registry, id_role FROM client WHERE id = ?"); 
            $sql_user->execute(array($id)); $row_sql_user = $sql_user->fetch();

            $sql_registry = $connection->getConnection()->prepare("SELECT * FROM registry WHERE id = ?"); 
            $sql_registry->execute(array($row_sql_user['id_registry'])); $row_sql_registry = $sql_registry->fetch();

            $sql_city = $connection->getConnection()->prepare("SELECT * FROM city WHERE id = ?"); 
            $sql_city->execute(array($row_sql_registry['id_city'])); $row_sql_city = $sql_city->fetch();

            $sql_state = $connection->getConnection()->prepare("SELECT * FROM state WHERE id = ?"); 
            $sql_state->execute(array($row_sql_city['id_state'])); $row_sql_state = $sql_state->fetch();

            $sql_role = $connection->getConnection()->prepare("SELECT * FROM role WHERE status = ? AND id = ?"); 
            $sql_role->execute(array("ativo", $row_sql_user['id_role'])); $row_sql_role = $sql_role->fetch();
          } else if ($type == "employee"){
            echo "<input type='hidden' id='hasEmployee'>";

            $sql_user = $connection->getConnection()->prepare("SELECT name, email, t_group, id_credential, id_role FROM employee WHERE id = ?");
            $sql_user->execute(array($id)); $row_sql_user = $sql_user->fetch();

            $sql_role = $connection->getConnection()->prepare("SELECT * FROM role WHERE status = ? AND id = ?"); 
            $sql_role->execute(array("ativo", $row_sql_user['id_role'])); $row_sql_role = $sql_role->fetch();
          } else {
            $thereIsProblem = true;
          }

          $sql_credential = $connection->getConnection()->prepare("SELECT login FROM credential WHERE id = ?"); 
          $sql_credential->execute(array(@$row_sql_user['id_credential'])); $row_sql_credential = $sql_credential->fetch();
        } else{
          $thereIsProblem = true;
        }
      ?>

      <?php 
        $sql_state = $connection->getConnection()->prepare("SELECT id, description FROM `state` ORDER BY `initials`");
        $sql_state->execute();

        @$user_role = $row_sql_role['description'];

        echo '<input type="hidden" id="userInformed" value="'.$user_role.'">';
      ?>
      <section class="forms">
        <div class="container-fluid">
          <header> 
            <h1 class="h3 display">Cadastro de usuários</h1>
          </header>
          <div class="row">    
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h2 class="h5 display"><?php echo isset($_GET['id']) ? "Visualizar/Alterar Usuário" : "Novo Usuário"?></h2>
                </div>
                <div class="card-body">
                  <form class="form-horizontal" id="formAdd" name="formAdd" action="../../utils/controller/ctrl_user.php" method="POST">                    
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Tipo</label>
                      <div class="col-sm-10">
                        <div class="i-checks <?php if(isset($_GET['type']) AND $_GET['type'] == 'employee') echo 'control';?>" id="divClient">
                          <input id="radioCustom1" type="radio" value="client" name="typeUser" class="form-control-custom radio-custom" checked <?php if(isset($type) && $type == "employee"){echo "disabled";}?>>
                          <label for="radioCustom1">Cliente</label>
                        </div>
                        <div class="i-checks <?php if(isset($_GET['type']) AND $_GET['type'] == 'client') echo 'control';?>" id="divEmployee">
                          <input id="radioCustom2" type="radio" value="employee" name="typeUser" class="form-control-custom radio-custom" <?php if(isset($type) && $type == "employee"){echo "checked";}?>>
                          <label for="radioCustom2">Funcionário</label>
                        </div>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Nome Completo</label>
                      <div class="col-sm-10">
                        <input type="text" name="name" class="form-control" value="<?php if(isset($row_sql_user['name'])){echo $row_sql_user['name'];}?>"><span class="help-block-none">Informe o nome completo do usuário.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Email</label>
                      <div class="col-sm-10">
                        <input type="text" id="email" name="email" class="form-control" value="<?php if(isset($row_sql_user['email'])){echo $row_sql_user['email'];}?>"><span class="help-block-none">Informe o email do usuário.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row dataOfClient">
                      <label class="col-sm-2 form-control-label">Estado</label>
                      <div class="col-sm-3 select">
                        <select class="form-control" name="state" id="state">
                          <?php if (@$row_sql_state != NULL): ?>
                            <option value=<?php echo '"'.$row_sql_state['id'].'"';?>><?php echo $row_sql_state['description'];?></option>
                          <?php else: ?>
                            <option value="">Selecione um estado...</option>
                          <?php endif; ?>

                          <?php while($row = $sql_state->fetch()) { ?>
                            <option value="<?php echo $row['id'] ?>"><?php echo $row['description']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <label class="col-sm-3 form-control-label text-center">Cidade</label>
                      <div class="col-sm-4 select">
                        <select name="city" class="form-control" id="city">
                          <?php if (@$row_sql_city != NULL): ?>
                            <option value=<?php echo '"'.$row_sql_city['id'].'"';?>><?php echo $row_sql_city['description'];?></option>
                          <?php else: ?>
                            <option value="">Selecione um estado...</option>
                          <?php endif; ?>
                        </select>
                      </div>
                    </div>
                    <div class="line dataOfClient"></div>
                    <div class="form-group row dataOfClient">
                      <label class="col-sm-2 form-control-label">Cartório</label>
                      <div class="col-sm-10 select">
                        <select name="registry" class="form-control" id="id_registry">
                          <?php if (@$row_sql_registry != NULL): ?>
                            <option value=<?php echo '"'.$row_sql_registry['id'].'"';?>><?php echo $row_sql_registry['name'];?></option>
                          <?php else: ?>
                            <option value="">Selecione uma cidade...</option>
                          <?php endif; ?>
                        </select>
                      </div>
                    </div>
                    <div class="line dataOfClient"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Cargo</label>
                      <div class="col-sm-10 select">
                        <select name="role" class="form-control">
                        </select>
                      </div>
                    </div>
                    <div class="line dataOfEmployee"></div>
                    <div class="form-group row dataOfEmployee">
                      <label class="col-sm-2 form-control-label">Grupo</label>
                      <div class="col-sm-10">
                        <select name="group" class="form-control" id="group" required>
                          <?php switch($row_sql_user['t_group']){
                            case "nivel1":
                              echo "<option value='nivel1' selected>Nível 1</option>";
                              echo "<option value='nivel2'>Nível 2</option>";
                              break;
                            case "nivel2":
                              echo "<option value='nivel1'>Nível 1</option>";
                              echo "<option value='nivel2' selected>Nível 2</option>";
                              break;
                            default:
                              echo "<option>Selecione um grupo...</option>";
                              echo "<option value='nivel1'>Nível 1</option>";
                              echo "<option value='nivel2'>Nível 2</option>";
                            }
                          ?> 
                        </select>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Nickname</label>
                      <div class="col-sm-10">
                        <input type="text" name="login" class="form-control" value="<?php if(isset($row_sql_credential['login'])){echo $row_sql_credential['login'];}?>" required><span class="help-block-none">Informe um nome para que o usuário acesse o sistema.</span>
                      </div>
                      <input type="hidden" name="id_user" value="<?php if(isset($_GET['id'])){echo $_GET['id'];} ?>">
                    </div>
                    <div class="line <?php if(isset($row_sql_user['name'])){echo 'control';}?>"></div>
                    <div class="form-group row <?php if(isset($row_sql_user['name'])){echo 'control';}?>">
                      <label class="col-sm-2 form-control-label">Senha</label>
                      <div class="col-sm-10">
                        <input type="text" name="password" class="form-control" value="sistemabrain" required><span class="help-block-none">Crie uma senha de acesso para o usuário.</span>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <div class="col-sm-4 offset-sm-2">
                        <?php 
                          if ((isset($_GET['id']) && @$row_sql_user['name'] == NULL) || (isset($_GET['type']) && !isset($_GET['id']))){
                            echo '<span id="wrongIdChat"><strong>Erro!</strong> Tipo e/ou usuário informado não existe. Contate o Administrador.</span>';
                          } else {
                            echo '<button type="reset" class="btn btn-secondary">Limpar</button>';

                            if(isset($_GET['id'])){
                              echo '<button type="submit" name="submit" value="alterUser" class="btn 
                              btn-primary btnAction">Salvar!</button>';
                            } else {
                              echo '<button type="submit" name="submit" value="newUser" class="btn btn-primary btnAction">Cadastrar!</button>';
                            }
                          }
                        ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="../jquery-ui.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../js/front.js"></script>
    <script src="../js/brain.js"></script>    
    
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
  </body>
</html>