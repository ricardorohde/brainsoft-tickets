<?php
  include_once "../../utils/controller/ticket/show-all.ctrl.php";
  $allTicketCtrl = AllTicketController::getInstance();
  $allTicketCtrl->verifyPermission();

  $allTicketCtrl->verifyCookie($_COOKIE);
  $allTicketCtrl->verifyPost($_POST);
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

    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="./css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="./css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="./css/style.default.css" id="theme-stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="./css/custom.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <link rel="shortcut icon" href="favicon.png">
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
          <div class="row">
            <div class="col-sm-12 text-center">
              <button id="show-hide-filters" class="btn btn-info mb-3" type="button" data-toggle="collapse" data-target="#collapseExample" 
                      aria-expanded="false" aria-controls="collapseExample">
                <?= $allTicketCtrl->getHasFilter() ? "Esconder Filtros" : "Exibir Filtros" ?>
              </button>
            </div>
          </div>
          <div class="collapse <?= $allTicketCtrl->getHasFilter() ? "show" : "" ?>" id="collapseExample">
            <form id="form-filter-all-ticket" action="#" method="POST">
              <div class="row mb-2">
                <div class="col col-lg-3">
                  <input type="date" name="initial-date-filter" id="initial-date-filter" class="form-control" min="2018-10-10" value="<?= $allTicketCtrl->getInitialDateToFind() ?>">
                  <span>Data Inicial</span>
                </div>
                <div class="col col-lg-3">
                  <input type="date" name="final-actual-date-filter" id="final-actual-date-filter" class="form-control" min="2018-10-10" value="<?= $allTicketCtrl->getActualDateToFind() ?>">
                  <span>Data Atual ou Final</span>
                </div>
                <div class="col col-lg-1">
                  <button type="submit" id="filter-by-period" name="filter-by-period" class="btn btn-primary" title="Pela data inicial e final.">Filtrar</button>
                </div>
                <div class="col-lg-3 offset-md-2">
                  <input type="text" id="txtBusca" class="form-control" autofocus disabled>
                  <span>Pesquisa</span>
                </div>
              </div>
              <div class="row">
                <div class="col col-lg-3">
                  <select name="attendant" class="form-control" id="attendant" required>
                    <option>Selecione o atendente...</option>
                    <?php foreach ($allTicketCtrl->getAttendants() as $attendant) : ?>
                      <option value="<?= $attendant['id'] ?>" <?= @$allTicketCtrl->getAttendantIdOfFilter() == $attendant['id'] ? "selected" : "" ?>><?= $attendant['name'] ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col col-lg-3">
                  <select name="status" class="form-control" id="status" required>
                    <option>Selecione o status...</option>
                    <option value="aberto" <?= @$allTicketCtrl->getStatusOfFilter() == "aberto" ? "selected" : ""?>>Aberto</option>
                    <option value="pendente" <?= @$allTicketCtrl->getStatusOfFilter() == "pendente" ? "selected" : ""?>>Pendente</option>
                    <option value="solucionado" <?= @$allTicketCtrl->getStatusOfFilter() == "solucionado" ? "selected" : ""?>>Solucionado</option>
                    <option value="fechado" <?= @$allTicketCtrl->getStatusOfFilter() == "fechado" ? "selected" : ""?>>Fechado</option>
                  </select>
                </div>
                <div class="col col-lg-3">
                   <button type="submit" id="filter-by-attendant" name="filter-by-attendant" class="btn btn-primary"  title="Pela data inicial, final, atendente e status.">Filtrar</button>
                </div>
              </div>
            </form>
            <br>
            <div id="divCarregando">
              <p>Aguarde...</p>
            </div>
            <div id="actual-filter" class="hide">
              <p>Filtro: <?= $allTicketCtrl->getFilterToShow() ?></p>
            </div>
            <div id="qtd-tickets" class="hide">
              <p></p>
            </div>
          </div>
          <div class="row">
            <div id="conteudo" class="col-md-12">
              <?php if (!empty($allTicketCtrl->getTickets())) : ?>
                <?php $allTicketCtrl->forwardTo(); ?>

                <?php foreach ($allTicketCtrl->getTickets() as $ticket) : ?>
                  <?php 
                    /*$sql_module = $connection->getConnection()->prepare("SELECT description, id_category FROM ticket_module WHERE id = ?"); 
                    $sql_module->execute(array($ticket['id_module'])); 
                    $module = $sql_module->fetch();*/
                    $allTicketCtrl->findModule($ticket['id_module']);

                    /*$sql_category_module = $connection->getConnection()->prepare("SELECT description FROM category_module WHERE id = ?"); 
                    $sql_category_module->execute(array($module['id_category'])); 
                    $category_module = $sql_category_module->fetch();*/
                    $allTicketCtrl->findCategoryModule($allTicketCtrl->getModuleOfTicket()['id_category']);

                    /*$sql_registry = $connection->getConnection()->prepare("SELECT name FROM registry WHERE id = ?"); 
                    $sql_registry->execute(array($ticket['id_registry'])); 
                    $registry = $sql_registry->fetch();*/
                    $allTicketCtrl->findRegistry($ticket['id_registry']);

                    /*$sql_client = $connection->getConnection()->prepare("SELECT name FROM client WHERE id = ?"); 
                    $sql_client->execute(array($ticket['id_client'])); 
                    $client = $sql_client->fetch();*/
                    $allTicketCtrl->findClient($ticket['id_client']);

                    /*$sql_attendant = $connection->getConnection()->prepare("SELECT id, name FROM employee WHERE id = ?"); 
                    $sql_attendant->execute(array($ticket['id_attendant'])); 
                    $attendant = $sql_attendant->fetch();*/
                    $allTicketCtrl->findEmployee($ticket['id_attendant']);

                    /*$sql_chat = $connection->getConnection()->prepare("SELECT id_chat, opening_time, final_time FROM chat WHERE id = ?"); 
                    $sql_chat->execute(array($ticket['id_chat'])); 
                    $id_chat = $sql_chat->fetch();*/
                    $allTicketCtrl->findChat($ticket['id_chat']);
                  ?>

                  <?php 
                    if ($ticket['t_status'] == "solucionado" || $ticket['t_status'] == "fechado") {
                      $status_background = "border-success border-left-success";
                      $status_icon = "color: green; font-size: 1.5em; float: right;";
                    } else if ($ticket['t_status'] == "pendente") {
                      $status_background = "border-warning border-left-warning";
                      $status_icon = "font-size: 1.5em; float: right; opacity: 0.1;";
                    } else {
                      $status_background = "border-danger border-left-danger";
                      $status_icon = "font-size: 1.5em; float: right; opacity: 0.1;";
                    }
                  ?>

                  <div class="row">
                    <div class="col-11" style="padding-right: 0px;">    
                      <a href="ticket/<?= $allTicketCtrl->getChat()['id_chat']; ?>/<?= $allTicketCtrl->getEmployee()['id']; ?>" style="padding: 0px!important; width: 100%;">
                        <div class="card-in-ticket-list card <?= $status_background?> mb-3">
                          <div class="card-header">
                            <?= $allTicketCtrl->getCategoryModule()['description']. " / " .$allTicketCtrl->getModuleOfTicket()['description']; ?> | 
                            <span><?= $allTicketCtrl->getClient()['name'] ?> do <?= $allTicketCtrl->getRegistry()['name'] ?></span>
                            <?php if ($allTicketCtrl->getChat()['id_chat'] > 100000) : ?>
                              <i class="material-icons" style="float: left; opacity: 0.4;">chat</i>
                            <?php else : ?>
                              <i class="material-icons" style="float: left; opacity: 0.4;">phone</i>
                            <?php endif; ?> 
                          </div>
                          <div class="card-body" style="font-size: 0.8em;">
                            <p class="card-text" id="data-ticket-text">
                              <?php if ($allTicketCtrl->getChat()['id_chat'] > 100000) : ?>
                                <strong>Chat:</strong>
                              <?php else : ?>
                                <strong>Ligação:</strong>
                              <?php endif; ?> 
                              <?= $allTicketCtrl->getChat()['id_chat']; ?> |
                              <strong>Atendente:</strong> <?= $allTicketCtrl->getEmployee()['name'] ?> |
                              <strong>Abertura:</strong> <?= date('d/m/Y H:i:s', strtotime($ticket['registered_at'])) ?> 
                              <?php
                                $closure = date('d/m/Y H:i:s', strtotime($ticket['finalized_at']));
                                if (strtotime($ticket['finalized_at']) > strtotime("01-01-2018 01:00:00")) : ?>
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
                        <button type="button" class="btn btn-info dropdown" data-toggle="dropdown" aria-haspopup="true" 
                                aria-expanded="false" style="border-radius: 0px .25rem .25rem 0px;" title="Transferir">
                          <i class="material-icons" style="padding-top: 10px;">forward</i>
                        </button>
                        <div class="dropdown-menu">
                          <?php foreach ($allTicketCtrl->getAttendantsToForward() as $at) : ?>
                            <?php if($allTicketCtrl->getEmployee()['name'] != $at['name']) : ?>
                              <a class="dropdown-item" href="ticket/<?= $allTicketCtrl->getChat()['id_chat'] ?>/<?= $at['id'] ?>"><?= $at['name'] ?></a>
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