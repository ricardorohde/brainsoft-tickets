<?php 
  include_once "../../utils/controller/ticket/new-find.ctrl.php";
  $ticketController = NewFindTicketController::getInstance();
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Buscar | Pesquisar Tickets</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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

    <link type="text/css" href="../css/jquery-ui.css" rel="stylesheet"/>
    <!-- Favicon-->
    <link rel="shortcut icon" href="favicon.png">
  </head>
  
  <body>
    <?php include ("../navs/navbar.php");?>
    <div class="root-page forms-page">
      <?php include ("../navs/header.php");?>

      <?php
        $attendants = $ticketController->findAttendantsInQueue();
        $ticketController->findCalls();
        $calls = $ticketController->findDataOfCalls();
      ?>

      <section class="forms">
        <div class="container-fluid">
          <header> 
            <h1 class="h3 display">Criar Ticket</h1>
          </header>
          <div class="row">    
            <div class="col-lg-12">
              <div class="card">
                <div class="card-header d-flex align-items-center">
                  <h2 class="h5 display">Informe o código do Chat ou Ligação!</h2>
                </div>
                <div class="card-body">
                  <div class="row">
                    <label class="col-sm-2 form-control-label mt-4">Código</label>
                    <div class="col-sm-10 select ui-widget">
                      <input type="text" name="id_chat" id="id_chat" class="justNumbers form-control" maxlength="7" autofocus="" autocomplete="off"><span class="help-block-none">Informe o código do chat atendido.</span>
                    </div>
                  </div> 
                  <div class="row" style="margin-left: 0px!important;">
                    <label class="col-sm-2 form-control-label mt-4" style="padding-left: 0px!important;">Atendente</label>
                      <div class="col-sm-10 mt-3" style="padding-left: 0px!important;">
                        <?php foreach ($attendants as $attendant) : ?>
                          <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="attendant<?= $attendant['id'] ?>" name="id_attendant" value="<?= $attendant['id'] ?>" class="custom-control-input" <?= $attendant['id_credential'] == $id ? "checked" : "" ?>>
                            <label class="custom-control-label" for="attendant<?= $attendant['id'] ?>"><?= explode(' ', $attendant['name'])[0]; ?></label>
                          </div>
                        <?php endforeach; ?>
                      </div>
                      <span class="offset-sm-2 mb-4" style="margin-top: -12px;">(Caso o atendente esteja com 2 atendimentos ou Offline ele não aparecerá aqui.)</span>
                  </div>            
                  <div class="row">
                    <div class="col-sm-12 offset-sm-2">
                      <button id="submit-search-register" class="btn col-sm-10 btn-primary">BUSCAR / REGISTRAR!</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row mt-3">    
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0" style="padding: 0.65rem 0.75rem; font-size: 1rem;">Chats em atendimento</h5>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <div class="table-responsive" style="overflow-x: hidden;">
                      <table class="table table-striped last-tickets">
                      <thead>
                        <tr>
                          <th>Chat</th>    
                          <th>Atendente</th>
                          <th>Cliente</th>
                          <th>Iniciado ás</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $actual_date = $ticketController->getDay() . "/" . $ticketController->getMonth() . "/" . $ticketController->getYear();
                          foreach ($ticketController->getCustomersAtReception() as $key => $value):
                            $date_formated = date('d/m/Y', strtotime($value->chat_inicio));
                            if($date_formated == $actual_date && $value->chat_final == null): 
                              $chat_started = date('H:m:s', strtotime($value->chat_inicio));
                        ?>
                              <tr>
                                <td><a href="#" class="chat-code"><?= $value->cod_chat ?></a></td>
                                <td><?= ucfirst($value->chat_atendente) ?></td>
                                <td><?= ucfirst($value->cliente_nome) ?></td>
                                <td><?= $chat_started ?></td>
                              </tr>
                        <?php
                            endif;
                          endforeach;
                        ?>
                      </tbody>
                      </table>
                    </div>
                  </div>                  
                </div>
              </div>
            </div>

            <div class="col-lg-6">

              <div id="accordion">
                <div class="card">
                  <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                      <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Ligações Recentes
                      </button>
                    </h5>
                  </div>

                  <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                      <div class="table-responsive">
                        <table class="table table-striped last-tickets">
                        <thead>
                          <tr>
                            <th>ID</th>
                            <th>Status</th>      
                            <th>Módulo</th>
                            <th>Atendente</th>
                            <th>Registrado</th>
                          </tr>
                        </thead>
                        <tbody>
                        <?php if (!empty($calls)) : ?>
                          <?php foreach ($calls as $call) : ?>
                            <tr>
                              <td><?= $call['chat']; ?></td>
                              <td><?= ucfirst($call['t_status']); ?></td>
                              <td><?= $call['module']; ?></td>
                              <td><?= explode(' ', $call['name'])[0]; ?></td>
                              <td><?= date('d/m/Y H:i:s', strtotime($call['registered_at'])); ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else : ?>
                          <tr>
                            <td colspan="6">Nenhuma ligação registrada.</td>
                          </tr>
                        <?php endif; ?>
                        </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="../jquery-ui.js"></script>
    <script src="../../js/jquery.mask.js"></script>
    <script src="../js/find-ticket.js"></script>
    <script src="../js/front.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
  </body>
</html>