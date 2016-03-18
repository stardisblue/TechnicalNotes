<?php
/**
 * TechnicalNotes <https://www.github.com/stardisblue/TechnicalNotes>
 * Copyright (C) 2016  TechnicalNotes Team
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace techweb\config;

use techweb\core\database\DriverFactory;
use techweb\core\Error;

class Config
{
    private static $_debug = true;

    private static $_database = [
        'driver' => DriverFactory::MYSQL_PDO,
        'host' => '',
        'database' => '',
        'login' => '',
        'password' => ''
    ];

    private static $_error = [
        '500' => '/internal-server-error',
        '404' => '/not-found',
        '403' => '/forbidden'
    ];

    private static $_encryption = [
        'mode' => MCRYPT_MODE_CBC,
        'cypher' => MCRYPT_RIJNDAEL_256,
        'key' => 'CHANGEME', // TODO
        'iv' => 'CHANGEME'
    ];

    public static function isDebug(): bool
    {
        return self::$_debug;
    }

    public static function getDatabase(string $key): string
    {
        if (isset(self::$_database[$key])) {
            return self::$_database[$key];
        } else {
            Error::create('Unknown database key : ' . $key, 500);
        }

        return null;
    }

    public static function getEncryption(string $key): string
    {
        if (isset(self::$_encryption[$key])) {
            return self::$_encryption[$key];
        } else {
            Error::create('Unknown encryption key : ' . $key, 500);
        }

        return null;
    }

    public static function getError(string $key): string
    {
        if (isset(self::$_error[$key])) {
            return self::$_error[$key];
        } else {
            Error::create('Unknown error key : ' . $key, 404);
        }

        return null;
    }

}
