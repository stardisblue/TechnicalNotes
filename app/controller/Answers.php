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
use techweb\app\entity\AnswersEntity;
use techweb\app\model\AnswersModel;

class Answers extends FrontEndController
{

    public function create($id)
    {
        if (isset($this->data['userLogged'])) {
            $answer = new AnswersEntity();
            if (In::isSetPost(['content'])) {
                $this->checkCSRF('questions');

                $content = Text::clean(In::post('content'));

                $answer->content = $content;

                if (empty($content)) {
                    $this->redirect('question/' . $id . '-i');

                    return;
                }

                $answer->user_id = $this->data['userLogged']->id;
                $answer->question_id = $id;

                AnswersModel::save($answer);

                Out::session('success', 'note_added');
                $this->redirect('questions');
            }

            $this->loadView('create', ['answer' => $answer]);
        } else {
            $this->redirect('login');
        }

    }

    public function delete($id)
    {
        $answerUser = $this->data['userLogged'];
        if (isset($answerUser)) {
            $this->checkCSRF('questions');
            $answer = AnswersModel::get(['id' => $id]);

            if (!isset($answer) || $answer->user_id !== $answerUser->id) {
                Out::session('warning', 'not_exist');
                $this->redirect('questions');
            }

            AnswersModel::delete($answer);

            Out::session('success', 'deleted');
            $this->redirect('questions');
        }

        $this->redirect('login');
    }

    public function addComment($id)
    {
        $answerUser = $this->data['userLogged'];
        if (isset($answerUser)) {
            $answer = AnswersModel::get(['id' => $id]);

            if (!isset($answer) || $answer->user_id !== $answerUser->id) {
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
                    $this->redirect('question/' . $id . '-i');

                    return;
                }

                AnswersModel::addComment($id, $answerUser->id, $parent, $content);
            }

            $this->redirect('question/' . $id . '-i');
        } else {
            $this->redirect('login');
        }
    }

    public function deleteComment()
    {
        $answerUser = $this->data['userLogged'];
        if (isset($answerUser)) {
            $this->checkCSRF('questions');
            if ($comment_id = In::post('comment_id', FILTER_SANITIZE_NUMBER_INT)) {
                $comment = AnswersModel::getComment($comment_id);

                if ($answerUser->id === $comment->user_id) {
                    AnswersModel::deleteComment($comment_id);
                }

                $this->redirect('questions');
            }
            $this->redirect('questions');
        } else {
            $this->redirect('login');
        }
    }

}