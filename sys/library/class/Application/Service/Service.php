<?php namespace Application\Service;

use \Application\Exception;
use \Application\Application;
use \Application\Config;

abstract class Service
    implements ServiceInterface
{
    protected $app;
    protected $name;
    protected $method;
    protected $methodAccept;
    protected $allowedRequestMethods = [];
    protected $config;

    public function __construct($name = null) {
        $this->name = $name;
        // autoload config
        $this->loadConfig();
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
    final public function callMethodBefore() {
        return $this->callMethod(ServiceInterface::METHOD_BEFORE, false);
    }
    final public function callMethodAfter() {
        return $this->callMethod(ServiceInterface::METHOD_AFTER, false);
    }
    final public function callMethodInvoked() {
        if ($this->methodAccept) {
            return $this->callMethod($this->method);
        }
        // always use home method
        return $this->callMethodHome();
    }

    final public function setApp(Application $app) {
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

    final public function setMethodAccept($methodAccept) {
        $this->methodAccept = $methodAccept;
    }
    final public function getMethodAccept() {
        return $this->methodAccept;
    }

    final public function isRequestMethodAllowed($requestMethod) {
        if (empty($this->allowedRequestMethods)) {
            return true;
        }
        return in_array($requestMethod, $this->allowedRequestMethods);
    }
    final public function setAllowedRequestMethods(...$allowedRequestMethods) {
        $this->allowedRequestMethods = $allowedRequestMethods;
        return $this;
    }
    final public function getAllowedRequestMethods() {
        return $this->allowedRequestMethods;
    }

    final public function loadConfig() {
        $configFile = sprintf('./app/service/%s/config/config.php', $this->name);
        if (is_file($configFile)) {
            $this->config = new Config($configFile);
        }
        return $this;
    }
}
