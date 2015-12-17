<?php declare(strict_types=1);
namespace Application\Service;

use \Application\Application;
use \Application\Http\Response\Status;
use \Application\Service\Service;

final class ServiceAdapter
{
    private $app;
    private $serviceName;
    private $serviceNameDefault = Service::SERVICE_MAIN;
    private $serviceMethod;
    private $serviceMethodDefault = Service::METHOD_MAIN;
    private $serviceFile;
    private $serviceViewData;

    final public function __construct(Application $app) {
        $this->app = $app;
        $serviceName = (string) $this->app->request->uri->segment(0);
        $serviceMethod = (string) $this->app->request->uri->segment(1);
        // main?
        if ($serviceName == '/') {
            $serviceName = $this->serviceNameDefault;
        }
        if ($serviceMethod == '') {
            $serviceMethod = $this->serviceMethodDefault;
        }
        $this->setServiceName($serviceName)
             ->setServiceMethod($serviceMethod)
             ->setServiceFile($serviceName);
        if (!$this->isServiceExists()) {
            $this->serviceViewData['fail']['code'] = Status::NOT_FOUND;
            $this->serviceViewData['fail']['text'] = sprintf('Service not found! name: %s', $serviceName);
            $this->setServiceName(Service::SERVICE_FAIL);
        }
    }

    final public function createService(): Service {
        return (new $this->serviceName($this->serviceName))
            ->setApp($this->app)
            ->setMethod($this->serviceMethod)
            ->setViewData($this->serviceViewData);
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
        }, ucfirst($method));
        return $method;
    }
}
