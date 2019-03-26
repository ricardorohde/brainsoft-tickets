<?php
include_once __DIR__ . '/../../utils/controller/marketing/email.ctrl.php';
$emailController = EmailController::getInstance();
$emailController->verifyPermission();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - E-mail Marketing</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="../../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../../dashboard/css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="../../dashboard/css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="../../dashboard/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../../dashboard/css/custom.css">
    <link rel="stylesheet" href="../../dashboard/css/email.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
    <link type="text/css" href="../../dashboard/css/jquery-ui.css" rel="stylesheet" />

    <link rel="shortcut icon" href="../../brain_icon">
</head>

<body>
    <?php include("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
        <?php include("../navs/header.php"); ?>
        <section class="forms">
            <div class="container-fluid">
                <header>
                </header>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Enviar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Histórico</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <form id="sendEmail" action="send.php" method="POST" target="_blank" enctype="multipart/form-data">
                                            <div class="form-group row">
                                                <label class="col-sm-1 pt-2 form-control-label">Assunto</label>
                                                <div class="col-sm-6">
                                                    <input type="text" name="subject" id="subject" class="form-control" autofocus="">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-1 pt-2 form-control-label">Destino</label>
                                                <div class="col-sm-3">
                                                    <select name="destiny" class="form-control" id="destiny" required>
                                                        <option value="choose" selected="">Selecione o destinatário...</option>
                                                        <option value="all">Todos</option>
                                                        <option value="state">Estado</option>
                                                        <option value="registry">Cartório</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row divAllStates hide">
                                                <div class="offset-sm-1 col-sm-3 select">
                                                    <select class="form-control" name="allStates" id="allStates">
                                                        <option value="">Selecione um estado...</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row divSetRegistry hide">
                                                <div class="offset-sm-1 col-sm-3 select">
                                                    <input type="text" name="setRegistry" id="setRegistry" class="form-control">
                                                    <span class="help-block-none">Informe o cartório do cliente.</span>
                                                </div>
                                            </div>
                                            <div class="form-group row tableClients hide">
                                                <div class="offset-sm-1 col-sm-11" style="height: 250px; overflow: auto;">
                                                    <table name="tableWithData" class="table table-sm table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Cliente</th>
                                                                <th scope="col">Usuário</th>
                                                                <th scope="col">Email</th>
                                                                <th scope="col">Cartório</th>
                                                                <th scope="col">Enviar</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-1 pt-2 form-control-label">Anexo(s):</label>
                                                <div class="col-sm-3">
                                                    <input type="file" name="file[]" multiple="multiple" class="multi" accept="png|jpg|pdf|txt" />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-1 pt-2 form-control-label">Mensagem</label>
                                                <div class="col-sm-11">
                                                    <textarea name="message" id="message" rows="10" cols="80"></textarea>
                                                    <span style="color: #000000;"><strong class="var">$cliente:</strong> Nome completo do cliente &nbsp;|&nbsp; <strong class="var">$cartorio:</strong> Nome do cartório do cliente &nbsp;|&nbsp; <strong class="var">$usuario:</strong> Nome do usuário para acesso ao sistema &nbsp;|&nbsp; <strong class="var">$senha:</strong> Senha temporária do usuário para acesso ao sistema</span>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div id="divBtnSendEmail" class="col-sm-12">
                                                    <button type="submit" name="submit" class="btn btn-primary btnAction btnSendEmail">Enviar!</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label id="label-date-email" for="initial-date">Data</label>
                                                <input type="date" name="date-email" id="date-email" class="form-control" min="2019-02-19">
                                            </div>
                                            <div class="form-group offset-md-1 col-md-4" style="margin-top: 8px;">
                                                <label id="label-date-email" for="get-history-email"></label>
                                                <button id="btn-get-history-email" name="get-history-email" class="btn btn-primary generate-report" disabled>Buscar Emails</button>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-sm-12 mt-2 divTableHistory" style="overflow: auto; display: none;">
                                                <table name="tableHistoryEmail" class="table table-sm table-hover table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Situação</th>
                                                            <th scope="col">Responsável</th>
                                                            <th scope="col">Destino</th>
                                                            <th scope="col">Assunto</th>
                                                            <th scope="col">Mensagem</th>
                                                            <th scope="col">Info</th>
                                                            <th scope="col">Enviado em</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
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
    <script src="../../dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
    <script src="../../dashboard/jquery-ui.js"></script>
    <script src="../../js/jquery.mask.js"></script>
    <script src="../../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../../dashboard/js/marketing/controls.js"></script>
    <script src="../../dashboard/js/marketing/history.js"></script>
    <script src="../../dashboard/vendor/marketing/multiFile/jquery.MultiFile.min.js" type="text/javascript"></script>
    <script src="../../dashboard/vendor/marketing/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replace('message');
    </script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
</body>

</html> 