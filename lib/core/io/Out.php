<?php

namespace techweb\lib\core\io;

class Out
{

    public static function session(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function sSession(string $name, $value)
    {
        $_SESSION[$name] = serialize($value);
    }

    public static function sessionDestroy()
    {
        session_unset();
        session_destroy();
    }

    public static function unsetSession(string $name)
    {
        if (isset($_SESSION[$name])) {
            unset($_SESSION[$name]);
        }
    }

    public static function cookie(string $name, string $value, int $expire)
    {
        setcookie($name, $value, time() + $expire, null, null, false, true);
    }

    public static function unsetCookie(string $name)
    {
        setcookie($name, null, 0, null, null, false, false);
    }

}
