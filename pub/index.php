<?php
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
$appRoot = '/api/v1';

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
