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

use rave\core\Error;
use rave\core\exception\RouterException;
use rave\core\router\Router;

/**
 * Instantiation of the Router object
 */
$router = new Router(isset($_GET['url']) ? $_GET['url'] : '/');

/* #########################################
 * ###                                  ####
 * ###          Ajax Routes             ####
 * ###                                  ####
 * #########################################
 */

$router->post('/ajax/admin/users', ['Ajax' => 'usersIndex']); #
$router->post('/ajax/tags', ['Ajax' => 'tagsIndex']); #

/* #########################################
 * ###                                  ####
 * ###          App Routes              ####
 * ###                                  ####
 * #########################################
 */

$router->get('/', ['Main' => 'index']); #
$router->get('/login', ['Users' => 'login']); #
$router->post('/login', ['Users' => 'login']); #
$router->get('/logout', ['Users' => 'logout']); #

// search, yep, SEARCH
$router->get('/search/', ['Main' => 'search']);

/*
 * User
 */
$router->get('/register', ['Users' => 'create']); #

$router->get('/validation', ['Users' => 'validate']); #

$router->post('/register', ['Users' => 'create']);

$router->get('/users', ['Users' => 'index']); #
$router->get('/user', ['Users' => 'view']); #
$router->get('/user/:id', ['Users' => 'view'])->with('id', '\d+'); #

$router->get('/user/edit', ['Users' => 'update']); #
$router->post('/user/edit', ['Users' => 'update']); #

$router->post('/user/delete', ['Users' => 'delete']); #

/*
 * Technotes
 */
$router->get('/technotes', ['Technotes' => 'index']); #

$router->get('/technote/create', ['Technotes' => 'create']); #
$router->post('/technote/create', ['Technotes' => 'create']); #

$router->get('/technote/:id-:slug', ['Technotes' => 'view'])->with('id', '(\d+)')->with('slug', '[\d\w-_]+'); #

$router->get('/technote/:id/edit', ['Technotes' => 'update'])->with('id', '(\d+)'); #
$router->post('/technote/:id/edit', ['Technotes' => 'update'])->with('id', '(\d+)'); #

$router->post('/technote/:id/comment', ['Technotes' => 'addComment'])->with('id', '(\d+)'); #
$router->post('/technote/comment/delete', ['Technotes' => 'deleteComment'])->with('id', '(\d+)'); #

$router->post('/technote/:id/delete', ['Technotes' => 'delete'])->with('id', '(\d+)'); #

/*
 * Questions
 */
$router->get('/questions', ['Questions' => 'index']); #

$router->get('/question/create', ['Questions' => 'create']); #
$router->post('/question/create', ['Questions' => 'create']); #

$router->get('/question/:id-:slug', ['Questions' => 'view'])->with('id', '(\d+)')->with('slug', '[\d\w-_]+'); #

$router->get('/question/:id/edit', ['Questions' => 'update'])->with('id', '(\d+)'); #
$router->post('/question/:id/edit', ['Questions' => 'update'])->with('id', '(\d+)'); #

$router->post('/question/:id/delete', ['Questions' => 'delete'])->with('id', '(\d+)'); #

$router->post('/question/:id/comment', ['Questions' => 'addComment'])->with('id', '(\d+)'); #
$router->post('/question/comment/delete', ['Questions' => 'deleteComment'])->with('id', '(\d+)'); #

$router->post('/question/:id/answer', ['Answers' => 'create'])->with('id', '(\d+)'); #
$router->post('/answer/:id/delete', ['Answers' => 'delete'])->with('id', '(\d+)'); #

$router->post('/question/:id/:idanswer/comment', ['Answers' => 'addComment'])
    ->with('id', '(\d+)')
    ->with('idanswer', '\d+'); #
$router->post('/question/answer/comment/delete', ['Answers' => 'deleteComment']); #
/*
 * Tags
 */
$router->get('/tags', ['Tags' => 'index']);
//TODO

/* #########################################
 * ###                                  ####
 * ###          Admin routes            ####
 * ###                                  ####
 * #########################################
 */

$router->get('/admin', ['AdminInterface' => 'index']); #

$router->get('/admin/login', ['AdminInterface' => 'login']); #
$router->post('/admin/login', ['AdminInterface' => 'login']); #
$router->get('/admin/logout', ['AdminInterface' => 'logout']); #

/*
 * Users
 */
$router->get('/admin/users', ['AdminUsers' => 'index']);
$router->get('/admin/users/:page', ['AdminUsers' => 'index'])->with('page', '(\d+)');

$router->get('/admin/user/create', ['AdminUsers' => 'create']);
$router->post('/admin/user/create', ['AdminUsers' => 'create']);

$router->get('/admin/user/:id', ['AdminUsers' => 'view'])->with('id', '(\d+)');

$router->get('/admin/user/:id/update', ['AdminUsers' => 'update'])->with('id', '(\d+)');
$router->post('/admin/user/:id/update', ['AdminUsers' => 'update'])->with('id', '(\d+)');

$router->post('/admin/user/:id/delete', ['AdminUsers' => 'delete'])->with('id', '(\d+)');

$router->post('/admin/user/:id/validate', ['AdminUsers' => 'validate'])->with('id', '(\d+)');

$router->post('/admin/user/:id/upgrade', ['AdminUsers' => 'upgrade'])->with('id', '(\d+)');
$router->post('/admin/user/:id/downgrade', ['AdminUsers' => 'downgrade'])->with('id', '(\d+)');

/*
 * TAGS
 */
$router->get('/admin/tags:page:type', ['AdminTags' => 'index'])
    ->with('page', '(\/\d*)?')
    ->with('type', '(\/[rp])?');
$router->get('/admin/tags/:page:type', ['AdminTags' => 'index'])
    ->with('page', '((\d+)?)')
    ->with('type', '((\/[rp])?)');

$router->get('/admin/tag/create', ['AdminTags' => 'create']);
$router->post('/admin/tag/create', ['AdminTags' => 'create']);

$router->get('/admin/tag/:id:type', ['AdminTags' => 'view'])
    ->with('id', '(\d+)')
    ->with('type', '((\/[rp])?)');

$router->get('/admin/tag/:id:type/update', ['AdminTags' => 'update'])
    ->with('id', '(\d+)')
    ->with('type', '((\/[rp])?)');
$router->post('/admin/tag/:id:type/update', ['AdminTags' => 'update'])
    ->with('id', '(\d+)')
    ->with('type', '((\/[rp])?)');

$router->post('/admin/tag/:id:type/delete', ['AdminTags' => 'delete'])
    ->with('id', '(\d+)')
    ->with('type', '((\/[rp])?)');

$router->post('/admin/tag/accept', ['adminTags' => 'acceptExisting']);
$router->post('/admin/tag/refuse', ['adminTags' => 'refuseExisting']);
$router->post('/admin/tag/propose', ['adminTags' => 'proposeExisting']);

/*
 * Technotes
 */
$router->get('/admin/technotes', ['AdminTechnotes' => 'index']);
$router->get('/admin/technotes/:page', ['AdminTechnotes' => 'index'])->with('page', '(\d+)');

$router->get('/admin/technote/create', ['AdminTechnotes' => 'create']);
$router->post('/admin/technote/create', ['AdminTechnotes' => 'create']);

$router->get('/admin/technote/:id', ['AdminTechnotes' => 'view'])->with('id', '(\d+)');

$router->get('/admin/technote/:id/update', ['AdminTechnotes' => 'update'])->with('id', '(\d+)');
$router->post('/admin/technote/:id/update', ['AdminTechnotes' => 'update'])->with('id', '(\d+)');

$router->post('/admin/technote/:id/delete', ['AdminTechnotes' => 'delete'])->with('id', '(\d+)');

/**
 * Questions
 */
$router->get('/admin/questions', ['AdminQuestions' => 'index']);
$router->get('/admin/questions/:page', ['AdminQuestions' => 'index'])->with('page', '(\d+)');

$router->get('/admin/question/create', ['AdminQuestions' => 'create']);
$router->post('/admin/question/create', ['AdminQuestions' => 'create']);

$router->get('/admin/question/:id', ['AdminQuestions' => 'view'])->with('id', '(\d+)');

$router->get('/admin/question/:id/update', ['AdminQuestions' => 'update'])->with('id', '(\d+)');
$router->post('/admin/question/:id/update', ['AdminQuestions' => 'update'])->with('id', '(\d+)');

$router->post('/admin/question/:id/delete', ['AdminQuestions' => 'delete'])->with('id', '(\d+)');

$router->post('/admin/question/:id/close', ['AdminQuestions' => 'close'])->with('id', '(\d+)');
$router->post('/admin/question/:id/open', ['AdminQuestions' => 'open'])->with('id', '(\d+)');

/**
 * Run the router. If an exception is caught, the user
 * will be redirected to a 404 error page.
 */
try {
    $router->run();
} catch (RouterException $exception) {
    Error::create($exception->getMessage(), 404);
}