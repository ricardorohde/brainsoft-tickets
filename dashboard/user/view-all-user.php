<?php 
include_once __DIR__ . '/../../utils/controller/user/all-user.ctrl.php';
$allUserController = AllUserController::getInstance();
$allUserController->verifyPermission();

$clients = $allUserController->findAllClients();
$employees = $allUserController->findAllEmployees();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Listagem de Usuários</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../dashboard/css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="../dashboard/css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../dashboard/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../dashboard/css/custom.css">

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
                            <h1 class="h3 display">Usuários</h1>
                        </div>

                        <div class="col-sm-6 text-right h2">
                            <a class="btn btn-primary" href="usuario/novo"><i class="fa fa-plus"></i> Novo Usuário</a>
                            <a class="btn btn-default" href="usuarios"><i class="fa fa-refresh"></i> Atualizar</a>
                        </div>
                    </div>
                </header>

                <hr>

                <?php if (isset($_SESSION['UserOk']) || isset($_SESSION['UserNo'])) : ?>
                <div id="statusUser" class="alert alert-<?= isset($_SESSION['UserOk']) ? 'success' : 'danger' ?>" style="display:block;" ?>
                    <?= isset($_SESSION['UserOk']) ? $_SESSION['UserOk'] : '' ?>
                    <?= isset($_SESSION['UserNo']) ? $_SESSION['UserNo'] : '' ?>
                    <?php unset($_SESSION['UserOk']);
                    unset($_SESSION['UserNo']) ?>
                </div>
                <?php endif ?>

                <ul class="nav nav-tabs menu-users">
                    <li class="nav-item">
                        <a class="nav-link active" href="#first-tab" data-toggle="tab">Clientes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#second-tab" data-toggle="tab">Funcionários</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane active in" id="first-tab">
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>Cartório</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($clients)) : ?>
                                    <?php foreach ($allUserController->findDataOfClients() as $client) : ?>
                                    <tr>
                                        <td>00<?= $client['id'] ?></td>
                                        <td><?= $client['name'] ?></td>
                                        <td><?= $client['email'] ?></td>
                                        <td><?= $client['registry'] ?></td>
                                        <td class="actions text-right">
                                            <a href="usuario/client/<?= $client['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Visualizar</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="6">Nenhum cliente encontrado.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane" id="second-tab">
                        <div class="table-responsive">
                            <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($employees)) : ?>
                                    <?php foreach ($allUserController->findDataOfEmployees() as $employee) : ?>
                                    <tr>
                                        <td>00<?= $employee['id'] ?></td>
                                        <td><?= $employee['name'] ?></td>
                                        <td><?= $employee['email'] ?></td>
                                        <td class="actions text-right">
                                            <a href="usuario/employee/<?= $employee['id']; ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i> Visualizar</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="6">Nenhum funcionário encontrado.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
    <script src="../dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="../../dashboard/js/front.js"></script>
    <script src="../../dashboard/js/user.js"></script>
    <script src="../../dashboard/jquery-ui.js"></script>
    <script src="../../js/jquery.mask.js"></script>
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