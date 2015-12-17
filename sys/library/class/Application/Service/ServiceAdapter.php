<?php declare(strict_types=1);
namespace Application\Service;

use \Application\Application;
use \Application\Http\Response\Status;

final class ServiceAdapter
{
    private $app;
    private $service;
    private $serviceName;
    private $serviceNameDefault = Service::SERVICE_MAIN;
    private $serviceMethod;
    private $serviceMethodDefault = Service::METHOD_MAIN;
    private $serviceFile;
    private $serviceViewData = null;

    final public function __construct(Application $app) {
        $this->app = $app;

        $this->serviceName = ('/' == ($serviceName = $this->app->request->uri->segment(0, '/')))
            ? $this->serviceNameDefault : $this->toServiceName($serviceName);
        $this->serviceMethod = ('' == ($serviceMethod = $this->app->request->uri->segment(1, '')))
            ? $this->serviceMethodDefault : $this->toServiceMethod($serviceMethod);

        $this->serviceFile = sprintf('./app/service/%s/%s.php',
            $this->serviceName, $this->serviceName);

        if (!$this->isServiceExists()) {
            $this->serviceViewData['fail']['code'] = Status::NOT_FOUND;
            $this->serviceViewData['fail']['text'] = sprintf(
                'Service not found! name: %s()', $this->serviceName);
            $this->serviceName = Service::SERVICE_FAIL;
        }

        $this->service = $this->createService();
        if (!$this->isServiceMethodExists()) {
            $this->serviceViewData['fail']['code'] = Status::NOT_FOUND;
            $this->serviceViewData['fail']['text'] = sprintf(
                'Service method not found! name: %s::%s()', $this->serviceName, $this->serviceMethod);
            $this->serviceName = Service::SERVICE_FAIL;
            $this->service = $this->createService();
        }

    }

    final public function isServiceExists(): bool {
        return (is_file($this->serviceFile) && class_exists($this->serviceName));
    }

    final public function isServiceMethodExists(): bool {
        return ($this->service && method_exists($this->service, $this->serviceMethod));
    }

    final public function getService(): Service {
        return $this->service;
    }

    final public function getServiceName(): string {
        return $this->serviceName;
    }

    final public function getServiceMethod(): string {
        return $this->serviceMethod;
    }

    final public function getServiceFile(): string {
        return $this->serviceFile;
    }

    final private function createService(): Service {
        return new $this->serviceName($this->app,
            $this->serviceName, $this->serviceMethod, $this->serviceViewData);
    }

    final private function toServiceName(string $name): string {
        $name = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($name));
        return sprintf('%s%s', $name, Service::NAME_SUFFIX);
    }
    final private function toServiceMethod(string $method): string {
        $method = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($method));
        return sprintf('%s%s', Service::METHOD_PREFIX, $method);
    }
}
