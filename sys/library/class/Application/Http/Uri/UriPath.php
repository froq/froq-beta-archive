<?php declare(strict_types=1);
namespace Application\Http\Uri;

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

    final public function isRoot(): bool {
        return ($this->path == '/');
    }

    final public function getPath(): string {
        return $this->path;
    }
    final public function getSegment($i, $def = null) {
        if (isset($this->segments[$i])) {
            return $this->segments[$i];
        }
        return $def;
    }
    final public function getSegmentAll(): array {
        return $this->segments;
    }

    final public function segment($i, $def = null) {
        return $this->getSegment($i, $def);
    }

    final public static function parse($path): array {
        $path = preg_split('~/+~', $path, -1, PREG_SPLIT_NO_EMPTY);
        $path = array_filter(array_map('trim', $path));
        return $path;
    }
}
