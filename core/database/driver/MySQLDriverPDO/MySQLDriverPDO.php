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

namespace techweb\core\database\driver\MySQLDriverPDO;

use PDO;
use PDOException;
use techweb\config\Config;
use techweb\core\database\driver\GenericDriver;
use techweb\core\Error;

class MySQLDriverPDO implements GenericDriver
{
    private static $instance;

    /**
     * {@inheritdoc}
     * @see queryDatabase()
     */
    public function query(string $statement, array $values = [])
    {
        return $this->queryDatabase($statement, $values, false);
    }


    /**
     * Executes the given query in the database
     *
     * @param string $statement
     * @param array $values [optional]
     * @param bool $unique [optional]
     *
     * fetch only one result
     *
     * @return array|null the result, null if failed
     * @see query()
     * @see queryOne()
     */
    private function queryDatabase(string $statement, array $values = [], bool $unique)
    {
        try {
            $sql = self::getInstance()->prepare($statement);
            $sql->execute($values);

            if ($unique === true) {
                $result = $sql->fetch(PDO::FETCH_OBJ);

                return $result === false ? null : $result;
            }
            $result = $sql->fetchAll(PDO::FETCH_OBJ);

            return $result === false ? null : $result;
        } catch (PDOException $pdoException) {
            Error::create($pdoException->getMessage(), 500);
        }
    }

    private static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new PDO('mysql:dbname=' . Config::getDatabase('database') . ';host=' . Config::getDatabase('host'), Config::getDatabase('login'), Config::getDatabase('password'), [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $pdoException) {
                Error::create($pdoException->getMessage(), 500);
            }
        }

        return self::$instance;
    }

    /**
     * {@inheritdoc}
     * @see queryDatabase()
     */
    public function queryOne(string $statement, array $values = [])
    {
        return $this->queryDatabase($statement, $values, true);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(string $statement, array $values = [])
    {
        try {
            $sql = $this->getInstance()->prepare($statement);
            $sql->execute($values);
        } catch (PDOException $pdoException) {
            Error::create($pdoException->getMessage(), 500);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function lastInsertId()
    {
        return self::getInstance()->lastInsertId();
    }

}
