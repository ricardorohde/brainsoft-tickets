<?php
include_once __DIR__ . "/../../../common/session.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../log/log.ctrl.php";
include_once __DIR__ . "/../../model/state.php";

new StateController();

class StateController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $sessionController;
    private $logController;

    private $dataReceived;
    private $urlRequest;

    private $allStates;
    private $stateToEdit;
    private $currentIdState;
    private $currentDescriptionState;

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    public function getAllStates()
    {
        return $this->allStates;
    }

    public function setAllStates($allStates)
    {
        $this->allStates = $allStates;
    }

    public function getStateToEdit()
    {
        return $this->stateToEdit;
    }

    function __construct()
    {
        $this->sessionController = Session::getInstance();
        $this->logController = LogController::getInstance();
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
        $this->dataReceived = $_POST;
        $this->urlRequest = explode("/", $_SERVER["REQUEST_URI"]);

        $this->currentIdState = '';
        $this->currentDescriptionState = '';

        $this->verifyDataReceived();
    }

    public function verifyDataReceived()
    {
        if (isset($this->dataReceived['submitFromState'])) {
            switch ($this->dataReceived['submitFromState']) {
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
        if ($this->urlRequest[2] == 'estado') {
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
        $state = new State($this, $this->prepareInstance);
        $state->setDescription($this->dataReceived['descState']);
        $state->setInitials(strtoupper($this->dataReceived['initialsState']));
        $result = $state->register();

        $this->currentDescriptionState = $this->dataReceived['descState'];

        $this->setSession($result, 'new', 'registrado', 'registrar');
    }

    public function update()
    {
        $state = new State($this, $this->prepareInstance);
        $state->setId($this->dataReceived['idState']);
        $state->setDescription($this->dataReceived['descState']);
        $state->setInitials(strtoupper($this->dataReceived['initialsState']));
        $result = $state->update();

        $this->currentIdState = $this->dataReceived['idState'];
        $this->currentDescriptionState = $this->dataReceived['descState'];

        $this->setSession($result, 'update', 'atualizado', 'atualizar');
    }

    public function show()
    {
        $this->stateToEdit = $this->findById($this->urlRequest[3]);
    }

    public function remove()
    {
        $state = new State($this, $this->prepareInstance);
        $state->setId($this->urlRequest[4]);
        $currentState = $state->findById();
        
        $this->currentIdState = $currentState['id'];
        $this->currentDescriptionState = $currentState['description'];

        $result = $state->remove();
        $this->setSession($result, 'remove', 'removido', 'remover');
    }

    public function findAllStates()
    {
        $state = new State($this, $this->prepareInstance);
        $this->allStates = $state->findAll();
        return $this->allStates;
    }

    public function findById($id)
    {
        $state = new State($this, $this->prepareInstance);
        $state->setId($id);
        return $state->findById();
    }

    public function setSession($result, $action, $verbOk, $verbNo)
    {
        if ($result == 1) {
            $this->sessionController->setSession("stateOk");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Estado " . $verbOk . " com êxito.");
            $this->sessionController->set();

            $this->logController->new('state', $action, $this->currentDescriptionState, 'success', $this->currentIdState);
        } else {
            $this->sessionController->setSession("stateNo");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao " . $verbNo . " o estado.");
            $this->sessionController->set();

            $this->logController->new('state', $action, $this->currentDescriptionState, 'fail', $this->currentIdState);
        }

        header("Location:../../../painel/estados");
    }

    public function verifyPermission()
    {
        if (!isset($_SESSION['State' . '_page_' . $_SESSION['login']])) {
            header("Location:../painel");
        }
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new StateController();
        }
        return self::$instance;
    }
}
