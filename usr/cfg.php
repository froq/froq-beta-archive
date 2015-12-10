<?php defined('root') or die('Access denied!');
/**
 * User configuration file.
 */
$config = [];

/**
 * Application options.
 */
// allowed hosts
$config['app']['hosts'] = ['hazirtur.com', 'hazirtur.com.local'];

// defaults
$config['app']['locales'] = ['en_US' => 'English', 'tr_TR' => 'Türkçe'];

/**
 * Etc. options.
 */
// currency
$config['etc']['currency'] = [
    'EUR' => 'EUR (€)', 'GBP' => 'GBP (£)',
    'TRY' => 'TRY (₺)', 'USD' => 'USD ($)',
];

return $config;
