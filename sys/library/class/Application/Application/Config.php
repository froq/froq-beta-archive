<?php namespace Application\Application;

final class Config
{
    private $data = [];

    final public function __construct($data) {
        if (is_string($data)) {
            $data = include($data);
        }
        if (!is_array($data)) {
            throw new \RuntimeException(
                'Config data must be array or path to array file!');
        }
        $this->setData($data);
    }

    final public function set($key, $value) {}
    final public function get($key, $valueDefault = null) {
        return dig($this->data, $key, $valueDefault);
    }

    final public static function merge(array $source, array $target) {
        foreach ($source as $key => $value) {
            if (isset($target[$key]) && is_array($value)) {
                $target[$key] = array_merge($target[$key], $value);
            } else {
                $target[$key] = $value;
            }
        }
        return $target;
    }

    final public function setData(array $data) {
        $this->data = $data;
    }
    final public function getData() {
        return $this->data;
    }
}
