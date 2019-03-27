<?php
class Client
{
	private static $instance;
    private $prepareInstance;
    private $myController;

	protected $id;
	protected $name;
	protected $email;
	protected $idCredential;
	protected $idRegistry;
	protected $idRole;

	protected $connection;

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

	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function getEmail()
	{
	    return $this->email;
	}

	public function setEmail($email)
	{
	    $this->email = $email;
	}

	public function getIdCredential()
	{
	    return $this->idCredential;
	}

	public function setIdCredential($idCredential)
	{
	    $this->idCredential = $idCredential;
	}

	public function getIdRegistry()
	{
	    return $this->idRegistry;
	}

	public function setIdRegistry($idRegistry)
	{
	    $this->idRegistry = $idRegistry;
	}

	public function getIdRole()
	{
	    return $this->idRole;
	}

	public function setIdRole($idRole)
	{
	    $this->idRole = $idRole;
	}

	function __construct($controller, $prepareInstance)
    {
    	$this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
   	}

   	public function register()
    {
    	$elements = [$this->getName(), $this->getEmail(), $this->getIdCredential(), $this->getIdRegistry(), $this->getIdRole()];
        $query = "INSERT INTO client (`id`, `name`, `email`, `id_credential`, `id_registry`, `id_role`) VALUES (NULL, ?, ?, ?, ?, ?)";
        return $this->prepareInstance->prepareStatus($query, $elements, "");		
    }

    public function update()
    {
    	$elements = [$this->getName(), $this->getEmail(), $this->getIdRegistry(), $this->getIdRole(), $this->getId()];
        $query = "UPDATE client SET name = ?, email = ?, id_registry = ?, id_role = ? WHERE id = ?";
        return $this->prepareInstance->prepareStatus($query, $elements, "");
	}
	
	public function remove()
    {
    	$element = $this->getId();
        $query = "DELETE FROM client WHERE id = ?";
		return $this->prepareInstance->prepareStatus($query, $element, "");
    }

	public function findAll()
	{
		$query = "SELECT * FROM client ORDER BY name";
        return $this->prepareInstance->prepare($query, "", "all");
	}

	public function findById()
	{
		$element = $this->getId();
        $query = "SELECT * FROM client WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
	}

	public function findByCredential()
    {
    	$element = $this->getIdCredential();
        $query = "SELECT * FROM client WHERE id_credential = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

	public function findIdRegistry()
    {
        $element = $this->getId();
        $query = "SELECT id_registry FROM client WHERE id = ?";
        $id = $this->prepareInstance->prepare($query, $element, "");
        return $id['id_registry'];
    }

    public function findClients()
    {
    	$element = $this->getIdRegistry();
        $query = "SELECT id, name FROM client WHERE id_registry = ? ORDER BY name";
        return $this->prepareInstance->prepare($query, $element, "all");
	}

	public function findByIdCredential()
    {
        $element = $this->idCredential;
        $query = "SELECT client.name as name, role.description as role FROM client, role WHERE client.id_credential = ? AND client.id_role = role.id";
        $resultDb = $this->prepareInstance->prepare($query, $element, "");

        if (!$resultDb) {
            $element = $this->idCredential;
            $query = "SELECT employee.name as name, role.description as role FROM employee, role WHERE employee.id_credential = ? AND employee.id_role = role.id";
            $resultDb = $this->prepareInstance->prepare($query, $element, "");
        }
        return $resultDb;
    }

	public function findAllByEmailNotNull() //used by MARKETING
	{
        $query = "SELECT client.id_credential as id, client.name, credential.login, client.email, registry.name as registry FROM client, credential, registry WHERE client.id_credential = credential.id AND client.email != '' AND client.id_registry = registry.id ORDER BY registry.name";
        return $this->prepareInstance->prepare($query, "", "all");
	}

	public function findAllByStateEmailNotNull($state) //used by MARKETING
	{
		$element = $state;
        $query = "SELECT client.id_credential as id, client.name, credential.login, client.email, registry.name as registry FROM client, credential, registry, city WHERE client.id_credential = credential.id AND client.email != '' AND client.id_registry = registry.id AND registry.id_city = city.id AND city.id_state = ? ORDER BY registry.name";
        return $this->prepareInstance->prepare($query, $element, "all");
	}

	public function findAllByRegistryEmailNotNull($registry) //used by MARKETING
	{
		$element = $registry;
        $query = "SELECT client.id_credential as id, client.name, credential.login, client.email, registry.name as registry FROM client, credential, registry WHERE client.id_credential = credential.id AND client.email != '' AND client.id_registry = registry.id AND registry.name = ? ORDER BY registry.name";
        return $this->prepareInstance->prepare($query, $element, "all");
	}

	public function findDataBySqlIds($sqlIds)
	{
		$query = sprintf("SELECT client.id, client.name, email, city.description as city FROM client, registry, city WHERE client.id IN(%s) AND client.id_registry = registry.id AND registry.id_city = city.id ORDER BY id DESC", $sqlIds);
		return $this->prepareInstance->prepare($query, "", "all");
	}
}
