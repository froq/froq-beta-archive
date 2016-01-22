<?php declare(strict_types=1);
namespace Application\Cache;

abstract class Cache
{
   abstract public function set($key, $value);
   abstract public function get($key);
   abstract public function delete($key);
}
