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
function set_global(string $key, $value) {
    $GLOBALS['@'][$key] = $value;
}

/**
 * Global getter.
 * @param  string $key
 * @param  mixed  $valueDefault
 * @return mixed
 */
function get_global(string $key, $valueDefault = null) {
    return isset($GLOBALS['@'][$key])
        ? $GLOBALS['@'][$key] : $valueDefault;
}

/**
 * Shortcut for app address.
 * @return \Application\Application|mixed
 */
function app(string $prop = null) {
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
function dig(array $array = null, string $key, $valueDefault = null) {
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
function set_env(string $key, $value) {}

/**
 * Real env getter.
 * @param  string $key
 * @param  mixed  $valueDefault
 * @return mixed
 */
function get_env(string $key, $valueDefault = null) {
    if (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    }
    if (false !== ($value = getenv($key))) {
        return $value;
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
function _isset($var): bool { return isset($var); }
function _empty($var): bool { return empty($var); }
// boolval
if (!function_exists('boolval')) {
    function boolval($value): bool {
        return (bool) $value;
    }
}
// get_callee
if (!function_exists('get_callee')) {
    function get_callee($i = 1): array {
        $trace = debug_backtrace();
        if (isset($trace[$i])) {
            $trace[$i]['object'] = get_class($trace[$i]['object']);
            return $trace[$i];
        }
    }
}

// @tmp debug
function _prp($s) {
    $p = '';
    if (is_null($s)) {
        $p = 'NULL';
    } elseif (is_bool($s)) {
        $p = $s ? 'TRUE' : 'FALSE';
    } else {
        $p = preg_replace('~\[(.+):(.+):(private|protected)\]~', '[\\1:\\3]', print_r($s, 1));
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
function prd($s, $e=false) {
    print '<pre>'; var_dump($s); print '</pre>'. PHP_EOL;
    $e && exit;
}
