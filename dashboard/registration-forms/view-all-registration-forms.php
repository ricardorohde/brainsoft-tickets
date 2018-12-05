<?php 
  session_start();
  if (!isset($_SESSION['Module'.'_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Listagem de Usuários</title>
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
    $targets = array(
      "Billet" => "administrativo Administrativo fa-files-o",
      "Ticket" => "tickets Tickets fa-ticket",
      "User" => "usuarios Usuários fa-user-circle",
      "Registry" => "cartorios Cartórios fa-home",
      "Module" => "cadastros Módulos fa-caret-square-o-right",
      "Queue" => "fila-interna Fila fa-sort-amount-asc",
      "Authorization" => "autorizacoes Autorizações fa-caret-square-o-right",
      "Report" => "relatorios Relatórios fa-caret-square-o-right",
      "Logout" => "logout"
    );
  ?>
  <body>
    <?php include ("../navs/navbar.php");?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php");?>
      <?php 
        $sql_ticket_module = $connection->getConnection()->prepare("SELECT * FROM ticket_module");
        $sql_ticket_module->execute(); $row_ticket_module = $sql_ticket_module->fetchAll();

        $sql_role = $connection->getConnection()->prepare("SELECT * FROM role");
        $sql_role->execute(); $row_role = $sql_role->fetchAll();
      ?>
      <section class="forms">
        <div class="container-fluid">
          <div id="statusCategory" class="alert alert-success" 
            <?php echo (isset($_SESSION['categoryOk'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
            <?php echo $_SESSION['categoryOk']; unset($_SESSION['categoryOk']);?>
          </div>
          <div id="statusCategory" class="alert alert-danger" 
            <?php echo (isset($_SESSION['categoryNo'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
            <?php echo $_SESSION['categoryNo']; unset($_SESSION['categoryNo']);?>
          </div>
          <div id="statusLogin" class="alert alert-success" 
            <?php echo (isset($_SESSION['moduleOk'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
            <?php echo $_SESSION['moduleOk']; unset($_SESSION['moduleOk']);?>
          </div>
          <div id="statusLogin" class="alert alert-danger" 
            <?php echo (isset($_SESSION['moduleNo'])) ? 'style="display:block;"' : 'style="display:none;"'?> >
            <?php echo $_SESSION['moduleNo']; unset($_SESSION['moduleNo']);?>
          </div>
          <ul class="nav nav-tabs menu-users">
            <li class="nav-item">
              <a class="nav-link active" href="#first-tab" data-toggle="tab">Categorias / Módulos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#second-tab" data-toggle="tab">Cargos</a>
            </li>
          </ul>
           
          <div class="tab-content">
            <div class="tab-pane active in" id="first-tab">
              <header>
                <div class="row">
                  <div class="col-sm-6">
                    <h1 class="h3 display"></h1>
                  </div>
                  <div class="col-sm-6 text-right h2">
                      <a class="btn btn-primary" href="categorias-e-modulos/novo"><i class="fa fa-plus"></i> Nova Categoria / Módulo</a>
                      <a class="btn btn-default" href="cadastros"><i class="fa fa-refresh"></i> Atualizar</a>
                  </div>
                </div>
              </header>

              <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Categoria</th>
                    <th>Módulo</th>  
                    <th>Grupo</th>
                    <th>Tempo Limite (minutos)</th>    
                    <th>Situação</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php if (!empty($row_ticket_module)) : ?>
                  <?php foreach ($row_ticket_module as $module) : ?>

                    <?php
                      $sql_category_module = $connection->getConnection()->prepare("SELECT * FROM category_module WHERE id = ? ORDER BY id DESC");
                      $sql_category_module->execute(array($module['id_category'])); $row_category_module =
                      $sql_category_module->fetch();
                    ?>

                    <tr>
                      <td><?= $row_category_module['description']?></td>
                      <td><?= $module['description']?></td>
                      <td><?= $row_category_module['t_group']?></td>
                      <td name="limit" id="<?php echo $module['id'];?>" title="Duplo clique para editar!"><?php echo $module['limit_time']?></td>
                      <td><?= ucfirst($module['status'])?></td>
                      <td class="actions text-right">
                        <?php if ($module['status'] == "ativo") { ?>
                          <a href="../utils/controller/ctrl-module.php?id=<?php echo $module['id']; ?>" class="btn btn-sm btn-danger" title="desativar"><i class="fa fa-trash-o"></i> Desativar</a>
                        <?php } else { ?>
                          <a href="../utils/controller/ctrl-module.php?type=active&id=<?php echo $module['id']; ?>" class="btn btn-sm btn-success" title="ativar"><i class="fa fa-check-circle-o"></i> Ativar</a>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="6">Nenhum módulo encontrado.</td>
                  </tr>
                <?php endif; ?>
                </tbody>
                </table>
              </div>
            </div>
            <div class="tab-pane" id="second-tab">
              <header>
                <div class="row">
                  <div class="col-sm-6">
                    <h1 class="h3 display"></h1>
                  </div>
                  <div class="col-sm-6 text-right h2">
                      <a class="btn btn-primary" href="cargos/novo"><i class="fa fa-plus"></i> Novo Cargo</a>
                      <a class="btn btn-default" href="cadastros"><i class="fa fa-refresh"></i> Atualizar</a>
                  </div>
                </div>
              </header>

              <div class="table-responsive">
                <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Descrição</th>
                    <th>Destinado a</th>   
                    <th>Situação</th>   
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                <?php if (!empty($row_role)) : ?>
                  <?php foreach ($row_role as $role) : ?>
                    <tr>
                      <td><?php echo $role['description']; ?></td>
                      <td><?php echo ($role['type'])==1 ? "Cliente" : "Funcionário"; ?></td>
                      <td><?php echo ucfirst($role['status']); ?></td>
                      <td class="actions text-right">
                        <?php if ($role['status'] == "ativo") { ?>
                          <a href="../utils/controller/ctrl-role.php?id=<?php echo $role['id']; ?>" class="btn btn-sm btn-danger" title="desativar"><i class="fa fa-trash-o"></i> Desativar</a>
                        <?php } else { ?>
                          <a href="../utils/controller/ctrl-role.php?type=active&id=<?php echo $role['id']; ?>" class="btn btn-sm btn-success" title="ativar"><i class="fa fa-check-circle-o"></i> Ativar</a>
                        <?php } ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="6">Nenhum funcionário encontrado.</td>
                  </tr>
                <?php endif; ?>
                </tbody>
                </table>
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