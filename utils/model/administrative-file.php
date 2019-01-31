<?php
include_once('../controller/ctrl-file.php');

class File
{
	protected $id;
	protected $id_registry;
	protected $path_to_file;

	protected $connection;
    protected $myController;

	function __construct()
	{
    	$this->connection = new ConfigDatabase();
    	$this->myController = new FileController();
   	}

	public function delete($id, $paid_date)
	{
  		$sql = $this->connection->getConnection()->prepare("UPDATE `administrative_file` SET status = ?, paid_date = ? WHERE id = ?");
  		$result = $sql->execute(array("pago", $paid_date, $id));
    
    	//$this->myController->verifyResultRegister($result);
	}

	public function setExpirationDate($date, $id)
	{
		$sql = $this->connection->getConnection()->prepare("UPDATE `administrative_file` SET expiration_date = ? WHERE id = ?");
  		$result = $sql->execute(array($date, $id));
	}
}
