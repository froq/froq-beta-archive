<?php defined('root') or die('Access denied!');
/**
 * Default configuration file.
 */
$cfg = [];

/**
 * Application options.
 */
$cfg['app'] = [];

// gzip
$cfg['app']['gzip'] = [
    'use'   => true,
    'level' => -1,
    'mode'  => FORCE_GZIP,
];

// load avg
$cfg['app']['loadAvg'] = 85.00;

// protocols
$cfg['app']['http'] = 'http://'. $_SERVER['SERVER_NAME'];
$cfg['app']['https'] = 'https://'. $_SERVER['SERVER_NAME'];

// directories
$cfg['app']['dir'] = [
    'tmp' => root .'/.tmp',
    'class' => root .'/sys/library/class',
    'function' => root .'/sys/library/function',
];

// defaults
$cfg['app']['language']  = 'en';
$cfg['app']['languages'] = ['en'];
$cfg['app']['timezone']  = 'UTC';
$cfg['app']['encoding']  = 'utf-8';
$cfg['app']['locale']    = 'en_US';
$cfg['app']['locales']   = ['en_US' => 'English'];

// initial headers
$cfg['app']['headers'] = [
    'Expires' => 'Thu, 19 Nov 1981 08:10:00 GMT',
    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0, pre-check=0, post-check=0',
    'Pragma' => 'no-cache',
    'Connection' => 'close',
    'X-Powered-By' => null, // remove
    // security (https://www.owasp.org/index.php/List_of_useful_HTTP_headers)
    'X-Frame-Options' => 'SAMEORIGIN',
    'X-XSS-Protection' => '1; mode=block',
    'X-Content-Type-Options' => 'nosniff',
];

// initial cookies
$cfg['app']['cookies'] = [];

// session
$cfg['app']['session'] = [];
// session cookie
$cfg['app']['session']['cookie'] = [
    'name'      => 'SID',   'domain'   => '',
    'path'      => '/',     'secure'   => false,
    'httponly'  => false,   'lifetime' => 0,
    'save_path' => $cfg['app']['dir']['tmp'] .'/session',
    'length'    => 128, // 128-bit
];

/**
 * Security options.
 */
$cfg['security'] = [];
$cfg['security']['maxRequest'] = 100;
$cfg['security']['allowEmptyUserAgent'] = false;
$cfg['security']['allowFileExtensionSniff'] = false;

/**
 * Etc. options.
 */
$cfg['etc'] = [];

// pager
$cfg['etc']['pager'] = [
    's'     => 's',   // start
    'ss'    => 'ss',  // stop
    'limit' => 10,    // limit
];

return $cfg;
