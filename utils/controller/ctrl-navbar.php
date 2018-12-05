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

		function makeMenu($targets){
			$menu = "";
			$id   = $this->idInSession;

			foreach ($targets as $name => $target) {
				$target = explode(" ", $target);
				if (isset($_SESSION[$name.'_page_'.$id])) {
					$menu = $menu . "<li><a href='" . $target[0] . "'><i class='fa " . $target[2] . "'></i><span>" . $target[1] . "</span></a></li>";
				}
			}

			return $menu;
		}
	}

?>