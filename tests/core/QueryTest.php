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
use techweb\core\Query;

class QueryTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $query = new Query();
        $this->assertEquals($query->getParams(), []);
    }

    public function testSelect()
    {
        $query = new Query();

        $this->assertEquals(
            $query->select()
                ->getParams(),
            ['statement' => 'SELECT *;']);

        $this->assertEquals(
            $query->select(['id', 'title'])
                ->getParams(),
            ['statement' => 'SELECT id, title;']);
    }

    public function testFrom()
    {
        $query = new Query();
        $this->assertEquals($query->from('test')->getParams(), ['statement' => 'FROM test;']);
        $this->assertEquals($query->from(['article', 'blog'])->getParams(), ['statement' => 'FROM article, blog;']);

        // TODO ModelTest
    }

    public function testWhere()
    {
        $query = new Query();
        $this->assertEquals($query->where()->getParams(), ['statement' => ';', 'values' => []]);
        $this->assertEquals($query->where(['conditions' => 'id = :id', 'values' => [':id' => 2]])->getParams(),
            ['statement' => 'WHERE id = :id;', 'values' => [':id' => 2]]);
    }

    public function testAppendSQL()
    {
        $query = new Query();
        $query->appendSQL('GROUP BY id');
        $result = ['statement' => 'GROUP BY id;'];
        $this->assertEquals($query->getParams(), $result);
    }

    public function testAll()
    {
        $query = new Query();
        $query->select()
            ->from('articles')
            ->where(['conditions' => 'id <= :id',
                'values' => [':id' => 2]])
            ->appendSQL('GROUP BY id');
        $result = ['statement' => 'SELECT * FROM articles WHERE id <= :id GROUP BY id;', 'values' => [':id' => 2]];
        $this->assertEquals($query->getParams(), $result);
    }
}
