<?php

session_start();

use techweb\core\AutoLoader;

/**
 * Some useful constants
 */
define('ROOT', __DIR__);

$webRoot = dirname(filter_input(INPUT_SERVER, 'SCRIPT_NAME'));

if ($webRoot === '/') {
    define('WEB_ROOT', null);
} else {
    define('WEB_ROOT', $webRoot);
}

/**
 * Include the autoloader
 */
require_once ROOT . '/core/AutoLoader.php';

require_once ROOT . '/vendor/autoload.php';

/**
 * Enable the autoloader
 */
AutoLoader::register();

require_once ROOT . '/routes.php';
