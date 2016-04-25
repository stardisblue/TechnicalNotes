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
use rave\lib\core\security\Text;
use techweb\app\entity\TagsEntity;
use techweb\app\entity\TagsProposedEntity;
use techweb\app\model\TagsModel;
use techweb\app\model\TagsProposedModel;
use techweb\app\model\TagsRefusedModel;

class AdminTags extends AdminController
{
    public function index($page = 0)
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index', [
            'tags' => TagsModel::page($page),
            'count' => TagsModel::count(),
            'info' => $info,
            'warning' => $warning,
            'success' => $success,
        ]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function indexProposed($page = 0)
    {
        $this->loadView('index_proposed',
            ['tags' => TagsProposedModel::page($page), 'count' => TagsProposedModel::count()]);
    }

    public function indexRefused($page = 0)
    {
        $this->loadView('index_refused',
            ['tags' => TagsRefusedModel::page($page), 'count' => TagsRefusedModel::count()]);
    }

    public function create()
    {
        if (In::isSetPost(['word', 'type'])) {
            $this->checkCSRF('admin/tags');

            $word = Text::clean(In::post('word'));
            $type = In::post('type');

            if (empty($word)) {
                Out::session('warning', 'tag_empty');
                $this->redirect('admin/tag/create');
            } elseif (TagsModel::tagExist($word)) {
                Out::session('info', 'already_exist');
                $this->redirect('admin/tags');
            }

            if ($type === 'proposed' && In::isSetPost(['positive', 'total'])) {
                $tagProposed = new TagsProposedEntity();
                $tagProposed->word = $word;
                $tagProposed->positive_votes = In::post('positive', FILTER_SANITIZE_NUMBER_INT);
                $tagProposed->total_votes = In::post('total', FILTER_SANITIZE_NUMBER_INT);

                if ($tagProposed->positive_votes < 0 || $tagProposed->total_votes < 0) {
                    Out::session('warning', 'incorrect_votes');
                    $this->redirect('admin/tags');
                }

                TagsProposedModel::save($tagProposed);

                Out::session('success', 'tag_created');
                $this->redirect('admin/tags');
            } else {
                $tag = new TagsEntity();
                $tag->word = $word;

                if ($type === 'tag') {
                    TagsModel::save($tag);
                } elseif ($type === 'refused') {
                    TagsRefusedModel::save($tag);
                } else {
                    Out::session('warning', 'invalid_type');
                    $this->redirect('admin/tag/create');
                }

                Out::session('success', 'tag_created');
                $this->redirect('admin/tags');
            }
        }

        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('create', ['info' => $info, 'warning' => $warning, 'success' => $success]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function update($id)
    {
        $tag = TagsModel::get(['id' => $id]);

        if (!isset($tag)) {
            Out::session('tag', 'not_exist');
            $this->redirect('admin / tags');
        }

        if (In::isSetPost(['word'])) {
            $this->checkCSRF('admin / tags');

            $word = Text::clean(In::post('word'));

            if (empty($word)) {
                Out::session('warning', 'tag_empty');
                $this->redirect('admin / tag / ' . $id . ' / update');
            }

            if ($tag->word === $word) {
                Out::session('info', 'not_changed');
                $this->redirect('admin / tag / ' . $id . ' / update');
            }

            if (TagsModel::tagExist($word)) {
                Out::session('info', 'already_exist');
                $this->redirect('admin / tag / ' . $id . ' / update');
            }
            $tag->word = $word;

            TagsModel::save($tag);

            Out::session('success', 'tag_updated');
            $this->redirect('admin / tags');
        }

        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('update', ['tag' => $tag, 'info' => $info, 'warning' => $warning, 'success' => $success]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function acceptExisting()
    {
        if (In::isSetPost(['word'])) {
            $this->checkCSRF('admin / tags');

            if ($tagOrigin = TagsProposedModel::getByWord(In::post('word'))) {
                $origin = 'proposed';
            } elseif ($tagOrigin = TagsRefusedModel::getByWord(In::post('word'))) {
                $origin = 'refused';
            } else {
                Out::session('warning', 'not_exist');
                $this->redirect('admin / tags');

                return;
            }

            $tag = new TagsEntity();
            $tag->word = $tagOrigin->word;

            TagsModel::save($tag);
            if ($origin === 'proposed') {
                TagsProposedModel::delete($tagOrigin);
            } elseif ($origin === 'refused') {
                TagsRefusedModel::delete($tagOrigin);
            }

            Out::session('success', 'tag_accepted');
            $this->redirect('admin / tags');
        }
    }

    public function refuseExisting()
    {

        if (In::isSetPost(['word'])) {
            $this->checkCSRF('admin / tags');

            if ($tagOrigin = TagsModel::getByWord(In::post('word'))) {
                $origin = 'tags';
            } elseif ($tagOrigin = TagsProposedModel::getByWord(In::post('word'))) {
                $origin = 'proposed';
            } else {
                Out::session('warning', 'not_exist');
                $this->redirect('admin / tags');

                return;
            }

            $tagRefused = new TagsRefusedEntity();
            $tagRefused->word = $tagOrigin->word;

            TagsRefusedModel::save($tagRefused);

            if ($origin === 'proposed') {
                TagsProposedModel::delete($tagOrigin);
            } elseif ($origin === 'tags') {
                TagsModel::delete($tagOrigin);
            }

            Out::session('success', 'tag_refused');
            $this->redirect('admin / tags');
        }
    }

    public function proposeExisting()
    {

        if (In::isSetPost(['word'])) {
            $this->checkCSRF('admin / tags');
            //TODO : finish and optimize
            if ($tagOrigin = TagsRefusedModel::getByWord(In::post('word'))) {
                $origin = 'refused';
            } elseif ($tagOrigin = TagsModel::getByWord(In::post('word'))) {
                $origin = 'tags';
            } else {
                Out::session('warning', 'not_exist');
                $this->redirect('admin / tags');

                return;
            }

            $tagProposed = new TagsProposedEntity();
            $tagProposed->word = $tagOrigin->word;

            TagsProposedModel::save($tagProposed);

            if ($origin === 'refused') {
                TagsRefusedModel::delete($tagOrigin);
            } elseif ($origin === 'tags') {
                TagsModel::delete($tagOrigin);
            }

            Out::session('success', 'tag_proposed');
            $this->redirect('admin / tags');
        }
    }
}