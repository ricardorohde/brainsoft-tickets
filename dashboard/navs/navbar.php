<?php 
  include_once __DIR__.'/../../utils/controller/navbar/navbar.ctrl.php';

  $navBarController = new NavBarController();
  $id = $navBarController->getIdInSession();
?>

<!-- Side Navbar -->
    <nav class="side-navbar">
      <div class="side-navbar-wrapper">
        <div class="sidenav-header d-flex align-items-center justify-content-center">
          <div class="sidenav-header-inner text-center"><img src="/dashboard/img/avatar-1.jpg" alt="person" class="img-fluid rounded-circle">
            <h2 class="h5 text-uppercase"><?= $navBarController->findRoleById()['name']; ?></h2>
            <span class="text-uppercase">
              <?= $navBarController->findRoleById()['role'] == "supportBrain" ? "Suporte Brain" : 
                                                                           $navBarController->findRoleById()['role']?>       
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
            <?php 
              $root = "http://" . $_SERVER["HTTP_HOST"] . "/dashboard/";
              $targets = array(
                "Account"       => $root . "conta Conta fa-user",
                "Billet"        => $root . "administrativo Administrativo fa-file-alt",
                "Authorization" => $root . "autorizacoes Autorizações fa-key",
                "Registry"      => $root . "cartorios Cartórios fa-home",
                "Queue"         => $root . "fila-interna Fila fa-sort-amount-down",
                "Marketing"     => $root . "marketing/email.php Marketing fa-mail-bulk",
                "Module"        => $root . "cadastros Módulos fa-plus-square",
                "Report"        => $root . "relatorios Relatórios fa-chart-pie",
                "Ticket"        => $root . "tickets Tickets fa-ticket-alt",
                "User"          => $root . "usuarios Usuários fa-users",             
                "Logout"        => $root . "logout"
              );
            ?>
            <?= $navBarController->makeMenu($targets); ?>
          </ul>
        </div>  
      </div>
    </nav>