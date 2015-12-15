<?php namespace Application\Util;

use Application\Service\Service;

final class View
{
    const PARTIAL_HEAD = 'partial/head',
          PARTIAL_FOOT = 'partial/foot';

    private $service;

    final public function __construct(Service $service) {
        $this->service = $service;
    }

    final public function display($file, array $data = null) {
        $this->includeFile($this->prepareFile($file), $data);
    }

    final public function displayHead(array $data = null) {
        // check local service file
        $file = $this->prepareFile(self::PARTIAL_HEAD, false);
        if (!is_file($file)) {
            // look up for global service file
            $file = $this->prepareFileGlobal(self::PARTIAL_HEAD);
        }
        $this->includeFile($file, $data);
    }
    final public function displayFoot(array $data = null) {
        // check local service file
        $file = $this->prepareFile(self::PARTIAL_FOOT, false);
        if (!is_file($file)) {
            // look up for global service file
            $file = $this->prepareFileGlobal(self::PARTIAL_FOOT);
        }
        $this->includeFile($file, $data);
    }

    final public function includeFile($file, array $data = null) {
        extract((array) $data);
        include($file);
    }
    final public function prepareFile($file, $fileCheck = true) {
        $file = sprintf('./app/service/%s/view/%s.php', $this->service->getName(), $file);
        if ($fileCheck && !is_file($file)) {
            throw new \RuntimeException('View file not found! file: '. $file);
        }
        return $file;
    }
    final public function prepareFileGlobal($file, $fileCheck = true) {
        $file = sprintf('./app/service/view/%s.php', $file);
        if ($fileCheck && !is_file($file)) {
            throw new \RuntimeException('View file not found! file: '. $file);
        }
        return $file;
    }
}
