<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <k-gun@mail.com>
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

/**
 * Set everything as relative to the application root.
 * @important
 */
chdir(dirname(__dir__));

/**
 * Include bootstrap that registers Autoload
 * and returns Application.
 */
$app = require('./sys/Boot.php');

/**
 * Application root.
 */
$appRoot = '/';

/**
 * User app config.
 */
$appConfig = require('./app/global/cfg.php');

/**
 * Application env.
 */
$env = Application\Application::ENVIRONMENT_PRODUCTION;
if (is_local()) {
   $env = Application\Application::ENVIRONMENT_DEVELOPMENT;
}

/**
 * Set output handler as you wish.
 * @todo error, exception, shutdown
 */
// $app->setHandler('output', function($output) {
//    return trim($output);
// });

/**
 * Set application env/root/config and run application.
 */
$app->setEnv($env)
    ->setRoot($appRoot)
    ->setConfig($appConfig)
    ->run();
