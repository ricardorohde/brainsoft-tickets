<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . '/../../../common/session.php'; 

class AuthorizationController
{
	private static $instance;
	private $prepareInstance;
	private $sessionInstance;

	private $idInSession;
	private $authorizations;

	function __construct()
	{
		$this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
		$this->sessionInstance = new Session("");
	}

	public function getIdInSession()
	{
	  	return $this->idInSession;
	}
	
	public function setIdInSession($idInSession)
	{
	  	$this->idInSession = $idInSession;
	}

	public function getAuthorizations()
	{
	  	return $this->authorizations;
	}
	
	public function setAuthorizations($authorizations)
	{
	  	$this->authorizations = $authorizations;
	}

	function authorizePage()
	{
		foreach ($this->authorizations as $auth) {
			$this->sessionInstance->setSession(trim($auth['name']) . "_page_" . $this->idInSession);
			$this->sessionInstance->authorize();	
		}
	}

	function findAuthorizationsById()
	{
		$elements_to_authorizations = [$this->idInSession, "yes"];
		$query = "SELECT DISTINCT page.name FROM page, authorization_user_page WHERE authorization_user_page.id_user = ? AND authorization_user_page.id_page = page.id AND authorization_user_page.access = ?";
	  	$authorizations = $this->prepareInstance->prepare($query, $elements_to_authorizations, "all");

	  	return $authorizations;
	}

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new AuthorizationController();
        }
        return self::$instance;
    }
}
