<?php
include_once __DIR__.'/../../common/config.php';

class Credential
{
    private static $instance;
    private $prepareInstance;
    private $myController;

	protected $id;
	protected $login;
	protected $password;
	protected $b_salt;

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

	public function getLogin()
	{
        return $this->login;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getBSalt()
    {
        return $this->b_salt;
    }

    public function setId($id)
    {
    	$this->id = $id;
    }

    public function setLogin($login)
    {
        $this->login = $login;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setBSalt($b_salt)
    {
        $this->b_salt = $b_salt;
    }

    public function getConn()
    {
        return $this->connection;
    }

    public function setConn($conn)
    {
        $this->connection = $conn;
    }

    function __construct($controller, $prepareInstance)
    {
        $this->setMyController($controller);
        $this->setPrepareInstance($prepareInstance);
        $this->setConn(new ConfigDatabase());
    }

    public function findById() //NEW
    {
        $element = $this->getId();
        $query = "SELECT login FROM credential WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function checkLogin()
    {
		$sql = $this->getConn()->getConnection()->prepare("SELECT * FROM credential WHERE login = ?");
		$sql->bindValue(1, $this->getLogin());
		$sql->execute();

		while ($row = $sql->fetch()) {
  			$id = $row['id'];
	    	$passwd = $row['password'];
	    	$b_salt = $row['b_salt'];
		}

	    $bs_salt="ftosniarbsistemas";
	    $salted_hash = hash('sha256', $this->getPassword().$bs_salt.$b_salt);

	    if ($passwd == $salted_hash) {
	    	$this->myController->setHeader($id, $this->getPassword(), '200');
	    } else {
	    	$this->myController->setHeader(0, NULL, '404');
    	}
    }

    public function changePassword()
    {
    	//GERANDO A SENHA DO USUÁRIO COM O SALT 
	    $b_salt = $this->rand_string(20); 
	    $site_salt = "ftosniarbsistemas"; 
	    $salted_hash = hash('sha256', $this->getPassword().$site_salt.$b_salt);

	    echo $this->getId();

	    //ATUALIZANDO O ACESSO DO USUÁRIO
	    $sql = $this->getConn()->getConnection()->prepare("UPDATE credential SET password = ?, b_salt = ? WHERE id = ?");
	    $sql->bindValue(1, $salted_hash);
	    $sql->bindValue(2, $b_salt);
	    $sql->bindValue(3, $this->getId());
	    $result = $sql->execute();

	    $this->myController->verifyChangePass($result);
    }

    public function register()
    {
	    //GERANDO A SENHA DO USUÁRIO COM O SALT 
	    $b_salt = $this->rand_string(20);
	    $site_salt = "ftosniarbsistemas";
	    $salted_hash = hash('sha256', $this->getPassword().$site_salt.$b_salt);

	    //REGISTRANDO O ACESSO DO USUÁRIO
	    $sql = $this->getConn()->getConnection()->prepare("INSERT INTO credential (`id`, `login`, `password`, `b_salt`) VALUES (NULL, ?, ?, ?)");
	    $sql->bindValue(1, $this->getLogin());
	    $sql->bindValue(2, $salted_hash);
	    $sql->bindValue(3, $b_salt);
	    $sql->execute();

	    //RECEBENDO O ID DO ULTIMO REGISTRO FEITO EM 'CREDENTIAL'
	  	$sql = $this->getConn()->getConnection()->prepare("SELECT MAX(ID) as last FROM credential");
	  	$sql->execute();
	  	return $sql->fetchAll();
    }

    public function verifyIfExists()
    {
        $element = $this->getLogin();
        $query = "SELECT COUNT(*) as total FROM credential WHERE login LIKE ?";
        return $this->prepareInstance->prepare($query, $element, "");
	}

	private function rand_string($length)
	{
    	$str = "";
      	$chars = "ftosniarbabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
      	$size = strlen($chars);
      	
      	for ($i = 0;$i < $length;$i++) {
        	$str .= $chars[rand(0,$size-1)];
      	}
      	return $str; 
    }
}
