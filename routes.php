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

use techweb\config\Config;
use techweb\core\Error;
use techweb\core\exception\RouterException;
use techweb\core\router\Router;

/**
 * Instantiation of the Router object
 */
$router = new Router(isset($_GET['url']) ? $_GET['url'] : '/');

$router->get('/', ['Main' => 'index']);

$router->get('/users', ['UserManagement' => 'list_users']);
$router->get('/user/add', ['UserManagement' => 'add_user']);
$router->post('/user/add', ['UserManagement' => 'add_user']);

/**
 * Error routes
 */
$router->get(Config::getError('404'), ['Error' => 'notFound']);

$router->get(Config::getError('403'), ['Error' => 'forbidden']);

$router->get(Config::getError('500'), ['Error' => 'internalServerError']);

/**
 * Run the router. If an exception is caught, the user
 * will be redirected to a 404 error page.
 */
try {
    $router->run();
} catch (RouterException $exception) {
    Error::create($exception->getMessage(), 404);
}
