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


use techweb\core\exception\IncorrectQueryException;

class Query
{
    const CONDITIONS = 'conditions';
    const STATEMENT = 'statement';
    const VALUES = 'values';
    private $params;

    private $firstelement;
    private $from;
    private $where;
    private $more;

    public function __construct()
    {
        $this->params = [];
    }

    /**
     * Generates the SELECT statement
     *
     * $params can be an array:
     *
     * ```
     * ['id', 'title', ...]
     * ```
     *
     * or a string :`'id, title'`
     *
     * @param string|array $params [optional]
     * @return Query
     * @throws IncorrectQueryException
     */
    public function select($params = '*'): self
    {
        $this->firstelement = 'SELECT ';

        if (is_string($params)) {
            $this->firstelement .= $params . ' ';
            $this->concat();

            return $this;
        } else if (is_array($params) && !empty($params)) {
            $this->firstelement .= implode(', ', $params) . ' ';
            $this->concat();

            return $this;
        }

        throw new IncorrectQueryException('Incorrect SELECT');

    }

    /**
     * Concatène pour fabriquer une requete sql
     */
    private function concat()
    {
        $this->params[self::STATEMENT] = rtrim($this->firstelement . $this->from . $this->where . $this->more) . ';';
    }

    /**
     * Adds a WHERE clause to the query
     * $params MUST HAVE this structure :
     *
     * ```
     * [
     *   'conditions' => 'id = :id AND ...',
     *   'values' => [':id'=> 2, ...]
     * ]
     * ```
     *
     * @param array $params
     * @return Query
     */
    public function where(array $params = []): self
    {
        if (empty($params)) {
            $this->where = '';
            $this->params[self::VALUES] = [];
            $this->concat();

            return $this;
        }

        $this->where = 'WHERE ' . $params[self::CONDITIONS] . ' ';
        $this->params[self::VALUES] = $params[self::VALUES];
        $this->concat();

        return $this;
    }

    /**
     * @param string $append
     * @return Query
     */
    public function appendSQL(string $append): self
    {
        $this->more = $append;
        $this->concat();

        return $this;
    }

    /**
     * @param $table
     * @return $this
     *
     * @see from()
     */
    public function deletefrom($table)
    {
        $this->firstelement = 'DELETE ';
        $this->from($table);

        return $this;
    }

    /**
     * adds FROM clause to the query
     *
     * $model can either be :
     * - a string : `blog, ...`
     * - Model Class
     * - array of strings or Model Classes :
     * ```
     * [new ArticleModel(), 'article', ...]
     * ```
     *
     *
     * @param Model|array|string $model
     * @return Query
     */
    public function from($model): self
    {
        if (is_string($model)) {
            $this->from = 'FROM ' . $model . ' ';
            $this->concat();

            return $this;

        }

        if (is_subclass_of($model, Model::class, false)) {
            $this->from = 'FROM ' . $model->getTable() . ' ';
            $this->concat();
        }

        if (is_array($model)) {
            $this->from = 'FROM ';
            foreach ($model as $item) {
                if ($item instanceof Model) {
                    $this->from .= $model->getTable() . ', ';
                } elseif (is_string($item)) {
                    $this->from .= $item . ', ';
                }
            }
            $this->from = rtrim($this->from, ', ');
            $this->from .= ' ';

            $this->concat();
        }

        return $this;
    }

    /**
     * Returns the array containing the SQL query
     *
     * @return mixed
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Returns the parameter
     *
     * @param string $parameter
     * @return array|mixed
     */
    public function get(string $parameter)
    {
        return $this->params[$parameter] ?? null;
    }

    /**
     * Custom query generator
     * @param string $statement
     * @param array $values [optional]
     */
    public function setQuery(string $statement, array $values = [])
    {
        $this->params[self::STATEMENT] = $statement;
        $this->params[self::VALUES] = $values;
    }

    /**
     * @param Model|string $model
     * @param Entity $entity
     * @return Query
     * @throws IncorrectQueryException
     */
    public function update($model, Entity $entity): Query
    {
        $this->firstelement = 'UPDATE ';
        if (is_string($model)) {
            $this->firstelement .= $model;

        } else if (is_subclass_of($model, Model::class, false)) {
            $this->firstelement .= $model->getTable();
        } else {
            throw new IncorrectQueryException('Model incorrect');
        }

        $this->firstelement .= ' SET ';


        $statement = 'UPDATE ' . static::$table . ' SET ';

        foreach ($rows as $key => $value) {
            $statement .= $key . ' = :' . $key . ', ';
            stripcslashes($value);
            trim($value);
        }

        $request = rtrim($statement, ', ');
        $request .= ' WHERE ' . static::$primary . ' = :primary';

        $rows[':primary'] = $primary;


        throw new IncorrectQueryException('the query is not valid');
    }
}