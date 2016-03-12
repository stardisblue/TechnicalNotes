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
use techweb\core\database\driver\GenericDriver;
use techweb\core\database\DriverFactory;
use techweb\core\exception\UnknownDriverException;

abstract class Model
{
    const QUERY = 'query';
    const VALUES = 'values';
    const DRIVER = 'driver';
    protected static $table;
    protected static $primary;
    private static $olddriver;

    protected $query;
    protected $params;
    private $driver;

    public function __construct(GenericDriver $driver, Query $query)
    {
        //$this->driver = DriverFactory::get(Config::getDatabase(self::DRIVER));
        $this->driver = $driver;
        $this->query = $query;
    }

    public static function lastInsertId(): string
    {
        return self::getDriver()->lastInsertId();
    }


    /**
     * @return GenericDriver
     * @deprecated use $driver instead
     * @see $driver
     */
    private static function getDriver(): GenericDriver
    {
        if (!isset(self::$olddriver)) {
            try {
                self::$olddriver = DriverFactory::get(Config::getDatabase('driver'));
            } catch (UnknownDriverException $exception) {
                Error::create($exception->getMessage(), 500);
            }
        }

        return self::$olddriver;
    }

    public static function insert(array $rows)
    {
        $firstHalfStatement = 'INSERT INTO ' . static::$table . ' (';

        $secondHalfStatement = ') VALUES (';

        foreach ($rows as $key => $value) {
            $firstHalfStatement .= $key . ', ';
            $key = ':' . $key;
            $secondHalfStatement .= $key . ', ';
            stripcslashes($value);
            trim($value);
        }

        $firstHalfRequest = rtrim($firstHalfStatement, ', ');
        $secondHalfRequest = rtrim($secondHalfStatement, ', ');

        $statement = $firstHalfRequest . $secondHalfRequest . ')';

        self::execute($statement, $rows);
    }

    public static function execute(string $statement, array $values = [])
    {
        self::getDriver()->execute($statement, $values);
    }

    /**
     * @return array
     *
     * @deprecated use find() instead
     * @see find()
     */
    public static function selectAll(): array
    {
        return self::query('SELECT * FROM ' . static::$table);
    }

    public static function query(string $statement, array $values = []): array
    {
        return self::getDriver()->query($statement, $values);
    }

    public static function select($primary)
    {
        return self::queryOne('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primary . ' = :primary', [':primary' => $primary]);
    }

    /**
     * @param string $statement
     * @param array $values
     * @return mixed
     *
     * @deprecated use first() instead
     */
    public static function queryOne(string $statement, array $values = [])
    {
        return self::getDriver()->queryOne($statement, $values);
    }

    public static function update(string $primary, array $rows)
    {
        $statement = 'UPDATE ' . static::$table . ' SET ';

        foreach ($rows as $key => $value) {
            $statement .= $key . ' = :' . $key . ', ';
            stripcslashes($value);
            trim($value);
        }

        $request = rtrim($statement, ', ');
        $request .= ' WHERE ' . static::$primary . ' = :primary';

        $rows[':primary'] = $primary;

        self::execute($request, $rows);
    }

    public static function delete(string $primary)
    {
        self::execute('DELETE FROM ' . static::$table . ' WHERE ' . static::$primary . ' = :primary', [':primary' => $primary]);
    }

    public static function count(): int
    {
        return self::queryOne('SELECT COUNT(' . static::$primary . ') AS count FROM ' . static::$table)->count;
    }

    /**
     * Return the first matching value of the query
     * @see queryOne()
     * @return mixed
     */
    public function first()
    {
        return $this->driver->queryOne($this->params[self::VALUES], $this->params[self::VALUES]);
    }

}
