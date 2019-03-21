<?php 
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
include_once __DIR__ . '/../../model/log.php';

class LogController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    function __construct()
    {
        $this->sessionController = Session::getInstance();
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function new($area, $action, $content, $status, $referenceId)
    {
        $log = new Log($this, $this->prepareInstance);
        $log->setArea($area);
        $log->setAction($action);
        $log->setContent($content);
        $log->setStatus($status);
        $log->setReferenceId($referenceId);
        $log->setWhoDid($_SESSION['login']);
        $log->register();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new LogController();
        }
        return self::$instance;
    }
}

?>
