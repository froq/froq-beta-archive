<?php namespace Application\Service;

use \Application\Exception;
use \Application\Application;

final class ServiceAdapter
{
    private $app;
    private $serviceName;
    private $serviceNameDefault = ServiceInterface::DEFAULT_NAME;
    private $serviceMethod;
    private $serviceMethodDefault = ServiceInterface::METHOD_MAIN;
    private $serviceFile;

    final public function __construct(Application $app) {
        $this->app = $app;
        // home?
        if ($this->app->request->uri->getPath() == '/') {
            $serviceName = $this->serviceNameDefault;
            $serviceMethod = $this->serviceMethodDefault;
        } else {
            $serviceName = $this->toServiceName($this->app->request->uri->segment(0));
            $serviceMethod = $this->toServiceMethod($this->app->request->uri->segment(1));
        }
        $this->setServiceName($serviceName);
        $this->setServiceMethod($serviceMethod);
        $this->setServiceFile($serviceName);
    }

    final public function createService() {
        $service = (new $this->serviceName($this->serviceName))
            ->setApp($this->app)
            ->setMethod($this->serviceMethod)
        ;
        return $service;
    }

    final public function isServiceExists() {
        return file_exists($this->serviceFile) && class_exists($this->serviceName);
    }

    final public function setServiceName($serviceName) {
        $this->serviceName = trim($serviceName);
    }
    final public function getServiceName() {
        return $this->serviceName;
    }

    final public function setServiceMethod($serviceMethod) {
        $this->serviceMethod = trim($serviceMethod);
    }
    final public function getServiceMethod() {
        return $this->serviceMethod;
    }

    final public function setServiceFile($serviceName) {
        $this->serviceFile = sprintf('./app/service/%s/%s.php', $serviceName, $serviceName);
    }
    final public function getServiceFile() {
        return $this->serviceFile;
    }

    final private function toServiceName($name) {
        $name = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($name));
        return sprintf('%sService', $name);
    }
    final private function toServiceMethod($method) {
        $method = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, lcfirst($method));
        return $method;
    }
}
