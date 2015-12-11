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
    'hazirtur.com',
    'hazirtur.com.local',
];

// defaults
$cfg['app']['locales'] = [
    'tr_TR' => 'Türkçe',
    'en_US' => 'English',
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
