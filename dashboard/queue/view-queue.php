<?php 
date_default_timezone_set('America/Sao_Paulo');

include_once __DIR__ . '/../../utils/controller/ctrl-queue.php';
$queueController = QueueController::getInstance();
$queueController->verifyPermission();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv='refresh' content='60;url=fila-interna'>
    <title>Brainsoft Sistemas - Fila Interna</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="./vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./vendor/font-awesome/all.css">
    <link rel="stylesheet" href="./css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="./css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="./vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="./css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="./css/custom.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <link rel="shortcut icon" href="../../brain_icon">

    <script type="text/javascript">
        var contador = '60';

        function startTimer(duration, display) {
            var timer = duration,
                minutes, seconds;
            setInterval(function() {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                --timer;
            }, 1000);
        }

        window.onload = function() {
            var count = parseInt(contador),
                display = document.querySelector('#time');
            startTimer(count, display);
        };
    </script>
</head>

<body>
    <?php include("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
        <?php include("../navs/header.php"); ?>
        <?php
        $queueController->openChats();

        $queueController->attendantsAbleOnGroup1();
        $queueController->makeQueueToGroup1();

        $queueController->attendantsAbleOnGroup2();
        $queueController->makeQueueToGroup2();

        $queueGroup1 = $queueController->getOrderedQueue(1);
        $queueGroup2 = $queueController->getOrderedQueue(2);
        ?>
        <section class="forms">
            <div class="container-fluid">
                <header>
                    <div class="row">
                        <div class="col-sm-6 title">
                            <h1 class="h3 display">Fila Interna | <span>Atualização automática em <span id="time">...</span></span></h1>
                        </div>
                    </div>
                </header>
                <hr>
                <div id="conteudo">
                    <iframe class="iframe-queue" width='300px' height='23px' frameborder='0' src='/dashboard/attendance/status.php' SCROLLING="NO"></iframe>
                    <hr>
                    <h1>Disponibilidade Grupo 1</h1>
                    <div class="row" id="internal-row">
                        <?php if ($queueGroup1 != null) : ?>
                        <?php $placeInLine1 = 1; ?>
                        <table align="center">
                            <tr>
                                <?php for ($i = 0; $i < $queueController->getCountGroupOne(); $i++) {
                                    echo "<th class='place_in_line'>" . $placeInLine1 . "º </th>";
                                    $placeInLine1++;
                                } ?>
                            </tr>
                            <tr>
                                <?php foreach ($queueGroup1 as $newQueue) : ?>
                                <td class="colum_of_place">
                                    <div class="card mb-3 user<?= $newQueue ?>" style="max-width: 18rem; float: left; margin-left: 3%;">
                                        <div class="card-header"><?= $queueController->getGroupOne()[$newQueue]; ?></div>
                                        <div class="card-body nivel1">
                                            <?php foreach ($queueController->getAllOpenChats() as $chat) : ?>
                                            <?php if ($chat['t_group'] == "nivel1") : ?>
                                            <?php if ($chat['id'] == $newQueue) : ?>
                                            <div>
                                                <?php $minutos = $queueController->progressBar($chat['registered_at']); ?>

                                                <button class="btn btn-secondary filha" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<div id='popover_content_wrapper'>
																   	<p><strong>Ticket: </strong><?= $chat['id_chat'] ?></p>
																   	<p>
                                                                        <strong>Inicio: </strong><?= date('d/m/Y H:i:s', strtotime($chat['registered_at'])) ?><br>
                                                                        <strong>Cliente: </strong><?= $queueController->findClientOfTicketById($chat['client']) ?> do <?= $queueController->findRegistryOfTicketById($chat['registry']) ?><br>
                                                                        <strong>Fonte: </strong><?= ucfirst($chat['source']) ?><br>
                                                                        <strong>Módulo: </strong><?= $queueController->findModuleOfTicketById($chat['id_module']) ?>
                                                                    </p>
																   	<button id='btn-modal' class='btn btn-primary' value='<?= $chat['id_chat'] ?>' onClick='redirectToTicket(this.value, <?= $newQueue ?>)'>Visualizar Ticket</button></div>"><?= $chat['id_chat'] ?></button>

                                                <a href="#"></a>
                                                <input type="hidden" name="startedTime<?= $rand ?>" value="<?= $chat['registered_at'] ?>">

                                                <?php $time = $queueController->limitTimeToFinish($chat['id_module']); ?>

                                                <div class="progress" title="<?= (int)$minutos ?> <?= (int)$minutos > 1 ? 'minutos' : 'minuto' ?> de <?= $time[0]['limit_time'] ?>">
                                                    <progress id="pg" value="<?= (int)$minutos ?>" max="<?= $time[0]['limit_time'] ?>"></progress>
                                                </div>
                                            </div>
                                            <?php endif ?>
                                            <?php endif ?>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </td>
                                <?php $placeInLine1++; ?>
                                <?php endforeach ?>
                            </tr>
                        </table>
                        <?php else : ?>
                        <div class="col-md-12">
                            <span>Atenção! Fila não iniciada (Atendentes Off ou nenhum ticket atribuído).</span>
                        </div>
                        <?php endif ?>
                    </div>
                    <hr>
                    <h1>Disponibilidade Grupo 2</h1>
                    <div class="row" id="internal-row">
                        <?php if ($queueGroup2 != null) : ?>
                        <?php $placeInLine2 = 1; ?>
                        <table align="center">
                            <tr>
                                <?php for ($i = 0; $i < $queueController->getCountGroupTwo(); $i++) {
                                    echo '<th class="place_in_line">' . $placeInLine2 . 'º </th>';
                                    $placeInLine2++;
                                } ?>
                            </tr>
                            <tr>
                                <?php foreach ($queueGroup2 as $newQueue) : ?>
                                <td class="colum_of_place">
                                    <div class="card mb-3 user<?= $newQueue ?>" style="max-width: 18rem; float: left; margin-left: 3%;">
                                        <div class="card-header"><?= $queueController->getGroupTwo()[$newQueue]; ?></div>
                                        <div class="card-body nivel2">
                                            <?php foreach ($queueController->getAllOpenChats() as $chat) : ?>
                                            <?php if ($chat['t_group'] == "nivel2") : ?>
                                            <?php if ($chat['id'] == $newQueue) : ?>
                                            <div>
                                                <?php $minutos = $queueController->progressBar($chat['registered_at']); ?>

                                                <button class="btn btn-secondary filha" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true" data-content="<div id='popover_content_wrapper'>
														<p><strong>Chat / Ticket: </strong><?= $chat['id_chat'] ?></p>
											   			<p>
                                                            <strong>Inicio do chat: </strong><?= date('d/m/Y H:i:s', strtotime($chat['registered_at'])) ?><br>
                                                            <strong>Cliente: </strong><?= $queueController->findClientOfTicketById($chat['client']) ?> do <?= $queueController->findRegistryOfTicketById($chat['registry']) ?><br>
                                                            <strong>Fonte: </strong><?= ucfirst($chat['source']) ?><br>
                                                            <strong>Módulo: </strong><?= $queueController->findModuleOfTicketById($chat['id_module']) ?>
                                                        </p>
											   			<button id='btn-modal' class='btn btn-primary' value='<?= $chat['id_chat'] ?>' onClick='redirectToTicket(this.value, <?= $newQueue ?>)'>Visualizar Ticket</button>
														</div>"><?= $chat['id_chat'] ?></button>

                                                <a href="#"></a>
                                                <input type="hidden" name="startedTime<?= $rand ?>" value="<?= $chat['registered_at'] ?>">

                                                <?php $time = $queueController->limitTimeToFinish($chat['id_module']); ?>

                                                <div class="progress" title="<?= (int)$minutos ?> <?= (int)$minutos > 1 ? 'minutos' : 'minuto' ?> de <?= $time[0]['limit_time'] ?>">
                                                    <progress id="pg" value="<?= (int)$minutos ?>" max="<?= $time[0]['limit_time'] ?>"></progress>
                                                </div>
                                            </div>
                                            <?php endif ?>
                                            <?php endif ?>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </td>
                                <?php endforeach ?>
                            </tr>
                        </table>
                        <?php else : ?>
                        <div class="col-md-12">
                            <span>Atenção! Fila não iniciada (Atendentes Off ou nenhum ticket atribuído).</span>
                        </div>
                        <?php endif ?>
                    </div>
                    <br>
                    <h1>Atendimentos</h1>
                    <?php
                    $attendants = $queueController->dataToTable();
                    ?>
                    <div class="row col-md-12" id="internal-row" style="overflow: auto; width: 100%;">
                        <table id="report" class="table table-sm table-bordered table-hover" align="center">
                            <tr>
                                <td style="min-width: 50px;"></td>
                                <td style="min-width: 150px;">Atendente</td>
                                <?php for ($i = 0; $i < 20; $i++) : ?>
                                <?php if ($i == 0) : ?>
                                <td style="min-width: 150px;">Hoje</td>
                                <?php else : ?>
                                <td style="min-width: 150px;"><?= date('d/m', strtotime('-' . $i . ' days')); ?></td>
                                <?php endif ?>
                                <?php endfor ?>
                            </tr>
                            <?php foreach ($attendants as $key => $attendant) : ?>
                            <tr>
                                <td><?= $attendant['on_chat'] == "yes" ? "<img src='img/is-on.png'></img>" : "<img src='img/is-off.png'></img>" ?></td>
                                <td><?= explode(" ", $attendant['name'])[0] ?></td>

                                <?php
                                $totalCalls = $queueController->totalCalls($attendant, date('Y-m-d'));
                                ?>

                                <td><?= $totalCalls['total']; ?></td>

                                <?php
                                if (!isset($_SESSION['user' . $key])) {
                                    $dataOfUser = array();
                                    for ($j = 1; $j < 20; $j++) : ?>
                                <?php 
                                $totalCalls = $queueController->totalCalls($attendant, date('Y-m-d', strtotime('-' . $j . ' days')));
                                array_push($dataOfUser, $totalCalls);
                                ?>

                                <td><?= $totalCalls['total']; ?></td>
                                <?php endfor ?>
                                <?php $_SESSION['user' . $key] = $dataOfUser; ?>
                                <?php 
                            } else {
                                $data = $_SESSION['user' . $key];
                                for ($j = 0; $j < 19; $j++) : ?>
                                <td><?= $data[$j]['total']; ?></td>
                                <?php endfor ?>
                                <?php 
                            } ?>
                            </tr>
                            <?php endforeach ?>
                            <tr>
                                <td></td>
                                <td><strong>TOTAL</strong></td>
                                <?php for ($j = 0; $j < 20; $j++) : ?>
                                <td id="<?= $j + 2 ?>"></td>
                                <?php endfor ?>
                            </tr>
                        </table>
                        <div>
                            <div style="font-size: 10px">
                                <img src='img/is-on.png' style="height: 10px;"></img> Online
                                <img src='img/is-busy.png' style="height: 10px; margin-left: 20px;"></img> Backup
                                <img src='img/is-trainer.png' style="height: 10px; margin-left: 20px;"></img> Treinamento
                                <img src='img/is-off.png' style="height: 10px; margin-left: 20px;"></img> Offline
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="./js/jquery-3.2.1.min.js"></script>
    <script src="./js/front.js"></script>
    <script src="./js/queue.js"></script>
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