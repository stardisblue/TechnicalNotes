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

use rave\core\Controller;
use rave\lib\core\io\In;
use rave\lib\core\io\Out;
use rave\lib\core\security\CSRF;
use rave\lib\core\security\Text;
use techweb\app\model\TagsModel;
use techweb\app\model\UsersModel;

class Ajax extends Controller
{
    public function __construct()
    {
        $token = CSRF::getToken();
        $this->data['csrf_ajax'] = $token;
        setcookie('csrf_ajax', $token, 0, WEB_ROOT . '/');
        $this->setLayout('ajax');

    }

    public function beforeCall($method)
    {
        $this->checkCSRF('ajax/');
    }

    protected function checkCSRF($redirect = '', $message = 'csrf_ajax', $method = 'post', $name = 'csrf_ajax')
    {
        if ($method === 'post' || $method === 'get') {
            if (In::$method($name) !== In::cookie('csrf_ajax')) {
                Out::session('warning', $message);
                $this->redirect($redirect);
            }
        }
    }

    public function usersIndex()
    {
        if (In::isSetPost(['search'])) {
            $username = Text::clean(In::post('search', FILTER_SANITIZE_EMAIL));
            $page = In::post('page') ? In::post('page', FILTER_SANITIZE_NUMBER_INT) : 0;

            $result = new \stdClass();
            $result->items = UsersModel::searchByUsername($username, $page);
            $result->total_count = count($result->items);
            $this->loadView('json', ['json' => $result]);

        }
    }

    public function tagsIndex()
    {
        if (In::isSetPost(['search'])) {
            $word = Text::clean(In::post('search', FILTER_SANITIZE_EMAIL));
            $page = In::post('page') ? In::post('page', FILTER_SANITIZE_NUMBER_INT) : 0;

            $result = new \stdClass();
            $result->items = TagsModel::searchByTag($word, $page);
            $result->total_count = count($result->items);
            $this->loadView('json', ['json' => $result]);

        }
    }
}