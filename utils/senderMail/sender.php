<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../../dashboard/vendor/marketing/PhpMailer/Exception.php";
require_once __DIR__ . "/../../dashboard/vendor/marketing/PhpMailer/PHPMailer.php";
require_once __DIR__ . "/../../dashboard/vendor/marketing/PhpMailer/SMTP.php";

class SenderMail
{
	private $mail;
	private $destiny;
	private $subject;
	private $message;
	private $totalUp;
	private $path;

	function __construct($destiny, $subject, $message, $totalUp, $path)
	{
		$this->mail = new PHPMailer;
		$this->destiny = $destiny;
		$this->subject = $subject;
		$this->message = $message;
		$this->totalUp = $totalUp;
		$this->path = $path;
	}

	public function sender()
	{
		//Tell PHPMailer to use SMTP
		$this->mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$this->mail->SMTPDebug = 1;
		//Set the hostname of the mail server
		$this->mail->Host = 'smtp.brainsoftsistemas.com.br';
		//Set the SMTP port number - likely to be 25, 465 or 587
		$this->mail->Port = 587;
		//Whether to use SMTP authentication
		$this->mail->SMTPAuth = true;

		$this->mail->SMTPOptions = array(
		    'ssl' => array(
		        'verify_peer' => false,
		        'verify_peer_name' => false,
		        'allow_self_signed' => true
		    )
		);
		//Username to use for SMTP authentication
		$this->mail->Username = 'comunicados@brainsoftsistemas.com.br';
		//Password to use for SMTP authentication
		$this->mail->Password = 'comunicados5150';
		//Set who the message is to be sent from
		$this->mail->setFrom('comunicados@brainsoftsistemas.com.br', 'Brainsoft Sistemas');
		//Set who the message is to be sent to
		$this->mail->addAddress($this->destiny);
		//Set the subject line
		$this->mail->Subject = $this->subject;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$this->mail->msgHTML($this->message);
		//send the message, check for errors
		//Attach an image file
		for ($i=0; $i < $this->totalUp; $i++) {
			$this->mail->addAttachment($this->path[$i]);
		}
		
		if (!$this->mail->send()) {
		    echo 'Mailer Error: ' . $this->mail->ErrorInfo;
		} else {
		    echo 'Message sent!';
		}
	}
}

