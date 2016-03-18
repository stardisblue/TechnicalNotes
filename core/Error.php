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

namespace techweb\core;

use techweb\config\Config;
use techweb\core\exception\UnknownErrorException;

class Error
{

    /**
     * Triggers when there is an error
     *
     * @param string $errorMessage
     * @param int $errorCode
     * @throws UnknownErrorException
     */
    public static function create(string $errorMessage, int $errorCode = 404)
    {
        if (Config::isDebug()) {
            die($errorMessage);
        } else {
            self::show($errorCode);
        }
    }

    /**
     * Shows the error code
     *
     * @param int $errorCode
     * @throws UnknownErrorException
     */
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
