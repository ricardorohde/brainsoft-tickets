<?php
class EmailMarketing
{
    private $prepareInstance;
    private $myController;

	private $id;
	private $idSender;
	private $idRecipient;
	private $subject;
	private $message;
	private $status;
	private $info;
	private $sentAt;

	public function getId() 
	{
		return $this->id;
	}
	
	public function setId($id) 
	{
		$this->id = $id;
	}

	public function getIdSender() 
	{
		return $this->idSender;
	}
	
	public function setIdSender($idSender) 
	{
		$this->idSender = $idSender;
	}

	public function getIdRecipient() 
	{
		return $this->idRecipient;
	}
	
	public function setIdRecipient($idRecipient) 
	{
		$this->idRecipient = $idRecipient;
	}

	public function getSubject() 
	{
		return $this->subject;
	}
	
	public function setSubject($subject) 
	{
		$this->subject = $subject;
	}

	public function getMessage() 
	{
		return $this->message;
	}
	
	public function setMessage($message) 
	{
		$this->message = $message;
	}

	public function getStatus() 
	{
		return $this->status;
	}
	
	public function setStatus($status) 
	{
		$this->status = $status;
	}

	public function getInfo() 
	{
		return $this->info;
	}
	
	public function setInfo($info) 
	{
		$this->info = $info;
	}

	public function getSentAt() 
	{
		return $this->sentAt;
	}
	
	public function setSentAt($sentAt) 
	{
		$this->sentAt = $sentAt;
	}

    function __construct($controller, $prepareInstance)
    {
    	$this->myController = $controller;
        $this->prepareInstance = $prepareInstance;
	}

    public function register()
    {
    	$elements = [$this->getIdSender(), $this->getIdRecipient(), $this->getSubject(), $this->getMessage(), $this->getStatus(), $this->getInfo()];
    	$query = "INSERT INTO email_marketing (`id`, `id_sender`, `id_recipient`, `subject`, `message`, `status`, `info`) VALUES (NULL, ?, ?, ?, ?, ?, ?)";
        return $this->prepareInstance->prepare($query, $elements, "");
    }

    public function findAll()
    {
        $query = "SELECT * FROM email_marketing ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, "", "all");
    }

    public function findById()
    {
    	$elements = $this->getId();
    	$query = "SELECT * FROM email_marketing WHERE id = ?";
        return $this->prepareInstance->prepare($query, $elements, "");
    }

    public function findAllByDate()
    {
    	$element = $this->getSentAt() . "%";
    	$query = "SELECT * FROM email_marketing WHERE sent_at LIKE ? ORDER BY id DESC";
        return $this->prepareInstance->prepare($query, $element, "all");
    }
}
