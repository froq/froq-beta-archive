<?php namespace Application\Service;

final class Service
    implements ServiceInterface
{
    private $name;

    final public function __construct($name = null) {
        $this->name = $this->toName($name);
    }

    final public function toName($name) {
        return preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($name));
    }
}
