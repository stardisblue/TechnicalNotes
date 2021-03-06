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
use techweb\app\controller\abstracts\AdminController;
use techweb\app\controller\interfaces\CRUDInterface;
use techweb\app\entity\TechnotesEntity;
use techweb\app\model\TagsModel;
use techweb\app\model\TechnotesModel;
use techweb\app\model\UsersModel;

class AdminTechnotes extends AdminController implements CRUDInterface
{
    public function index($page = 0)
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index',
            [
                'count' => TechnotesModel::count(),
                'technotes' => TechnotesModel::page($page),
                'info' => $info,
                'warning' => $warning,
                'success' => $success
            ]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function create()
    {
        if (In::isSetPost(['user_id', 'title', 'content'])) {
            $this->checkCSRF('admin/technotes');

            $title = Text::clean(In::post('title'));
            $content = Text::clean(In::post('content'));

            $technote = new TechnotesEntity();
            $technote->title = $title;
            $technote->content = $content;

            if (empty($title) || empty($content)) {
                $this->loadView('create',
                    ['technote' => $technote, 'users' => UsersModel::all(), 'warning' => 'empty']);

                return;
            }

            $technote->user_id = In::post('user_id', FILTER_SANITIZE_NUMBER_INT);
            $technote->slug = Text::slug(In::post('title'));

            TechnotesModel::save($technote);
            $technote->id = TechnotesModel::lastInsertId();

            $tags = In::post('tags', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE | FILTER_FORCE_ARRAY);
            foreach ($tags as $tagId) {
                if ($tag = TagsModel::get(['id' => $tagId])) {
                    TechnotesModel::addTag($technote->id, $tag->id);
                }
            }

            Out::session('success', 'note_added');
            $this->redirect('admin/technotes');
        }

        $this->loadView('create', ['users' => UsersModel::all()]);
    }

    public function view($id)
    {
        $technote = TechnotesModel::get(['id' => $id]);

        if (isset($technote)) {
            $this->loadView('view', [
                'technote' => $technote,
                'user' => UsersModel::get(['id' => $technote->user_id]),
                'tags' => TechnotesModel::getTags($id),
                'comments' => TechnotesModel::sortComments($id)
            ]);

            return;
        }

        Out::session('warning', 'not_exist');
        $this->redirect('admin/technotes');

    }

    public function update($id)
    {
        $technote = TechnotesModel::get(['id' => $id]);

        if (!isset($technote)) {
            Out::session('warning', 'not_exist');
            $this->redirect('admin/technotes');
        }

        $technoteUser = UsersModel::get(['id' => $technote->user_id]);

        $technoteTags = TechnotesModel::getTags($id);

        if (In::isSetPost(['user_id', 'title', 'content'])) {
            $this->checkCSRF('admin/technotes');

            $title = Text::clean(In::post('title'));
            $content = Text::clean(In::post('content'));

            if (empty($title) || empty($content)) {
                $this->loadView('update',
                    [
                        'technote' => $technote,
                        'user' => $technoteUser,
                        'tags' => $technoteTags,
                        'warning' => 'empty'
                    ]);

                return;
            }

            $technote->title = $title;
            $technote->content = $content;
            $technote->user_id = In::post('user_id', FILTER_SANITIZE_NUMBER_INT);
            $technote->slug = Text::slug(In::post('title', FILTER_DEFAULT));
            $technote->creation_date = date("Y-m-d H:i:s");

            TechnotesModel::save($technote);

            $tags = In::post('tags', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE | FILTER_FORCE_ARRAY);

            $technoteTagsId = [];

            foreach ($technoteTags as $technoteTag) {
                $technoteTagsId[] = $technoteTag->id;
            }

            $tagsToAdd = array_diff($tags, $technoteTagsId);
            $tagsToRemove = array_diff($technoteTagsId, $tags);

            foreach ($tagsToAdd as $tagId) {
                if ($tag = TagsModel::get(['id' => $tagId])) {
                    TechnotesModel::addTag($technote->id, $tag->id);
                }
            }

            foreach ($tagsToRemove as $tagId) {
                if ($tag = TagsModel::get(['id' => $tagId])) {
                    TechnotesModel::removeTag($technote->id, $tag->id);
                }
            }

            Out::session('success', 'updated');
            $this->redirect('admin/technotes');
        }

        $this->loadView('update',
            ['technote' => $technote, 'user' => $technoteUser, 'tags' => $technoteTags]);

    }

    public function delete($id)
    {
        $this->checkCSRF('admin/technotes');

        $technote = TechnotesModel::get(['id' => $id]);
        if (isset($technote)) {
            TechnotesModel::delete($technote);

            Out::session('success', 'deleted');
            $this->redirect('admin/technotes');
        }

        Out::session('warning', 'not_exist');
        $this->redirect('admin/technotes');
    }

}