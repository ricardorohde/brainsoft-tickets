<?php
class State
{
    private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $description;
	private $initials;

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

	public function getInitials()
	{
	  return $this->initials;
	}
	
	public function setInitials($initials)
	{
	  $this->initials = $initials;
	}

	function __construct($controller, $prepareInstance)
    {
    	$this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
	}

	public function findById()
	{
		$element = $this->getId();
		$query = "SELECT * FROM state WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
	}

	public function findAll()
	{
        $query = "SELECT id, description FROM state ORDER BY description";
        return $this->prepareInstance->prepare($query, "", "all");
	}
}
