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

namespace techweb\core\database;

use techweb\core\database\driver\GenericDriver;
use techweb\core\database\driver\MySQLDriverPDO\MySQLDriverPDO;
use techweb\core\database\driver\SQLiteDriverPDO\SQLiteDriverPDO;
use techweb\core\exception\UnknownDriverException;

class DriverFactory
{
    const MYSQL_PDO = 'MySQLPDO';
    const SQLITE_PDO = 'SQLitePDO';

    public static function get(string $driverConstant): GenericDriver
    {
        switch ($driverConstant) {
            case self::MYSQL_PDO:
                return new MySQLDriverPDO();
            case self::SQLITE_PDO:
                return new SQLiteDriverPDO();
            default:
                throw new UnknownDriverException('Driver ' . $driverConstant . ' does not exists');
        }
    }

}