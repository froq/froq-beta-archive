<?php namespace Application\Service;

final class Service
    implements ServiceInterface
{
    private $name;

    final public function __construct($name = null) {
        $this->name = $name;
    }

    final public function prepareName($name) {
        return preg_replace_callback('~-([a-z])~i', function($match) {
            pre($match);
            return ucfirst($match[1]);
        }, ucfirst($name));
    }
}
