<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <http://qeremy.com>
 *
 * GNU General Public License v3.0
 *    <http://www.gnu.org/licenses/gpl-3.0.txt>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Application\Database\Model;

use Application\Database\Database;
use Application\Database\Vendor\Vendor;
use Application\Database\Model\Stack\Stack;
use Application\Database\Model\Stack\Agent\Mysql;
// @todo
// use Application\Database\Model\Stack\Agent\Couch;
// use Application\Database\Model\Stack\Agent\Mongo;

/**
 * @package    Application
 * @subpackage Application\Database\Model\Model
 * @object     Application\Database\Model\Model\Model
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
abstract class Model
{
   /**
    * Vendor.
    * @var string
    */
   protected $vendor;

   /**
    * [$stack description]
    * @var [type]
    */
   protected $stack, $stackName, $stackPrimary;

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

   final public function getVendor(): string
   {
      return $this->vendor;
   }
   final public function getStack(): Stack
   {
      return $this->stack;
   }
   final public function getStackDb(): Vendor
   {
      return $this->stack->db;
   }
   final public function getStackName(): string
   {
      return $this->stack->name;
   }
   final public function getStackPrimary(): string
   {
      return $this->stack->primary;
   }
}
