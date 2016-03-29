<?php

session_start();

$webRoot = dirname(filter_input(INPUT_SERVER, 'SCRIPT_NAME'));

if ($webRoot === '/') {
    define('WEB_ROOT', null);
} else {
    define('WEB_ROOT', $webRoot);
}

/**
 * Init Autoloader
 */
require_once 'config/bootstrap.php';
require_once 'config/routes.php';