<?php

class SessionHelper
{
    private static $instance;

    public function storeInSession($name, $content)
    {
        $_SESSION[$name] = $content;
    }

    public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new SessionHelper();
		}
		return self::$instance;
	}
}
