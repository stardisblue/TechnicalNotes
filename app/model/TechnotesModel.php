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


namespace techweb\app\model;

use rave\core\database\orm\Model;

class TechnotesModel extends Model
{
    protected static $table = 'technotes';

    public function getUserForTechnotesId($id)
    {
        $query = $this->newQuery()->select('user_id')->from($this)->where(['id', '=', $id]);

        $user = new UserModel();
        if ($query->first()]){
        	$user_entity = $user->get(['id'=> $query->first()]);
        }
        
        return $user_entity;
    }

    public function getTechnotesComments($id){

    	$query = $this->newQuery()->select()->from(['technote_comments'])->where(['tec_id', '=', $id]);

    	return $query->all();

    }

    public function getTechnotesTags($id){

    	$query = $this->newQuery()->select()->from(['tags_technotes', 'tags'])->where(
    	[	'AND' => [
    			['tags_technotes.tag_id', '=', 'tags.id']
    			['tags_technotes.technote_id', '=', $id]
    		]
    	]);
    	return $query->all();

    }

}