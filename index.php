<?php

ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(-1);

session_start();

/**
 * Init Autoloader
 */
require_once 'config/bootstrap.php';
require_once 'config/routes.php';