<?php namespace Application\Application;

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
        $this->data = self::merge($data, include('./sys/global/cfg.php'));
    }

    final public function set($key, $value) {}
    final public function get($key, $valueDefault = null) {
        return dig($this->data, $key, $valueDefault);
    }

    final private static function merge(array $source, array $target) {
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
