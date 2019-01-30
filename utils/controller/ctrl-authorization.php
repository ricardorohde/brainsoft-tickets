<?php
	include_once __DIR__.'/../../common/prepare.php';
	include_once __DIR__.'/../../common/session.php';
?>

<?php 

class AuthorizationController{

	private $prepareInstance;
	private $session;
	private $idInSession;

	function __construct(){
		$this->setPrepareInstance();
		$this->setSession();
		$this->setIdLoginInSession();
	}

	function setPrepareInstance(){
		$this->prepareInstance = new PrepareQuery();
	}
	function setSession(){
		$this->session = new Session("");
	}
	function setIdLoginInSession(){
		$this->idInSession = $_SESSION['login'];
	}

	function authorizePage($authorizations){
		foreach ($authorizations as $auth) {
			$this->session->setSession(trim($auth['name']) . "_page_" . $this->idInSession);
			$this->session->authorize();	
		}
	}

	function findAuthorizationsById(){
		$elements_to_authorizations = [$this->idInSession, "yes"];
		$query = "SELECT DISTINCT page.name FROM page, authorization_user_page WHERE authorization_user_page.id_user = ? AND authorization_user_page.id_page = page.id AND authorization_user_page.access = ?";
	  $authorizations = $this->prepareInstance->prepare($query, $elements_to_authorizations, "all");

	  return $authorizations;
	}

}

?>