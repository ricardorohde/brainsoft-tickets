<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/city.php";

new CityJsController();

class CityJsController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->verifyDataReceived($_POST);
    }

    public function verifyDataReceived($post)
    {
        if (isset($post['valor'])){
            $idState = $post['valor'];

            $city = new City(self::$instance, $this->prepareInstance);
            $city->setIdState($idState);
            $cities = $city->findCitiesByState();

            $info = utf8_encode("<option value=''>Selecione uma cidade...</option>");
            echo $info;

            for ($i=0; $i < count($cities); $i++) { 
                $id = $cities[$i]['id'];
                $desc = $cities[$i]['description'];
                $option = "<option value='$id'>$desc</option>";
                echo $option;
            }
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new CityJsController();
        }
        return self::$instance;
    }
}
