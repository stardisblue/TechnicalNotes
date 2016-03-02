<?php

namespace techweb\core\database;

use techweb\core\database\driver\GenericDriver;
use techweb\core\exception\UnknownDriverException;
use techweb\core\database\driver\MySQLDriverPDO\MySQLDriverPDO;
use techweb\core\database\driver\SQLiteDriverPDO\SQLiteDriverPDO;

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