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
    const SQL = 'sql';
    const VALUES = 'values';
    const DRIVER = 'driver';

    protected static $table;
    protected static $primary;
    private static $olddriver;

    protected $query;
    private $driver;

    public function __construct(GenericDriver $driver)
    {
        //$this->driver = DriverFactory::get(Config::getDatabase(self::DRIVER));
        $this->driver = $driver;
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

    /**
     * @param string $statement
     * @param array $values
     * @deprecated
     * @see find()
     */
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

    public function query(string $statement, array $values = []): array
    {
        return $this->driver->query($statement, $values);
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

    /**
     * @deprecated see find instead
     * @see find()
     * @return int
     */
    public static function count(): int
    {
        return self::queryOne('SELECT COUNT(' . static::$primary . ') AS count FROM ' . static::$table)->count;
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

    /**
     * Getter
     *
     * @return string the name of the table
     */
    public static function getTable(): string
    {
        return self::$table;
    }

    public function select($primary)
    {
        $this->createQuery()->select()->from(self::$table)->where([self::$primary => $primary]);

        return $this->first();
        //return self::queryOne('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primary . ' = :primary', [':primary' => $primary]);
    }

    public function createQuery(): Query
    {
        $this->query = new Query();

        return $this->query;
    }

    /**
     * Return the first matching value of the query
     * @see queryOne()
     * @see find('first')
     * @return mixed
     *
     */
    public function first()
    {
        $params = $this->query->getParams();

        return $this->driver->queryOne($params[self::SQL], $params[self::VALUES]);
    }

    /**
     * Executes the query
     *
     * $options can either be :
     *
     * - a string :
     *      - 'all' `SELECT * ` from the current table
     *      - 'first' select first matching element
     * - an array :
     * ```
     *  ['select' => ['id', 'title'],
     *      'from' =>
     *          ['articles', new BlogModel()],
     *      'where' =>
     *          [
     *              'conditions' => 'id = :id',
     *              'values' => [':id' => 2]
     *          ],
     *      'append' => 'GROUP BY id'
     *  ]
     * ```
     *
     * @see queryOne()
     * @param string|array|null $options
     * @return mixed
     */
    public function find($options = null): array
    {
        if (is_array($options)) {
            $this->createQuery()->select($options['select'])->from($options['from'])->where($options['where']);
            if (isset($options['append'])) {
                $this->query->appendSQL($options['append']);
            }
            $params = $this->query->getParams();

            return $this->driver->query($params[self::SQL], $params[self::VALUES]);
        }

        if ($options == 'first') {
            $params = $this->query->getParams();
            $this->driver->queryOne($params[self::SQL], $params[self::VALUES]);
        }

        if (empty($query) && $options === null || $options === 'all') {
            $this->createQuery()->select()->from(self::$table);

            return $this->driver->query($this->query->getParams()[self::SQL]);
        }

    }

}
