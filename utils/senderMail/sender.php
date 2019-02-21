<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . "/../../dashboard/vendor/marketing/PhpMailer/Exception.php";
require_once __DIR__ . "/../../dashboard/vendor/marketing/PhpMailer/PHPMailer.php";
require_once __DIR__ . "/../../dashboard/vendor/marketing/PhpMailer/SMTP.php";
require_once __DIR__ . "/../controller/marketing/email.ctrl.php";

class SenderMail
{
	private $recipientId;
	private $mail;
	private $destiny;
	private $subject;
	private $message;
	private $totalUp;
	private $path;

	private $emailController;

	function __construct($recipient, $destiny, $subject, $message, $totalUp, $path)
	{
		$this->recipientId = $recipient;
		
		$this->mail = new PHPMailer;
		$this->destiny = $destiny;
		$this->subject = $subject;
		$this->message = $message;
		$this->totalUp = $totalUp;
		$this->path = $path;

		$this->emailController = EmailController::getInstance();
	}

	public function sender()
	{
		$status = 0;
		$info = "";

		echo $this->subject;

		try {
			//Server settings
			$this->mail->CharSet = 'UTF-8';
			$this->mail->SMTPDebug = 0;
			$this->mail->isSMTP();
			$this->mail->Host = 'smtp.brainsoftsistemas.com.br';
			$this->mail->SMTPAuth = true;
			$this->mail->Username = 'comunicados@brainsoftsistemas.com.br';
			$this->mail->Password = 'comunicados5150';
			$this->mail->SMTPSecure = 'tls';
			$this->mail->Port = 587;
			$this->mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);

			//Recipients
			$this->mail->setFrom('comunicados@brainsoftsistemas.com.br', 'Brainsoft Sistemas');
			$this->mail->addAddress($this->destiny);

			//Attachments
			for ($i = 0; $i < $this->totalUp; $i++) {
				@$this->mail->addAttachment($this->path[$i]);
			}

			//Content
			$this->mail->Subject = $this->subject;
			$this->mail->msgHTML($this->message);

			//Send
			$this->mail->send();

			$status = 1;
			echo 'Sucesso! Mensagem enviada para: ' . $this->destiny . '!<br>';
		} catch (Exception $e) {
			$status = 0;
			$info = $this->mail->ErrorInfo;
			echo 'Erro! Mensagem não enviada para:' . $this->destiny . '! Descrição do Erro: ' . $this->mail->ErrorInfo . '<br>';
		}

		$this->emailController->new($_SESSION['login'], $this->recipientId, $this->subject, $this->message, $status, $info);
	}
}
