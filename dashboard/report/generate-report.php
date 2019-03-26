<?php 
include_once __DIR__ . '/../../utils/controller/report/report.ctrl.php';
$reportController = ReportController::getInstance();
$reportController->verifyPermission();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Gerar Relatório</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../dashboard/css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="../dashboard/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../dashboard/css/custom.css">

    <link rel="shortcut icon" href="../../brain_icon">
</head>

<body>
    <?php include("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
        <?php include("../navs/header.php"); ?>
        <section class="forms">
            <div class="container-fluid">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card main-card">
                                <div class="card-body">
                                    <form method="POST">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label id="label-initial-date" for="initial-date">Data Inicial</label>
                                                <input type="date" name="initial-date" id="initial-date" class="form-control" min="2018-10-10">
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label id="label-final-date" for="final-date">Data Final</label>
                                                <input type="date" name="final-date" id="final-date" class="form-control" value="<?= date("Y-m-d") ?>" max="<?= date("Y-m-d") ?>">
                                            </div>
                                        </div>
                                        <button type="submit" id="btn-generate-report" class="btn btn-primary generate-report" disabled>Gerar Relatório</button>
                                    </form>

                                    <?php if (isset($_POST['initial-date']) and isset($_POST['final-date'])) : ?>
                                    <?php 
                                    $initialDate = $_POST['initial-date'];
                                    $finalDate = $_POST['final-date'];

                                    $reportController->setIdClient($id);
                                    $reportController->setInitialDate($initialDate . " 00:00:01");
                                    $reportController->setFinalDate($finalDate . " 23:59:59");

                                    @$reportController->make();
                                    ?>
                                    <div class="report">
                                        <br>
                                        <br>
                                        <h1 id="title_of_report">Relatório de Atentimentos - <?= $reportController->getRegistryName() ?></h1>
                                        <span id='filter_period'>Período: <?= date('d/m/Y', strtotime($initialDate)) ?> à <?= date('d/m/Y', strtotime($finalDate)) ?></span>
                                        <span id='requester_of_report'>Solicitante: <?= $reportController->getClientName() ?></span>
                                        <span id='date_of_report'>Formalizado em: <?= date('d/m/Y H:i:s') ?></span>

                                        <br>

                                        <?php if ($reportController->getTotalOfTickets() >= 1) : ?>
                                        <div class='table-responsive'>
                                            <table id='example' class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th scope='row'>Total de chats realizados</th>
                                                        <td><?= $reportController->getTotalOfTickets() ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope='row'>Maior tempo em conversação</th>
                                                        <td><?= $reportController->convertData($reportController->getBiggestTime()) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope='row'>Tempo total em conversação</th>
                                                        <td><?= $reportController->convertData($reportController->getTotalMin()) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope='row'>Módulo com maior incidência</th>
                                                        <td><?= $reportController->getModule() ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th scope='row'>Funcionários requerentes (Top 10)</th>
                                                        <td>
                                                            <?php foreach ($reportController->getTopClients() as $key => $value) : ?>
                                                            <?= $value ?>
                                                            <?php endforeach ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br><br>
                                        <div>
                                            <h4>Detalhamento</h4><br>
                                            <div class='table-responsive'>
                                                <table id='details' class='table'>
                                                    <tbody>
                                                        <tr>
                                                            <th>Funcionário</th>
                                                            <th>Número de chats requisitados</th>
                                                        </tr>
                                                        <?php foreach ($reportController->getTopClients() as $key => $value) : ?>
                                                        <?php if ($value != "") : ?>
                                                        <tr>
                                                            <?php if (strstr($value, ',')) : ?>
                                                            <td scope='row'><?= substr($value, 6) ?></td>
                                                            <?php else : ?>
                                                            <td scope='row'><?= substr($value, 3) ?></td>
                                                            <?php endif ?>
                                                            <td><?= $reportController->getTopTicketOfClient()[$key] ?></td>
                                                        </tr>
                                                        <?php endif ?>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <br>
                                            <hr style="margin-top: -10px;">
                                            <br>
                                            <div class='table-responsive'>
                                                <table id='tickets-details' class='table'>
                                                    <tbody>
                                                        <tr>
                                                            <th>Data</th>
                                                            <th>Ticket / Chat</th>
                                                            <th>Funcionário</th>
                                                            <th>Categoria / Módulo</th>
                                                            <th>Atendente</th>
                                                            <th>Duração</th>
                                                        </tr>
                                                        <?php foreach ($reportController->makeDetailTicketTable() as $key => $value) : ?>
                                                        <tr>
                                                            <td scope="row"><?= date('d/m/Y', strtotime($value['registered_at'])); ?></td>
                                                            <td scope="row"><?= $value['id'] ?> / <?= $reportController->getChat($value['id_chat']) ?></td>
                                                            <td scope="row"><?= $reportController->getClient($value['id_client']) ?></td>

                                                            <?php $module = $reportController->getModuleById($value['id_module']) ?>
                                                            <td scope="row"><?= $reportController->getCategoryById($module['id_category']) ?> / <?= $module['description'] ?></td>
                                                            <td scope="row"><?= $reportController->getAttendantById($value['id_attendant']) ?></td>
                                                            <td scope="row"><?= $reportController->getDurationOfChat($value['id_chat']) ?></td>
                                                        </tr>
                                                        <?php endforeach ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php else : ?>
                                        <div class='table-responsive'>
                                            <table id='example' class='table'>
                                                <tbody>
                                                    <tr>
                                                        <th scope='row'>Não há dados para o período selecionado</th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php endif ?>
                                    </div>
                                    <?php endif ?>
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
                        <p>Brainsoft Sistemas - IMOB</p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <p class="generate-report">Design by <a href="https://bootstrapious.com" class="external">Bootstrapious</a></p>
                        <!-- Please do not remove the backlink to us unless you support further theme's development at https://bootstrapious.com/donate. It is part of the license conditions. Thank you for understanding :)-->
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <!-- Javascript files-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="../dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="../../dashboard/js/front.js"></script>
    <script src="../../dashboard/jquery-ui.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../dashboard/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
</body>

</html> 