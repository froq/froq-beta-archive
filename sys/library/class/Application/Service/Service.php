<?php namespace Application\Service;

use \Application\Http\Uri\UriPath;

final class Service
    implements ServiceInterface
{
    private $name;
    private $nameDefault = 'Home';

    final public function __construct() {
        $uriPath = new UriPath();
        if ($uriPath->isRoot()) {
            $name = $this->nameDefault;
        } else {
            $name = $uriPath->getSegment(0);
        }

        $this->name = $this->toName($name);
    }

    final public function toName($name) {
        return preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($name));
    }
}
