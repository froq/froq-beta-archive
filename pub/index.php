<?php
/**
 * Set everything as relative to the application root.
 */
chdir(dirname(__dir__));

/**
 * Include bootstrap that registers
 * Autoload and returns Application.
 */
$app = require('./sys/Boot.php');

/**
 * User app config.
 */
$cfg = require('./app/global/cfg.php');

/**
 * Application env.
 */
$env = Application\Application::ENV_DEV;

/**
 * Set application config and run application.
 */
$app->setEnv($env)
    ->setConfig($cfg)
    ->run();
