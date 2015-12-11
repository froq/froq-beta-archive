<?php
/**
 * Set everything as relative to the application root.
 */
chdir(dirname(__dir__));

/**
 * Include bootstrap that returns Application.
 */
$app = include('./sys/Boot.php');

/**
 * New application config with user config.
 */
$cfg = new Application\Config('./app/global/cfg.php', true);

/**
 * Set application config.
 * Set application defaults.
 * Run application.
 */
$app->setConfig($cfg)
    ->setDefaults()
    ->run();
