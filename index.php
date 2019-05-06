<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Brainsoft Sistemas - Administrativo</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="../dashboard/vendor/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="../dashboard/css/style.default.css" id="theme-stylesheet">
    <link rel="stylesheet" href="../dashboard/css/custom.css">

    <link rel="shortcut icon" href="/brain_icon">

</head>

<body>
    <div class="container-fluid px-3">
        <div class="row min-vh-100">
            <div class="col-md-5 col-lg-6 col-xl-4 px-lg-5 d-flex align-items-center">
                <div class="w-100 py-5">
                    <div class="text-center">
                        <img src="http://brainsoftsistemas.com.br/img/comum/logomarca_brainsoft.png" alt="Logotipo da empresa Brainsoft" style="max-width: 22rem;" class="img-fluid mb-4">
                    </div>
                    <?php session_start(); ?>
                    <?= isset($_SESSION['loginFail']) ? $_SESSION['loginFail'] : "" ?>
                    <?php unset($_SESSION['loginFail']) ?>
                    <form class="form-validate" method="POST" action="/controller/credential/data">
                        <div class="form-group">
                            <label>Usuário</label>
                            <input name="login" type="text" placeholder="ex: brainsoft" autocomplete="off" required data-msg="Por gentileza informe seu usuário de acesso" class="form-control" autofocus>
                        </div>
                        <div class="form-group mb-4">
                            <div class="row">
                                <div class="col">
                                    <label>Senha</label>
                                </div>
                                <div class="col-auto"><a href="#" class="form-text small text-muted">Esqueceu sua senha?</a></div>
                            </div>
                            <input name="password" type="password" required data-msg="Por gentileza informe sua senha" class="form-control">
                        </div>
                        <button type="submit" name="submit" value="fromDoLogin" class="btn btn-lg btn-block btn-primary mb-3">Acessar</button>
                    </form>
                </div>
            </div>
            <div class="col-12 col-md-7 col-lg-6 col-xl-8 d-none d-lg-block">
                <div style="background-image: url(/img/page-login.jpg);" class="bg-cover h-100 mr-n3"></div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="../dashboard/vendor/jquery.cookie/jquery.cookie.js"> </script>
    <script src="../dashboard/js/grasp_mobile_progress_circle-1.0.0.min.js"></script>
    <script src="../js/jquery.mask.js"></script>
    <script src="../dashboard/js/front.js"></script>
    <script src="../dashboard/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../dashboard/vendor/jquery-validation/jquery.validate.min.js"></script>

</body>

</html> 