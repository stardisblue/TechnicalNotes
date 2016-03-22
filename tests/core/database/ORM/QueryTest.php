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
namespace techweb\tests\core\database\ORM;

use PHPUnit_Framework_TestCase;
use techweb\core\database\ORM\Query;
use techweb\core\exception\IncorrectQueryException;
use techweb\tests\app\model\ArticlesModel;

class QueryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedExceptionMessage  Cannot concat inexisting statement
     */
    public function testConstruct()
    {
        $this->expectException(IncorrectQueryException::class);
        $query = new Query();
        $query->getParams();
    }

    /**
     * @throws IncorrectQueryException
     * @expectedExceptionMessage  Incomplete SELECT statement
     */
    public function testInvalidSelect()
    {
        $this->expectException(IncorrectQueryException::class);
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
            ['statement' => 'SELECT * FROM articles ']);

        $query = new Query();

        $this->assertEquals(
            $query->select(['id', 'title'])
                ->from('articles')
                ->where(['conditions' => 'id = :id', 'values' => [':id' => 2]])
                ->getParams(),
            ['statement' => 'SELECT id, title FROM articles WHERE id = :id ', 'values' => [':id' => 2]]);

        $query = new Query();
        $articles_model = new ArticlesModel();

        $this->assertEquals(
            $query->select()
                ->from($articles_model)
                ->getParams(),
            ['statement' => 'SELECT * FROM articles ']
        );
    }

    /**
     * @throws IncorrectQueryException
     * @expectedExceptionMessage Cannot add FROM statement
     */
    public function testInvalidFrom()
    {
        $this->expectException(IncorrectQueryException::class);
        $query = new Query();
        $query->from('articles');
        $query->getParams();
    }

    public function testFrom()
    {
        $query = new Query();

        $this->assertEquals(
            $query->select()
                ->from('articles')
                ->getParams(),
            ['statement' => 'SELECT * FROM articles ']);

        $query = new Query();
        $articles_model = new ArticlesModel();

        $this->assertEquals(
            $query->delete()
                ->from($articles_model)
                ->where(['statement' => 'id = :id', 'values' => ])
                ->getParams(),
            ['statement' => 'SELECT * FROM articles ']
        );
    }

    //TODO : update, delete, add queries
}
