<?php declare(strict_types=1);
namespace Application\Database\Model\Stack;

use Application\Util\Traits\GetterTrait as Getter;

abstract class Stack implements StackInterface
{
   use Getter;

   const SELECT_LIMIT = 10,
         UPDATE_LIMIT = 1,
         DELETE_LIMIT = 1;

   protected $db;
   protected $name;
   protected $primary;

   protected $data = array();

   final public function set(string $key, $value): self
   {
      $this->data[$key] = $value;
      return $this;
   }
   final public function get(string $key)
   {
      if ($key == '*') {
         return $this->data;
      }
      if (array_key_exists($key, $this->data)) {
         return $this->data[$key];
      }
      return null;
   }

   final public function isset(string $key): bool
   {
      return array_key_exists($key, $this->data);
   }

   final public function unset(string $key)
   {
      unset($this->data[$key]);
   }
}
