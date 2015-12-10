<?php defined('root') or die('Access denied!');
/**
 * Global cfg stack.
 * @var array
 */
$cfg = [];

/*** app ***/
$cfg['hosts'] = ['hazirtur.com', 'hazirtur.com.local'];
/*** app paths ***/
$cfg['app.dir.tmp'] = root .'/.tmp';
$cfg['app.dir.class'] = root .'/sys/library/class';
$cfg['app.dir.function'] = root .'/sys/library/function';
/*** app defaults ***/
$cfg['app.language'] = 'en';
$cfg['app.timezone'] = 'UTC';
$cfg['app.encoding'] = 'utf-8';
$cfg['app.locale']   = 'en_US';
$cfg['app.locales']  = [
    'tr_TR' => 'Türkçe',
    'en_US' => 'English'
];

/*** initial headers ***/
$cfg['site.init.headers'] = [
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
/*** initial cookies ***/
$cfg['site.init.cookies'] = [];

/*** site session ***/
$cfg['site.session.cookie'] = [
    'name'      => 'SID',   'domain'   => null,
    'path'      => '/',     'secure'   => false,
    'httponly'  => false,   'lifetime' => 0,
    'save_path' => $cfg['app.dir.tmp'] .'/session',
    // 128-bit
    'length' => 128
];
/*** site meta (used in site module) ***/
$cfg['site.name'] = '';
$cfg['site.title'] = '';
$cfg['site.description'] = '';
$cfg['site.http'] = 'http://'. $_SERVER['SERVER_NAME'];
$cfg['site.https'] = 'https://'. $_SERVER['SERVER_NAME'];

/*** image ***/
$cfg['image.mimeTypes'] = [
    'image/jpeg', 'image/pjpeg',
    'image/png',  'image/x-png', 'image/gif',
];
$cfg['image.tmp.path'] = '/upload/_tmp';
$cfg['image.maxUploadSize'] = 2097152; // 2MB

/*** etc ***/
// pager
$cfg['etc.pager.s'] = 's';
$cfg['etc.pager.limit'] = 5;
// currency
$cfg['etc.currency'] = ['EUR' => 'EUR (€)', 'GBP' => 'GBP (£)', 'TRY' => 'TRY (₺)', 'USD' => 'USD ($)'];

// set into globals.cfg
$GLOBALS['@']['cfg'] = $cfg;

// remove cfg from global scope
unset($cfg);

/**
 * Global config setter/getter.
 * @param  string $key
 * @param  mixed  $value
 * @return mixed
 */
function cfg($key = null, $value = null) {
    // get all
    if ($key === null) {
        return $GLOBALS['@']['cfg'];
    }
    // get
    if ($value === null) {
        return isset($GLOBALS['@']['cfg'][$key])
            ? $GLOBALS['@']['cfg'][$key] : null;
    }
    // set
    $GLOBALS['@']['cfg'][$key] = $value;
}

/**
 * Load cfg file.
 * @param  string $name
 * @return array
 */
function cfg_load($name) {
    $file = sprintf('%s/sys/global/cfg/%s.php', root, $name);
    // check file
    if (!is_file($file)) {
        throw new \RuntimeException("Config file `{$file}` not found!");
    }

    return include($file);
}
