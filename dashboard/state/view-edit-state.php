<?php 
include_once __DIR__ . '/../../utils/controller/state/state.ctrl.php';
$stateController = StateController::getInstance();
$stateController->verifyPermission();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Estado</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="../../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../../dashboard/css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="../../dashboard/css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../../dashboard/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../../dashboard/css/state/style.css">
    <link rel="stylesheet" href="../../dashboard/css/custom.css">

    <link rel="shortcut icon" href="../../brain_icon">
</head>

<body>
    <?php require_once('../navs/navbar.php'); ?>
    <div class="root-page forms-page">
        <?php require_once('../navs/header.php'); ?>
        <section class="forms">
            <div class="container-fluid">
                <header>
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="h3 display">Estado</h1>
                        </div>
                    </div>
                </header>

                <hr>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h2 class="h5 display">Alterar Estado - <span><?= $stateController->getStateToEdit()['description'] ?></span></h2>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" action="../../utils/controller/state/state.ctrl.php" method="POST">
                                    <input type="hidden" name="idState" value="<?= $stateController->getStateToEdit()['id'] ?>">
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Descrição</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="descState" name="descState" class="form-control" value="<?= $stateController->getStateToEdit()['description'] ?>"><span class="help-block-none">Informe o nome do estado.</span>
                                        </div>
                                        <label class="col-sm-2 form-control-label">Sigla</label>
                                        <div class="col-sm-10 select">
                                            <input type="text" id="descInitials" name="initialsState" class="form-control" maxlength="2" value="<?= $stateController->getStateToEdit()['initials'] ?>"><span class="help-block-none">Informe a sigla do estado.</span>
                                        </div>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-right">
                                            <button type="reset" class="btn btn-secondary">Limpar</button>
                                            <button type="submit" id="update" name="submitFromState" value="update" class="btn btn-primary btnAction">Salvar</button>
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
    <script src="../../dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="../../dashboard/js/front.js"></script>
    <script src="../../dashboard/js/user.js"></script>
    <script src="../../dashboard/jquery-ui.js"></script>
    <script src="../../js/jquery.mask.js"></script>
    <script src="../../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../../dashboard/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
</body>

</html> 