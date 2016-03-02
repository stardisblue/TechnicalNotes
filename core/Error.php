<?php

namespace techweb\core;

use techweb\config\Config;

use techweb\core\exception\UnknownErrorException;

class Error
{

    public static function create(string $errorMessage, int $errorCode = 404)
    {
        if (Config::isDebug()) {
            die($errorMessage);
        } else {
            self::show($errorCode);
        }
    }

    private static function show(int $errorCode)
    {
        switch ($errorCode) {
            case 403:
                header('HTTP/1.1 403 Forbidden');
                break;
            case 404:
                header('HTTP/1.1 404 Not Found');
                break;
            case 500:
                header('HTTP/1.1 500 Internal Server Error');
                break;
            default:
                throw new UnknownErrorException('Unknown error code ' . $errorCode);
        }

        header('Location: ' . WEB_ROOT . Config::getError($errorCode));

        exit;
    }

}
