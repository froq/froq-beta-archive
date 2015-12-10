<?php defined('root') or die('Access denied!');
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
