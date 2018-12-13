<?php 
  include_once __DIR__.'/../../utils/controller/ctrl-navbar.php';

  $controller = new NavBarController();
  $prepareInstance = $controller->getPrepareInstance();
  $connection = $controller->getConnection();
  $id = $controller->getIdInSession();
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
            <?php 
              $root = "http://localhost/dashboard/";
              $targets = array(
                "Billet" => $root . "administrativo Administrativo fa-files-o",
                "Authorization" => $root . "autorizacoes Autorizações fa-caret-square-o-right",
                "Registry" => $root . "cartorios Cartórios fa-home",
                "Queue" => $root . "fila-interna Fila fa-sort-amount-asc",
                "Module" => $root . "cadastros Módulos fa-caret-square-o-right",
                "Report" => $root . "relatorios Relatórios fa-caret-square-o-right",
                "Ticket" => $root . "tickets Tickets fa-ticket",
                "User" => $root . "usuarios Usuários fa-user-circle",             
                "Logout" => $root . "logout"
              );
            ?>
            <?= $controller->makeMenu($targets); ?>
          </ul>
        </div>  
      </div>
    </nav>