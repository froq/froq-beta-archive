<?php namespace Application\Service;

use \Application\Exception;
use \Application\Http\Uri\UriPath;

abstract class Service
    implements ServiceInterface
{
    protected $app;
    protected $name;
    protected $method;
    protected $uriPath;
    protected $requestMethods = [];

    public function __construct($name = null) {
        $this->name = $name;
    }

    final public function isRequestMethodAllowed($requestMethod) {
        if (empty($this->requestMethods)) {
            return true;
        }
        return in_array($requestMethod, $this->requestMethods);
    }

    final public function isHome() {
        return ($this->method == '');
    }

    final public function callMethod($method, $halt = true) {
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        if ($halt) {
            throw new Exception(sprintf('`%s` method not found on `%s`',
                $method, get_called_class()));
        }
    }
    final public function callMethodInit() {
        return $this->callMethod(ServiceInterface::DEFAULT_METHOD_INIT, false);
    }
    final public function callMethodHome() {
        return $this->callMethod(ServiceInterface::DEFAULT_METHOD_HOME);
    }
    final public function callMethodInvoked() {
        return $this->callMethod($this->method);
    }

    final public function setApp($app) {
        $this->app = $app;
        return $this;
    }
    final public function getApp() {
        return $this->app;
    }

    final public function setName($name) {
        $this->name = $name;
        return $this;
    }
    final public function getName() {
        return $this->name;
    }

    final public function setMethod($method) {
        $this->method = $method;
        return $this;
    }
    final public function getMethod() {
        return $this->method;
    }

    final public function setUriPath(UriPath $uriPath) {
        $this->uriPath = $uriPath;
        return $this;
    }
    final public function getUriPath() {
        return $this->uriPath;
    }

    final public function setRequestMethods(...$requestMethods) {
        $this->requestMethods = $requestMethods;
        return $this;
    }
    final public function getRequestMethods() {
        return $this->requestMethods;
    }
}
