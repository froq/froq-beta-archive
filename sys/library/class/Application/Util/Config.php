<?php declare(strict_types=1);
namespace Application\Util;

final class Config
   extends Collection
{
   final public function __construct($data)
   {
      if (is_string($data)) {
         $data = require($data);
      }

      if (!is_array($data)) {
         throw new \RuntimeException(
            'Config data must be array or path to array file!');
      }

      $this->setData($data);
   }

   final public function set($key, $value): self
   {
      $this->data[$key] = $value;

      return $this;
   }

   final public function get($key, $valueDefault = null)
   {
      return dig($this->data, $key, $valueDefault);
   }

   final public function setData(array $data): self
   {
      $this->data = $data;

      return $this;
   }

   final public function getData(): array
   {
      return $this->data;
   }

   final public static function merge(array $source, array $target): array
   {
      if (empty($source)) {
         return $source;
      }
      foreach ($source as $key => $value) {
         if (isset($target[$key]) && is_array($value)) {
            $target[$key] = self::merge($target[$key], $value);
         } else {
            $target[$key] = $value;
         }
      }

      return $target;
   }
}
