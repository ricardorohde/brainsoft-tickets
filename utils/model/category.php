<?php
class CategoryModule
{
	private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $description;
	private $tGroup;

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

	public function getTGroup()
	{
	    return $this->tGroup;
	}

	public function setTGroup($tGroup)
	{
	    $this->tGroup = $tGroup;
	}

	function __construct($controller, $prepareInstance)
    {
    	$this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
   	}

	public function register()
	{
		$elements = [$this->description, $this->tGroup];
		$query = "INSERT INTO category_module (id, description, t_group) VALUES (NULL, ?, ?)";
		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function findById() // NEW
	{
		$element = $this->getId();
		$query = "SELECT description FROM category_module WHERE id = ?";
		return $this->prepareInstance->prepare($query, $element, "");
	}

	public function findIdByDescription()
	{
		$element = $this->description;
		$query = "SELECT id FROM category_module WHERE description LIKE ?";
		$id = $this->prepareInstance->prepare($query, $element, "")['id'];

		if (isset($id)) {
			return $id;
		} else {
			return $id = "";
		}
	}

	public function findCategoryByIdAndOrder(){
		$element = $this->getId();
		$query = "SELECT * FROM category_module WHERE id = ? ORDER BY id DESC";
		return $this->prepareInstance->prepare($query, $element, "");
	}
}
