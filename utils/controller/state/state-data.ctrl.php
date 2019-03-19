<?php
include_once __DIR__ . "/../../../common/session.php";
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . "/../../model/state.php";

new StateDataController();

class StateDataController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    private $sessionController;

    private $dataReceived;
    private $urlRequest;

    function __construct()
    {
        $this->sessionController = Session::getInstance();
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
                    header("Location:../../../dashboard/estados");
                    break;
            }
        } else {
            $this->verifyUrlRequest();
        }
    }

    private function verifyUrlRequest()
    {
        if ($this->urlRequest[2] == 'estado') {
            if ($this->urlRequest[3] == 'remover' && $this->urlRequest[4] != '') {
                $this->remove();
            }
        }
        header("Location:../../../dashboard/estados");
    }

    public function new()
    {
        $state = new State($this, $this->prepareInstance);
        $state->setDescription($this->dataReceived['descState']);
        $state->setInitials(strtoupper($this->dataReceived['initialsState']));
        $result = $state->register();
        $this->setSession($result, "state", "registrado", "registrar");
    }

    public function update()
    {
        $state = new State($this, $this->prepareInstance);
        $state->setId($this->dataReceived['idState']);
        $state->setDescription($this->dataReceived['descState']);
        $state->setInitials(strtoupper($this->dataReceived['initialsState']));
        $result = $state->update();
        $this->setSession($result, "state", "atualizado", "atualizar");
    }

    public function remove()
    {
        $state = new State($this, $this->prepareInstance);
        $state->setId($this->urlRequest[4]);
        $result = $state->remove();
        $this->setSession($result, "state", "removido", "remover");
    }

    public function setSession($result, $sender, $verbOk, $verbNo)
    {
        if ($result == 1) {
            $this->sessionController->setSession($sender . "Ok");
            $this->sessionController->setContent("<strong>Sucesso!</strong> Estado " . $verbOk . " com êxito.");
            $this->sessionController->set();
        } else {
            $this->sessionController->setSession($sender . "No");
            $this->sessionController->setContent("<strong>Erro!</strong> Problema ao " . $verbNo . " o estado.");
            $this->sessionController->set();
        }

        header("Location:../../../dashboard/estados");
    }
}
