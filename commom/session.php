<?php

class Session{

	function unset($session){
		unset($session);	
	}

	function authorize($session){
		$_SESSION[$session] = "authorized";
	}

}

?>