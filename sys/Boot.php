<?php
/** !!! MUST BE CALLED FIRST HERE ONCE !!! **/
ob_start();

/**
 * Application constants.
 * @const bool, bool, float
 */
define('APP_TEST', true);
define('APP_DEBUG', true);
define('APP_START_TIME', microtime(true));

/**
 * Just for fun.
 * @const null
 */
define('nil', null, true);

/**
 * These defs stand here cos of PHP's typehint leak. So, you can use these
 * instead of NULL when defining as defult params like foo(bool $x = NULL).
 * @const bool, bool, string
 */
define('True', true);
define('False', false);
define('None', '', true);

/**
 * Application root path.
 * @const string
 */
define('root', __dir__ .'/..', true);

/**
 * Used to detect development environment.
 * @const bool
 */
define('local', (bool) strstr($_SERVER['SERVER_NAME'], '.local'), true);

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

/** !!! START APPLICATION !!! **/
// register autoload
$autoload = require(root .'/sys/library/class/Autoload.php');
$autoload->register();

// init application
return Application\Application::init();
