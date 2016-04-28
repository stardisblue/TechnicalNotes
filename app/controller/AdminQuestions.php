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
use techweb\app\model\UsersModel;

class AdminQuestions extends AdminController implements CRUDInterface
{
    public function index($page = 0)
    {
        $info = In::session('info');
        $warning = In::session('warning');
        $success = In::session('success');
        $this->loadView('index', [
            'questions' => QuestionsModel::page($page),
            'info' => $info,
            'warning' => $warning,
            'success' => $success,
        ]);
        Out::unsetSession('info');
        Out::unsetSession('warning');
        Out::unsetSession('success');
    }

    public function view($id)
    {
        $question = QuestionsModel::get(['id' => $id]);

        if (!isset($question)) {
            Out::session('warning', 'not_exist');
            $this->redirect('admin/questions');
        }

        $this->loadView('view', ['question' => $question, 'tags' => QuestionsModel::getTags($id)]);

    }

    public function create()
    {
        if (In::isSetPost(['title', 'content'])) {
            $this->checkCSRF('admin/questions');

            $title = Text::clean(In::post('title'));
            $content = Text::clean(In::post('content'));

            $question = new QuestionsEntity();
            $question->title = $title;
            $question->content = $content;

            if (empty($title) || empty($content)) {
                $this->loadView('create',
                    ['question' => $question, 'users' => QuestionsModel::all(), 'warning' => 'empty']);

                return;
            }

            $question->user_id = In::post('user_id', FILTER_SANITIZE_NUMBER_INT);
            $question->slug = Text::slug(In::post('title'));

            QuestionsModel::save($question);
            Out::session('success', 'question_added');
            $this->redirect('admin/questions');
        }

        $this->loadView('create', ['users' => UsersModel::all()]);
    }

    public function update($id)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }
}