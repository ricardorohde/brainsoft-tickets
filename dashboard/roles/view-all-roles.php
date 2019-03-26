<?php 
include_once __DIR__ . '/../../utils/controller/role/role.ctrl.php';
$roleController = RoleController::getInstance();
$roleController->verifyPermission();

$roleController->findAll();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Listagem de Categorias e Módulos</title>
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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <link rel="shortcut icon" href="../../brain_icon">

    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
</head>

<body>
    <?php include("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
        <?php include("../navs/header.php"); ?>
        <section class="forms">
            <div class="container-fluid">
                <header>
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="h3 display"></h1>
                        </div>
                        <div class="col-sm-6 text-right h2">
                            <a class="btn btn-primary" href="cargo/novo"><i class="fa fa-plus"></i> Novo Cargo</a>
                            <a class="btn btn-default" href="cargos"><i class="fa fa-refresh"></i> Atualizar</a>
                        </div>
                    </div>
                </header>

                <div class="row">
                    <div class="table-responsive">
                        <table id="example2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Descrição</th>
                                    <th>Destinado a</th>
                                    <th>Situação</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($roleController->getAllRoles())) : ?>
                                <?php foreach ($roleController->getAllRoles() as $role) : ?>
                                <tr>
                                    <td><?= $role['id'] ?></td>
                                    <td><?= $role['description'] ?></td>
                                    <td><?= ($role['type']) == 1 ? "Cliente" : "Funcionário" ?></td>
                                    <td><?= ucfirst($role['status']) ?></td>
                                    <td class="actions text-right">
                                        <?php if ($role['status'] == "ativo") { ?>
                                        <a href="../utils/controller/role/role-data.ctrl.php?id=<?= $role['id']; ?>" class="btn btn-sm btn-danger" title="desativar"><i class="fa fa-trash-o"></i> Desativar</a>
                                        <?php 
                                    } else { ?>
                                        <a href="../utils/controller/role/role-data.ctrl.php?type=active&id=<?= $role['id']; ?>" class="btn btn-sm btn-success" title="ativar"><i class="fa fa-check-circle-o"></i> Ativar</a>
                                        <?php 
                                    } ?>
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
    <script src="../../js/jquery.mask.js"></script>
    <script src="../../dashboard/js/front.js"></script>
    <script src="../../dashboard/jquery-ui.js"></script>
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