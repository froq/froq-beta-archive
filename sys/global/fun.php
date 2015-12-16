<?php defined('root') or die('Access denied!');
/*************************************************
 * Core functions module, that used all over the *
 * application. Define all global functions will *
 * be used anywhere.                             *
 ************************************************/

if (!isset($GLOBALS['@'])) {
    $GLOBALS['@'] = [];
}

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

/**
 * Array getter with dot notation support for sub-array paths.
 * @param  array  $array
 * @param  string $key (aka path)
 * @param  mixed  $valueDefault
 * @return mixed
 */
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

// @wait
function set_env($key, $value) {}

/**
 * Real env getter.
 * @param  string $key
 * @param  mixed  $valueDefault
 * @return mixed
 */
function get_env($key, $valueDefault = null) {
    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    if (false !== ($value = getenv($key))) {
        return $value;
    }
    if (function_exists('apache_getenv') && false !== ($return = apache_getenv($key, true))) {
        return $return;
    }
    return $valueDefault;
}

/**
 * Default value getter for null variables.
 * @param  any $a
 * @param  any $b
 * @return any
 */
function if_null($a, $b) {
    return (null !== $a) ? $a : $b;
}

/**
 * Default value getter for none variables.
 * @param  any $a
 * @param  any $b
 * @return any
 */
function if_none($a, $b) {
    return (none !== trim($a)) ? $a : $b;
}

/**
 * Some tricky functions.
 */
// nö!
function _isset($var) { return isset($var); }
function _empty($var) { return empty($var); }
// boolval
if (!function_exists('boolval')) {
    function boolval($value) {
        return (bool) $value;
    }
}
// get_callee
if (!function_exists('get_callee')) {
    function get_callee() {
        $trace = debug_backtrace();
        if (isset($trace[2])) {
            $trace[2]['object'] = get_class($trace[2]['object']);
            return $trace[2];
        }
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
    print _prp($s) . PHP_EOL;
    $e && exit;
}
function pre($s, $e=false) {
    print '<pre>'. _prp($s) .'</pre>'. PHP_EOL;
    $e && exit;
}
