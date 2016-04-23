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

use rave\core\Config;
use rave\core\Error;
use rave\core\exception\RouterException;
use rave\core\router\Router;

/**
 * Instantiation of the Router object
 */
$router = new Router(isset($_GET['url']) ? $_GET['url'] : '/');

$router->get('/', ['Main' => 'index']);

$router->get('/', ['Main' => 'index']);

$router->get('/users', ['Users' => 'listUsers']);
$router->get('/user/add', ['Users' => 'addUser']);
$router->post('/user/add', ['Users' => 'addUser']);

/*
 * Admin routes
 */

$router->get('/admin', ['AdminInterface' => 'index']);

$router->get('/admin/login', ['AdminInterface' => 'login']);
$router->post('/admin/login', ['AdminInterface' => 'login']);
$router->get('/admin/logout', ['AdminInterface' => 'logout']);

/*
 * Users
 */
$router->get('/admin/users', ['AdminUsers' => 'index']);
$router->get('/admin/users/:page', ['AdminUsers' => 'index'])->with('page', '([0-9]+)');

$router->get('/admin/user/create', ['AdminUsers' => 'create']);
$router->post('/admin/user/create', ['AdminUsers' => 'create']);

$router->get('/admin/user/:id', ['AdminUsers' => 'view'])->with('id', '([0-9]+)');

$router->get('/admin/user/:id/update', ['AdminUsers' => 'update'])->with('id', '([0-9]+)');
$router->post('/admin/user/:id/update', ['AdminUsers' => 'update'])->with('id', '([0-9]+)');

$router->post('/admin/user/:id/delete', ['AdminUsers' => 'delete'])->with('id', '([0-9]+)');

$router->post('/admin/user/:id/validate', ['AdminUsers' => 'validate'])->with('id', '([0-9]+)');

$router->post('/admin/user/:id/upgrade', ['AdminUsers' => 'upgrade'])->with('id', '([0-9]+)');
$router->post('/admin/user/:id/downgrade', ['AdminUsers' => 'downgrade'])->with('id', '([0-9]+)');

/*
 * Technotes
 */
$router->get('/admin/technotes', ['AdminTechnotes' => 'index']);
$router->get('/admin/technotes/:page', ['AdminTechnotes' => 'index']);

$router->get('/admin/technote/create', ['AdminTechnotes' => 'create']);
$router->post('/admin/technote/create', ['AdminTechnotes' => 'create']);

$router->get('/admin/technote/:id', ['AdminTechnotes' => 'view'])->with('id', '([0-9]+)');

$router->get('/admin/technote/:id/update', ['AdminTechnotes' => 'update'])->with('id', '([0-9]+)');
$router->post('/admin/technote/:id/update', ['AdminTechnotes' => 'update'])->with('id', '([0-9]+)');

$router->post('/admin/technote/:id/delete', ['AdminTechnotes' => 'delete'])->with('id', '([0-9]+)');
/*
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