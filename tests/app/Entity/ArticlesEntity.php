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

namespace techweb\tests\app\Entity;

use techweb\core\database\ORM\Entity;

class ArticlesEntity extends Entity
{
    public function __construct()
    {
        $properties = [
            'id' => null,
            'user_id' => null,
            'title' => '',
            'content' => '',
            'date' => null,
        ];

        $options = [
            'primary' => 'id',
            'belongs_to' => [
                'table' => 'users',
                'foreign_key' => 'user_id',
            ]
        ];

        parent::__construct($properties, $options);
    }
}