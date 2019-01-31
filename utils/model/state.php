<?php
class State
{
	private $id;
	private $description;
	private $initials;

	private $conn;
	private $myController;

	public function getId()
	{
	  return $this->id;
	}
	
	public function setId($id)
	{
	  $this->id = $id;
	}

	public function getDescription()
	{
	  return $this->description;
	}
	
	public function setDescription($description)
	{
	  $this->description = $description;
	}

	public function getInitials()
	{
	  return $this->initials;
	}
	
	public function setInitials($initials)
	{
	  $this->initials = $initials;
	}

	public function getMyController()
	{
	  return $this->myController;
	}
	
	public function setMyController($myController)
	{
	  $this->myController = $myController;
	}
}
