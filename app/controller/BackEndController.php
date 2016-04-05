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
 *
 */

namespace techweb\app\controller;

use rave\core\Controller;
use rave\lib\core\security\Auth;

abstract class BackEndController extends Controller
{
    public function __construct()
    {
        $this->setLayout('backend');
    }

    public function beforeCall(string $method)
    {
        if ($method != 'login' && $method != 'logout') {
            $this->checkAdmin();
        }
    }

    protected function checkAdmin()
    {
        if (!Auth::check('admin')) {
            $this->redirect('admin/login');
        }
    }

}