<?php namespace Application;

final class Config
{
    private $data = [];

    final public function __construct($data, $merge = false) {
        if (is_string($data)) {
            $this->data = include($data);
        }

        if (!is_array($this->data)) {
            throw new \RuntimeException('Config data must be array or path to array file!');
        }

        if ($merge) {
            $this->data = self::merge($this->data, include('./sys/global/cfg.php'));
        }
    }

    final public function set($key, $value) {}
    final public function get($key, $valueDefault = null) {
        return dig($this->data, $key, $valueDefault);
    }

    final private static function merge(array $source, array $target) {
        foreach ($source as $key => $value) {
            if (is_array($value)) {
                if (isset($target[$key])) {
                    $target[$key] = array_merge($target[$key], $value);
                } else {
                    $target[$key] = array_merge($target, $value);
                }
            } else {
                $target[$key] = $value;
            }
        }
        return $target;
    }
}
