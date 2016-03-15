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
use techweb\core\Query;

class MySQLDriverPDO implements GenericDriver
{
    private static $instance;

    /**
     * {@inheritdoc}
     * @see queryDatabase()
     */
    public function query(Query $query, string $entity = null)
    {
        return $this->queryDatabase($query, $entity, false);
    }


    /**
     * Executes the given query in the database
     *
     * @param Query $query
     * @param string $entity
     * @param bool $unique [optional]
     *
     * fetch only one result
     * @return array|null the result, null if failed
     * @see query()
     * @see queryOne()
     */
    private function queryDatabase(Query $query, string $entity = null, bool $unique)
    {
        try {
            $sql = self::getInstance()->prepare($query->get(Query::STATEMENT));
            $sql->execute($query->get(Query::VALUES));

            if ($unique === true) {
                if (null === $entity) {
                    $result = $sql->fetch(PDO::FETCH_OBJ);
                } else {
                    $result = $sql->fetch(PDO::FETCH_CLASS, $entity);
                }

                return $result === false ? null : $result;
            }

            if (null === $entity) {
                $result = $sql->fetchAll(PDO::FETCH_OBJ);
            } else {
                $result = $sql->fetchAll(PDO::FETCH_CLASS, $entity);

            }

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
    public function queryOne(Query $query, string $entity = null)
    {
        return $this->queryDatabase($query, $entity, true);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Query $query)
    {
        try {
            $sql = $this->getInstance()->prepare($query->get(Query::STATEMENT));
            $sql->execute($query->get(Query::VALUES));
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
