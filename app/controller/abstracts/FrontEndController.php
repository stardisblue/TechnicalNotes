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

use rave\lib\core\io\In;
use rave\lib\core\io\Out;
use techweb\app\model\UsersModel;

abstract class FrontEndController extends AppController
{

    public function __construct()
    {
        parent::__construct();

        $this->setLayout('frontend', $this->data);

        $this->data['userLogged'] = UsersModel::get(['id' => In::session('login')]);
        if ($this->data['userLogged'] === null) {
            Out::unsetSession('admin');
            Out::unsetSession('login');
        }

    }

}
