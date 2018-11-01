<?php 
  session_start();
  if (!isset($_SESSION['ticket_page_'.$_SESSION['login']])) {
    header("Location:../dashboard");
  }
?>

<?php 
  include_once "../../utils/api-chat/api.php"; 
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Ticket Detalhado</title>
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

    <!--link href="http://www.easyjstree.com/content/skin-win8/ui.easytree.css" rel="stylesheet" class="skins" type="text/css" /-->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />

    <link type="text/css" href="../css/jquery-ui.css" rel="stylesheet"/>

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
        $sql_id_chat = $connection->getConnection()->prepare("SELECT id FROM chat WHERE id_chat = ?");
        $sql_id_chat->execute(array($_GET["id_chat"])); $row_id_chat = $sql_id_chat->fetch();

        $sql_ticket = $connection->getConnection()->prepare("SELECT id, id_registry, id_client, priority, t_status, source, type, t_group, id_module, id_attendant, resolution, id_who_closed, is_repeated FROM ticket WHERE id_chat = ?");
        $sql_ticket->execute(array($row_id_chat['id'])); $row_ticket = $sql_ticket->fetch();

        $sql_registry = $connection->getConnection()->prepare("SELECT id, name FROM registry WHERE id = ?");
        $sql_registry->execute(array($row_ticket['id_registry'])); $row_registry = $sql_registry->fetch();

        $sql_client = $connection->getConnection()->prepare("SELECT id, name FROM client WHERE id = ?");
        $sql_client->execute(array($row_ticket['id_client'])); $row_client = $sql_client->fetch();

        $sql_m_ticket = $connection->getConnection()->prepare("SELECT ticket_module.description as module, category_module.description as category FROM ticket_module, category_module WHERE ticket_module.id = ? AND ticket_module.id_category = category_module.id");
        $sql_m_ticket->execute(array($row_ticket['id_module'])); $row_m_ticket = $sql_m_ticket->fetch();

        $sql_attendant = $connection->getConnection()->prepare("SELECT id, name FROM employee WHERE id = ?");
        $sql_attendant->execute(array($row_ticket['id_attendant'])); $row_attendant = $sql_attendant->fetch();
      ?>

      <?php 
        //NÍVEL 1
        $sql_category_module_nivel1 = $connection->getConnection()->prepare("SELECT id, description as category FROM 
          category_module WHERE t_group = ? ORDER BY description");
        $sql_category_module_nivel1->execute(array("nivel1"));
        $row_category_module_nivel1 = $sql_category_module_nivel1->fetchAll();

        $json_str_nivel1 = json_encode($row_category_module_nivel1);
        $json_obj_nivel1 = json_decode($json_str_nivel1);

        //NÍVEL 2
        $sql_category_module_nivel2 = $connection->getConnection()->prepare("SELECT id, description as category FROM 
          category_module WHERE t_group = ? ORDER BY description");
        $sql_category_module_nivel2->execute(array("nivel2"));
        $row_category_module_nivel2 = $sql_category_module_nivel2->fetchAll();

        $json_str_nivel2 = json_encode($row_category_module_nivel2);
        $json_obj_nivel2 = json_decode($json_str_nivel2);

        $sql_all_attendant = $connection->getConnection()->prepare("SELECT id, name FROM employee ORDER BY name"); 
        $sql_all_attendant->execute();
      ?>

      <section class="forms">
        <div class="container-fluid">
          <header>
            <?php if (!isset($_GET["id_chat"])) { ?>   
              <h1 class="h3 display">Cadastro de Tickets</h1>
            <?php } else { ?>
              <h1 class="h3 display">Ticket do chat #<?= $_GET["id_chat"]?></h1>
            <?php } ?>
          </header>
          <div class="row">    
            <div class="col-lg-12">
              <div class="card">
                <?php if (!isset($_GET["id_chat"])) { ?>
                  <div class="card-header d-flex align-items-center">
                    <h2 class="h5 display">Novo ticket</h2>
                  </div>
                <?php } ?>
                <div class="card-body">
                  <form id="ticket-form" class="form-horizontal" action="../../utils/controller/ctrl_ticket.php" method="POST">
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Reincidente</label>
                      <div class="col-sm-1 select ui-widget">
                        <input type="checkbox" name="is_repeated" id="is_repeated" class="form-control repeated" <?php echo $row_ticket['is_repeated'] == 1 ? "checked" : " "?>>
                      </div>
                    </div> 
                    <div class="form-group row">
                      <label class="col-sm-2 pt-2 form-control-label">Cartório</label>
                      <div class="col-sm-4 select ui-widget">
                        <input type="text" name="registry" id="registry" class="form-control" value= <?php echo '"'.$row_registry['name'].'"'; ?> required><span class="help-block-none">Informe o cartório do cliente.</span>
                      </div>
                      <label class="col-sm-2 pt-2 form-control-label text-center">Usuário</label>
                      <div class="col-sm-4 select">
                        <select name="client" class="form-control" id="client">
                          <?php if ($row_client != NULL): ?>
                            <option value=<?php echo '"'.$row_client['id'].'"';?>><?php echo $row_client['name'];?></option>
                          <?php else: ?>
                            <option >Primeiramente, informe o cartório...</option>
                          <?php endif; ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 pt-2 form-control-label">Prioridade</label>
                      <div class="col-sm-3 select">
                        <select name="priority" class="form-control" id="priority" required>
                          <?php switch($row_ticket['priority']){
                            case "baixa":
                              echo "<option value='baixa' selected>Baixa</option>";
                              echo "<option value='media'>Média</option>";
                              echo "<option value='alta'>Alta</option>";
                              echo "<option value='urgente'>Urgente</option>";
                              break;
                            case "media":
                              echo "<option value='baixa'>Baixa</option>";
                              echo "<option value='media' selected>Média</option>";
                              echo "<option value='alta'>Alta</option>";
                              echo "<option value='urgente'>Urgente</option>";
                              break;
                            case "alta":
                              echo "<option value='baixa'>Baixa</option>";
                              echo "<option value='media'>Média</option>";
                              echo "<option value='alta' selected>Alta</option>";
                              echo "<option value='urgente'>Urgente</option>";
                              break;
                            case "urgente":
                              echo "<option value='baixa'>Baixa</option>";
                              echo "<option value='media'>Média</option>";
                              echo "<option value='alta'>Alta</option>";
                              echo "<option value='urgente' selected>Urgente</option>";
                              break;
                            default:
                              echo "<option value='baixa'>Baixa</option>";
                              echo "<option value='media'>Média</option>";
                              echo "<option value='alta'>Alta</option>";
                              echo "<option value='urgente'>Urgente</option>";
                            }
                          ?>       
                        </select>
                      </div>
                      <label class="col-sm-3 pt-2 form-control-label text-center">Status</label>
                      <div class="col-sm-4 select">
                        <select name="status" class="form-control" id="status" required>
                          <?php switch($row_ticket['t_status']){
                            case "aberto":
                              echo "<option value='aberto' selected>Aberto</option>";
                              echo "<option value='pendente'>Pendente</option>";
                              echo "<option value='solucionado'>Solucionado</option>";
                              echo "<option value='fechado'>Fechado</option>";
                              break;
                            case "pendente":
                              echo "<option value='aberto'>Aberto</option>";
                              echo "<option value='pendente' selected>Pendente</option>";
                              echo "<option value='solucionado'>Solucionado</option>";
                              echo "<option value='fechado'>Fechado</option>";
                              break;
                            case "solucionado":
                              echo "<option value='aberto'>Aberto</option>";
                              echo "<option value='pendente'>Pendente</option>";
                              echo "<option value='solucionado' selected>Solucionado</option>";
                              echo "<option value='fechado'>Fechado</option>";
                              break;
                            case "fechado":
                              echo "<option value='aberto'>Aberto</option>";
                              echo "<option value='pendente'>Pendente</option>";
                              echo "<option value='solucionado'>Solucionado</option>";
                              echo "<option value='fechado' selected>Fechado</option>";
                              break;
                            default:
                              echo "<option value='aberto'>Aberto</option>";
                              echo "<option value='pendente'>Pendente</option>";
                              echo "<option value='solucionado'>Solucionado</option>";
                              echo "<option value='fechado'>Fechado</option>";
                            }
                          ?>                          
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 pt-2 form-control-label">Origem</label>
                      <div class="col-sm-3 select">
                        <select name="source" class="form-control" id="source" required>
                          <?php if($_GET['id_chat'] < 100000){
                              echo "<option value='chat'>Chat</option>";
                              echo "<option value='telefone' selected>Telefone</option>";
                              echo "<option value='email'>Email</option>";
                            } else {
                              switch($row_ticket['source']){
                                case "chat":
                                  echo "<option value='chat' selected>Chat</option>";
                                  echo "<option value='telefone'>Telefone</option>";
                                  echo "<option value='email'>Email</option>";
                                  break;
                                case "telefone":
                                  echo "<option value='chat'>Chat</option>";
                                  echo "<option value='telefone' selected>Telefone</option>";
                                  echo "<option value='email'>Email</option>";
                                  break;
                                case "email":
                                  echo "<option value='chat'>Chat</option>";
                                  echo "<option value='telefone'>Telefone</option>";
                                  echo "<option value='email' selected>Email</option>";
                                  break;
                                default:
                                  echo "<option value='chat'>Chat</option>";
                                  echo "<option value='telefone'>Telefone</option>";
                                  echo "<option value='email'>Email</option>";
                              }
                            }
                          ?>       
                        </select>
                      </div>
                      <label class="col-sm-3 pt-2 form-control-label text-center">Tipo</label>
                      <div class="col-sm-4 select">
                        <select name="type" class="form-control" id="type" required>
                          <?php switch($row_ticket['type']){
                            case "solicitacao":
                              echo "<option value='solicitacao' selected>Solicitação</option>";
                              echo "<option value='duvida'>Dúvida</option>";
                              echo "<option value='configuracao'>Configuração</option>";
                              echo "<option value='problema'>Problema</option>";
                              echo "<option value='novaFuncionalidade'>Nova Funcionalidade</option>";
                              echo "<option value='reclamacao'>Reclamação</option>";
                              break;
                            case "duvida":
                              echo "<option value='solicitacao'>Solicitação</option>";
                              echo "<option value='duvida' selected>Dúvida</option>";
                              echo "<option value='configuracao'>Configuração</option>";
                              echo "<option value='problema'>Problema</option>";
                              echo "<option value='novaFuncionalidade'>Nova Funcionalidade</option>";
                              echo "<option value='reclamacao'>Reclamação</option>";
                              break;
                            case "configuracao":
                              echo "<option value='solicitacao'>Solicitação</option>";
                              echo "<option value='duvida'>Dúvida</option>";
                              echo "<option value='configuracao' selected>Configuração</option>";
                              echo "<option value='problema'>Problema</option>";
                              echo "<option value='novaFuncionalidade'>Nova Funcionalidade</option>";
                              echo "<option value='reclamacao'>Reclamação</option>";
                              break;
                            case "problema":
                              echo "<option value='solicitacao'>Solicitação</option>";
                              echo "<option value='duvida'>Dúvida</option>";
                              echo "<option value='configuracao'>Configuração</option>";
                              echo "<option value='problema' selected>Problema</option>";
                              echo "<option value='novaFuncionalidade'>Nova Funcionalidade</option>";
                              echo "<option value='reclamacao'>Reclamação</option>";
                              break;
                            case "novaFuncionalidade":
                              echo "<option value='solicitacao'>Solicitação</option>";
                              echo "<option value='duvida'>Dúvida</option>";
                              echo "<option value='configuracao'>Configuração</option>";
                              echo "<option value='problema'>Problema</option>";
                              echo "<option value='novaFuncionalidade' selected>Nova Funcionalidade</option>";
                              echo "<option value='reclamacao'>Reclamação</option>";
                              break;
                            case "reclamacao":
                              echo "<option value='solicitacao'>Solicitação</option>";
                              echo "<option value='duvida'>Dúvida</option>";
                              echo "<option value='configuracao'>Configuração</option>";
                              echo "<option value='problema'>Problema</option>";
                              echo "<option value='novaFuncionalidade'>Nova Funcionalidade</option>";
                              echo "<option value='reclamacao' selected>Reclamação</option>";
                              break;
                            default:
                              echo "<option value='solicitacao'>Solicitação</option>";
                              echo "<option value='duvida'>Dúvida</option>";
                              echo "<option value='configuracao'>Configuração</option>";
                              echo "<option value='problema'>Problema</option>";
                              echo "<option value='novaFuncionalidade'>Nova Funcionalidade</option>";
                              echo "<option value='reclamacao'>Reclamação</option>";
                            }
                          ?>   
                        </select>
                      </div>
                    </div>
                    <div class="form-group row">
                      
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 pt-2 form-control-label">Módulo</label>
                      <div class="col-sm-3 select">
                         <input type="text" name="module" id="module" class="form-control" value="<?= $row_m_ticket['category'] == NULL ? "" : $row_m_ticket['category']."/".$row_m_ticket['module']; ?>" disabled>
                      </div>
                      <label class="col-sm-3 pt-2 form-control-label text-center">Filtro</label>
                      <div class="col-sm-4 select">
                        <input type="text" id="filter-tree" value="" class="form-control">
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-5">
                        <div id="jstree">
                          <ul>
                            <li>Grupo 1
                              <ul>
                                <?php foreach ($json_obj_nivel1 as $key => $value){ ?>
                                  <li id='<?= $value->category ?>'><?= $value->category ?>
                                     <ul>
                                      <?php 
                                        $sql_children = $connection->getConnection()->prepare("SELECT description FROM ticket_module 
                                          WHERE status = ? AND id_category = ?"); 
                                        $sql_children->execute(array("ativo", $value->id)); 
                                        $row_children = $sql_children->fetchAll();
                                    
                                        $json_children = json_encode($row_children);
                                        $json_dec_children = json_decode($json_children);

                                        foreach ($json_dec_children as $key => $c_value) {
                                          echo "<li id='$value->category/$c_value->description'>$c_value->description</li>";
                                        }
                                      ?>
                                    </ul>
                                  </li>
                                <?php } ?>
                              </ul>
                            </li>

                            <li>Grupo 2
                              <ul>
                                <?php foreach ($json_obj_nivel2 as $key => $value){ ?>
                                  <li id='<?= $value->category ?>'><?= $value->category ?>
                                    <ul>
                                      <?php 
                                        $sql_children = $connection->getConnection()->prepare("SELECT description FROM ticket_module
                                          WHERE status = ? AND id_category = ?"); 
                                        $sql_children->execute(array("ativo", $value->id)); 
                                        $row_children = $sql_children->fetchAll();
                                    
                                        $json_children = json_encode($row_children);
                                        $json_dec_children = json_decode($json_children);

                                        foreach ($json_dec_children as $key => $c_value) {
                                          echo "<li id='$value->category/$c_value->description'>$c_value->description</li>";
                                        }
                                      ?>
                                    </ul>
                                  </li>
                                <?php } ?>
                              </ul>
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="col-sm-2 pt-2 form-control-label">Grupo</label>
                      <div class="col-sm-3 select">
                        <select name="group" class="form-control" id="group" required>
                          <?php switch($row_ticket['t_group']){
                            case "nivel1":
                              echo "<option value='nivel1' selected>Nível 1</option>";
                              echo "<option value='nivel2'>Nível 2</option>";
                              break;
                            case "nivel2":
                              echo "<option value='nivel1'>Nível 1</option>";
                              echo "<option value='nivel2' selected>Nível 2</option>";
                              break;
                            default:
                              echo "<option value=''>Selecione um grupo...</option>";
                              echo "<option value='nivel1'>Nível 1</option>";
                              echo "<option value='nivel2'>Nível 2</option>";
                            }
                          ?> 
                        </select>
                      </div>
                      <label class="col-sm-3 pt-2 form-control-label text-center">Atendente</label>
                      <div class="col-sm-4 select">
                        <select name="attendant" class="form-control" id="attendant">
                          <?php if ($row_attendant != NULL): ?>
                            <option value=<?php echo '"'.$row_attendant['id'].'"';?>><?php echo $row_attendant['name'];?></option>
                          <?php else: ?>
                            <option value="">Selecione um atendente...</option>
                          <?php endif; ?>

                          <!--?php while($row = $sql_all_attendant->fetch()) { ?>
                            <option value="<?php //echo $row['id'] ?>"><?php //echo $row['name'] ?></option>
                          <?php //} ?><-->
                        </select>
                      </div>
                    </div>

                    <div class="form-group row">
                      
                    </div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Resolução</label>
                      <div class="col-sm-10 select">
                        <textarea class="form-control yourMessage" id="resolution" name="resolution" rows="8" placeholder="Escreva sua mensagem"><?php echo $row_ticket['resolution'];?></textarea>
                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Histórico do chat</label>
                      <div class="col-sm-10 select" style="height: 400px; overflow: auto;">

                        <?php foreach ($data as $key => $value): $date = strtotime($value->timestamp); 

                          if (empty($opening_time)){$opening_time = date('H:i:s', $date);};?>

                          <div style="display: table;">
                            <div style="float: left;">
                              <p style="font-size: 10px;"><?php echo date('H:i:s', $date); ?></p>
                            </div>
                            <?php if ($value->tipo == "M"): $type = "--- Sistema";?>
                              <?php
                                $termo = 'transferido';
                                $termo2 = 'Atendido por';

                                $pattern = '/' . $termo . '/';//Padrão a ser encontrado
                                if (preg_match($pattern, $value->texto)) {
                                  $transfer_time = date('H:i:s', $date);
                                }

                                $pattern2 = '/' . $termo2 . '/';//Padrão a ser encontrado
                                if (preg_match($pattern2, $value->texto)) {
                                  $attendant_time_after_transfer = date('H:i:s', $date);
                                }
                              ?>
                              <div style="float: left; margin-left: 8px;">
                                <p><?php echo "<b>".$type."</b>: ".$value->texto; ?></p>
                              </div>
                            <?php elseif ($value->tipo == "A"): $type = ucfirst($attendant);?>
                              <div style="float: left; margin-left: 8px;">
                                <p><?php echo "<b>".$type."</b>: ".$value->texto; ?></p>
                              </div>
                            <?php else: $type = ucfirst($client);?>
                              <div style="float: left; margin-left: 8px;">
                                <p><?php echo "<b>".$type."</b>: ".$value->texto; ?></p>
                              </div>
                            <?php endif; ?>
                          </div>

                        <?php endforeach; ?>

                      </div>
                    </div>
                    <div class="line"></div>
                    <div class="form-group row">
                      <label class="col-sm-2 form-control-label">Relatório do chat</label>
                      <div class="col-sm-10 select">
                        <p><b>IP:</b> <?php echo $client_ip; ?> </p>
                        <p><b>Solicitou chat às:</b> <?php echo date('H:i:s', strtotime($start)); ?> </p>
                        <p><b>Finalizado às:</b> <?php echo date('H:i:s', strtotime($final)); ?> </p>
                        <p><b>Nota de Atendimento:</b> 
                          <?php if(empty($rating)){echo "Não votou";} else {echo $rating;} ?> 
                        </p>
                        <br>
                        <p><b>Tempo até ser atendido:</b> 
                          <?php
                            $enterDate = new DateTime($start, new DateTimeZone( 'America/Sao_Paulo'));
                            $attendantDate = new DateTime($opening_time, new DateTimeZone( 'America/Sao_Paulo')); 

                            $totalTime = $attendantDate->diff($enterDate);
                            echo ($totalTime->h." horas ".$totalTime->i." minutos e ".$totalTime->s." segundos"); 
                          ?> 
                        </p>
                        <p><b>Duração do atendimento:</b> 
                          <?php
                            $openedIn = new DateTime($opening_time, new DateTimeZone( 'America/Sao_Paulo')); 
                            $transferTime = new DateTime($transfer_time, new DateTimeZone( 'America/Sao_Paulo'));
                            $finalDate = new DateTime(date('H:i:s', strtotime($final)), new DateTimeZone( 'America/Sao_Paulo'));

                            if ($transfer_time != ""){
                              $totalTime = $transferTime->diff($openedIn);
                            } else {
                              $totalTime = $finalDate->diff($openedIn);
                            }
                            echo ($totalTime->h." horas ".$totalTime->i." minutos e ".$totalTime->s." segundos"); 
                          ?> 
                        </p>

                        <?php if ($transfer_time != ""): ?>
                          
                          <p><b>Tempo de espera (Transferido - Atendido):</b> 
                            <?php
                              $transferedTime = new DateTime($transfer_time, new DateTimeZone( 'America/Sao_Paulo'));
                              $afterTransfer = new DateTime($attendant_time_after_transfer, new DateTimeZone( 'America/Sao_Paulo')); 

                              $totalTime = $afterTransfer->diff($transferedTime);
                              echo ($totalTime->h." horas ".$totalTime->i." minutos e ".$totalTime->s." segundos");
                            ?> 
                          </p>
                          <p><b>Duração do suporte:</b> 
                            <?php
                              $initialSupport = new DateTime($attendant_time_after_transfer, new DateTimeZone( 'America/Sao_Paulo')); 
                              $finalSupport = new DateTime(date('H:i:s', strtotime($final)), new DateTimeZone( 'America/Sao_Paulo'));

                              $totalTime = $finalSupport->diff($initialSupport);
                              echo ($totalTime->h." horas ".$totalTime->i." minutos e ".$totalTime->s." segundos"); 
                            ?> 
                          </p>
                        
                        <?php endif; ?>

                        <p><b>Duração total do chat:</b> 
                          <?php 
                            $startDate = new DateTime($start, new DateTimeZone('America/Sao_Paulo'));
                            $finalDate = new DateTime($final, new DateTimeZone('America/Sao_Paulo')); 

                            $totalTime = $finalDate->diff($startDate);

                            $totalTimeInMinutes = ($totalTime->h * 60) + $totalTime->i;

                            echo ($totalTime->h." horas ".$totalTime->i." minutos e ".$totalTime->s." segundos");
                          ?> 
                        </p>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-12">
                        <input type="hidden" name="id_chat" value="<?php echo $_GET['id_chat']; ?>">
                        <input type="hidden" name="opening_time" value="<?php echo $start == "" ? "0000-00-00 00:00:00" : $start ?>">
                        <input type="hidden" name="final_time" value="<?php echo $final == "" ? "0000-00-00 00:00:00" : $final ?>">
                        <input type="hidden" name="duration_in_minutes" value="<?php if($totalTimeInMinutes < 1){echo 1;} else{echo $totalTimeInMinutes;} ?>">
                        <input type='hidden' name='selected_category' value="<?php if(!is_null($row_m_ticket['category'])){echo $row_m_ticket['category'];} ?>">
                        <input type='hidden' name='selected_module' value="<?php if(!is_null($row_m_ticket['module'])){echo $row_m_ticket['module'];} ?>">

                        <?php
                          if($client_ip != NULL || $_GET['id_chat'] < 100000) {
                            echo '<button type="reset" class="btn btn-secondary">Limpar</button>';

                            if($row_attendant['name'] != NULL){
                              echo '<button type="submit" name="submit" class="btn btn-primary btnAction">Salvar Alterações!</button>';
                              if(!($row_ticket['id_who_closed'] != NULL)){
                                echo '<button type="submit" name="finishTicket" class="btn btn-danger btnAction">Finalizar Ticket!</button>';
                              }
                            } else{
                              echo '<button type="submit" name="submit" class="btn btn-primary btnAction">Cadastrar!</button>';
                            }
                          } else{
                            echo '<span id="wrongIdChat"><strong>Erro!</strong> Número do chat informado não existe. Contate o Administrador ou informe outro chat.</span>';
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="../js/jquery-3.2.1.min.js"></script>
    <!--script src="http://www.easyjstree.com/bundles/jquery?v=5r0dFjH__tJcUIAQyQUG4tMptq0H5PoqgaCRzuzpfIs1"></script>
    <script src="http://www.easyjstree.com/Scripts/jquery.easytree.min.js"></script-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script>
      $(function(){
        $("#jstree").jstree({
          "plugins" : [ "search", "wholerow", "sort" ]
        });

        var to = false;
        $('#filter-tree').keyup(function(){
          if(to){clearTimeout(to);}
          to = setTimeout(function(){
            var v = $('#filter-tree').val();
            $('#jstree').jstree(true).search(v);
          }, 250);
        });

        $('#jstree').on("changed.jstree", function (e, data) {
          var treeData = data.selected[0];
          var selectedModuleSplit = treeData.split("/");

          $('[name="selected_category"]').val(selectedModuleSplit[0]);
          $('[name="selected_module"]').val(selectedModuleSplit[1]);
          $('[name="module"]').val(treeData);
        });
      });
    </script>
    <script src="../jquery-ui.js"></script>
    <script src="../../js/jquery.mask.js"></script>
    <script src="../js/front.js"></script>
    <script src="../js/ticket.js"></script>
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