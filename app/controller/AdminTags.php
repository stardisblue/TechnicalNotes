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

namespace techweb\app\controller;

use rave\lib\core\io\In;
use rave\lib\core\io\Out;
use techweb\app\entity\TagsEntity;
use techweb\app\entity\TagsRefusedEntity;
use techweb\app\model\TagsModel;
use techweb\app\model\TagsProposedModel;
use techweb\app\model\TagsRefusedModel;

class AdminTags extends AdminController
{

    public function acceptExisting()
    {
        $this->checkCSRF('admin/tags');
        if (In::isSetPost(['word'])) {

            $tagProposed = TagsProposedModel::getByWord(In::post('word'));

            if (isset($tag)) {
                $tag = new TagsEntity();
                $tag->id = $tagProposed->id;
                $tag->word = $tagProposed->word;

                TagsModel::save($tag);
                TagsProposedModel::delete($tagProposed);

                Out::session('success', 'tag_accepted');
                $this->redirect('admin/tags');
            }

            $tagRefused = TagsRefusedModel::getByWord(In::post('word'));

            if (isset($tag)) {
                $tag = new TagsEntity();
                $tag->id = $tagRefused->id;
                $tag->word = $tagRefused->word;

                TagsModel::save($tag);
                TagsRefusedModel::delete($tagRefused);

                Out::session('success', 'tag_accepted');
                $this->redirect('admin/tags');
            }

        }
    }

    public function refuseExisting()
    {
        $this->checkCSRF('admin/tags');

        if (In::isSetPost(['word'])) {
            $tag = TagsModel::getByWord(In::post('word'));

            if (isset($tag)) {
                $tagRefused = new TagsRefusedEntity();
                $tagRefused->id = $tag->id;
                $tagRefused->word = $tag->word;

                TagsRefusedModel::save($tagRefused);
                TagsModel::delete($tag);

                Out::session('success', 'tag_refused');
                $this->redirect('admin/tags');
            }

            $tagProposed = TagsProposedModel::getByWord(In::post('word'));

            if (isset($tagProposed)) {
                $tagRefused = new TagsRefusedEntity();
                $tagRefused->id = $tagProposed->id;
                $tagRefused->word = $tagProposed->word;

                TagsRefusedModel::save($tagRefused);
                TagsProposedModel::delete($tagProposed);

                Out::session('success', 'tag_refused');
                $this->redirect('admin/tags');
            }
        }
    }

    public function proposeExisting()
    {
        $this->checkCSRF('admin/tags');

        if(In::isSetPost(['word'])){

        }
    }
}