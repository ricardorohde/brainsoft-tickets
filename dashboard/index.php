<?php
    include_once __DIR__.'/../utils/controller/dashboard/dashboard.ctrl.php';
    $dashboardController = DashboardController::getInstance();  
    $dashboardController->verifyPermission();
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

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">>
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="css/custom.css">

    <link rel="shortcut icon" href="../brain_icon">
  </head>

  <body>

    <?php include ("navs/navbar.php");?>
    <div class="root-page home-page">
      <?php include ("navs/header.php") ?>      
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
            <div class="col-lg-12 col-md-12 col-sm-6" style="text-align: center; width: 100%;">
              <div id="chart-container" style="display: inline-block;">FusionCharts will render here</div>
            </div>
          </div>
        </div>
      </section>
      <section class="statistics section-padding section-no-padding-bottom">
        <div class="container-fluid">
          <div class="row d-flex align-items-stretch">
            <div class="col-lg-4">
              <div class="wrapper income text-center">
                <div class="icon"><i class="icon-line-chart"></i></div>
                <div class="number">126,418</div><strong class="text-primary">All Income</strong>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit sed do.</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="wrapper data-usage">
                <h2 class="display h4">Monthly Usage</h2>
                <div class="row d-flex align-items-center">
                  <div class="col-sm-6">
                    <div id="progress-circle" class="d-flex align-items-center justify-content-center"></div>
                  </div>
                  <div class="col-sm-6"><strong class="text-primary">80.56 Gb</strong><small>Current Plan</small><span>100 Gb Monthly</span></div>
                </div>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing.</p>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="wrapper user-activity">
                <h2 class="display h4">User Activity</h2>
                <div class="number">210</div>
                <h3 class="h4 display">Social Users</h3>
                <div class="progress">
                  <div role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" class="progress-bar progress-bar bg-primary"></div>
                </div>
                <div class="page-statistics d-flex justify-content-between">
                  <div class="page-visites"><span>Pages Visites</span><strong>230</strong></div>
                  <div class="new-visites"><span>New Visites</span><strong>73.4%</strong></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
      <section class="updates section-padding">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 col-md-12">              
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
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/fusioncharts.js"></script>
    <script src="js/fusioncharts.charts.js"></script>
    <script type="text/javascript">
      FusionCharts.ready(function () {
          var revenueChart = new FusionCharts({
              type: 'doughnut2d',
              renderAt: 'chart-container',
              width: '450',
              height: '450',
              dataFormat: 'json',
              dataSource: {
                  "chart": {
                      "caption": "Gráfico de Tickets",
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
                      "defaultCenterLabel": "Total: <?= $dashboardController->getTotalTickets()['total']?>",
                      "centerLabel": "Tickets $label: $value",
                      "centerLabelBold": "1",
                      "showTooltip": "0",
                      "decimals": "0",
                      "captionFontSize": "18",
                      "subcaptionFontSize": "18",
                      "subcaptionFontBold": "0"
                  },
                  "data": [
                      {
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
    </script>
  </body>
</html>