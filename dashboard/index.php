<?php
include_once __DIR__ . '/../utils/controller/dashboard/dashboard.ctrl.php';
$dashboardController = DashboardController::getInstance();
$dashboardController->verifyPermission();

$dashboardController->makeBarChart($_POST);
$dashboardController->makePolarChart($_POST);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Administrativo</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="/../../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/../../dashboard/css/fontastic.css">
    <link rel="stylesheet" href="/../../dashboard/css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="/../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="/../../dashboard/css/style.default.css">
    <link rel="stylesheet" href="/../../dashboard/css/custom.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">

    <link rel="shortcut icon" href="brain_icon">
</head>

<body>

    <?php include("navs/navbar.php"); ?>
    <div class="root-page home-page">
        <?php include("navs/header.php") ?>
        <section class="dashboard-counts section-padding">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xl-3 col-md-6 col-8">
                        <div class="wrapper count-title d-flex">
                            <div class="icon"><i class="icon-ticket"></i></div>
                            <div class="name"><strong class="text-uppercase">Tickets</strong>
                                <div class="count-number"><?= $dashboardController->getTotalTickets()['total'] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-8">
                        <div class="wrapper count-title d-flex">
                            <div class="icon"><i class="icon-folder-open-alt"></i></div>
                            <div class="name"><strong class="text-uppercase">Abertos</strong>
                                <div class="count-number"><?= $dashboardController->getOpenTickets()['total'] ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-8">
                        <div class="wrapper count-title d-flex">
                            <div class="icon"><i class="icon-folder-open-alt"></i></div>
                            <div class="name"><strong class="text-uppercase">Solucionados</strong>
                                <div class="count-number"><?= $dashboardController->getSolvedTickets() ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6 col-8">
                        <div class="wrapper count-title d-flex">
                            <div class="icon"><i class="icon-folder-close-alt"></i></div>
                            <div class="name"><strong class="text-uppercase">Pendentes</strong>
                                <div class="count-number"><?= $dashboardController->getPendingTickets()['total'] ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="dashboard-header section-padding">
            <div class="container-fluid">
                <div class="row d-flex align-items-md-stretch">
                    <div class="col-lg-4 col-md-4 col-sm-4" style="text-align: center; width: 100%;">
                        <div id="chart-container" style="display: inline-block;"></div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 bar-tickets-in-week">
                        <form class="form-inline" action="" method="POST">
                            <div class="form-group col-lg-11 mb-2 mr-1">
                                <input type="date" class="form-control" id="bar-tickets-in-week-filter" name="bar-tickets-in-week-filter" min="2018-10-10" max="<?= $dashboardController->getActualDate() ?>" value="<?= isset($_POST['bar-tickets-in-week-filter']) ? $_POST['bar-tickets-in-week-filter'] : $dashboardController->getActualDate() ?>">
                            </div>
                            <button type="submit" name="filter-to-tickets-in-week" class="btn btn-primary mb-2">Filtrar</button>
                        </form>
                        <canvas id="bar-chart"></canvas>
                    </div>
                </div>
            </div>
        </section>
        <section class="statistics section-padding section-no-padding-bottom">
            <div class="container-fluid">
                <form id="polar-modules-form" class="form-inline" action="" method="POST">
                    <div class="form-group col-lg-5 mb-2 mr-1">
                        <input type="date" class="form-control" id="polar-modules-initial-filter" name="polar-modules-initial-filter" min="2018-10-10" max="<?= $dashboardController->getActualDate() ?>" value="<?= isset($_POST['polar-modules-initial-filter']) ? $_POST['polar-modules-initial-filter'] : $dashboardController->getActualDate() ?>" required>
                    </div>
                    <div class="form-group col-lg-5 mb-2 mr-1">
                        <input type="date" class="form-control" id="polar-modules-final-filter" name="polar-modules-final-filter" min="2018-10-10" max="<?= $dashboardController->getActualDate() ?>" value="<?= isset($_POST['polar-modules-final-filter']) ? $_POST['polar-modules-final-filter'] : $dashboardController->getActualDate() ?>" required>
                    </div>
                    <button id="polar-modules-submit" type="submit" name="filter-to-modules" class="btn btn-primary mb-2">Filtrar</button>
                </form>
                <div class="row d-flex align-items-stretch">
                    <div id="div-polar-chart" class="col-lg-5 col-md-5 col-sm-5 polar-group-1">
                        <h3 class="text-center">Módulos Grupo 1</h3>
                        <canvas id="polar-chart-group-1"></canvas>
                    </div>
                    <div id="div-polar-chart" class="offset-lg-2 col-lg-5 col-md-5 col-sm-5 polar-group-2">
                        <h3 class="text-center">Módulos Grupo 2</h3>
                        <canvas id="polar-chart-group-2"></canvas>
                    </div>
                </div>
            </div>
        </section>
        <footer class="main-footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <p>Brainsoft Sistemas &copy; 2018</p>
                    </div>
                    <div class="col-sm-6 text-right">
                        <p style="font-size: 12px;">Design by <a href="https://bootstrapious.com" class="external">Bootstrapious</a></p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../../dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="../../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../../dashboard/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../../dashboard/js/fusioncharts.js"></script>
    <script src="../../dashboard/js/fusioncharts.charts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script type="text/javascript">
        FusionCharts.ready(function() {
            var revenueChart = new FusionCharts({
                type: 'doughnut3d',
                renderAt: 'chart-container',
                width: '450',
                height: '450',
                dataFormat: 'json',
                dataSource: {
                    "chart": {
                        "caption": "Total de Tickets",
                        "subCaption": "",
                        "numberPrefix": "",
                        "paletteColors": "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
                        "bgColor": "#ffffff",
                        "showBorder": "0",
                        "use3DLighting": "0",
                        "showShadow": "0",
                        "enableSmartLabels": "0",
                        "startingAngle": "310",
                        "showLabels": "0",
                        "showPercentValues": "1",
                        "showLegend": "1",
                        "legendShadow": "0",
                        "legendBorderAlpha": "0",
                        "defaultCenterLabel": "Total: <?= $dashboardController->getTotalTickets()['total'] ?>",
                        "centerLabel": "Tickets $label: $value",
                        "centerLabelBold": "1",
                        "showTooltip": "0",
                        "decimals": "0",
                        "captionFontSize": "18",
                        "subcaptionFontSize": "18",
                        "subcaptionFontBold": "0"
                    },
                    "data": [{
                            "label": "Solucionados",
                            "value": "<?= $dashboardController->getSolvedTickets() ?>"
                        },
                        {
                            "label": "Abertos",
                            "value": "<?= $dashboardController->getOpenTickets()['total'] ?>"
                        },
                        {
                            "label": "Pendentes",
                            "value": "<?= $dashboardController->getPendingTickets()['total'] ?>"
                        }
                    ]
                }
            }).render();
        });

        $(function() {
            var ctx = $("#bar-chart");
            var data = {
                labels: <?= $dashboardController->getLabelsToBarChart() ?>,
                datasets: <?= $dashboardController->getDataSetsToBarChart() ?>
            };
            var options = <?= $dashboardController->getOptionsToBarChart() ?>;

            var chart = new Chart(ctx, {
                type: "bar",
                data: data,
                options: options
            });
        });

        $(function() {
            new Chart(document.getElementById("polar-chart-group-1"), <?= $dashboardController->getElementToPolarChart("nivel1") ?>);
        });

        $(function() {
            new Chart(document.getElementById("polar-chart-group-2"), <?= $dashboardController->getElementToPolarChart("nivel2") ?>);
        });
    </script>
</body>

</html>