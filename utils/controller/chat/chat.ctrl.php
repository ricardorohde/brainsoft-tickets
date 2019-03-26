<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
require_once __DIR__ . "/../../model/chat.php";

class ChatController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function findByIdAndIdAttendant($idChat, $idAttendant)
    {
        $chat = new Chat($this, $this->prepareInstance);
        $chat->setId($idChat);
        return $chat->findByIdAndIdAttendant($idAttendant);
    }

    public function findById($id)
    {
        $chat = new Chat($this, $this->prepareInstance);
        $chat->setId($id);
        return $chat->findById();
    }

    public function new($id, $openingTime, $finalTime, $durationInMinutes)
    {
        $chat = new Chat($this, $this->prepareInstance);
        $chat->setId($id);
        $chat->setOpeningTime($openingTime);
        $chat->setFinalTime($finalTime);
        $chat->setDurationInMinutes($durationInMinutes);
        return $chat->register();
    }

    public function searchChatIdCtrl($id) {
        $chat = new Chat();

        return $chat->searchChatId($id);
    }

    public function update($idChat, $finalTime, $durationInMinutes)
    {
        $chat = new Chat($this, $this->prepareInstance);
        $chat->setIdChat($idChat);
        $chat->setFinalTime($finalTime);
        $chat->setDurationInMinutes($durationInMinutes);
        return $chat->update();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ChatController();
        }
        return self::$instance;
    }
}
