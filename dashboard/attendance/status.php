<?php
include_once "../../utils/pct-chat/api.php";
$apiPct = new apiPct();
$apiPct->consultCustomersAtReception();
$apiPct->countCustomersAtReception();
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv='refresh' content='20;url=status.php'>
    <title></title>
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="../vendor/bootstrap/css/bootstrap.min.css">
    <!-- Google fonts - Roboto -->
    <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="../css/style.default.css" id="theme-stylesheet">
</head>

<body style="background-color: transparent;">
    <h3>Recepção | <a href=""><?= $apiPct->toStringTotalCustomersAtReception(); ?></a></h3>
    <pre>
    <?= var_dump($apiPct->getInfoCustomersAtReception()); ?>
    </pre>
</body>

</html> 