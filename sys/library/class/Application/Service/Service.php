<?php declare(strict_types=1);
namespace Application\Service;

use Application\Application;
use Application\Util\{View, Config};
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

    protected $validations = []; // @todo from <service>/config/config.php
    protected $allowedRequestMethods = [];

    final public function __construct(string $name) {
        $this->name = $name;

        // autoloads
        $this->loadConfig();
        $this->loadModel();
        $this->view = new View($this);

        if (!empty($this->allowedRequestMethods)) {
            $this->allowedRequestMethods = array_map('strtoupper', $this->allowedRequestMethods);
        }
    }

    final public function isHome(): bool {
        return empty($this->method);
    }

    final public function callMethod(string $method, bool $halt = true) {
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
    final public function callMethodOnBefore() {
        return $this->callMethod(ServiceInterface::METHOD_ONBEFORE, false);
    }
    final public function callMethodOnAfter() {
        return $this->callMethod(ServiceInterface::METHOD_ONAFTER, false);
    }
    final public function callMethodInvoked() {
        // always uses main method
        if ($this->useMainOnly) {
            return $this->callMethodMain();
        }
        return $this->callMethod($this->method);
    }

    final public function setApp(Application $app): self {
        $this->app = $app;
        return $this;
    }
    final public function getApp(): Application {
        return $this->app;
    }

    final public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }
    final public function getName(): string {
        return $this->name;
    }

    final public function setMethod($method): self {
        $this->method = $method;
        return $this;
    }
    final public function getMethod(): string {
        return $this->method;
    }

    final public function isRequestMethodAllowed(string $requestMethod): bool {
        if (empty($this->allowedRequestMethods)) {
            return true;
        }
        return in_array($requestMethod, $this->allowedRequestMethods);
    }
    final public function setAllowedRequestMethods(array ...$allowedRequestMethods): self {
        $this->allowedRequestMethods = array_map('strtoupper', $allowedRequestMethods);
        return $this;
    }
    final public function getAllowedRequestMethods(): array {
        return $this->allowedRequestMethods;
    }

    final public function loadConfig(): self {
        $file = sprintf('./app/service/%s/config/config.php', $this->name);
        if (is_file($file)) {
            $this->config = new Config($file);
        }
        return $this;
    }
    final public function loadModel(): self {
        $file = sprintf('./app/service/%s/model/model.php', $this->name);
        if (is_file($file)) {
            include($file);
        }
        return $this;
    }

    final public function view(string $file, array $data = null) {
        if ($this->useViewPartialAll || ($this->useViewPartialHead && $this->useViewPartialFoot)) {
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
