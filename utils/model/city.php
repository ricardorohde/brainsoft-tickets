<?php
class City
{
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
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

    public function register()
    {
    	$elements = [$this->description, $this->idState];
        $query = "INSERT INTO city (`id`, `description`, `id_state`) VALUES (NULL, ?, ?)";
        return $this->prepareInstance->prepareStatus($query, $elements, ""); 			
    }

    public function update()
    {
    	$elements = [$this->description, $this->idState, $this->id];
        $query = "UPDATE city SET description = ?, id_state = ? WHERE id = ?";
        return $this->prepareInstance->prepareStatus($query, $elements, "");
	}

    public function remove()
    {
    	$element = $this->getId();
        $query = "DELETE FROM city WHERE id = ?";
        return $this->prepareInstance->prepareStatus($query, $element, "");
    }

    public function findById() //NEW
    {
        $element = $this->getId();
        $query = "SELECT * FROM city WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function findAll()
    {
        $query = "SELECT * FROM city ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, "", "all");
    }

    public function findCitiesByState()
    {
        $element = $this->getIdState();
        $query = "SELECT id, description FROM city WHERE id_state = ? ORDER BY description";
        return $this->prepareInstance->prepare($query, $element, "all");
    }

    public function findDataBySqlIds($sqlIds)
	{
		$query = sprintf("SELECT city.id, city.description, state.description as state FROM city, state WHERE city.id IN(%s) AND city.id_state = state.id ORDER BY id DESC", $sqlIds);
		return $this->prepareInstance->prepare($query, "", "all");
	}   
}
