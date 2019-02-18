<?php
class Module
{
	private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $description;
	private $idCategory;
	private $limitTime;
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

	public function getIdCategory()
	{
	  return $this->idCategory;
	}
	
	public function setIdCategory($idCategory)
	{
	  $this->idCategory = $idCategory;
	}

	public function getLimitTime() 
	{
		return $this->limitTime;
	}
	
	public function setLimitTime($limitTime) 
	{
		$this->limitTime = $limitTime;
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
   	}

   	public function findAll()
   	{
   		$query = "SELECT * FROM ticket_module";
   		return $this->prepareInstance->prepare($query, "", "all");
   	}

	public function findById()
	{
		$element = $this->getId();
		$query = "SELECT description, id_category FROM ticket_module WHERE id = ?";
		return $this->prepareInstance->prepare($query, $element, "");
	}

	public function register()
	{
		$elements = [$this->getDescription(), $this->getIdCategory(), $this->getLimitTime(), "ativo"];
		$query = "INSERT INTO ticket_module (id, description, id_category, limit_time, status) VALUES (NULL, ?, ?, ?, ?)";
		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function findIdByDescriptionAndCategory()
	{
		$elements = [$this->getDescription(), $this->getIdCategory()];
		$query = "SELECT id FROM ticket_module WHERE description = ? AND id_category = ?";
		$id = $this->prepareInstance->prepare($query, $elements, "")['id'];

		if (isset($id)) {
			return $id;
		} else {
			return $id = "";
		}
	}

	public function active()
	{
		$elements = ["ativo", $this->getId()];
		$query = "UPDATE ticket_module SET status = ? WHERE id = ?";
		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function delete()
	{
		$elements = ["inativo", $this->getId()];
		$query = "UPDATE ticket_module SET status = ? WHERE id = ?";
		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function update()
	{
		$elements = [$this->getLimitTime(), $this->getId()];
		$query = "UPDATE ticket_module SET limit_time = ? WHERE id = ?";
		return $this->prepareInstance->prepare($query, $elements, "");
	}
}
