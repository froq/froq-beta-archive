<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <http://qeremy.com>
 *
 * GNU General Public License v3.0
 *    <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Application\Service;

use Application\Application;
use Application\Http\Response\Status;

/**
 * @package    Application
 * @subpackage Application\Service
 * @object     Application\Service\ServiceAdapter
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class ServiceAdapter
{
   /**
    * Application object.
    * @var Application\Application
    */
   private $app;

   /**
    * Service object.
    * @var Application\Service\Service
    */
   private $service;

   /**
    * Service name.
    * @var string
    */
   private $serviceName;

   /**
    * Service default name.
    * @var string
    */
   private $serviceNameDefault = ServiceInterface::SERVICE_MAIN;

   /**
    * Service method.
    * @var string
    */
   private $serviceMethod;

   /**
    * Service default method.
    * @var string
    */
   private $serviceMethodDefault = ServiceInterface::METHOD_MAIN;

   /**
    * Service file.
    * @var string
    */
   private $serviceFile;

   /**
    * Service view data.
    * @var string
    */
   private $serviceViewData = null;

   /**
    * Constructor.
    *
    * @param Application\Application $app
    */
   final public function __construct(Application $app)
   {
      $this->app = $app;

      // detect service name
      $this->serviceName = ($serviceName = $this->app->request->uri->segment(0))
         ? $this->toServiceName($serviceName) : $this->serviceNameDefault;

      // detect service file
      $this->serviceFile = $this->toServiceFile($this->serviceName);

      // set service as FailService
      if (!$this->isServiceExists()) {
         $this->serviceViewData['fail']['code'] = Status::NOT_FOUND;
         $this->serviceViewData['fail']['text'] = sprintf(
            'Service not found! [%s]', $this->serviceName);
         $this->serviceName = ServiceInterface::SERVICE_FAIL;
         $this->serviceFile = $this->toServiceFile($this->serviceName);
      }

      // create service
      $this->service = $this->createService();

      // detect service method
      if ($this->service->protocol == ServiceInterface::PROTOCOL_SITE && !$this->service->useMainOnly) {
         $this->serviceMethod = ($serviceMethod = $this->app->request->uri->segment(1, ''))
            ? $this->toServiceMethod($serviceMethod) : $this->serviceMethodDefault;
      } elseif ($this->service->protocol == ServiceInterface::PROTOCOL_REST) {
         $this->serviceMethod = strtolower($this->app->request->method);
      }

      // set service as FailService
      if (!$this->isServiceFail() && !$this->isServiceMethodExists()) {
         $this->serviceViewData['fail']['code'] = Status::NOT_FOUND;
         $this->serviceViewData['fail']['text'] = sprintf(
            'Service method not found! [%s::%s()]', $this->serviceName, $this->serviceMethod);
         // overwrite
         $this->serviceName = ServiceInterface::SERVICE_FAIL;
         $this->serviceMethod = ServiceInterface::METHOD_MAIN;
         $this->serviceFile = $this->toServiceFile($this->serviceName);

         // re-create service as FailService
         $this->service = $this->createService();
      }

      // re-set service method
      $this->service->setMethod($this->serviceMethod);
   }

   /**
    * Check service is FailService.
    *
    * @return bool
    */
   final public function isServiceFail(): bool
   {
      return ($this->serviceName == ServiceInterface::SERVICE_FAIL);
   }

   /**
    * Cehck service is exists.
    *
    * @return bool
    */
   final public function isServiceExists(): bool
   {
      return is_file($this->serviceFile) && class_exists($this->serviceName);
   }

   /**
    * Cehck service method is exists.
    *
    * @return bool
    */
   final public function isServiceMethodExists(): bool
   {
      return ($this->service && method_exists($this->service, $this->serviceMethod));
   }

   /**
    * Get service.
    *
    * @return Application\Service\ServiceInterface
    */
   final public function getService(): ServiceInterface
   {
      return $this->service;
   }

   /**
    * Get service name.
    *
    * @return string
    */
   final public function getServiceName(): string
   {
      return $this->serviceName;
   }

   /**
    * Get service method.
    *
    * @return string
    */
   final public function getServiceMethod(): string
   {
      return $this->serviceMethod;
   }

   /**
    * Get service file.
    *
    * @return string
    */
   final public function getServiceFile(): string
   {
      return $this->serviceFile;
   }

   /**
    * Create service.
    *
    * @return Application\Service\ServiceInterface
    */
   final private function createService(): ServiceInterface
   {
      return new $this->serviceName(
         $this->app,
         $this->serviceName,
         $this->serviceMethod,
         $this->serviceViewData
      );
   }

   /**
    * Prepare service name.
    *
    * @param  string $name
    * @return string
    */
   final private function toServiceName(string $name): string
   {
      $name = preg_replace_callback('~-([a-z])~i', function($match) {
         return ucfirst($match[1]);
      }, ucfirst($name));

      return sprintf('%s%s', $name, ServiceInterface::SERVICE_NAME_SUFFIX);
   }

   /**
    * Prepare service method.
    *
    * @param  string $method
    * @return string
    */
   final private function toServiceMethod(string $method): string
   {
      $method = preg_replace_callback('~-([a-z])~i', function($match) {
         return ucfirst($match[1]);
      }, ucfirst($method));

      return sprintf('%s%s', ServiceInterface::METHOD_NAME_PREFIX, $method);
   }

   /**
    * Prepare service file.
    *
    * @param  string $file
    * @return string
    */
   final private function toServiceFile(string $serviceName): string
   {
      $serviceFile = sprintf('./app/service/%s/%s.php', $serviceName, $serviceName);
      if (!is_file($serviceFile) && (
         $serviceName == ServiceInterface::SERVICE_MAIN ||
         $serviceName == ServiceInterface::SERVICE_FAIL
      )) {
         $serviceFile = sprintf('./app/service/default/%s/%s.php', $serviceName, $serviceName);
         // no need to autoload
         require_once($serviceFile);
      }

      return $serviceFile;
   }
}
