<?php 
  include_once __DIR__.'/../../commom/config.php';

  $connection = new ConfigDatabase();

  $id = $_SESSION['login'];

  if(isset($id)){
    $sql = $connection->getConnection()->prepare("SELECT client.name as name, role.description as role FROM client, role WHERE client.id_credential = ? AND client.id_role = role.id");
    $sql->execute(array($id));

    $resultDb = $sql->fetchAll();

    if (!$resultDb) {
      $sql = $connection->getConnection()->prepare("SELECT employee.name as name, role.description as role FROM employee, role WHERE employee.id_credential = ? AND employee.id_role = role.id");
      $sql->execute(array($id));

      $resultDb = $sql->fetchAll();
    }

    $sql_authorization = $connection->getConnection()->prepare("SELECT DISTINCT page.name FROM page, authorization_user_page WHERE authorization_user_page.id_user = ? AND authorization_user_page.id_page = page.id AND authorization_user_page.access = ?");
    $sql_authorization->execute(array($id, "yes")); $row_sql_authorization = $sql_authorization->fetchAll();
  } else {
    $resultDb = "Não identificado";
  }
?>

<?php
  unset($_SESSION['administrative_page_'.$id]);
  unset($_SESSION['ticket_page_'.$id]);
  unset($_SESSION['user_page_'.$id]);
  unset($_SESSION['registry_page_'.$id]);
  unset($_SESSION['registration_page_'.$id]);
  unset($_SESSION['internal_queue_page_'.$id]);
  unset($_SESSION['authorization_page_'.$id]);
  unset($_SESSION['report_page_'.$id]);
?>

<!-- Side Navbar -->
    <nav class="side-navbar">
      <div class="side-navbar-wrapper">
        <div class="sidenav-header d-flex align-items-center justify-content-center">
          <div class="sidenav-header-inner text-center"><img src="/dashboard/img/avatar-1.jpg" alt="person" class="img-fluid rounded-circle">
            <h2 class="h5 text-uppercase"><?= $resultDb[0]['name']; ?></h2><span class="text-uppercase"><?= $resultDb[0]['role'] == "supportBrain" ? "Suporte Brain" : $resultDb[0]['role']?></span>
          </div>
          <div class="sidenav-header-logo"><a href="index.php" class="brand-small text-center"> <strong>S</strong><strong class="text-primary">B</strong></a></div>
        </div>
        <?php if ($resultDb[0]['role'] == "adm") : ?>
          <div class="admin-menu">
            <ul id="side-admin-menu" class="side-menu list-unstyled"> 
              <li><a href="<?php echo $target_adm?>"><i class="fa fa-files-o"></i><span>Boletos</span></a></li>
              <li><a href="<?php echo $target_ticket?>"><i class="fa fa-ticket"></i><span>Tickets</span></a></li>
              <li><a href="<?php echo $target_user?>"><i class="fa fa-user-circle"></i><span>Usuários</span></a></li>
              <li><a href="<?php echo $target_registry?>"><i class="fa fa-home"></i><span>Cartórios</span></a></li>
              <li><a href="<?php echo $target_registration_forms?>"><i class="fa fa-caret-square-o-right"></i><span>Módulos</span></a></li>
              <li><a href="<?php echo $target_internal_queue?>"><i class="fa fa-sort-amount-asc"></i><span>Fila Interna</span></a></li>
              <li><a href="<?php echo $target_authorization?>"><i class="fa fa fa-key"></i><span>Controle de Acesso</span></a></li>
              <li><a href="<?php echo $target_report?>"><i class="fa fa fa-key"></i><span>Relatórios</span></a></li>
              <?php 
                $_SESSION['administrative_page_'.$id] = "authorized"; 
                $_SESSION['ticket_page_'.$id] = "authorized"; 
                $_SESSION['user_page_'.$id] = "authorized"; 
                $_SESSION['registry_page_'.$id] = "authorized"; 
                $_SESSION['registration_page_'.$id] = "authorized"; 
                $_SESSION['internal_queue_page_'.$id] = "authorized";
                $_SESSION['authorization_page_'.$id] = "authorized";
                $_SESSION['report_page_'.$id] = "authorized";
              ?>
            </ul>
          </div>  
        <?php elseif ($resultDb[0]['role'] == "supportBrain" || $resultDb[0]['role'] == "Atendimento") : ?>
          <div class="main-menu">
            <ul id="side-admin-menu" class="side-menu list-unstyled"> 
              <?php foreach ($row_sql_authorization as $authorization) : ?> 
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
                <?php elseif ($authorization['name'] == "Autorizacoes") : ?>
                  <li><a href="<?php echo $target_registration_forms?>"><i class="fa fa-caret-square-o-right"></i><span>Controle de Acesso</span></a></li>
                  <?php $_SESSION['authorization_page_'.$id] = "authorized"?>
                <?php elseif ($authorization['name'] == "Relatorios") : ?>
                  <li><a href="<?php echo $target_report?>"><i class="fa fa-caret-square-o-right"></i><span>Relatórios</span></a></li>
                  <?php $_SESSION['report_page_'.$id] = "authorized"?>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>  
        <?php else : ?>
          <div class="main-menu">
            <ul id="side-main-menu" class="side-menu list-unstyled">  
              <?php foreach ($row_sql_authorization as $authorization) : ?> 
                <?php if ($authorization['name'] == "Boletos") : ?>
                  <li><a href="<?php echo $target_adm?>"><i class="fa fa-files-o"></i><span>Boletos</span></a></li>
                  <?php $_SESSION['administrative_page_'.$id] = "authorized";?>
                <?php elseif ($authorization['name'] == "Relatorios") : ?>
                  <li><a href="<?php echo $target_report?>"><i class="fa fa-caret-square-o-right"></i><span>Relatórios</span></a></li>
                  <?php $_SESSION['report_page_'.$id] = "authorized"?>
                <?php endif; ?>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>
      </div>
    </nav>