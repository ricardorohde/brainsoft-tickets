<?php 
  include_once __DIR__.'/../../utils/controller/ctrl-navbar.php';

  $controller = new NavBarController();
  $prepareInstance = $controller->getPrepareInstance();
  $connection = $controller->getConnection();
  $id = $controller->getIdInSession();

  $authorizations = $controller->findAuthorizationsById();
?>

<!-- Side Navbar -->
    <nav class="side-navbar">
      <div class="side-navbar-wrapper">
        <div class="sidenav-header d-flex align-items-center justify-content-center">
          <div class="sidenav-header-inner text-center"><img src="/dashboard/img/avatar-1.jpg" alt="person" class="img-fluid rounded-circle">
            <h2 class="h5 text-uppercase"><?= $controller->findRolesById()['name']; ?></h2>
            <span class="text-uppercase">
              <?= $controller->findRolesById()['role'] == "supportBrain" ? "Suporte Brain" : 
                                                                           $controller->findRolesById()['role']?>       
            </span>
          </div>
          <div class="sidenav-header-logo">
            <a href="index.php" class="brand-small text-center"> 
              <strong>S</strong><strong class="text-primary">B</strong>
            </a>
          </div>
        </div>
        <div class="main-menu">
          <ul id="side-admin-menu" class="side-menu list-unstyled"> 
            <?php foreach ($authorizations as $authorization) : ?> 
              <?php if ($authorization['name'] == "Boletos") : ?>
                <li><a href="<?php echo $target_adm?>"><i class="fa fa-files-o"></i><span>Boletos</span></a></li>
                <?php $_SESSION['administrative_page_'.$id] = "authorized";?>
              <?php elseif ($authorization['name'] == "Tickets") : ?>
                <li><a href="<?php echo $target_ticket?>"><i class="fa fa-ticket"></i><span>Tickets</span></a></li>
                <?php $_SESSION['ticket_page_'.$id] = "authorized"?>
              <?php elseif ($authorization['name'] == "Usuarios") : ?>
                <li><a href="<?php echo $target_user?>"><i class="fa fa-user-circle"></i><span>Usuários</span></a></li>
                <?php $_SESSION['user_page_'.$id] = "authorized"?>
              <?php elseif ($authorization['name'] == "Cartórios") : ?>
                <li><a href="<?php echo $target_registry?>"><i class="fa fa-home"></i><span>Cartórios</span></a></li>
                <?php $_SESSION['registry_page_'.$id] = "authorized"?>
              <?php elseif ($authorization['name'] == "Cadastros") : ?>
                <li><a href="<?php echo $target_registration_forms?>"><i class="fa fa-caret-square-o-right"></i><span>Módulos</span></a></li>
                <?php $_SESSION['registration_page_'.$id] = "authorized"?>
              <?php elseif ($authorization['name'] == "FilaInterna") : ?>
                <li><a href="<?php echo $target_internal_queue?>"><i class="fa fa-sort-amount-asc"></i><span>Fila Interna</span></a></li>
                <?php $_SESSION['internal_queue_page_'.$id] = "authorized"?>
              <?php elseif ($authorization['name'] == "Autorizações") : ?>
                <li><a href="<?php echo $target_registration_forms?>"><i class="fa fa-caret-square-o-right"></i><span>Controle de Acesso</span></a></li>
                <?php $_SESSION['authorization_page_'.$id] = "authorized"?>
              <?php elseif ($authorization['name'] == "Relatorios") : ?>
                <li><a href="<?php echo $target_report?>"><i class="fa fa-caret-square-o-right"></i><span>Relatórios</span></a></li>
                <?php $_SESSION['report_page_'.$id] = "authorized"?>
              <?php endif; ?>
            <?php endforeach; ?>
          </ul>
        </div>  
      </div>
    </nav>