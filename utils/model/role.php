<?php
include_once __DIR__.'/../../common/config.php';

class Role
{
	private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $description;
	private $type;
	private $status;

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

	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	}

	public function getDescription()
	{
	    return $this->description;
	}

	public function setDescription($description)
	{
	    $this->description = $description;
	}

	public function getType()
	{
	    return $this->type;
	}

	public function setType($type)
	{
	    $this->type = $type;
	}

	public function getStatus()
	{
	    return $this->status;
	}

	public function setStatus($status)
	{
	    $this->status = $status;
	}

	function __construct($controller, $prepareInstance)
    {
    	$this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
        $this->connection = new ConfigDatabase();
   	}

   	public function findAll()
   	{
   		$query = "SELECT * FROM role";
   		return $this->prepareInstance->prepare($query, "", "all");
   	}

   	public function findAllByType() //NEW
   	{
   		$element = $this->getType();
   		$query = "SELECT * FROM role WHERE type = ?";
   		return $this->prepareInstance->prepare($query, $element, "all");
   	}

   	public function findRoleByStatusAndId() //NEW
   	{
   		$elements = [$this->getStatus(), $this->getId()];
   		$query = "SELECT * FROM role WHERE status = ? AND id = ?";
   		return $this->prepareInstance->prepare($query, $elements, "");
   	}

	public function findAllByStatusAndType()
	{
		$elements = ["ativo", $this->getType()];
   		$query = "SELECT id, description FROM `role` WHERE status = ? AND type = ? ORDER BY `description`";
   		return $this->prepareInstance->prepare($query, $elements, "all");
	}

	public function register()
	{
		$elements = [$this->getDescription(), $this->getType(), "ativo"];
   		$query = "INSERT INTO role (id, description, type, status) VALUES (NULL, ?, ?, ?)";
   		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function active()
	{
		$elements = ["ativo", $this->getId()];
   		$query = "UPDATE role SET status = ? WHERE id = ?";
   		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function delete()
	{
		$elements = ["inativo", $this->getId()];
   		$query = "UPDATE role SET status = ? WHERE id = ?";
   		return $this->prepareInstance->prepare($query, $elements, "");
	}
}
