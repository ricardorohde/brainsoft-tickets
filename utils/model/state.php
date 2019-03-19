<?php
class State
{
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

    public function register()
    {
    	$elements = [$this->getDescription(), $this->getInitials()];
        $query = "INSERT INTO state (`id`, `description`, `initials`) VALUES (NULL, ?, ?)";
        return $this->prepareInstance->prepareStatus($query, $elements, "");  			
    }

    public function update()
    {
    	$elements = [$this->getDescription(), $this->getInitials(), $this->getId()];
        $query = "UPDATE state SET description = ?, initials = ? WHERE id = ?";
        return $this->prepareInstance->prepareStatus($query, $elements, "");
	}

    public function remove()
    {
        $element = $this->getId();
        $query = "DELETE FROM state WHERE id = ?";
        return $this->prepareInstance->prepareStatus($query, $element, "");
    }

    public function findById()
    {
        $element = $this->getId();
        $query = "SELECT * FROM state WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function findAll()
    {
        $query = "SELECT * FROM state ORDER BY description";
        return $this->prepareInstance->prepare($query, "", "all");
    }
}
