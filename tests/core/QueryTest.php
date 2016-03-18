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
namespace techweb\test\core;

use PHPUnit_Framework_TestCase;
use techweb\core\database\ORM\Query;
use techweb\core\exception\IncorrectQueryException;

class QueryTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $this->expectException(IncorrectQueryException::class);
        $this->expectExceptionMessage('Cannot concat inexisting statement');
        $query = new Query();
        $query->getParams();
    }

    public function testInvalidSelect()
    {
        $this->expectException(IncorrectQueryException::class);
        $this->expectExceptionMessage('Incomplete SELECT statement');
        $query = new Query();
        $query->select();
        $query->getParams();
    }

    public function testSelect()
    {
        $query = new Query();

        $this->assertEquals(
            $query->select()
                ->from('articles')
                ->getParams(),
            ['statement' => 'SELECT * FROM articles ;']);

        $query = new Query();

        $this->assertEquals(
            $query->select(['id', 'title'])
                ->from('articles')
                ->where(['conditions' => 'id = :id', 'values' => [':id' => 2]])
                ->getParams(),
            ['statement' => 'SELECT id, title FROM articles WHERE id = :id ;', 'values' => [':id' => 2]]);

        //todo Model select
        //TODO : update, delete, add queries
    }
}
