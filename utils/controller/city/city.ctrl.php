<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/city.php";

class CityController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    function findById($id)
    {
    	$city = new City($this->getInstance(), $this->prepareInstance);
    	$city->setId($id);
    	return $city->findById();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CityController();
        }
        return self::$instance;
    }
}
?>
