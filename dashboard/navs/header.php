<?php
include_once __DIR__ . '/../../utils/controller/header/header.ctrl.php';
$headerController = headerController::getInstance();

$element = $id;

$query = "SELECT registry.name FROM client, registry WHERE client.id_credential = ? AND client.id_registry = registry.id";
$registry = $navBarController->getPrepareInstance()->prepare($query, $element, "all");

$query = "SELECT notification.description, date_format(notification.date, '%d/%m/%Y') as nDate FROM notification, user_notification WHERE notification.id = user_notification.id_notification AND user_notification.id_user = ?";
$notifications = $navBarController->getPrepareInstance()->prepare($query, $element, "all");
?>
<!-- navbar-->
<header class="header">
    <nav class="navbar">
        <div class="container-fluid">
            <div class="navbar-holder d-flex align-items-center justify-content-between">
                <div class="navbar-header"><a id="toggle-btn" href="#" class="menu-btn"><i class="fa fa-bars"> </i></a><a href="/painel" class="navbar-brand">
                        <?php if (empty($registry)) { ?>
                            <div class="brand-text d-none d-md-inline-block"><span>Equipe </span><strong class="text-primary">Brainsoft</strong></div></a></div>
                <?php
            } else { ?>
                    <div class="brand-text d-none d-md-inline-block"><span>Cartório </span><strong class="text-primary"> <?= $registry[0]['name']; ?></strong></div></a>
                </div>
            <?php
        } ?>
            <ul class="nav-menu list-unstyled d-flex flex-md-row align-items-md-center">
                <div class="form-check form-check-inline status-to-attendant">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="status-backup" value="backup" <?= $headerController->checkAttendantStatus($id, "backup") ?>>
                    <label class="form-check-label" for="status-backup">Backup</label>
                </div>
                <div class="form-check form-check-inline status-to-attendant">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="status-online" value="on" <?= $headerController->checkAttendantStatus($id, "on") ?>>
                    <label class="form-check-label" for="status-online">Online</label>
                </div>
                <div class="form-check form-check-inline status-to-attendant">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="status-training" value="training" <?= $headerController->checkAttendantStatus($id, "training") ?>>
                    <label class="form-check-label" for="status-training">Treinamento</label>
                </div>
                <li class="nav-item dropdown"><a id="notifications" rel="nofollow" data-target="#" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link"><i class="fa fa-bell"></i><span class="badge badge-warning"><?php $qtd = sizeof($notifications);
                                                                                                                                                                                                                                         echo $qtd; ?></span></a>
                    <ul aria-labelledby="notifications" class="dropdown-menu">

                        <?php for ($i = 0; $i < sizeof($notifications); $i++) { ?>
                            <li><a rel="nofollow" href="#" class="dropdown-item">
                                    <div class="notification d-flex justify-content-between">
                                        <div class="notification-content"><i class="fa fa-twitter"></i><?php $nfs = substr($notifications[$i]['description'], 0, 22);
                                                                                                        echo $nfs . "..." ?></div>
                                        <div class="notification-time"><small><?= $notifications[$i]['nDate'] ?></small></div>
                                    </div>
                                </a></li>
                        <?php
                    } ?>

                        <li><a rel="nofollow" href="#" class="dropdown-item all-notifications text-center"> <strong> <i class="fa fa-bell"></i>view all notifications</strong></a></li>
                    </ul>
                </li>
                <li class="nav-item"><a href="<?= $targets['Logout'] ?>" class="nav-link logout">Sair do Sistema<i class="fa fa-sign-out"></i></a></li>
            </ul>
        </div>
        </div>
    </nav>
</header>