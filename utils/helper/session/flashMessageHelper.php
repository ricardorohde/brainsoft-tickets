<?php

class FlashMessageHelper
{
    private static $instance;

    public function makeFlash($name, $type, $content, $redirect)
    {
        $alert = '<div class="alert alert-' . $type . ' mt-3" role="alert">' . $content . '</div>';
        $_SESSION[$name] = $alert;
        header("Location:" . $redirect);
        die();
    }

    public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new FlashMessageHelper();
		}
		return self::$instance;
	}
}
