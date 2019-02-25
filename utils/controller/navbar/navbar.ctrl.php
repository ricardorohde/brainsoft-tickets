<?php
include_once __DIR__ . '/../../../common/prepare.php';
include_once __DIR__ . '/../../../common/session.php';

class NavBarController
{
	private static $instance;
	private $prepareInstance;

	private $session;
	private $idInSession;

	function getPrepareInstance()
	{
    	return $this->prepareInstance;
	}

	function getIdInSession()
	{
	    return $this->idInSession;
	}

	function __construct()
	{
		$this->setInstances();
		$this->setIdLoginInSession();
	}

	function setInstances()
	{
		$this->prepareInstance = PrepareQuery::getInstance();
		$this->session = new Session("");
	}

	function setIdLoginInSession()
	{
		session_start();
		$this->idInSession = $_SESSION['login'];
	}

	function cleanDataOfCall()
	{
		$this->session->cleanDataOfCalls();
	}

	function findRoleById()
	{
		$element = $this->idInSession;
		$query = "SELECT client.name as name, role.description as role FROM client, role WHERE client.id_credential = ? AND client.id_role = role.id";
		$resultDb = $this->prepareInstance->prepare($query, $element, "all");

	  	if (!$resultDb) {
	  		$element = $this->idInSession;
	  		$query = "SELECT employee.name as name, role.description as role FROM employee, role WHERE employee.id_credential = ? AND employee.id_role = role.id";
	    	$resultDb = $this->prepareInstance->prepare($query, $element, "all");
	  	}
	  	return $resultDb[0];
	}

	function makeMenu($targets)
	{
		$menu = "";
		$id = $this->idInSession;

		foreach ($targets as $name => $target) {
			$target = explode(" ", $target);

			if (@$target[1] == "Conta") {
				$menu = $menu . "<li><a href='" . $target[0] . "'><i class='fa " . $target[2] . "'></i><span> Minha " . $target[1] . "</span></a></li><hr>";
			}

			if (isset($_SESSION[$name.'_page_'.$id])) {
				$menu = $menu . "<li><a href='" . $target[0] . "'><i class='fas " . $target[2] . "'></i><span>" . $target[1] . "</span></a></li>";
			}
		}
		return $menu;
	}

	public static function getInstance()
	{
		if (!self::$instance) {
      		self::$instance = new NavBarController();
		}
    	return self::$instance;
	}
}
