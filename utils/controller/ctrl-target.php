<?php

class TargetController {

	public function targetRoot() {
		$targets = array(
      "Billet" => "administrativo Administrativo fa-files-o",
      "Authorization" => "autorizacoes Autorizações fa-caret-square-o-right",
      "Registry" => "cartorios Cartórios fa-home",
      "Queue" => "fila-interna Fila fa-sort-amount-asc",
      "Module" => "cadastros Módulos fa-caret-square-o-right",
      "Report" => "relatorios Relatórios fa-caret-square-o-right",
      "Ticket" => "tickets Tickets fa-ticket",
      "User" => "usuarios Usuários fa-user-circle",     
    
      "Logout" => "logout"
    );

    return $targets;
	} 

	public function targetPrevious() {
		$targets = array(
      "Billet" => "../administrativo Administrativo fa-files-o",
      "Ticket" => "../tickets Tickets fa-ticket",
      "User" => "../usuarios Usuários fa-user-circle",
      "Registry" => "../cartorios Cartórios fa-home",
      "Module" => "../cadastros Módulos fa-caret-square-o-right",
      "Queue" => "../fila-interna Fila fa-sort-amount-asc",
      "Authorization" => "../autorizacoes Autorizações fa-caret-square-o-right",
      "Report" => "../relatorios Relatórios fa-caret-square-o-right",

      "Logout" => "../logout"
    );

    return $targets;
	}

}

?>