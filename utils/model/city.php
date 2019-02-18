<?php
class City
{
	private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $description;
	private $idState;

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

	public function getIdState()
	{
	  return $this->idState;
	}
	
	public function setIdState($idState)
	{
	  $this->idState = $idState;
	}

	function __construct($controller, $prepareInstance)
    {
    	$this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
   	}

   	public function findById() //NEW
   	{
   		$element = $this->getId();
        $query = "SELECT * FROM city WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
   	}

	public function findCitiesByState()
	{
        $element = $this->getIdState();
        $query = "SELECT id, description FROM city WHERE id_state = ? ORDER BY description";
        return $this->prepareInstance->prepare($query, $element, "all");
	}
}
