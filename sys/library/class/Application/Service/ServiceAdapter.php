<?php namespace Application\Service;

use \Application\Application;
use \Application\Http\Uri\UriPath;

final class ServiceAdapter
{
    private $serviceName;
    private $serviceNameDefault = ServiceInterface::DEFAULT_NAME;
    private $serviceMethod;
    private $serviceMethodDefault = ServiceInterface::DEFAULT_METHOD_HOME;
    private $serviceFile;
    private $uriPath;

    final public function __construct() {
        $this->uriPath = new UriPath();
        if ($this->uriPath->isRoot()) {
            $serviceName = $this->serviceNameDefault;
            $serviceMethod = $this->serviceNameDefault;
        } else {
            $serviceName = $this->toServiceName($this->uriPath->getSegment(0));
            $serviceMethod = $this->toServiceMethod($this->uriPath->getSegment(1));
        }
        $this->setServiceName($serviceName);
        $this->setServiceMethod($serviceMethod);
        $this->setServiceFile($serviceName);
    }

    final public function createService(Application $app) {
        $service = (new $this->serviceName($this->serviceName))
            ->setApp($app)
            ->setMethod($this->serviceMethod)
            ->setUriPath($this->uriPath);
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
