<?php

namespace techweb\core;

class AutoLoader
{

    private static function autoload(string $className)
    {
        if (strpos($className, 'techweb') === 0) {
            require_once ROOT . '/' . str_replace('\\', '/', str_replace('techweb', null, $className)) . '.php';
        }
    }

    public static function register()
    {
        spl_autoload_register([self::class, 'autoload']);
    }

}
