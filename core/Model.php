<?php

namespace techweb\core;

use techweb\config\Config;

use techweb\core\database\DriverFactory;
use techweb\core\database\driver\GenericDriver;
use techweb\core\exception\UnknownDriverException;

abstract class Model
{
    private static $driver;

    protected static $table;

    protected static $primary;

	private static function getDriver(): GenericDriver
	{
		if (!isset(self::$driver)) {
            try {
                self::$driver = DriverFactory::get(Config::getDatabase('driver'));
            } catch (UnknownDriverException $exception) {
                Error::create($exception->getMessage(), 500);
            }
		}

		return self::$driver;
	}

    public static function query(string $statement, array $values = []): array
    {
        return self::getDriver()->query($statement, $values);
    }

    public static function queryOne(string $statement, array $values = [])
    {
        return self::getDriver()->queryOne($statement, $values);
    }

    public static function execute(string $statement, array $values = [])
    {
        self::getDriver()->execute($statement, $values);
    }

    public static function lastInsertId(): string
    {
        return self::getDriver()->lastInsertId();
    }

    public static function insert(array $rows)
    {
		$firstHalfStatement = 'INSERT INTO ' . static::$table . ' (';

		$secondHalfStatement = ') VALUES (';

		foreach ($rows as $key => $value)
		{
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

    public static function selectAll(): array
    {
		return self::query('SELECT * FROM ' . static::$table);
    }

    public static function select($primary)
    {
		return self::queryOne('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primary . ' = :primary', [':primary' => $primary]);
    }

    public static function update(string $primary, array $rows)
    {
		$statement = 'UPDATE ' . static::$table . ' SET ';

		foreach ($rows as $key => $value)
		{
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

}
