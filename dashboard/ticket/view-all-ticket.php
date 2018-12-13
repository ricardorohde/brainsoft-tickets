<?php 
  session_start();
  if (!isset($_SESSION['Ticket'.'_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }
?>

<?php
  if(isset($_COOKIE['date_to_filter'])){
    $actual_date_to_find = $_COOKIE['date_to_filter'];
  } else {
    date_default_timezone_set('America/Sao_Paulo');
    $day = date('d');
    $month = date('m');
    $year = date('Y');
    $actual_date_to_find = $year . "-" . $month . "-" . $day; 
  }

  $initial_date_to_find = date('Y-m-d', strtotime("-15 day", strtotime($actual_date_to_find)));
  $filter = "";
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

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
        if(!isset($_POST['filter-by-period'])){
          $sql_ticket = $connection->getConnection()->prepare("SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
            WHERE registered_at LIKE ? ORDER BY t_status ASC, id DESC");
          $sql_ticket->execute(array($actual_date_to_find."%")); 
          $tickets = $sql_ticket->fetchAll();

          $filter = date('d/m/Y', strtotime($actual_date_to_find));
        } else{
          $initial_date_to_find = date('Y-m-d', strtotime($_POST['initial-date-filter']));
          $actual_date_to_find = date('Y-m-d', strtotime("+1 day", strtotime($actual_date_to_find)));

          $sql_ticket = $connection->getConnection()->prepare("SELECT id_registry, id_client, id_module, id_attendant, id_chat, t_status, registered_at, finalized_at FROM ticket 
            WHERE registered_at BETWEEN ? AND ? ORDER BY id DESC");
          $sql_ticket->execute(array($initial_date_to_find."%", $actual_date_to_find."%")); 
          $tickets = $sql_ticket->fetchAll();

          $actual_date_to_find = date('Y-m-d', strtotime("-1 day", strtotime($actual_date_to_find)));

          $filter = "de " . date('d/m/Y', strtotime($initial_date_to_find)) . " até " . date('d/m/Y', strtotime($actual_date_to_find));
        }

        $sql_count_ticket = $connection->getConnection()->prepare("SELECT COUNT(*) as total FROM ticket");
        $sql_count_ticket->execute(); 
        $row_count_ticket = $sql_count_ticket->fetchAll();
      ?>
      <section class="forms">
        <div class="container-fluid">
          <header>
            <div class="row">
              <div class="col-sm-6">
                <h1 class="h3 display">Tickets</h1>
              </div>
              <div class="col-sm-6 text-right h2">
                  <a class="btn btn-primary" href="ticket/novo-busca"><i class="fa fa-plus"></i> Novo / Buscar Ticket</a>
                  <a class="btn btn-default" href="tickets"><i class="fa fa-refresh"></i> Atualizar</a>
                </div>
            </div>

            <?php if(isset($_SESSION['ticketStatus'])) : ?>
              <div id="status-sql" class="alert alert-success" style="display:block;">
                <?php echo $_SESSION['ticketStatus']; unset($_SESSION['ticketStatus']); ?>
              </div>
            <?php endif ?>

            <?php if(isset($_SESSION['thereIsProblemInTicket'])) : ?>
              <div id="status-sql" class="alert alert-danger" style="display:block;">
                <?php echo $_SESSION['thereIsProblemInTicket']; unset($_SESSION['thereIsProblemInTicket']);?>
              </div>
            <?php endif ?>
          </header>

          <hr>

          <form id="form-filter-all-ticket" action="#" method="POST">
            <div class="row">
              <div class="col col-lg-3">
                <input type="date" name="initial-date-filter" id="initial-date-filter" class="form-control" min="2018-09-20" value="<?= $initial_date_to_find?>">
                <span>Data Inicial</span>
              </div>
              <div class="col col-lg-3">
                <input type="date" name="final-actual-date-filter" id="final-actual-date-filter" class="form-control" min="2018-09-20" value="<?= $actual_date_to_find?>">
                <span>Data Atual ou Final</span>
              </div>
              <div class="col col-lg-1">
                 <button id="filter-by-period" name="filter-by-period" class="btn btn-primary">Filtrar</button>
              </div>
              <div class="col-lg-3 offset-md-2">
                <input type="text" id="txtBusca" class="form-control" autofocus disabled>
                <span>Pesquisa</span>
              </div>
            </div>
          </form>

          <br>

          <div id="divCarregando">
            <p>Aguarde...</p>
          </div>

          <div id="actual-filter" class="hide">
            <p>Filtro: <?= $filter ?></p>
          </div>

          <div id="qtd-tickets" class="hide">
            <p></p>
          </div>

          <div class="row">
            <div id="conteudo" class="col-md-12">
              <?php if (!empty($tickets)) : ?>
                <?php
                  $elements = ["nivel1", "nivel2", "yes", "yes"];
                  $query = "SELECT id, name FROM employee WHERE (t_group = ? OR t_group = ?) AND on_chat = ? AND (SELECT COUNT(*) FROM ticket WHERE id_attendant = employee.id AND t_status = ?) < 2 ORDER BY t_group, name";
                  $attendants = $prepareInstance->prepare($query, $elements, "all");
                ?>

                <?php foreach ($tickets as $ticket) : ?>
                  <?php 
                    $sql_module = $connection->getConnection()->prepare("SELECT description, id_category FROM ticket_module WHERE id = ?"); 
                    $sql_module->execute(array($ticket['id_module'])); 
                    $module = $sql_module->fetch();

                    $sql_category_module = $connection->getConnection()->prepare("SELECT description FROM category_module WHERE id = ?"); 
                    $sql_category_module->execute(array($module['id_category'])); 
                    $category_module = $sql_category_module->fetch();

                    $sql_registry = $connection->getConnection()->prepare("SELECT name FROM registry WHERE id = ?"); 
                    $sql_registry->execute(array($ticket['id_registry'])); 
                    $registry = $sql_registry->fetch();

                    $sql_client = $connection->getConnection()->prepare("SELECT name FROM client WHERE id = ?"); 
                    $sql_client->execute(array($ticket['id_client'])); 
                    $client = $sql_client->fetch();

                    $sql_attendant = $connection->getConnection()->prepare("SELECT id, name FROM employee WHERE id = ?"); 
                    $sql_attendant->execute(array($ticket['id_attendant'])); 
                    $attendant = $sql_attendant->fetch();

                    $sql_chat = $connection->getConnection()->prepare("SELECT id_chat, opening_time, final_time FROM chat WHERE id = ?"); 
                    $sql_chat->execute(array($ticket['id_chat'])); 
                    $id_chat = $sql_chat->fetch();
                  ?>

                  <?php if ($ticket['t_status'] == "solucionado" || $ticket['t_status'] == "fechado" ){
                          $status_background = "border-success border-left-success";
                          $status_icon = "color: green; font-size: 1.5em; float: right;";
                        } else if($ticket['t_status'] == "pendente"){
                          $status_background = "border-warning border-left-warning";
                          $status_icon = "font-size: 1.5em; float: right; opacity: 0.1;";
                        } else{
                          $status_background = "border-danger border-left-danger";
                          $status_icon = "font-size: 1.5em; float: right; opacity: 0.1;";
                        }
                  ?>

                  <div class="row">
                    <div class="col-11" style="padding-right: 0px;">    
                      <a href="ticket/<?= $id_chat[0]; ?>/<?= $attendant['id']; ?>" target="_blank" style="padding: 0px!important; width: 100%;">
                        <div class="card <?= $status_background?> mb-3">
                          <div class="card-header">
                            <?= $category_module['description']. " / " .$module['description']; ?> | 
                            <span><?= $client['name'] ?> do <?= $registry['name'] ?></span>
                            <?php if($id_chat[0] > 100000): ?>
                              <i class="material-icons" style="float: left; opacity: 0.4;">chat</i>
                            <?php else: ?>
                              <i class="material-icons" style="float: left; opacity: 0.4;">phone</i>
                            <?php endif; ?> 
                          </div>
                          <div class="card-body" style="font-size: 0.8em;">
                            <p class="card-text" id="data-ticket-text">
                              <?php if($id_chat[0] > 100000): ?>
                                <strong>Chat:</strong>
                              <?php else: ?>
                                <strong>Ligação:</strong>
                              <?php endif; ?> 
                              <?= $id_chat[0]; ?> |
                              <strong>Atendente:</strong> <?= $attendant['name']; ?> |
                              <strong>Abertura:</strong> <?= date('d/m/Y H:i:s', strtotime($ticket['registered_at'])); ?> 
                              <?php
                                $closure = date('d/m/Y H:i:s', strtotime($ticket['finalized_at']));
                                if(strtotime($ticket['finalized_at']) > strtotime("01-01-2018 01:00:00")): ?>
                                  | <strong>Encerramento:</strong> <?= $closure ?>
                              <?php
                                endif;
                              ?>
                            </p>
                          </div>
                        </div>      
                      </a>
                    </div>
                    <div class="col-1" style="padding-left: 0px; text-align: left;">
                      <div class="btn-group dropleft" style="height: 83%;">
                        <button type="button" class="btn btn-info dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 0px .25rem .25rem 0px;">
                          <i class="material-icons" style="padding-top: 10px;">forward</i>
                        </button>
                        <div class="dropdown-menu">
                          <?php foreach ($attendants as $at) : ?>
                            <?php if($attendant['name'] != $at['name']) : ?>
                              <a class="dropdown-item" href="ticket/<?= $id_chat[0] ?>/<?= $at['id'] ?>" target="_blank"><?= $at['name'] ?></a>
                              <div class="dropdown-divider"></div> 
                            <?php endif; ?>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              <?php else : ?>
                <tr>
                  <td colspan="6">Nenhum ticket registrado até o momento.</td>
                </tr>
              <?php endif; ?>
            </div>
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Selecione o atendente de destino</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    ...
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div id="pagi" style="margin-left: 250px;"></div>
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
    <script src="./js/jquery.easyPaginate.js"></script>
    <script type="text/javascript">
      /*$('#conteudo').easyPaginate({
        paginateElement: 'myElement',
        elementsPerPage: 70,
        effect: 'climb',
        firstButtonText: '<button type="button" class="btn btn-primary">&laquo; Primeiro</button>',
        prevButtonText: '<button type="button" class="btn btn-primary">&lsaquo; Anterior</button>',
        nextButtonText: '<button type="button" class="btn btn-primary">Próximo &rsaquo;</button>',
        lastButtonText: '<button type="button" class="btn btn-primary">Último &raquo;</button>'
      });*/
    </script>
    <script src="./../js/jquery.mask.js"></script>
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="./js/front.js"></script>
    <script src="./js/view-all-ticket.js"></script>
    <script src="./jquery-ui.js"></script>
    <script src="./vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="./js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="./vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
  </body>
</html>