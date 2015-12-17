<?php declare(strict_types=1);
namespace Application\Service;

use Application\Application;
use Application\Util\{View, Config};
use Application\Util\Traits\GetterTrait;
use Application\Http\Response\Status;

abstract class Service
    implements ServiceInterface
{
    use GetterTrait;

    protected $app;

    protected $name;
    protected $method;

    protected $model;

    protected $view;
    protected $viewData = null; // mixed

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

    final public function isMain(): bool {
        return (empty($this->method) || $this->method == self::METHOD_MAIN);
    }

    final public function run() {
        if (method_exists($this, self::METHOD_INIT)) {
            $this->{self::METHOD_INIT}();
        }
        if (method_exists($this, self::METHOD_ONBEFORE)) {
            $this->{self::METHOD_ONBEFORE}();
        }

        $output = null;
        // always uses main method
        if ($this->isMain() || $this->useMainOnly) {
            $output = $this->{self::METHOD_MAIN}();
        } elseif (method_exists($this, $this->method)) {
            $output = $this->{self::METHOD_PREFIX . $this->method}();
        } else {
            // fail!
            $viewData['fail']['code'] = Status::NOT_FOUND;
            $viewData['fail']['text'] = sprintf('Service not found! name: %s', $this->name);
            $this->setViewData($viewData);
        }

        if (method_exists($this, self::METHOD_ONAFTER)) {
            $this->{self::METHOD_ONAFTER}();
        }

        return $output;
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

    final public function setViewData($viewData): self {
        $this->viewData = $viewData;
        return $this;
    }
    final public function getViewData() {
        return $this->viewData;
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

    final private function loadConfig(): self {
        $file = sprintf('./app/service/%s/config/config.php', $this->name);
        if (is_file($file)) {
            $this->config = new Config($file);
        }
        return $this;
    }
    final private function loadModel(): self {
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
