<?php
namespace Application\Application;

final class Config
{
    private $data = [];

    final public function __construct($data) {
        if (is_string($data)) {
            $data = include($data);
        }
        if (!is_array($data)) {
            throw new \RuntimeException('Config data must be array or path to array file!');
        }

        $dataDefaultFile = './sys/global/cfg.php';
        if (!is_file($dataDefaultFile)) {
            throw new \RuntimeException('Default config file not found in /sys/global directory!');
        }
        $this->data = self::merge($data, include($dataDefaultFile));
    }

    final public function set($key, $value) {}
    final public function get($key, $valueDefault = null) {
        return dig($this->data, $key, $valueDefault);
    }

    final public static function merge(array $source, array $target) {
        foreach ($source as $key => $value) {
            if (is_array($value)) {
                $target[$key] = array_merge($target[$key], $value);
            } else {
                $target[$key] = $value;
            }
        }
        return $target;
    }
}
