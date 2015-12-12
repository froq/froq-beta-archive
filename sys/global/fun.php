<?php defined('root') or die('Access denied!');
/*************************************************
 * Core functions module, that used all over the *
 * application. Define all global functions will *
 * be used anywhere.                             *
 ************************************************/

/**
 * Global setter.
 * @param string $key
 * @param mixed  $value
 */
function set_global($key, $value) {
    $GLOBALS['@'][$key] = $value;
}

/**
 * Global getter.
 * @param  string $key
 * @param  mixed  $valueDefault
 * @return mixed
 */
function get_global($key, $valueDefault = null) {
    return isset($GLOBALS['@'][$key])
        ? $GLOBALS['@'][$key] : $valueDefault;
}

/**
 * Shortcut for app address.
 * @return \Application\Application
 */
function app($prop = null) {
    $app = get_global('app');
    return ($prop) ? $app->{$prop} : $app;
}

function dig(array $array = null, $key, $valueDefault = null) {
    // direct access
    if (isset($array[$key])) {
        $value =& $array[$key];
    }
    // trace element path
    else {
        $value =& $array;
        foreach (explode('.', $key) as $key) {
            $value =& $value[$key];
        }
    }
    return ($value !== null) ? $value : $valueDefault;
}

if (!function_exists('boolval')) {
    function boolval($value) {
        return (bool) $value;
    }
}

// @tmp debug
function _prp($s) {
    $p = '';
    if (is_null($s)) {
        $p .= 'NULL';
    } elseif (is_bool($s)) {
        $p .= $s ? 'TRUE' : 'FALSE';
    } else {
        $p .= preg_replace('~\[(.+):(.+):(private|protected)\]~', '[\\1:\\3]', print_r($s, 1));
    }
    return $p;
}
function prs($s, $e=false) {
    print $_prp($s) . PHP_EOL;
    $e && exit;
}
function pre($s, $e=false) {
    print '<pre>'. _prp($s) .'</pre>'. PHP_EOL;
    $e && exit;
}
