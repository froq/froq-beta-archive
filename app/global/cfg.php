<?php defined('root') or die('Access denied!');
/**
 * User configuration file.
 */
$cfg = [];

/**
 * Application options.
 */
// allowed hosts
$cfg['app']['hosts'] = [
    'froq.local',
];

// defaults
$cfg['app']['locales'] = [
    'tr_TR' => 'Türkçe',
    'en_US' => 'English',
];

/**
 * Database options.
 */
$cfg['db'] = [];
// mysql
$cfg['db']['mysql']['development'] = [
    'agent' => 'mysqli',
    'profiling' => true,
    'query_log' => true,
    'query_log_level' => Oppa\Logger::WARN | Oppa\Logger::FAIL, // @todo make your own logger
    'query_log_directory' => $app->config->get('app.dir.tmp') .'/log/db/',
    'query_log_filename_format' => 'Y-m-d',
    'database' => [
        'host'     => 'localhost',  'name'     => 'froq',
        'username' => 'root',       'password' => '********',
        'charset'  => 'utf8',       'timezone' => '+00:00',
    ],
];

/**
 * Etc. options.
 */
// currency
$cfg['etc']['currency'] = [
    'EUR' => 'EUR (€)', 'GBP' => 'GBP (£)',
    'TRY' => 'TRY (₺)', 'USD' => 'USD ($)',
];

return $cfg;
