<?php
include_once __DIR__ . "/../navbar/navbar.ctrl.php";
require_once __DIR__ . "/../../model/client.php";

class ClientController
{
    private static $instance;
    private $prepareInstance;
    private $navBarController;

    public function setPrepareInstance($prepareInstance)
    {
        $this->prepareInstance = $prepareInstance;
    }

    function __construct()
    {
        $this->navBarController = NavBarController::getInstance();
        $this->prepareInstance = $this->navBarController->getPrepareInstance();
    }

    public function findById($id)
    {
        $client = new Client($this, $this->prepareInstance);
        $client->setId($id);
        return $client->findById();
    }

    public function findAll()
    {
        $client = new Client($this, $this->prepareInstance);
        return $client->findAll();
    }

    public function findIdRegistryByIdClient($idClient){      
      $client = new Client($this, $this->prepareInstance);
      $client->setId($idClient);

      return $client->findIdRegistry();
    }

    public function findByIdCredential($idCredential)
    {
        $client = new Client($this, $this->prepareInstance);
        $client->setIdCredential($idCredential);
        return $client->findByIdCredential();
    }

    public function findAllEmailNotNull()
    {
        $client = new Client($this, $this->prepareInstance);
        return $client->findAllByEmailNotNull();
    }

    public function findAllByStateEmailNotNull($state)
    {
        $client = new Client($this, $this->prepareInstance);
        return $client->findAllByStateEmailNotNull($state);
    }

    public function findAllByRegistryEmailNotNull($registry)
    {
        $client = new Client($this, $this->prepareInstance);
        return $client->findAllByRegistryEmailNotNull($registry);
    }

    function new($data){ // NEW
        $client = new Client($this, $this->prepareInstance);
        $client->setName($data['name']);
        $client->setEmail($data['email']);
        $client->setIdCredential($data[0]);
        $client->setIdRegistry($data['registry']);
        $client->setIdRole($data['role']);

        $client->register();
    }

    function update($data){ // NEW
        $client = new Client($this->getInstance(), $this->prepareInstance);
        $client->setName($data['name']);
        $client->setEmail($data['email']);
        $client->setIdRegistry($data['registry']);
        $client->setIdRole($data['role']);
        $client->setId($data['id_user']);

        $client->update();
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new ClientController();
        }
        return self::$instance;
    }
}
