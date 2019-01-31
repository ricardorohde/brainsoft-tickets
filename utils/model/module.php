<?php
include_once __DIR__.'/../../common/config.php';

class Module
{
	protected $id;
	protected $description;
	protected $id_category;

	function __construct()
	{
    	$this->connection = new ConfigDatabase();
	}

	public function register($name, $id_category, $limit_time)
	{
		$sql = $this->connection->getConnection()->prepare("INSERT INTO ticket_module (id, description, id_category, limit_time, status) VALUES (NULL, ?, ?, ?, ?)");
	    $result = $sql->execute(array($name, $id_category, $limit_time, "ativo"));
	    return $result;
	}

	public function findIdByNameAndCategory($name, $id_category)
	{
		$sql = $this->connection->getConnection()->prepare("SELECT id FROM ticket_module WHERE description = ? AND id_category = ?");
    	$sql->execute(array($name, $id_category));

   		while ($row = $sql->fetch()) {
  			$id = $row['id'];
		}

		if (isset($id)) {
			return $id;
		} else {
			return $id = "";
		}
	}

	public function active($id)
	{
		$sql = $this->connection->getConnection()->prepare("UPDATE ticket_module SET status = ? WHERE id = ?");
	        
	    $result = $sql->execute(array("ativo", $id));
	    return $result;
	}

	public function delete($id)
	{
		$sql = $this->connection->getConnection()->prepare("UPDATE ticket_module SET status = ? WHERE id = ?");
	        
	    $result = $sql->execute(array("inativo", $id));
	    return $result;
	}

	public function update($id, $value)
	{
		$sql = $this->connection->getConnection()->prepare("UPDATE ticket_module SET limit_time = ? WHERE id = ?");
	        
	    $result = $sql->execute(array($value, $id));
	    return $result;
	}
}
