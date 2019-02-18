<?php
class Registry
{
	private static $instance;
    private $prepareInstance;
    private $myController;

	private $id;
	private $name;
	private $idCity;

	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	}

	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function getIdCity()
	{
	    return $this->idCity;
	}

	public function setIdCity($idCity)
	{
	    $this->idCity = $idCity;
	}

	function __construct($controller, $prepareInstance)
    {
    	$this->myController = $controller;
        $this->prepareInstance = $prepareInstance;
   	}

	function findRegistries()
	{
		$element = $this->getIdCity();
        $query = "SELECT id, name FROM registry WHERE id_city = ? ORDER BY name";
        return $this->prepareInstance->prepare($query, $element, "all");
	}

	public function register()
	{
		$elements = [$this->getName(), $this->getIdCity()];
        $query = "INSERT INTO registry (id, name, id_city) VALUES (NULL, ?, ?)";
        return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function update()
	{
		$elements = [$this->getName(), $this->getIdCity(), $this->getId()];
        $query = "UPDATE registry SET name = ?, id_city = ? WHERE id = ?";
        return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function findAll()
	{
		$query = "SELECT * FROM registry ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, "", "all");
	}

	public function findById() // NEW
	{
		$element = $this->getId();
        $query = "SELECT * FROM registry WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
	}

	public function findIdByName()
	{
	    $element = $this->getName();
        $query = "SELECT id FROM registry WHERE name LIKE ?";
        return $this->prepareInstance->prepare($query, $element, "all");
	}

	public function findFiles()
	{
   		$element = $this->getId();
        $query = "SELECT * FROM administrative_file WHERE id_registry = ?";
        return $this->prepareInstance->prepare($query, $element, "all");
	}

	function verifyIfExists()
	{
		$registry = utf8_decode($this->getName());

		$element = $registry;
        $query = "SELECT COUNT(*) as total FROM registry WHERE name LIKE ?";
        $total = $this->prepareInstance->prepare($query, $element, "all");
        return $total['total'];
	}
}
