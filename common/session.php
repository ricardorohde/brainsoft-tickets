<?php
class Session
{
	private static $instance;
	
	private $session;
	private $content;

	public function getSession()
	{
	  return $this->session;
	}
	
	public function setSession($session)
	{
	  $this->session = $session;
	}

	public function getContent() 
	{
		return $this->content;
	}
	
	public function setContent($content) 
	{
		$this->content = $content;
	}

	public function __construct($session)
	{
		$this->session = $session;
	}

	public function set()
	{
		$_SESSION[$this->session] = $this->content;
	}

	public function unset()
	{
		session_unset($_SESSION[$this->session]);	
	}

	public function authorize()
	{
		$_SESSION[$this->session] = "authorized";
	}

	public function destroy()
	{
		session_destroy();
	}

	public function cleanDataOfCalls()
	{
		$schedule = date("H:i");

		if ($schedule == "12:00" || $schedule == "18:00") {
			for ($i=1; $i<8; $i++) {
				$this->setSession("user".$i);
				$this->unset();
			}
		}
	}

	public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Session("");
        }
        return self::$instance;
    }
}
