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

namespace techweb\app\controller\abstracts;

use rave\core\Controller;
use rave\lib\core\io\In;
use rave\lib\core\io\Out;
use rave\lib\core\security\CSRF;
use techweb\app\model\UsersModel;

abstract class AppController extends Controller
{

    public function __construct()
    {
        $token = CSRF::getToken();
        $this->data['csrf'] = $token;
        setcookie('csrf', $token, 0, WEB_ROOT . '/', null, null, true);
    }

    protected function checkCSRF($redirect = '', $message = 'csrf', $method = 'post', $name = 'csrf')
    {
        if ($method === 'post' || $method === 'get') {
            if (In::$method($name) !== In::cookie('csrf')) {
                Out::session('warning', $message);
                $this->redirect($redirect);
            }
        }
    }
}