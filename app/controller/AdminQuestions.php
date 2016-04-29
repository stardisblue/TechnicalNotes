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
use techweb\app\entity\QuestionsEntity;
use techweb\app\model\QuestionsModel;
use techweb\app\model\TagsModel;
use techweb\app\model\UsersModel;

class AdminQuestions extends AdminController implements CRUDInterface
{
    public function index($page = 0)
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index',
            [
                'questions_count' => QuestionsModel::count(),
                'questions' => QuestionsModel::page($page),
                'info' => $info,
                'warning' => $warning,
                'success' => $success
            ]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function view($id)
    {
        $question = QuestionsModel::get(['id' => $id]);

        if (isset($question)) {
            $this->loadView('view', [
                'question' => $question,
                'user' => UsersModel::get(['id' => $question->user_id]),
                'answers' => QuestionsModel::getAnswers($id),
                'comments' => QuestionsModel::sortComments($id),
                'tags' => QuestionsModel::getTags($id)
            ]);

            return;
        }

        Out::session('warning', 'not_exist');
        $this->redirect('admin/questions');

    }

    public function create()
    {
        if (In::isSetPost(['user_id', 'title', 'content'])) {
            $this->checkCSRF('admin/questions');

            $title = Text::clean(In::post('title'));
            $content = Text::clean(In::post('content'));

            $question = new QuestionsEntity();
            $question->title = $title;
            $question->slug = Text::slug(In::post('title'));
            $question->content = $content;

            if (empty($title) || empty($content)) {
                $this->loadView('create',
                    ['question' => $question, 'warning' => 'empty']);

                return;
            }

            $question->user_id = In::post('user_id', FILTER_SANITIZE_NUMBER_INT);
            $question->status = In::post('post') === 'closed' ? 1 : 0;

            QuestionsModel::save($question);
            $question->id = QuestionsModel::lastInsertId();

            $tags = In::post('tags', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE | FILTER_FORCE_ARRAY);
            foreach ($tags as $tagId) {
                if ($tag = TagsModel::get($tagId)) {
                    QuestionsModel::addTag($question->id, $tag->id);
                }
            }

            Out::session('success', 'note_added');
            $this->redirect('admin/questions');
        }

        $this->loadView('create');
    }

    public function update($id)
    {
        $question = QuestionsModel::get(['id' => $id]);

        if (!isset($question)) {
            Out::session('warning', 'not_exist');
            $this->redirect('admin/questions');
        }

        $questionUser = UsersModel::get(['id' => $question->user_id]);

        $questionTags = QuestionsModel::getTags($id);

        if (In::isSetPost(['user_id', 'title', 'content'])) {
            $this->checkCSRF('admin/questions');

            $title = Text::clean(In::post('title'));
            $content = Text::clean(In::post('content'));

            //todo update In::post() and In::get() to add flags
            if (empty($title) || empty($content)) {
                $this->loadView('update',
                    [
                        'question' => $question,
                        'user' => $questionUser,
                        'tags' => $questionTags,
                        'warning' => 'empty'
                    ]);

                return;
            }

            $question->title = $title;
            $question->content = $content;
            $question->user_id = In::post('user_id', FILTER_SANITIZE_NUMBER_INT);
            $question->slug = Text::slug(In::post('title', FILTER_DEFAULT));
            $question->status = In::post('status') === 'closed' ? 1 : 0;
            $question->creation_date = date("Y-m-d H:i:s");

            QuestionsModel::save($question);

            $tags = In::post('tags', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE | FILTER_FORCE_ARRAY);

            $questionTagsId = [];

            foreach ($questionTags as $questionTag) {
                $questionTagsId[] = $questionTag->id;
            }

            $tagsToAdd = array_diff($tags, $questionTagsId);
            $tagsToRemove = array_diff($questionTagsId, $tags);

            foreach ($tagsToAdd as $tagId) {
                if ($tag = TagsModel::get(['id' => $tagId])) {
                    QuestionsModel::addTag($question->id, $tag->id);
                }
            }

            foreach ($tagsToRemove as $tagId) {
                if ($tag = TagsModel::get(['id' => $tagId])) {
                    QuestionsModel::removeTag($question->id, $tag->id);
                }
            }

            Out::session('success', 'updated');
            $this->redirect('admin/questions');
        }

        $this->loadView('update',
            ['question' => $question, 'user' => $questionUser, 'tags' => $questionTags]);

    }

    public function delete($id)
    {
        $this->checkCSRF('admin/questions');

        $question = QuestionsModel::get(['id' => $id]);
        if (isset($question)) {
            QuestionsModel::delete($question);

            Out::session('success', 'deleted');
            $this->redirect('admin/questions');
        }

        Out::session('warning', 'not_exist');
        $this->redirect('admin/questions');
    }

    public function close($id)
    {
        $this->checkCSRF('admin/questions');

        $question = QuestionsModel::get(['id' => $id]);

        if (isset($question)) {
            if ($question->status) {
                Out::session('warning', 'already_solved');
                $this->redirect('admin/questions');
            } else {
                $question->status = 1;
                QuestionsModel::save($question);

                Out::session('success', 'solved');
                $this->redirect('admin/questions');
            }
        }
    }

    public function open($id)
    {
        $this->checkCSRF('admin/questions');

        $question = QuestionsModel::get(['id' => $id]);

        if (isset($question)) {
            if (!$question->status) {
                Out::session('warning', 'already_desolved');
                $this->redirect('admin/questions');
            } else {
                $question->status = 0;
                QuestionsModel::save($question);

                Out::session('success', 'desolved');
                $this->redirect('admin/questions');
            }
        }
    }
}