<?php defined('root') or die('Access denied!');
/**
 * User configuration file.
 */
$cfg = [];

/**
 * Application options.
 */
// allowed hosts (add more if needed)
$cfg['app']['hosts'][] = $_SERVER['SERVER_NAME'];

// defaults
$cfg['app']['locales']['tr_TR'] = 'Türkçe';
$cfg['app']['locales']['en_US'] = 'English';

// logger
$cfg['app']['logger'] = [];
$cfg['app']['logger']['level'] = Application\Logger\Logger::ALL;
$cfg['app']['logger']['directory'] = $app->config->get('app.dir.tmp') .'/log/app/';
$cfg['app']['logger']['filenameFormat'] = 'Y-m-d';

/**
 * Database options.
 */
$cfg['db'] = [];
// mysql
$cfg['db']['mysql']['development'] = [
   'agent' => 'mysqli',
   'profiling' => true,
   'query_log' => true,
   'query_log_level' => Oppa\Logger::WARN | Oppa\Logger::FAIL,
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
