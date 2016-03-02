<?php

namespace techweb\lib\core\security;

class Auth
{

    private static function hash(string $data): string
    {
        return hash('sha256', $data);
    }

    public static function login(string $name)
    {
        $_SESSION[$name] = self::hash($_SERVER['REMOTE_ADDR']);
    }

    public static function check(string $name): bool
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name] === self::hash($_SERVER['REMOTE_ADDR']);
        } else {
            return false;
        }
    }

}
