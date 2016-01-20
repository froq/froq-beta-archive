<?php
/**
 * Set everything as relative to the application root.
 */
chdir(dirname(__dir__));

/**
 * Include bootstrap that registers Autoload
 * and returns Application.
 */
$app = require('./sys/Boot.php');

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
// set_global('app.handler.output', function($output) {
//    return trim($output);
// });

/**
 * Set application env/root/config and run application.
 */
$app->setEnv($env)
    // ->setRoot('/') @todo
    ->setConfig($appConfig)
    ->run();
