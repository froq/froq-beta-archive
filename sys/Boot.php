<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <http://qeremy.com>
 *
 * GNU General Public License v3.0
 *    <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

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
 * Init application with default configs (comes from cfg.php).
 */
return Application\Application::init($cfg);
