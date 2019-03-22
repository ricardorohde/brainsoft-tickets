<?php 
include_once __DIR__ . '/../../utils/controller/city/city.ctrl.php';
$cityController = CityController::getInstance();
$cityController->verifyPermission();

$cityController->findAllStates();
$cityController->findAllCities();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Listagem de Cidades</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="css/city/style.css">
    <link rel="stylesheet" href="css/custom.css">

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
                            <h1 class="h3 display">Cidades</h1>
                        </div>

                        <div class="col-sm-6 text-right h2">
                            <a class="btn btn-primary" href="cidades"><i class="fa fa-refresh"></i> Atualizar</a>
                        </div>
                    </div>
                </header>

                <hr>

                <?php if (isset($_SESSION['cityOk']) || isset($_SESSION['cityNo'])) : ?>
                <div id="statusCity" class="alert alert-<?= isset($_SESSION['cityOk']) ? 'success' : 'danger' ?>" style="display:block;" ?>
                    <?= isset($_SESSION['cityOk']) ? $_SESSION['cityOk'] : '' ?>
                    <?= isset($_SESSION['cityNo']) ? $_SESSION['cityNo'] : '' ?>
                    <?php unset($_SESSION['cityOk']);
                    unset($_SESSION['cityNo']) ?>
                </div>
                <?php endif ?>

                <div class="row">
                    <div class="col-sm-5">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <h2 class="h5 display">Nova Cidade</h2>
                            </div>
                            <div class="card-body">
                                <form action="../../utils/controller/city/city.ctrl.php" method="POST">
                                    <div class="form-group">
                                        <label class="form-control-label">Descrição</label>
                                        <input type="text" id="descCity" name="descCity" class="form-control" autofocus=""><span class="help-block-none">Informe o nome do estado.</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Estado</label>
                                        <select class="form-control" name="idState" id="idState">
                                            <option>Selecione um estado...</option>
                                            <?php foreach ($cityController->getAllStates() as $state) : ?>
                                            <option value="<?= $state['id'] ?>"><?= $state['description'] ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="line"></div>
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-right">
                                            <button type="reset" class="btn btn-secondary">Limpar</button>
                                            <button type="submit" id="new" name="submit" value="new" class="btn btn-primary btnAction">Cadastrar</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-7">
                        <div class="table-responsive">
                            <table id="stateList" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Cod.</th>
                                        <th>Descrição</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($cityController->getAllCities())) : ?>
                                    <?php foreach ($cityController->findDataOfCities() as $city) : ?>
                                    <tr>
                                        <td>00<?= $city['id'] ?></td>
                                        <td><?= $city['description'] ?></td>
                                        <td><?= $city['state'] ?></td>
                                        <td class="actions text-center">
                                            <a href="cidade/<?= $city['id'] ?>" class="btn btn-sm btn-success"><i class="far fa-edit"></i></a>
                                            <a href="cidade/remover/<?= $city['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja realmente excluir?')"><i class="far fa-trash-alt"></i></a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else : ?>
                                    <tr>
                                        <td colspan="6">Nenhuma cidade encontrada.</td>
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
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="./js/front.js"></script>
    <script src="./js/user.js"></script>
    <script src="./jquery-ui.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>

    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
</body>

</html> 