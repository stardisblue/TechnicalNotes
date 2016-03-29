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

namespace techweb\app\entity;

use rave\core\database\orm\Entity;

/**
 * Class TechnoteEntity
 * @package techweb\app\entity
 */
class QuestionsEntity extends Entity
{
    /**
     * TechnoteEntity constructor.
     */
    public function __construct()
    {
        $columns = [
            'id' => null,
            'user_id' => null,
            'title' => '',
            'content' => '',
            'creation_date' => null,
            'statut' => ''
        ];



        $options = ['primary' => 'id', 
            'belongTo' => ['table' => 'users', 'foreing_key' => 'user_id'],
            'hasMany' => [
                ['table' => 'tags',
                    'through' => 'questions_tags']
            ]
        ];

        parent::__construct($columns, $options);
    }

}