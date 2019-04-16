<?php 
include_once __DIR__ . '/../../utils/controller/user/new-user.ctrl.php';
$newUserController = NewUserController::getInstance();
$newUserController->verifyPermission();

$newUserController->verifyGet($_GET);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Cadastro de Usuário</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="../../../dashboard/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <link rel="stylesheet" href="../../../dashboard/css/fontastic.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="../../../dashboard/css/grasp_mobile_progress_circle-1.0.0.min.css">
    <link rel="stylesheet" href="../../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css">
    <link rel="stylesheet" href="../../../dashboard/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../../../dashboard/css/custom.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css">

    <link rel="shortcut icon" href="../../../brain_icon">
</head>

<body>
    <?php include("../navs/navbar.php"); ?>
    <div class="root-page forms-page">
        <?php include("../navs/header.php"); ?>

        <?php
        $row_sql_user = $newUserController->getUser();
        $row_sql_registry = $newUserController->getRegistry();
        $row_sql_city = $newUserController->getCity();
        $row_sql_state = $newUserController->getState();
        $row_sql_role = $newUserController->getRole();

        echo $newUserController->getInputHasEmployee();
        $row_sql_credential = $newUserController->getCredential();
        $thereIsProblem = $newUserController->getThereIsProblem();

        $type = $newUserController->getCurrentType();
        ?>

        <?php 
        $newUserController->findAllStates();
        $allStates = $newUserController->getAllStates();
        $allRoles = $newUserController->getAllRoles();

        echo '<input type="hidden" id="userInformed" value="' . $row_sql_role['description'] . '">';
        ?>
        <section class="forms">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card main-card">
                            <div class="card-header d-flex align-items-center">
                                <h2 class="h5 display main-card-title"><?= isset($_GET['id']) ? "Visualizar/Alterar Usuário" : "Novo Usuário" ?></h2>
                            </div>
                            <div class="card-body">
                                <form class="form-horizontal" id="formAdd" name="formAdd" action="/controller/user/data" method="POST" novalidate>
                                    <input type="hidden" value="<?= $newUserController->getDataInUrl()[3] ?>" name="typeUser">
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Nome Completo</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" class="form-control" value="<?= isset($row_sql_user['name']) ? $row_sql_user['name'] : '' ?>"><span class="help-block-none">Informe o nome completo do usuário.</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" id="email" name="email" class="form-control" value="<?= isset($row_sql_user['email']) ? $row_sql_user['email'] : '' ?>"><span class="help-block-none">Informe o email do usuário.</span>
                                        </div>
                                    </div>
                                    <div class="line dataOfClient"></div>
                                    <div class="form-group row dataOfClient">
                                        <label class="col-sm-2 form-control-label">Estado</label>
                                        <div class="col-sm-3 select">
                                            <select class="form-control" name="state" id="state">
                                                <?php if (@$row_sql_state != null) : ?>
                                                <option value=<?= '"' . $row_sql_state['id'] . '"'; ?>><?= $row_sql_state['description']; ?></option>
                                                <?php else : ?>
                                                <option value="">Selecione um estado...</option>
                                                <?php endif; ?>

                                                <?php foreach ($allStates as $state) : ?>
                                                <option value="<?= $state['id'] ?>"><?= $state['description']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <label class="col-sm-3 form-control-label text-center">Cidade</label>
                                        <div class="col-sm-4 select">
                                            <select name="city" class="form-control" id="city">
                                                <?php if (@$row_sql_city != null) : ?>
                                                <option value=<?= '"' . $row_sql_city['id'] . '"'; ?>><?= $row_sql_city['description']; ?></option>
                                                <?php else : ?>
                                                <option value="">Selecione um estado...</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row dataOfClient">
                                        <label class="col-sm-2 form-control-label">Cartório</label>
                                        <div class="col-sm-10 select">
                                            <select name="registry" class="form-control" id="id_registry">
                                                <?php if (@$row_sql_registry != null) : ?>
                                                <option value=<?= '"' . $row_sql_registry['id'] . '"'; ?>><?= $row_sql_registry['name']; ?></option>
                                                <?php else : ?>
                                                <option value="">Selecione uma cidade...</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="line dataOfEmployee"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Cargo</label>
                                        <div class="col-sm-10 select">
                                            <select name="role" id="role" class="form-control"></select>
                                        </div>
                                    </div>
                                    <div class="line dataOfClient"></div>
                                    <?php if (isset($_GET['type']) && $_GET['type'] == 'employee') : ?>
                                    <div class="form-group row dataOfEmployee">
                                        <label class="col-sm-2 form-control-label">Grupo</label>
                                        <div class="col-sm-10">
                                            <select name="group" class="form-control" id="group" required>
                                                <?php switch ($row_sql_user['t_group']) {
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
                                    </div>
                                    <?php endif ?>
                                    <div class="line dataOfEmployee"></div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 form-control-label">Nickname</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="login" class="form-control" value="<?= isset($row_sql_credential['login']) ? $row_sql_credential['login'] : '' ?>" disabled required>
                                            <span class="help-block-none">Informe um nome para que o usuário acesse o sistema.</span>
                                        </div>
                                        <input type="hidden" name="id_user" value="<?= isset($_GET['id']) ? $_GET['id'] : '' ?>">
                                    </div>
                                    <div class="line"></div>
                                    <?php if ($newUserController->getDataInUrl()[3] == 'client' && !empty($newUserController->getAlltickets())) : ?>
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="ticketListOfClient" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>Chat</th>
                                                            <th>Data</th>
                                                            <th>Origem</th>
                                                            <th>Categoria / Módulo</th>
                                                            <th>Atendente</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($newUserController->getAlltickets() as $ticket) : ?>
                                                        <tr>
                                                            <td><?= $ticket['chat'] ?></td>
                                                            <td><?= date('d/m/Y', strtotime($ticket['registered_at'])) ?></td>
                                                            <td><?= ucfirst($ticket['source']) ?></td>
                                                            <td><?= $ticket['category'] ?> / <?= $ticket['module'] ?></td>
                                                            <td><?= $ticket['attendant'] ?></td>
                                                            <td class="actions text-center">
                                                                <a href="../ticket/<?= $ticket['chat'] ?>/<?= $ticket['attendant_id'] ?>" class="btn btn-sm btn-success"><i class="fa fa-eye"></i></a>
                                                            </td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php elseif ($newUserController->getDataInUrl()[3] != 'employee') : ?>
                                    <div class="alert alert-primary" role="alert">
                                        Não existem tickets atribuidos para este cliente.
                                    </div>
                                    <?php endif; ?>
                                    <div class="form-group row text-right">
                                        <div class="offset-sm-10 offset-md-8 col-sm-2 col-md-4">
                                            <?php 
                                            if ((isset($_GET['id']) && @$row_sql_user['name'] == null) || (isset($_GET['type']) && !isset($_GET['id']))) {
                                                echo '<span id="wrongIdChat"><strong>Erro!</strong> Tipo e/ou usuário informado não existe. Contate o Administrador.</span>';
                                            } else {
                                                echo '<button type="reset" class="btn btn-secondary">Limpar</button>';

                                                if (isset($_GET['id'])) {
                                                    echo '<button type="submit" name="submit" value="alterUser" class="btn btn-primary btnAction">Salvar!</button>';

                                                    if (empty($newUserController->getAlltickets())) {
                                                        echo '<button type="submit" name="submit" value="removeUser" class="btn btn-danger btnAction" onclick="return confirm(\'Deseja realmente excluir?\')">Excluir!</button>';
                                                    }
                                                } else {
                                                    echo '<button type="submit" name="submit" value="newUser" class="btn btn-primary btnAction">Cadastrar!</button>';
                                                }
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
    <script src="../../../dashboard/js/jquery-3.2.1.min.js"></script>
    <script src="/script/header"></script>
    <script src="../../../js/jquery.mask.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"> </script>
    <script src="../../../dashboard/jquery-ui.js"></script>
    <script src="../../../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../../../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../../../dashboard/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../../../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="../../../dashboard/vendor/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="../../../dashboard/js/edit-user.js"></script>
    <script src="../../../dashboard/js/front.js"></script>
    <script src="../../../dashboard/js/brain.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
    <!-- Google Analytics: change UA-XXXXX-X to be your site's ID.-->
    <!---->
</body>

</html> 