<?php declare(strict_types=1);
namespace Application\Database\Model;

use Application\Database\Database;
use Application\Database\Model\Model\Mysql;
// @todo
// use Application\Database\Model\Model\Couch;
// use Application\Database\Model\Model\Mongo;

abstract class Model
{
   protected $vendor;
   protected $model, $modelName, $modelPrimary;
   protected $data = array();

   final public function __construct()
   {
      switch ($this->vendor) {
         case Database::VENDOR_MYSQL:
            $this->model = new Mysql(Database::init(Database::VENDOR_MYSQL),
            $this->modelName, $this->modelPrimary);
            break;
         default:
            throw new \Exception('Unimplemented vendor given!');
      }
      // copy public vars as data
      $vars = array_diff(
         array_keys(get_object_vars($this)),
         array_keys(get_class_vars(__class__))
      );
      foreach ($vars as $var) {
         $this->data[$var] = $this->{$var};
         unset($this->{$var});
      }
   }

   final public function __call($method, array $arguments)
   {
      if (method_exists($this->model, $method)) {
         return call_user_func_array([$this->model, $method], $arguments);
      }
      throw new \BadMethodCallException("Call to undefined method `{$method}`!");
   }

   final public function __set(string $key, $value)
   {
      $this->data[$key] = $value;
   }
   final public function __get(string $key)
   {
      if (array_key_exists($key, $this->data)) {
         return $this->data[$key];
      }
   }
   final public function __isset(string $key)
   {
      return array_key_exists($key, $this->data);
   }
   final public function __unset(string $key)
   {
      unset($this->data[$key]);
   }

   final public function setData(array $data)
   {
      $this->data = $data;
   }
   final public function getData(): array
   {
      return $this->data;
   }

   final public function getVendor(): string
   {
      return $this->vendor;
   }

   final public function getModel()
   {
      return $this->model;
   }
   final public function getModelName(): string
   {
      return $this->modelName;
   }
   final public function getModelPrimary(): string
   {
      return $this->modelPrimary;
   }

   final public function reset()
   {
      $this->data = array();
   }
}
