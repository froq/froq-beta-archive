<?php declare(strict_types=1);
namespace Application\Service;

use Application\Application;
use Application\Util\{View, Config};
use Application\Util\Traits\GetterTrait as Getter;

abstract class Service
   implements ServiceInterface
{
   use Getter;

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

   final public function __construct(Application $app, string $name, string $method, $viewData = null)
   {
      $this->app = $app;
      $this->name = $name;
      $this->method = $method;
      $this->viewData = $viewData;

      // autoloads
      $this->loadConfig();
      $this->loadModel();
      $this->view = new View($this);

      if (!empty($this->allowedRequestMethods)) {
         $this->allowedRequestMethods = array_map('strtoupper', $this->allowedRequestMethods);
      }
   }

   final public function isMain(): bool
   {
      return empty($this->method) || ($this->method == ServiceInterface::METHOD_MAIN);
   }

   final public function run()
   {
      if (method_exists($this, ServiceInterface::METHOD_INIT)) {
         $this->{ServiceInterface::METHOD_INIT}();
      }
      if (method_exists($this, ServiceInterface::METHOD_ONBEFORE)) {
         $this->{ServiceInterface::METHOD_ONBEFORE}();
      }

      $output = null;
      // always uses main method
      if ($this->isMain() || $this->useMainOnly) {
         $output = $this->{ServiceInterface::METHOD_MAIN}();
      } elseif (method_exists($this, $this->method)) {
         $output = $this->{$this->method}();
      } else {
         // call fail::main
         $output = $this->main();
      }

      if (method_exists($this, ServiceInterface::METHOD_ONAFTER)) {
         $this->{ServiceInterface::METHOD_ONAFTER}();
      }

      return $output;
   }

   final public function isRequestMethodAllowed(string $requestMethod): bool
   {
      if (empty($this->allowedRequestMethods)) {
         return true;
      }
      return in_array($requestMethod, $this->allowedRequestMethods);
   }
   final public function setAllowedRequestMethods(array ...$allowedRequestMethods): self
   {
      $this->allowedRequestMethods = array_map('strtoupper', $allowedRequestMethods);
      return $this;
   }
   final public function getAllowedRequestMethods(): array
   {
      return $this->allowedRequestMethods;
   }

   final private function loadConfig(): self
   {
      $file = sprintf('./app/service/%s/config/config.php', $this->name);
      if (is_file($file)) {
         $this->config = new Config($file);
      }
      return $this;
   }
   final private function loadModel(): self
   {
      $file = sprintf('./app/service/%s/model/model.php', $this->name);
      if (is_file($file)) {
         include($file);
      }
      return $this;
   }

   final public function view(string $file, array $data = null)
   {
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
