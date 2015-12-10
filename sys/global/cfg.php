<?php defined('root') or die('Access denied!');
/**
 * Default configuration file.
 */
$config = [];

/**
 * Application options.
 */
$config['app'] = [];

// load avg
$config['app']['loadAvg'] = 85.0;

// protocols
$config['app']['http'] = 'http://'. $_SERVER['SERVER_NAME'];
$config['app']['https'] = 'https://'. $_SERVER['SERVER_NAME'];

// directories
$config['app']['dir'] = [
    'tmp' => root .'/.tmp',
    'class' => root .'/sys/library/class',
    'function' => root .'/sys/library/function',
];

// defaults
$config['app']['language'] = 'en';
$config['app']['timezone'] = 'UTC';
$config['app']['encoding'] = 'utf-8';
$config['app']['locale']   = 'en_US';
$config['app']['locales']  = ['en_US' => 'English'];

// initial headers
$config['app']['headers'] = [
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
$config['app']['cookies'] = [];

// session
$config['app']['session'] = [
    'name'      => 'SID',   'domain'   => null,
    'path'      => '/',     'secure'   => false,
    'httponly'  => false,   'lifetime' => 0,
    'save_path' => $config['app']['dir']['tmp'] .'/session',
    'length'    => 128, // 128-bit
];

/**
 * Security options.
 */
$config['security'] = [];
$config['security']['maxRequest'] = 100;
$config['security']['allowEmptyUserAgent'] = false;
$config['security']['allowFileExtensionSniff'] = false;

/**
 * Etc. options.
 */
$config['etc'] = [];

// pager
$config['etc']['pager'] = [
    's'     => 's',   // start
    'ss'    => 'ss',  // stop
    'limit' => 10,    // limit
];

return $config;
