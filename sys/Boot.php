<?php
/**
 * Must be called first here once.
 * @important
 */
ob_start();

/**
 * Application constants.
 * @const bool, bool, float
 */
define('APP_START_TIME', microtime(true));

/**
 * Just for fun.
 * @const null
 */
define('nil', null, true);

/**
 * Application root path.
 * @const string
 */
define('root', __dir__ .'/..', true);

/**
 * Used to detect development environment.
 * @const bool
 */
define('local', ((bool) strstr($_SERVER['SERVER_NAME'], '.local')), true);

/**
 * HTTP/HTTPS constants.
 * @const string
 */
define('http', 'http://'. $_SERVER['SERVER_NAME'], true);
define('https', 'https://'. $_SERVER['SERVER_NAME'], true);

/**
 * Error settings.
 */
if (local) {
   ini_set('display_errors', 1);
   ini_set('error_reporting', E_ALL);
}

/**
 * Load global base files.
 */
require(root .'/sys/global/def.php');
require(root .'/sys/global/cfg.php');
require(root .'/sys/global/fun.php');

/**
 * Register autoload.
 */
$autoload = require(root .'/sys/library/class/Autoload.php');
$autoload->register();

/**
 * Init application.
 */
return Application\Application::init();
