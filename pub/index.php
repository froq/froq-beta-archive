<?php
/**
 * Set everything as relative to the application root.
 */
chdir(dirname(__dir__));

/**
 * Include bootstrap that registers
 * Autoload and returns Application.
 */
$app = include('./sys/Boot.php');

/**
 * User app config.
 */
$cfg = include('./app/global/cfg.php');

/**
 * Set application config and run application.
 */
$app->setConfig($cfg)
    ->run();
