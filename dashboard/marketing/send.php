<?php
require_once __DIR__ . "/../../utils/senderMail/sender.php";

$send = new SendEmail($_POST);
$send->sendToAllRecipient();

class SendEmail
{
	private $totalUploads;
	private $pathOfUploads;

	private $subject;
	private $recipientId;
	private $recipientName;
	private $recipientRegistry;
	private $recipientEmail;
	private $recipientLogin;
	private $recipientPass;
	private $message;

	private $formatedMessage;

	function __construct($post)
	{
		$this->pathOfUploads = array();

		$this->setTimezone();
		$this->receiveAllData($post);
	}

	public function setTimezone()
	{
		date_default_timezone_set("Brazil/East");
	}

	public function receiveAllData($post)
	{
		$this->subject = $_POST['subject'];
		$this->recipientId = $_POST['id'];
		$this->recipientName = $_POST['client'];
		$this->recipientRegistry = $_POST['registry'];
		$this->recipientEmail = $_POST['email'];
		$this->recipientLogin = $_POST['login'];
		$this->recipientPass = "temp123";
		$this->message = $_POST['message'];

		$this->getFiles();
	}

	public function changeNameVariableInMessage($position)
	{
		$this->formatedMessage = str_replace('$cliente', $this->recipientName[$position], $this->message);
	}

	public function changeRegistryVariableInMessage($position)
	{
		$this->formatedMessage = str_replace('$cartorio', $this->recipientRegistry[$position], $this->formatedMessage);
	}

	public function changeLoginVariableInMessage($position)
	{
		$this->formatedMessage = str_replace('$usuario', $this->recipientLogin[$position], $this->formatedMessage);
	}

	public function changePassVariableInMessage()
	{
		$this->formatedMessage = str_replace('$senha', $this->recipientPass, $this->formatedMessage);
	}

	public function sendToAllRecipient()
	{
		$qtdRecipientRaw = count($this->recipientEmail);

		for ($i=0; $i < $qtdRecipientRaw; $i++) {
			if ($_POST['radio' . $i] == "send") {
				$this->changeNameVariableInMessage($i);
				$this->changeRegistryVariableInMessage($i);
				$this->changeLoginVariableInMessage($i);
				$this->changePassVariableInMessage();

				$sender = new SenderMail($this->recipientId[$i], $this->recipientEmail[$i], $this->subject, $this->formatedMessage, $this->totalUploads, $this->pathOfUploads);
				$sender->sender();

				$this->recipientEmail[$i] = "";
			}
		}

		$this->unlinkUploads();
	}

	public function getFiles()
	{
		if (isset($_FILES['file'])) {
			$name = $_FILES['file']['name'];
		    $tmp_name = $_FILES['file']['tmp_name'];
		    $this->totalUploads = count($tmp_name);

		    $allowedExts = array(".jpg", ".png", ".pdf", ".gif");

		    $dir = 'uploadedFiles/';

		    for($i = 0; $i < $this->totalUploads; $i++) {
		    	$ext = strtolower(substr($name[$i],-4));

		        if(in_array($ext, $allowedExts)) {
		           $name_of_raw_file = strtolower(substr($name[$i],0,-4));
		           $new_name = $name_of_raw_file . $ext;

		           move_uploaded_file($_FILES['file']['tmp_name'][$i], $dir.$new_name);

		           array_push($this->pathOfUploads, $dir.$new_name);
		        }
		    }
		}
	}

	public function unlinkUploads()
	{
		for ($i = 0; $i < $this->totalUploads; $i++) {
			$toRemove = @$this->pathOfUploads[$i];
			@unlink($toRemove);
		}
	}
}
