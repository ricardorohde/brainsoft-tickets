<?php
class Employee
{
	private static $instance;
	private $prepareInstance;
	private $myController;

	protected $id;
	protected $name;
	protected $email;
	protected $onChat;
	protected $tGroup;
	protected $idCredential;
	protected $idRole;

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

	public function getOnChat()
	{
		return $this->onChat;
	}

	public function setOnChat($onChat)
	{
		$this->onChat = $onChat;
	}

	public function getTGroup()
	{
		return $this->tGroup;
	}

	public function setTGroup($tGroup)
	{
		$this->tGroup = $tGroup;
	}

	public function getIdCredential()
	{
		return $this->idCredential;
	}

	public function setIdCredential($idCredential)
	{
		$this->idCredential = $idCredential;
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
		$elements = [$this->getName(), $this->getEmail(), $this->getTGroup(), $this->getIdCredential(), $this->getIdRole()];
		$query = "INSERT INTO employee (`id`, `name`, `email`, `t_group`, `id_credential`, `id_role`) VALUES (NULL, ?, ?, ?, ?, ?)";
		return $this->prepareInstance->prepareStatus($query, $elements, "");
	}

	public function update()
	{
		$elements = [$this->getName(), $this->getEmail(), $this->getTGroup(), $this->getIdRole(), $this->getId()];
		$query = "UPDATE employee SET name = ?, email = ?, t_group = ?, id_role = ? WHERE id = ?";
		return $this->prepareInstance->prepareStatus($query, $elements, "");
	}

	public function findAll()
	{
		$query = "SELECT * FROM employee ORDER BY id DESC";
		return $this->prepareInstance->prepare($query, "", "all");
	}

	public function findAllByGroupAndName()
	{
		$elements = ["nivel1", "nivel2"];
		$query = "SELECT id, name, on_chat FROM employee WHERE t_group = ? OR t_group = ? ORDER BY t_group, name";
		return $this->prepareInstance->prepare($query, $elements, "all");
	}

	public function findById()
	{
		$element = $this->getId();
		$query = "SELECT * FROM employee WHERE id = ?";
		return $this->prepareInstance->prepare($query, $element, "");
	}

	public function findByName()
	{
		$element = $this->getName();
		$query = "SELECT * FROM employee WHERE name = ?";
		return $this->prepareInstance->prepare($query, $element, "");
	}

	public function findByCredential()
	{
		$elements = $this->getIdCredential();
		$query = "SELECT * FROM employee WHERE id_credential = ?";
		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function findAttendants()
	{
		$elements = [$this->getTGroup(), "off", "training", "aberto"];
		$query = "SELECT id, name FROM employee WHERE t_group = ? AND (on_chat != ? AND on_chat != ?) AND (SELECT COUNT(*) FROM ticket WHERE id_attendant = employee.id AND t_status = ?) < 2 ORDER BY name";
		return $this->prepareInstance->prepare($query, $elements, "all");
	}

	public function turnOn()
	{
		$elements = [$this->getOnChat(), $this->getIdCredential()];
		$query = "UPDATE employee SET on_chat = ? WHERE id_credential = ?";
		return $this->prepareInstance->prepare($query, $elements, "");
	}

	public function filterByTwoGroups()
	{
		$element = ["nivel1", "nivel2"];
		$query = "SELECT id, name FROM employee WHERE t_group = ? OR t_group = ?";
		return $this->prepareInstance->prepare($query, $element, "all");
	}

	public function findToForward()
	{
		$elements = ["nivel1", "nivel2", "off", "training", "aberto"];
		$query = "SELECT id, name, id_credential FROM employee WHERE (t_group = ? OR t_group = ?) AND (on_chat != ? AND on_chat != ?) AND (SELECT COUNT(*) FROM ticket WHERE id_attendant = employee.id AND t_status = ?) < 2 ORDER BY t_group, name";
		return $this->prepareInstance->prepare($query, $elements, "all");
	}

	public function verifyOnChat()
	{
		$element = [$this->getIdCredential()];
		$query = "SELECT on_chat FROM employee WHERE id_credential = ?";
		return $this->prepareInstance->prepare($query, $element, "");
	}

	public function findDataBySqlIds($sqlIds)
	{
		$query = sprintf("SELECT id, name, email FROM employee WHERE id IN(%s) ORDER BY id DESC", $sqlIds);
		return $this->prepareInstance->prepare($query, "", "all");
	}
}
