<?php namespace Application\Service;

use \Application\Http\Uri\UriPath;

abstract class Service
    implements ServiceInterface
{
    protected $name;
    protected $allowedMethods = [];

    final public function isMethodAllowed($method) {
        return in_array($method, $this->allowedMethods);
    }

    final public function setAllowedMethods(...$allowedMethods) {
        $this->allowedMethods = $allowedMethods;
    }
    final public function getAllowedMethods() {
        return $this->allowedMethods;
    }

    final public function setName($name) {
        $this->name = $name;
    }
    final public function getName() {
        return $this->name;
    }

    // final
}
