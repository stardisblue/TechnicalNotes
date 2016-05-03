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
use techweb\app\controller\abstracts\FrontEndController;
use techweb\app\controller\interfaces\CRUDInterface;
use techweb\app\entity\QuestionsEntity;
use techweb\app\model\QuestionsModel;
use techweb\app\model\TagsModel;
use techweb\app\model\UsersModel;

class Questions extends FrontEndController implements CRUDInterface
{
    public function index($page = 0)
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index',
            [
                'count' => QuestionsModel::count(),
                'questions' => QuestionsModel::page(),
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
                'tags' => QuestionsModel::getTags($id),
                'answers' => QuestionsModel::getAnswersComments($id),
                'comments' => QuestionsModel::sortComments($id)
            ]);

            return;
        }

        Out::session('warning', 'not_exist');
        $this->redirect('questions');
    }

    public function create()
    {
        if (isset($this->data['userLogged'])) {
            $question = new QuestionsEntity();
            if (In::isSetPost(['title', 'content'])) {
                $this->checkCSRF('questions');

                $title = Text::clean(In::post('title'));
                $content = Text::clean(In::post('content'));

                $question->title = $title;
                $question->content = $content;

                if (empty($title) || empty($content)) {
                    $this->loadView('create',
                        ['question' => $question, 'warning' => 'empty']);

                    return;
                }

                $question->user_id = $this->data['userLogged']->id;
                $question->slug = Text::slug(In::post('title', FILTER_DEFAULT));

                QuestionsModel::save($question);
                $question->id = QuestionsModel::lastInsertId();

                $tags = In::post('tags', FILTER_SANITIZE_NUMBER_INT, FILTER_NULL_ON_FAILURE | FILTER_FORCE_ARRAY);
                foreach ($tags as $tagId) {
                    if ($tag = TagsModel::get(['id' => $tagId])) {
                        QuestionsModel::addTag($question->id, $tag->id);
                    }
                }

                Out::session('success', 'note_added');
                $this->redirect('questions');
            }

            $this->loadView('create', ['question' => $question]);
        } else {
            $this->redirect('login');
        }
    }

    public function update($id)
    {
        $userLogged = $this->data['userLogged'];
        if (isset($userLogged)) {
            $question = QuestionsModel::get(['id' => $id]);

            if (!isset($question) || $question->user_id !== $userLogged->id) {
                Out::session('warning', 'not_exist');
                $this->redirect('questions');
            }

            $questionTags = QuestionsModel::getTags($id);

            if (In::isSetPost(['title', 'content'])) {
                $this->checkCSRF('questions');

                $title = Text::clean(In::post('title'));
                $content = Text::clean(In::post('content'));

                if (empty($title) || empty($content)) {
                    $this->loadView('update',
                        [
                            'question' => $question,
                            'tags' => $questionTags,
                            'warning' => 'empty'
                        ]);

                    return;
                }

                $question->title = $title;
                $question->content = $content;
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
                $this->redirect('questions');
            }

            $this->loadView('update',
                ['question' => $question, 'tags' => $questionTags]);
        } else {
            $this->redirect('login');
        }
    }

    public function delete($id)
    {
        $questionUser = $this->data['userLogged'];
        if (isset($questionUser)) {
            $this->checkCSRF('questions');
            $question = QuestionsModel::get(['id' => $id]);

            if (!isset($question) || $question->user_id !== $questionUser->id) {
                Out::session('warning', 'not_exist');
                $this->redirect('questions');
            }

            QuestionsModel::delete($question);

            Out::session('success', 'deleted');
            $this->redirect('questions');
        }

        $this->redirect('login');
    }

    public function addComment($id)
    {
        $questionUser = $this->data['userLogged'];
        if (isset($questionUser)) {
            $question = QuestionsModel::get(['id' => $id]);

            if (!isset($question) || $question->user_id !== $questionUser->id) {
                Out::session('warning', 'not_exist');
                $this->redirect('questions');
            }

            if (In::isSetPost(['content'])) {
                $this->checkCSRF('questions');
                $content = Text::clean(In::post('content'));
                $parent = In::post('parent_id', FILTER_SANITIZE_NUMBER_INT);

                if (empty($parent)) {
                    $parent = null;
                }

                if (empty($content)) {
                    $this->redirect('question/' . $id . '-' . $question->slug);

                    return;
                }

                QuestionsModel::addComment($id, $questionUser->id, $parent, $content);
            }

            $this->redirect('question/' . $id . '-' . $question->slug);
        } else {
            $this->redirect('login');
        }
    }

    public function deleteComment()
    {
        $questionUser = $this->data['userLogged'];
        if (isset($questionUser)) {
            $this->checkCSRF('questions');
            if ($comment_id = In::post('comment_id', FILTER_SANITIZE_NUMBER_INT)) {
                $comment = QuestionsModel::getComment($comment_id);

                if ($questionUser->id === $comment->user_id) {
                    QuestionsModel::deleteComment($comment_id);
                }

                $this->redirect('questions');
            }
            $this->redirect('questions');
        } else {
            $this->redirect('login');
        }
    }

}