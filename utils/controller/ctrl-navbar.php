<?php
	include_once __DIR__.'/../../commom/prepare.php';
	include_once __DIR__.'/../../commom/session.php';
?>

<?php 
	class NavBarController{

		private $prepareInstance;
		private $session;
		private $connection;
		private $idInSession;

		function getPrepareInstance() {
        return $this->prepareInstance;
    }

		function getConnection() {
        return $this->connection;
    }

    function getIdInSession() {
        return $this->idInSession;
    }

		function __construct(){
			$this->setInstances();
			$this->setPrepareAndConnection();
			$this->setIdLoginInSession();
			$this->unsetSessions();
		}

		function setInstances(){
			$this->prepareInstance = new PrepareQuery();
			$this->session         = new Session();
		}

		function setPrepareAndConnection(){
  		$this->connection = $this->prepareInstance->getConnToDatabase();
		}

		function setIdLoginInSession(){
			$this->idInSession = $_SESSION['login'];
		}

		function findRolesById(){
			$resultDb = $this->prepareInstance->prepare("SELECT client.name as name, role.description as role FROM client, role WHERE client.id_credential = ? AND client.id_role = role.id", $this->idInSession, "all");

		  if (!$resultDb){
		    $resultDb = $this->prepareInstance->prepare("SELECT employee.name as name, role.description as role FROM employee, role WHERE employee.id_credential = ? AND employee.id_role = role.id", $this->idInSession, "all");
		  }

		  return $resultDb[0];
		}

		function findAuthorizationsById(){
			$elements_to_authorizations = [$this->idInSession, "yes"];
		  return $this->prepareInstance->prepare("SELECT DISTINCT page.name FROM page, authorization_user_page WHERE authorization_user_page.id_user = ? AND authorization_user_page.id_page = page.id AND authorization_user_page.access = ?", 
		    $elements_to_authorizations, "all");
		}

		function unsetSessions(){
			$this->session->unset($_SESSION['administrative_page_' . $this->idInSession]);
			$this->session->unset($_SESSION['ticket_page_' . $this->idInSession]);
			$this->session->unset($_SESSION['user_page_' . $this->idInSession]);
			$this->session->unset($_SESSION['registry_page_' . $this->idInSession]);
			$this->session->unset($_SESSION['registration_page_' . $this->idInSession]);
			$this->session->unset($_SESSION['internal_queue_page_' . $this->idInSession]);
			$this->session->unset($_SESSION['authorization_page_' . $this->idInSession]);
			$this->session->unset($_SESSION['report_page_' . $this->idInSession]);
		}

		function authorizePage(){
			if(func_num_args() == 0){
				$this->session->authorize('administrative_page_' . $this->idInSession);
				$this->session->authorize('ticket_page_' . $this->idInSession);
				$this->session->authorize('user_page_' . $this->idInSession);
				$this->session->authorize('registry_page_' . $this->idInSession);
				$this->session->authorize('registration_page_' . $this->idInSession);
				$this->session->authorize('internal_queue_page_' . $this->idInSession);
				$this->session->authorize('authorization_page_' . $this->idInSession);
				$this->session->authorize('report_page_' . $this->idInSession);
			} else{
				$this->session->authorize(func_get_arg(0) . $this->idInSession);
			}			
		}

	}

?>