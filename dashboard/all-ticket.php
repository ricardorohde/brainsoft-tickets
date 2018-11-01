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
            <li class="breadcrumb-item active"><a href="/novo-site/dashboard/forms.html">Usuário</a></li>
          </ul>
        </div>
      </div>
      <?php 
        $sql_ticket = $connection->getConnection()->prepare("SELECT * FROM ticket");
        $sql_ticket->execute();

        $tickets = $sql_ticket->fetchAll();

        $sql_module = $connection->getConnection()->prepare("SELECT description FROM ticket_module WHERE id = ?");
        $sql_module->execute(array($tickets[0]['id_module']));

        $module = $sql_module->fetchAll();

        $sql_attendant = $connection->getConnection()->prepare("SELECT name FROM employee WHERE id = ?");
        $sql_attendant->execute(array($tickets[0]['id_attendant']));

        $attendant = $sql_attendant->fetchAll();
      ?>
      <section class="forms">
        <div class="container-fluid">
          <header>
            <div class="row">
              <div class="col-sm-6">
                <h1>Tickets</h1>
              </div>
              <div class="col-sm-6 text-right h2">
                  <a class="btn btn-primary" href="view_ticket.php"><i class="fa fa-plus"></i> Novo Ticket</a>
                  <a class="btn btn-default" href="all-ticket.php"><i class="fa fa-refresh"></i> Atualizar</a>
                </div>
            </div>
          </header>

          <hr>
          <div class="table-responsive">
            <table class="table table-clicked">
            <thead>
              <tr>
                <th>ID</th>      
                <th>Módulo</th>
                <th>Atendente</th>
                <th>Entrada</th>
                <th>Transferido</th>
                <th>Iniciado</th>
                <th>Finalizado</th>
                <th>Status</th>
                <th>Prioridade</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            <?php if (!empty($tickets)) : ?>
              <?php foreach ($tickets as $ticket) : ?>
                <tr>
                  <td><?php echo $ticket['id']; ?></td>
                  <td><?php echo $module[0]['description']; ?></td>
                  <td><?php echo $attendant[0]['name']; ?></td>
                  <td><?php echo $ticket['joined_at']; ?></td>
                  <td><?php echo $ticket['transfered_at']; ?></td>
                  <td><? ?></td>
                  <td><? ?></td>
                  <td><?php echo $ticket['status']; ?></td>
                  <td><?php echo $ticket['priority']; ?></td>
                  <td class="actions text-right">
                    <a href="view.php?id=<?php echo $ticket['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Visualizar</a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else : ?>
              <tr>
                <td colspan="6">Nenhum registro encontrado.</td>
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
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="js/front.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
  </body>
</html>