<?php declare(strict_types=1);
namespace Application\Service;

use \Application\Application;
use \Application\Service\ServiceInterface;

final class ServiceAdapter
{
    private $app;
    private $serviceName;
    private $serviceNameDefault = ServiceInterface::DEFAULT_SERVICE;
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
            $serviceName = $this->toServiceName((string) $this->app->request->uri->segment(0));
            $serviceMethod = $this->toServiceMethod((string) $this->app->request->uri->segment(1));
        }
        $this->setServiceName($serviceName);
        $this->setServiceMethod($serviceMethod);
        $this->setServiceFile($serviceName);
    }

    final public function createService(): ServiceInterface {
        return (new $this->serviceName($this->serviceName))
            ->setApp($this->app)
            ->setMethod($this->serviceMethod);
    }

    final public function isServiceExists(): bool {
        return file_exists($this->serviceFile) && class_exists($this->serviceName);
    }

    final public function setServiceName(string $serviceName): self {
        $this->serviceName = trim($serviceName);
        return $this;
    }
    final public function getServiceName(): string {
        return $this->serviceName;
    }

    final public function setServiceMethod(string $serviceMethod): self {
        $this->serviceMethod = trim($serviceMethod);
        return $this;
    }
    final public function getServiceMethod(): string {
        return $this->serviceMethod;
    }

    final public function setServiceFile(string $serviceName): self {
        $this->serviceFile = sprintf('./app/service/%s/%s.php', $serviceName, $serviceName);
        return $this;
    }
    final public function getServiceFile(): string {
        return $this->serviceFile;
    }

    final private function toServiceName(string $name): string {
        $name = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($name));
        return sprintf('%sService', $name);
    }
    final private function toServiceMethod(string $method): string {
        $method = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, lcfirst($method));
        return $method;
    }
}
