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
 */

namespace techweb\app\model;

use rave\core\Config;
use rave\core\database\orm\Entity;
use techweb\app\entity\TagsEntity;

class TagsProposedModel extends TagsModel
{
    protected static $table = 'tags_proposed';

    public static function save(Entity $entity)
    {
        $votes_config = Config::get('app')['votes'];
        $total = $entity->total_votes;

        if ($votes_config['min'] <= 0) {
            $tags_entity = new TagsEntity();
            $tags_entity->word = $entity->word;

            TagsModel::save($tags_entity);

            if (isset($entity->id)) {
                TagsProposedModel::delete($entity);
            }

        } elseif ($total >= $votes_config['min']) {
            $positive = $entity->positive_votes;

            if (($positive / $total) >= $votes_config['approuval_ratio']) {
                $tags_entity = new TagsEntity();
                $tags_entity->word = $entity->word;

                TagsModel::save($tags_entity);
                if (isset($entity->id)) {
                    TagsProposedModel::delete($entity);
                }
            } elseif (($positive / $total) <= $votes_config['refusal_ratio']) {
                $tags_entity = new TagsEntity();
                $tags_entity->word = $entity->word;

                TagsRefusedModel::save($tags_entity);
                if (isset($entity->id)) {
                    TagsProposedModel::delete($entity);
                }
            } else {
                parent::save($entity);
            }
        } else {
            parent::save($entity);
        }
    }
}