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
		try {
			//Server settings
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

			$this->mail->send();
			echo 'Sucesso! Mensagem enviada para: ' . $this->destiny . '!<br>';
		} catch (Exception $e) {
			echo 'Erro! Mensagem não enviada para:' . $this->destiny . '! Descrição do Erro: ' . $this->mail->ErrorInfo . '<br>';
		}
	}
}
