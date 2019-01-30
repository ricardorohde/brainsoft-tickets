<?php
class Session
{
	private $session;

	public function getSession() {
	  return $this->session;
	}
	
	public function setSession($session) {
	  $this->session = $session;
	}

	function __construct($session)
	{
		$this->session = $session;
	}

	function unset()
	{
		unset($this->session);	
	}

	function authorize()
	{
		$_SESSION[$this->session] = "authorized";
	}

	function destroy()
	{
		session_destroy();
	}

	function cleanDataOfCalls()
	{
		$schedule = date("H:i");

		if ($schedule == "12:00" || $schedule == "18:00") {
			for ($i=1; $i<8; $i++) {
				$this->setSession("user".$i);
				$this->unset();
			}
		}
	}
}
