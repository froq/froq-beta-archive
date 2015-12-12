<?php namespace Application\Service;

use Application\Application,
    Application\Application\View,
    Application\Application\Config;
use Application\Util\Traits\GetterTrait;

abstract class Service
    implements ServiceInterface
{
    use GetterTrait;

    protected $app;

    protected $name;
    protected $method;

    protected $model;
    protected $view;

    protected $config;

    protected $useMainOnly = false;

    protected $useViewPartialAll  = false,
              $useViewPartialHead = false,
              $useViewPartialFoot = false;

    protected $allowedRequestMethods = [];

    public function __construct($name) {
        $this->name = $name;
        // autoloads
        $this->loadConfig();
        $this->loadModel();
        $this->view = new View($this);

        if (!empty($this->allowedRequestMethods)) {
            $this->allowedRequestMethods = array_map('strtoupper', $this->allowedRequestMethods);
        }
    }

    final public function isHome() {
        return ($this->method == '');
    }

    final public function callMethod($method, $halt = true) {
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
        if ($halt) {
            throw new \Exception(sprintf('`%s` method not found on `%s`',
                $method, get_called_class()));
        }
    }
    final public function callMethodInit() {
        return $this->callMethod(ServiceInterface::METHOD_INIT, false);
    }
    final public function callMethodMain() {
        return $this->callMethod(ServiceInterface::METHOD_MAIN);
    }
    final public function callMethodBefore() {
        return $this->callMethod(ServiceInterface::METHOD_BEFORE, false);
    }
    final public function callMethodAfter() {
        return $this->callMethod(ServiceInterface::METHOD_AFTER, false);
    }
    final public function callMethodInvoked() {
        // always uses main method
        if ($this->useMainOnly) {
            return $this->callMethodMain();
        }
        return $this->callMethod($this->method);
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

    final public function isRequestMethodAllowed($requestMethod) {
        if (empty($this->allowedRequestMethods)) {
            return true;
        }
        return in_array($requestMethod, $this->allowedRequestMethods);
    }
    final public function setAllowedRequestMethods(...$allowedRequestMethods) {
        $this->allowedRequestMethods = array_map('strtoupper', $allowedRequestMethods);
        return $this;
    }
    final public function getAllowedRequestMethods() {
        return $this->allowedRequestMethods;
    }

    final public function loadConfig() {
        $file = sprintf('./app/service/%s/config/config.php', $this->name);
        if (is_file($file)) {
            $this->config = new Config($file);
        }
        return $this;
    }
    final public function loadModel() {
        $file = sprintf('./app/service/%s/model/%sModel.php',
            $this->name, substr($this->name, 0, -strlen('Service')));
        if (is_file($file)) {
            include($file);
        }
        return $this;
    }

    final public function view($file, array $data = null) {
        if ($this->useViewPartialAll || (
            $this->useViewPartialHead && $this->useViewPartialFoot)) {
            $this->view->displayHead($data);
            $this->view->display($file, $data);
            $this->view->displayFoot($data);
        } elseif ($this->useViewPartialHead && !$this->useViewPartialFoot) {
            $this->view->displayHead($data);
            $this->view->display($file, $data);
        } elseif (!$this->useViewPartialHead && $this->useViewPartialFoot) {
            $this->view->display($file, $data);
            $this->view->displayFoot($data);
        } else {
            $this->view->display($file, $data);
        }
    }
}
