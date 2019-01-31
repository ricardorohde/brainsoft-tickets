<?php
include_once __DIR__.'/../../common/config.php';

class Employee
{
	protected $id;
	protected $name;
	protected $email;
	protected $onChat;
	protected $tGroup;
	protected $idCredential;
	protected $idRole;

	protected $connection;
	protected $myController;
	
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

    public function getConn()
    {
        return $this->connection;
    }

    public function setConn($conn)
    {
        $this->connection = $conn;
    }

    public function getController()
    {
        return $this->myController;
    }

    public function setController($controller)
    {
        $this->myController = $controller;
    }

    function __construct($myController)
    {
    	$this->setConn(new ConfigDatabase());
    	$this->setController($myController);
	}

    public function register()
    {
  		$sql = $this->getConn()->getConnection()->prepare("INSERT INTO employee (`id`, `name`, `email`, `t_group`, `id_credential`, `id_role`) VALUES (NULL, ?, ?, ?, ?, ?)");
  		$sql->bindValue(1, $this->getName());
		$sql->bindValue(2, $this->getEmail());
		$sql->bindValue(3, $this->getTGroup());
		$sql->bindValue(4, $this->getIdCredential());
		$sql->bindValue(5, $this->getIdRole());

  		return $sql->execute();	
    }

    public function update()
    {
    	$sql = $this->getConn()->getConnection()->prepare("UPDATE employee SET name = ?, email = ?, t_group = ?, id_role = ? WHERE id = ?");
    	$sql->bindValue(1, $this->getName());
		$sql->bindValue(2, $this->getEmail());
		$sql->bindValue(3, $this->getTGroup());
		$sql->bindValue(4, $this->getIdRole());
		$sql->bindValue(5, $this->getId());

  		return $sql->execute();
    }

    public function findAttendants()
    {
		$sql = $this->getConn()->getConnection()->prepare("SELECT id, name FROM employee WHERE t_group = ? AND on_chat = ? AND (SELECT COUNT(*) FROM ticket WHERE id_attendant = employee.id AND t_status = ?) < 2 ORDER BY name");
		$sql->bindValue(1, $this->getTGroup());
		$sql->bindValue(2, $this->getOnChat());
		$sql->bindValue(3, "aberto");
    	$sql->execute();

   		return $sql->fetchAll();
	}

	public function turnOn()
	{
		$sql = $this->getConn()->getConnection()->prepare("UPDATE employee SET on_chat = ? WHERE id_credential = ?");
    	$sql->bindValue(1, $this->getOnChat());
		$sql->bindValue(2, $this->getIdCredential());

  		return $sql->execute();
	}
}
