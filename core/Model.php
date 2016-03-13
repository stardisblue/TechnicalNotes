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
use techweb\core\exception\IncorrectQueryException;
use techweb\core\exception\UnknownDriverException;

abstract class Model
{
    const SQL = 'sql';
    const VALUES = 'values';
    const DRIVER = 'driver';

    protected static $table;
    protected static $primary = 'id';

    /**
     * @deprecated use $driver instead
     * @see $driver
     */
    private static $staticdriver;

    /** @var Query $query */
    protected $query;

    private $driver;

    public function __construct(GenericDriver $driver)
    {
        //$this->driver = DriverFactory::get(Config::getDatabase(self::DRIVER));
        $this->driver = $driver;
    }

    /**
     * @param array $rows
     * @deprecated use add() instead
     * @see add()
     */
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

        self::staticExecute($statement, $rows);
    }

    /**
     * @param string $statement
     * @param array $values
     * @deprecated
     * @see find()
     */
    public static function staticExecute(string $statement, array $values = [])
    {
        self::getDriver()->execute($statement, $values);
    }

    /**
     * @return GenericDriver
     * @deprecated use $driver instead
     * @see $driver
     */
    private static function getDriver(): GenericDriver
    {
        if (!isset(self::$staticdriver)) {
            try {
                self::$staticdriver = DriverFactory::get(Config::getDatabase('driver'));
            } catch (UnknownDriverException $exception) {
                Error::create($exception->getMessage(), 500);
            }
        }

        return self::$staticdriver;
    }

    /**
     * @return array
     *
     * @deprecated use find('all') instead
     * @see find()
     */
    public static function selectAll(): array
    {
        return self::staticQuery('SELECT * FROM ' . static::$table);
    }

    /**
     * Executes the given query
     *
     * @param string $statement
     * @param array $values
     * @return array
     * @deprecated use query() instead
     * @see query()
     */
    public static function staticQuery(string $statement, array $values = []): array
    {
        return self::getDriver()->query($statement, $values);
    }

    /**
     * @param string $primary
     * @param array $rows
     * @deprecated use save() instead
     * @see save()
     */
    public static function staticUpdate(string $primary, array $rows)
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

        self::staticExecute($request, $rows);
    }

    /**
     * @deprecated see find instead
     * @see find()
     * @return int
     */
    public static function staticCount(): int
    {
        return self::queryOne('SELECT COUNT(' . static::$primary . ') AS count FROM ' . static::$table)->count;
    }

    /**
     * @param string $statement
     * @param array $values
     * @return mixed
     *
     * @deprecated use first() instead
     * @see first()
     * @see find()
     */
    public static function queryOne(string $statement, array $values = [])
    {
        return self::getDriver()->queryOne($statement, $values);
    }

    /**
     * @return string <p>the name of the table associated to the model</p>
     */
    public static function getTable(): string
    {
        return self::$table;
    }

    /**
     * Executes the given query
     *
     * @param string $statement <p>SQL statement</p>
     * @param array $values [optional] <p>PDO SQL injection security</p>
     * @return array result of the query
     */
    public function query(string $statement, array $values = []): array
    {
        return $this->driver->query($statement, $values);
    }

    /**
     * Get the last inserted ID
     * @return string
     */
    public function lastInsertId(): string
    {
        return $this->driver->lastInsertId();
    }

    public function add(Entity $entity)
    {
        //TODO
    }

    public function update(Entity $entity)
    {
        // TODO
    }

    public function delete(Entity $primary)
    {
        //TODO
        $this->driver->execute('DELETE FROM ' . static::$table . ' WHERE ' . static::$primary . ' = :primary', [':primary' => $primary]);
    }

    public function get($primary)
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
     * @throws IncorrectQueryException
     */
    public function first()
    {
        if (is_null($this->query)) {
            throw new IncorrectQueryException('The query is incorrect');
        }

        $params = $this->query->getParams();

        return $this->driver->queryOne($params[self::SQL], $params[self::VALUES]);
    }

    /**
     * Executes the query
     *
     *<p>
     * Can either be :
     * <ul>
     * <li>a string :
     *  <ul>
     *      <li>'all' `SELECT * ` from the current table</li>
     *      <li>'first' select first matching element</li>
     *  </ul>
     * </li>
     * <li>an array :
     * <code>
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
     * </code>
     * </li>
     * <ul></p>
     *
     * @see GenericDriver::queryOne()
     * @param string|array|null $options [optional]
     * @return array|mixed
     * @throws IncorrectQueryException if the query is not correct
     */
    public function find($options = null): array
    {
        if (is_array($options)) {
            $this->createQuery()->select($options['select'])->from($options['from'])->where($options['where']);
            if (isset($options['append'])) {
                /** @var Query $query */
                $this->query->appendSQL($options['append']);
            }
            $params = $this->query->getParams();

            return $this->driver->query($params[self::SQL], $params[self::VALUES]);
        }

        if (is_null($this->query)) {
            throw new IncorrectQueryException('The query is incorrect');

        }

        if ($options == 'first') {
            $params = $this->query->getParams();
            $this->driver->queryOne($params[self::SQL], $params[self::VALUES]);
        }

        if (empty($query) && $options === null || $options === 'all') {
            $this->createQuery()->select()->from(self::$table);

            return $this->driver->query($this->query->getParams()[self::SQL]);
        }

        throw new IncorrectQueryException('The query is incorrect');

    }

}
