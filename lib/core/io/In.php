<?php

namespace techweb\lib\core\io;

class In
{
    public static function get(string $get)
    {
        return filter_input(INPUT_GET, $get, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    }

    public static function post(string $post)
    {
        return filter_input(INPUT_POST, $post, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    }

    public static function cookie(string $cookie)
    {
        return filter_input(INPUT_COOKIE, $cookie, FILTER_SANITIZE_FULL_SPECIAL_CHARS, FILTER_NULL_ON_FAILURE);
    }

    public static function session(string $session)
    {
        return isset($_SESSION[$session]) ? $_SESSION[$session] : null;
    }

    public static function uSession(string $session)
    {
        return isset($_SESSION[$session]) ? unserialize($_SESSION[$session]) : null;
    }

    public static function isSetPost(string ...$post): bool
    {
        foreach ($post as $data) {
            if (isset($_POST[$data]) === false) {
                return false;
            }
        }

        return true;
    }

    public static function isSetSession(string ...$session): bool
    {
        foreach ($session as $data) {
            if (isset($_SESSION[$data]) === false) {
                return false;
            }
        }

        return true;
    }

}
