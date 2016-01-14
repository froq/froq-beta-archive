<?php declare(strict_types=1);
namespace Application\Service;

use \Application\Application;
use \Application\Http\Response\Status;

final class ServiceAdapter
{
    private $app;
    private $service;
    private $serviceName;
    private $serviceNameDefault = ServiceInterface::SERVICE_MAIN;
    private $serviceMethod;
    private $serviceMethodDefault = ServiceInterface::METHOD_MAIN;
    private $serviceFile;
    private $serviceViewData = null;

    final public function __construct(Application $app)
    {
        $this->app = $app;

        $this->serviceName = ('/' == ($serviceName = $this->app->request->uri->segment(0, '/')))
            ? $this->serviceNameDefault : $this->toServiceName($serviceName);
        $this->serviceMethod = ('' == ($serviceMethod = $this->app->request->uri->segment(1, '')))
            ? $this->serviceMethodDefault : $this->toServiceMethod($serviceMethod);

        $this->serviceFile = $this->toServiceFile($this->serviceName);

        if (!$this->isServiceExists()) {
            $this->serviceViewData['fail']['code'] = Status::NOT_FOUND;
            $this->serviceViewData['fail']['text'] = sprintf(
                'Service not found! [%s]', $this->serviceName);
            $this->serviceName = ServiceInterface::SERVICE_FAIL;
            $this->serviceFile = $this->toServiceFile($this->serviceName);
        }

        $this->service = $this->createService();

        if (!$this->isServiceMethodExists()) {
            $this->serviceViewData['fail']['code'] = Status::NOT_FOUND;
            $this->serviceViewData['fail']['text'] = sprintf(
                'Service method not found! [%s::%s()]', $this->serviceName, $this->serviceMethod);
            $this->serviceName = ServiceInterface::SERVICE_FAIL;
            $this->serviceFile = $this->toServiceFile($this->serviceName);

            $this->service = $this->createService();
        }

    }

    final public function isServiceExists(): bool
    {
        return (is_file($this->serviceFile) && class_exists($this->serviceName));
    }

    final public function isServiceMethodExists(): bool
    {
        return ($this->service && method_exists($this->service, $this->serviceMethod));
    }

    final public function getService(): ServiceInterface
    {
        return $this->service;
    }

    final public function getServiceName(): string
    {
        return $this->serviceName;
    }

    final public function getServiceMethod(): string
    {
        return $this->serviceMethod;
    }

    final public function getServiceFile(): string
    {
        return $this->serviceFile;
    }

    final private function createService(): ServiceInterface
    {
        return new $this->serviceName(
            $this->app,
            $this->serviceName,
            $this->serviceMethod,
            $this->serviceViewData
        );
    }

    final private function toServiceName(string $name): string
    {
        $name = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($name));
        return sprintf('%s%s', $name, ServiceInterface::SERVICE_NAME_SUFFIX);
    }
    final private function toServiceMethod(string $method): string
    {
        $method = preg_replace_callback('~-([a-z])~i', function($match) {
            return ucfirst($match[1]);
        }, ucfirst($method));
        return sprintf('%s%s', ServiceInterface::METHOD_NAME_PREFIX, $method);
    }
    final private function toServiceFile(string $serviceName): string
    {
        $serviceFile = sprintf('./app/service/%s/%s.php', $serviceName, $serviceName);
        if (!is_file($serviceFile) &&
           ($serviceName == ServiceInterface::SERVICE_MAIN || $serviceName == ServiceInterface::SERVICE_FAIL)) {
            $serviceFile = sprintf('./app/service/default/%s/%s.php', $serviceName, $serviceName);
            // no need to autoload
            require_once($serviceFile);
        }
        return $serviceFile;
    }
}
