<?php
class Log
{
    private $prepareInstance;
    private $myController;

    private $id;
    private $area;
    private $action;
    private $content;
    private $status;
    private $referenceId;
    private $whoDid;
    private $time;

    public function getId()
    {
        return $this->id;
    }
 
    public function setId($id)
    {
        $this->id = $id;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function setArea($area)
    {
        $this->area = $area;
    }

    public function getAction()
    {
        return $this->action;
    }
 
    public function setAction($action)
    {
        $this->action = $action;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getReferenceId()
    {
        return $this->referenceId;
    }

    public function setReferenceId($referenceId)
    {
        $this->referenceId = $referenceId;
    }

    public function getWhoDid()
    {
        return $this->whoDid;
    }

    public function setWhoDid($whoDid)
    {
        $this->whoDid = $whoDid;
    }
 
    public function getTime()
    {
        return $this->time;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }
    
    public function getPrepareInstance()
    {
        return $this->prepareInstance;
    }

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    public function getMyController()
    {
        return $this->myController;
    }

    public function setMyController($myController)
    {
        $this->myController = $myController;
    }

    function __construct($controller, $prepareInstance)
    {
        $this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
    }

    public function register()
    {
    	$elements = [$this->area, $this->action, $this->content, $this->status, $this->referenceId, $this->whoDid];
        $query = "INSERT INTO log (`id`, `area`, `action`, `content`, `status`, `reference_id`, `who_did`, `time`) VALUES (NULL, ?, ?, ?, ?, ?, ?, NULL)";
        return $this->prepareInstance->prepare($query, $elements, "");
    }
}
