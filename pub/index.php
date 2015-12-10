<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 * https://github.com/akrabat/zf2-tutorial/blob/master/public/index.php
 */
chdir(dirname(__DIR__));

header("content-type:");
function pre($s, $e=false) {
    if (is_null($s)) {
        print 'NULL';
    } elseif (is_bool($s)) {
        print $s ? 'TRUE' : 'FALSE';
    } else {
        $s = preg_replace('~\[(.+):(.+):(private|protected)\]~', '[\\1:\\3]', print_r($s, 1));
        print $s;
    }
    print PHP_EOL;
    $e && exit;
}

require(__DIR__.'/../sys/library/class/Application/Http/Uri/UriPath.php');

$uripath = new Application\Http\Uri\UriPath();
pre($uripath);
pre($uripath->getSegment(0));
