<?php 
include_once "../../utils/controller/ticket/ticket.ctrl.php";
$ticketController = TicketController::getInstance();
$ticketController->verifyPermission();

$ticketController->setIdChat($_GET["id_chat"]);
$connection = $ticketController->getConn();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Ticket</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="../../../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../../../dashboard/css/fontastic.css">
    <link rel="stylesheet" href="../../../dashboard/css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="../../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="../../../dashboard/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../../../dashboard/css/custom.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link type="text/css" href="../../../dashboard/css/jquery-ui.css" rel="stylesheet" />

    <link rel="shortcut icon" href="../../../brain_icon">
</head>

<body>
    <?php include("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
        <?php include("../navs/header.php"); ?>

        <?php
        $sql_id_chat = $connection->getConnection()->prepare("SELECT ticket.id, priority, t_status, ticket.source, type, ticket.t_group, id_attendant, resolution, id_who_closed, is_repeated, registry.name as registry, client.id as id_client, client.name as client, ticket_module.description as module, category_module.description as category, ticket.registered_at FROM chat, ticket, registry, client, ticket_module, category_module WHERE chat.id_chat = ? AND ticket.id_attendant = ? AND chat.id = ticket.id_chat AND ticket.id_registry = registry.id AND ticket.id_client = client.id AND ticket_module.id = ticket.id_module AND ticket_module.id_category = category_module.id");
        $sql_id_chat->execute(array($_GET["id_chat"], $_GET["id_attendant"]));
        $row_id_chat = $sql_id_chat->fetch();

        $sql_attendant = $connection->getConnection()->prepare("SELECT id, name FROM employee WHERE id = ?");
        if ($row_id_chat['id_attendant'] != null) {
            $sql_attendant->execute(array($row_id_chat['id_attendant']));
            $row_attendant = $sql_attendant->fetch();
        } else {
            $sql_attendant->execute(array($_GET['id_attendant']));
            $targetAttendant = $sql_attendant->fetch();
        }
        ?>

        <?php
        $ticketController->findAllCategoryModule();

        $sql_all_attendant = $connection->getConnection()->prepare("SELECT id, name FROM employee ORDER BY name");
        $sql_all_attendant->execute();
        ?>

        <section class="forms">
            <div class="container-fluid">
                <header>
                    <h1 class="h3 display">Ticket <?= $_GET['id_chat'] < 100000 ? 'da Ligação' : "do Chat" ?> #<?= $_GET["id_chat"] ?></h1>
                </header>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <?php if (!isset($_GET["id_chat"])) : ?>
                            <div class="card-header d-flex align-items-center">
                                <h2 class="h5 display">Novo ticket</h2>
                            </div>
                            <?php endif ?>
                            <div class="card-body">
                                <input type="hidden" id="target-attendant" value="<?= isset($targetAttendant) ? $targetAttendant['name'] : $row_attendant['name'] ?>">
                                <form id="ticket-form" class="form-horizontal" action="/controller/ticket/data" method="POST">
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Reincidente</label>
                                        <div class="col-sm-1 select ui-widget">
                                            <input type="checkbox" name="is_repeated" id="is_repeated" class="form-control repeated" <?= $row_id_chat['is_repeated'] == 1 ? "checked" : " " ?>>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 pt-2 form-control-label">Cartório</label>
                                        <div class="col-sm-4 select ui-widget">
                                            <input type="text" name="registry" id="registry" class="form-control" value=<?= '"' . $row_id_chat['registry'] . '"'; ?> required><span class="help-block-none">Informe o cartório do cliente.</span>
                                        </div>
                                        <label class="col-sm-2 pt-2 form-control-label text-center">Usuário</label>
                                        <div class="col-sm-4 select">
                                            <select name="client" class="form-control" id="client">
                                                <?php if ($row_id_chat != null) : ?>
                                                <option value=<?= '"' . $row_id_chat['id_client'] . '"'; ?>><?= $row_id_chat['client']; ?></option>
                                                <?php else : ?>
                                                <option>Primeiramente, informe o cartório...</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 pt-2 form-control-label">Prioridade</label>
                                        <div class="col-sm-3 select">
                                            <select name="priority" class="form-control" id="priority" required>
                                                <?php switch ($row_id_chat['priority']) {
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
                                                <?php switch ($row_id_chat['t_status']) {
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
                                                <?php if ($_GET['id_chat'] < 100000) {
                                                    echo "<option value='chat'>Chat</option>";
                                                    echo "<option value='telefone' selected>Telefone</option>";
                                                    echo "<option value='email'>Email</option>";
                                                } else {
                                                    switch ($row_id_chat['source']) {
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
                                                <?php switch ($row_id_chat['type']) {
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
                                            <input type="text" name="module" id="module" class="form-control" value="<?= $row_id_chat['category'] == null ? '' : $row_id_chat['category'] . '/' . $row_id_chat['module']; ?>" disabled>
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
                                                            <?php foreach ($ticketController->getCategoriesGroup1() as $key => $value) : ?>
                                                            <li id='<?= $value->category ?>'><?= $value->category ?>
                                                                <ul>
                                                                    <?php foreach ($ticketController->findDataOfCategoriesGroup1() as $key => $c_value) : ?>
                                                                    <?php if ($value->id == $c_value->id_category) : ?>
                                                                    <li id='<?= $value->category . '/' . $c_value->description ?>'><?= $c_value->description ?></li>
                                                                    <?php endif ?>
                                                                    <?php endforeach ?>
                                                                </ul>
                                                            </li>
                                                            <?php endforeach ?>
                                                        </ul>
                                                    </li>

                                                    <li>Grupo 2
                                                        <ul>
                                                            <?php foreach ($ticketController->getCategoriesGroup2() as $key => $value) : ?>
                                                            <li id='<?= $value->category ?>'><?= $value->category ?>
                                                                <ul>
                                                                    <?php foreach ($ticketController->findDataOfCategoriesGroup2() as $key => $c_value) : ?>
                                                                    <?php if ($value->id == $c_value->id_category) : ?>
                                                                    <li id='<?= $value->category . '/' . $c_value->description ?>'><?= $c_value->description ?></li>
                                                                    <?php endif ?>
                                                                    <?php endforeach ?>
                                                                </ul>
                                                            </li>
                                                            <?php endforeach ?>
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
                                                <?php switch ($row_id_chat['t_group']) {
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
                                                <?php if ($row_attendant != null) : ?>
                                                <option value=<?= '"' . $row_attendant['id'] . '"'; ?>><?= $row_attendant['name']; ?></option>
                                                <?php else : ?>
                                                <option value="">Selecione um atendente...</option>
                                                <?php endif; ?>
                                            </select>
                                            <?php if ($row_id_chat == null) : ?>
                                            <span id="span-attendant">Selecione o atendente <strong><?= $targetAttendant['name'] ?>.</strong></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Resolução</label>
                                        <div class="col-sm-10 select">
                                            <textarea class="form-control yourMessage" id="resolution" name="resolution" rows="8" placeholder="Escreva sua mensagem"><?= $row_id_chat['resolution']; ?></textarea>
                                        </div>
                                    </div>
                                    <?php $dateToGetChats = is_null($row_id_chat['registered_at']) ? date('Y/m/d') : $row_id_chat['registered_at'] ?>
                                    <?php $ticketController->getHistoryOfChat($dateToGetChats); ?>
                                    <?php if ($_GET['id_chat'] > 100000 && $row_id_chat['t_status'] != "aberto") : ?>
                                    <div class="row text-center">
                                        <a class="col-sm-4 offset-sm-4 btn btn-info text-center" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Histórico e Relatório do Chat
                                        </a>
                                    </div>
                                    <div class="collapse" id="collapseExample">
                                        <div class="card card-body mt-3">
                                            <div class="form-group row">
                                                <label class="col-sm-2 form-control-label">Histórico do chat</label>
                                                <div class="col-sm-10 select" style="height: 400px; overflow: auto;">

                                                    <?php foreach ($ticketController->getHistoryOfChat($dateToGetChats) as $key => $value) :
                                                        $date = strtotime($value->timestamp);

                                                        if (empty($ticketController->getOpenedAt())) {
                                                            $ticketController->setOpenedAt(date('H:i:s', $date));
                                                        }
                                                        ?>

                                                    <div style="display: table;">
                                                        <div style="float: left;">
                                                            <p style="font-size: 10px;"><?= date('H:i:s', $date); ?></p>
                                                        </div>
                                                        <?php if ($value->tipo == "M") : ?>
                                                        <?php
                                                        $type = "--- Sistema";
                                                        $termo = 'Chat transferido';
                                                        $termo2 = 'Atendido por';
                                                        $hasTransfer = "";

                                                        $pattern = '/' . $termo . '/'; //Padrão a ser encontrado
                                                        if (preg_match($pattern, $value->texto)) {
                                                            $ticketController->setTransferedAt(date('H:i:s', $date));
                                                        }

                                                        $pattern2 = '/' . $termo2 . '/'; //Padrão a ser encontrado
                                                        if (preg_match($pattern2, $value->texto)) {
                                                            $hasTransfer = date('H:i:s', $date);
                                                        }
                                                        ?>
                                                        <div style="float: left; margin-left: 8px;">
                                                            <p><?= "<b>" . $type . "</b>: " . $value->texto; ?></p>
                                                        </div>
                                                        <?php elseif ($value->tipo == "A") : ?>
                                                        <?php if ($hasTransfer != "") : ?>
                                                        <?php $type = ucfirst($ticketController->getAttendant()); ?>
                                                        <?php is_null($ticketController->getAfterTransferStartAt()) ? $ticketController->setAfterTransferStartAt(date('H:i:s', $date)) : "" ?>
                                                        <div style="float: left; margin-left: 8px;">
                                                            <p><?= "<b>" . $type . "</b>: " . $value->texto; ?></p>
                                                        </div>
                                                        <?php else : ?>
                                                        <div style="float: left; margin-left: 8px;">
                                                            <p><?= "<b>Camila</b>: " . $value->texto; ?></p>
                                                            <?php $ticketController->setLastMessageByReceptionAt(date('H:i:s', $date)) ?>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php else : $type = ucfirst($ticketController->getClient()); ?>
                                                        <div style="float: left; margin-left: 8px;">
                                                            <p><?= "<b>" . $type . "</b>: " . $value->texto; ?></p>
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
                                                    <p><b>IP:</b> <?= $ticketController->getIpClient(); ?> </p>
                                                    <p><b>Solicitou chat às:</b> <?= date('H:i:s', strtotime($ticketController->getStart())); ?> </p>
                                                    <p><b>Finalizado às:</b> <?= date('H:i:s', strtotime($ticketController->getFinal())); ?> </p>
                                                    <p><b>Nota de Atendimento:</b>
                                                        <?= empty($rating) ? 'Não votou' : $ticketController->getRating() ?>
                                                    </p>
                                                    <br>
                                                    <p><b>Tempo até ser atendido:</b>
                                                        <?php
                                                        $enterDate = new DateTime($ticketController->getStart(), new DateTimeZone('America/Sao_Paulo'));
                                                        $attendantDate = new DateTime($ticketController->getOpenedAt(), new DateTimeZone('America/Sao_Paulo'));

                                                        $totalTime = $attendantDate->diff($enterDate);
                                                        echo ($totalTime->h . " horas " . $totalTime->i . " minutos e " . $totalTime->s . " segundos");
                                                        ?>
                                                    </p>
                                                    <p><b>Duração do atendimento:</b>
                                                        <?php
                                                        $openedIn = new DateTime($ticketController->getOpenedAt(), new DateTimeZone('America/Sao_Paulo'));
                                                        $transferTime = new DateTime($ticketController->getLastMessageByReceptionAt(), new DateTimeZone('America/Sao_Paulo'));
                                                        $finalDate = new DateTime(date('H:i:s', strtotime($ticketController->getFinal())), new DateTimeZone('America/Sao_Paulo'));

                                                        if ($ticketController->getTransferedAt() != "") {
                                                            $totalTime = $transferTime->diff($openedIn);
                                                        } else {
                                                            $totalTime = $finalDate->diff($openedIn);
                                                        }
                                                        echo ($totalTime->h . " horas " . $totalTime->i . " minutos e " . $totalTime->s . " segundos");
                                                        ?>
                                                    </p>

                                                    <?php if ($ticketController->getTransferedAt() != "") : ?>
                                                    <p><b>Tempo de espera (Transferido - Atendido):</b>
                                                        <?php
                                                        $transferedTime = new DateTime($ticketController->getTransferedAt(), new DateTimeZone('America/Sao_Paulo'));
                                                        $afterTransfer = new DateTime($ticketController->getAfterTransferStartAt(), new DateTimeZone('America/Sao_Paulo'));

                                                        $totalTime = $afterTransfer->diff($transferedTime);
                                                        echo ($totalTime->h . " horas " . $totalTime->i . " minutos e " . $totalTime->s . " segundos");
                                                        ?>
                                                    </p>
                                                    <p><b>Duração do suporte:</b>
                                                        <?php
                                                        $initialSupport = new DateTime($ticketController->getAfterTransferStartAt(), new DateTimeZone('America/Sao_Paulo'));
                                                        $finalSupport = new DateTime(date('H:i:s', strtotime($ticketController->getFinal())), new DateTimeZone('America/Sao_Paulo'));

                                                        $totalTime = $finalSupport->diff($initialSupport);
                                                        echo ($totalTime->h . " horas " . $totalTime->i . " minutos e " . $totalTime->s . " segundos");
                                                        ?>
                                                    </p>
                                                    <?php endif; ?>

                                                    <p><b>Duração total do chat:</b>
                                                        <?php 
                                                        $startDate = new DateTime($ticketController->getStart(), new DateTimeZone('America/Sao_Paulo'));
                                                        $finalDate = new DateTime($ticketController->getFinal(), new DateTimeZone('America/Sao_Paulo'));

                                                        $totalTime = $finalDate->diff($startDate);

                                                        $totalTimeInMinutes = ($totalTime->h * 60) + $totalTime->i;

                                                        echo ($totalTime->h . " horas " . $totalTime->i . " minutos e " . $totalTime->s . " segundos");
                                                        ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <?php endif; ?>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-12">
                                            <input type="hidden" name="id_chat" value="<?php echo $_GET['id_chat']; ?>">
                                            <input type="hidden" name="opening_time" value="<?php echo $ticketController->getStart() == "" ? "0000-00-00 00:00:00" : $ticketController->getStart() ?>">
                                            <input type="hidden" name="final_time" value="<?php echo $ticketController->getFinal() == "" ? "0000-00-00 00:00:00" : $ticketController->getFinal() ?>">
                                            <input type="hidden" name="duration_in_minutes" value="<?php echo $totalTimeInMinutes < 1 ? "1" : $totalTimeInMinutes ?>">
                                            <input type='hidden' name='selected_category' value="<?php echo !is_null($row_id_chat['category']) ? $row_id_chat['category'] : "" ?>">
                                            <input type='hidden' name='selected_module' value="<?php echo !is_null($row_id_chat['module']) ? $row_id_chat['module'] : "" ?>">

                                            <?php
                                            echo '<button type="reset" class="btn btn-secondary">Limpar</button>';

                                            if (@$row_attendant['name'] != null) {
                                                echo '<button type="submit" name="submit" class="btn btn-primary btnAction">Salvar Alterações!</button>';
                                                if (!($row_id_chat['id_who_closed'] != null)) {
                                                    echo '<button type="submit" name="finishTicket" class="btn btn-danger btnAction">Finalizar Ticket!</button>';
                                                }
                                            } else {
                                                echo '<button type="submit" name="submit" class="btn btn-primary btnAction">Cadastrar!</button>';
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
    <script src="../../../dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="/script/header"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script>
        $(function() {
            $("#jstree").jstree({
                "plugins": ["search", "wholerow", "sort"]
            });

            var to = false;
            $('#filter-tree').keyup(function() {
                if (to) {
                    clearTimeout(to);
                }
                to = setTimeout(function() {
                    var v = $('#filter-tree').val();
                    $('#jstree').jstree(true).search(v);
                }, 250);
            });

            $('#jstree').on("changed.jstree", function(e, data) {
                var treeData = data.selected[0];
                var selectedModuleSplit = treeData.split("/");

                $('[name="selected_category"]').val(selectedModuleSplit[0]);
                $('[name="selected_module"]').val(selectedModuleSplit[1]);
                $('[name="module"]').val(treeData);
            });
        });
    </script>
    <script src="../../../dashboard/jquery-ui.js"></script>
    <script src="../../../js/jquery.mask.js"></script>
    <script src="../../../dashboard/js/front.js"></script>
    <script src="../../../dashboard/js/ticket.js"></script>
    <script src="../../../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../../../dashboard/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../../../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <script src="../../../dashboard/vendor/marketing/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('resolution');
    </script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
</body>

</html> 