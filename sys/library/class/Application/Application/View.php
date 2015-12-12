<?php namespace Application\Application;

use Application\Service\Service;

final class View
{
    private $service;

    final public function __construct(Service $service) {
        $this->service = $service;
    }

    final public function display($file, array $data = null) {
        $file = $this->prepareFile($file);

        extract((array) $data);

        return include($file);
    }

    final public function prepareFile($file) {
        $file = sprintf('./app/service/%s/view/%s.php', $this->service->getName(), $file);
        if (!is_file($file)) {
            throw new \RuntimeException('View file not found! file: '. $file);
        }
        return $file;
    }
}
