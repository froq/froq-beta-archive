<?php
namespace Application\Http\Uri;

/*
"/book/123"
bunu parse edip hangi servisin cagrildigini soylicek
misal burda BookService istendi
 */
final class UriPath
{
    private $path;
    private $segments = [];

    final public function __construct() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if ($path != '') {
            $this->path = $path;
            $this->segments = self::parse($path);
        }
    }

    final public function getPath() {
        return $this->path;
    }
    final public function getSegment($i) {
        if (isset($this->segments[$i])) {
            return $this->segments[$i];
        }
        return null;
    }
    final public function getSegmentAll() {
        return $this->segments;
    }

    final public static function parse($path) {
        return preg_split('~/+~', $path, -1, PREG_SPLIT_NO_EMPTY);
    }
}
