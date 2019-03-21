<?php
include_once __DIR__ . "/../../../common/session.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../state/state.ctrl.php";
include_once __DIR__ . "/../../model/city.php";

new CityController();

class CityController
{
	private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $sessionController;

    private $dataReceived;
    private $urlRequest;

    private $sqlCityIds;

    private $allStates;
    private $allCities;
    private $cityToEdit;

    public function getAllStates()
    {
        return $this->allStates;
    }

    public function getAllCities()
    {
        return $this->allCities;
    }

    public function getCityToEdit()
    {
        return $this->cityToEdit;
    }

    function __construct()
    {
        $this->sessionController = new Session("");
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->dataReceived = $_POST;
        $this->urlRequest = explode("/", $_SERVER["REQUEST_URI"]);
        $this->verifyDataReceived();
    }

    private function verifyDataReceived()
    {
        if (isset($this->dataReceived['submit'])) {
            switch ($this->dataReceived['submit']) {
                case 'new': 
                    $this->new();
                    break;
                case 'update':
                    $this->update();
                    break;
                default:
                    break;
            }
        } else {
            $this->verifyUrlRequest();
        }
    }

    private function verifyUrlRequest()
    {
        if ($this->urlRequest[2] == 'cidade') {
            switch ($this->urlRequest[3]) {
                case 'remover':
                    $this->remove();
                    break;
                default:
                    $this->show();
                    break;
            }
        }
    }

    public function new()
    {
        $city = new City($this, $this->prepareInstance);
        $city->setDescription($this->dataReceived['descCity']);
        $city->setIdState($this->dataReceived['idState']);
        $result = $city->register();
        $this->setSession($result, "city", "registrada", "registrar");
    }

    public function update()
    {
        $city = new City($this, $this->prepareInstance);
        $city->setId($this->dataReceived['idCity']);
        $city->setDescription($this->dataReceived['descCity']);
        $city->setIdState($this->dataReceived['idState']);
        $result = $city->update();
        $this->setSession($result, "city", "atualizada", "atualizar");
    }

    public function show()
    {
        $this->cityToEdit = $this->findById($this->urlRequest[3]);
    }

    public function remove()
    {
        $city = new City($this, $this->prepareInstance);
        $city->setId($this->urlRequest[4]);
        $result = $city->remove();
        $this->setSession($result, "city", "removida", "remover");
    }

    function findById($id)
    {
    	$city = new City($this, $this->prepareInstance);
    	$city->setId($id);
    	return $city->findById();
    }

    public function findAllCities()
    {
        $city = new City($this, $this->prepareInstance);
        $cities = $city->findAll();
        $cityIds = array_column($cities, 'id');
        $this->sqlCityIds = implode(',', $cityIds);
        $this->allCities = $cities;
    }

    public function findDataOfCities()
    {
        $city = new City($this, $this->prepareInstance);
        return $city->findDataBySqlIds($this->sqlCityIds);
    }

    public function findAllStates()
    {
        $stateController = StateController::getInstance();
    	$this->allStates = $stateController->findAllStates();
    }

    public function setSession($result, $sender, $verbOk, $verbNo)
    {
        if ($result == 1) {
            $this->sessionController->setSession($sender . "Ok");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Cidade " . $verbOk . " com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession($sender . "No");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao " . $verbNo . " a cidade.");
            $this->sessionController->set();
        }

        header("Location:../../../dashboard/cidades");
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['City'.'_page_'.$_SESSION['login']])) {
            header("Location:../dashboard");
        }
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
