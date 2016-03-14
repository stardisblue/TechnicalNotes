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


class Query
{
    const CONDITIONS = 'conditions';
    const SQL = 'sql';
    const VALUES = 'values';
    private $params;

    private $select;
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
     * $params MUST be :
     *
     * ```
     * ['id', 'title', ...]
     * ```
     *
     * @param array $params
     * @return Query
     */
    public function select(array $params = []): self
    {
        if (empty($params)) {
            $this->select = 'SELECT * ';
            $this->concat();

            return $this;
        }

        $this->select = 'SELECT ' . implode(', ', $params) . ' ';
        $this->concat();

        return $this;
    }

    /**
     * Concatène pour fabriquer une requete sql
     */
    private function concat()
    {
        $this->params[self::SQL] = rtrim($this->select . $this->from . $this->where . $this->more) . ';';
    }

    /**
     * adds FROM clause to the query
     *
     * $model can either be :
     * - a string : `blog, ...`
     * - Model Class
     * - null
     * - array of strings or Model Classes :
     * ```
     * [new ArticleModel(), 'article', ...]
     * ```
     *
     *
     * @param Model|array|null|string $model
     * @return Query
     */
    public function from($model = null): self
    {
        if ($model == null) {
            $this->from = '';
            $this->concat();

            return $this;
        }
        if (is_string($model)) {
            $this->from = 'FROM ' . $model . ' ';
            $this->concat();

            return $this;

        }

        if ($model instanceof Model) {
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
     * Returns the array containing the SQL query
     *
     * @return mixed
     */
    public function getParams(): array
    {
        return $this->params;
    }
}