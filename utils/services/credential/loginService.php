<?php

class LoginService
{
    private static $instance;

    public function isSamePassword($newPass, $confirmationPass)
    {
        if ($newPass == $confirmationPass) {
            return true;
        } else {
            return false;
        }
    }

    public function rand_string($length)
    {
        $str = "";
        $chars = "ftosniarbabcdefghijklmanopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);

        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

    public static function getInstance($prepareInstance)
    {
        if (!self::$instance) {
            self::$instance = new LoginService($prepareInstance);
        }
        return self::$instance;
    }
}
