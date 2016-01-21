<?php declare(strict_types=1);
namespace Application\Database\Model;

use Application\Database\Database;
use Application\Database\Model\Stack\Agent\Mysql;
// @todo
// use Application\Database\Model\Stack\Agent\Couch;
// use Application\Database\Model\Stack\Agent\Mongo;

abstract class Model
{
   protected $vendor;
   protected $stack;
   protected $stackName, $stackPrimary;

   final public function __construct()
   {
      switch ($this->vendor) {
         case Database::VENDOR_MYSQL:
            $this->stack = new Mysql(
               Database::init(Database::VENDOR_MYSQL), $this->stackName, $this->stackPrimary);
            break;
         default:
            throw new \Exception('Unimplemented vendor given!');
      }

      // copy public vars as stack data
      foreach (array_diff(
            array_keys(get_object_vars($this)),
            array_keys(get_class_vars(__class__))) as $var) {
         $this->stack->set($var, $this->{$var});
         unset($this->{$var});
      }
   }

   final public function __call($method, array $arguments)
   {
      if (method_exists($this->stack, $method)) {
         return call_user_func_array([$this->stack, $method], $arguments);
      }
      throw new \BadMethodCallException("Call to undefined method `{$method}`!");
   }

   final public function __set(string $key, $value)
   {
      return $this->stack->set($key, $value);
   }

   final public function __get(string $key)
   {
      return $this->stack->get($key);
   }

   final public function __isset(string $key)
   {
      return $this->stack->isset($key);
   }

   final public function __unset(string $key)
   {
      return $this->stack->unset($key);
   }
}
