<?php
include_once __DIR__ . '/../../common/config.php';

class Credential
{
    private $prepareInstance;
    private $myController;

    private $id;
    private $login;
    private $password;
    private $b_salt;

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

    public function findById()
    {
        $element = $this->getId();
        $query = "SELECT login FROM credential WHERE id = ?";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function findByLogin()
    {
        $element = $this->getLogin();
        $query = "SELECT id FROM credential WHERE login = ? ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, $element, "");
    }

    public function checkLogin()
    {
        $element = $this->getLogin();
        $query = "SELECT * FROM credential WHERE login = ?";
        $result = $this->prepareInstance->prepare($query, $element, "");

        $bs_salt = "ftosniarbsistemas";
        $salted_hash = hash('sha256', $this->getPassword() . $bs_salt . $result['b_salt']);

        if ($result['password'] == $salted_hash) {
            $this->myController->setHeader($result['id'], '200');
        } else {
            $this->myController->setHeader(0, '404');
        }
    }

    public function changePassword()
    {
        //GERANDO A SENHA DO USUÁRIO COM O SALT 
        $b_salt = $this->rand_string(20);
        $site_salt = "ftosniarbsistemas";
        $salted_hash = hash('sha256', $this->getPassword() . $site_salt . $b_salt);

        //ATUALIZANDO O ACESSO DO USUÁRIO
        $elements = [$salted_hash, $b_salt, $this->getId()];
        $query = "UPDATE credential SET password = ?, b_salt = ? WHERE id = ?";
        $result = $this->prepareInstance->prepareStatus($query, $elements, "");

        $this->myController->verifyChangePass($result);
    }

    public function register()
    {
        //GERANDO A SENHA DO USUÁRIO COM O SALT 
        $b_salt = $this->rand_string(20);
        $site_salt = "ftosniarbsistemas";
        $salted_hash = hash('sha256', $this->getPassword() . $site_salt . $b_salt);

        //REGISTRANDO O ACESSO DO USUÁRIO
        $elements = [$this->getLogin(), $salted_hash, $b_salt];
        $query = "INSERT INTO credential (`id`, `login`, `password`, `b_salt`) VALUES (NULL, ?, ?, ?)";
        return $this->prepareInstance->prepareStatus($query, $elements, "");
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

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }
}
