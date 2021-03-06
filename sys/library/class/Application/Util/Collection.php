<?php
/**
 * Copyright (c) 2016 Kerem Güneş
 *    <k-gun@mail.com>
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

namespace Application\Util;

/**
 * @package    Application
 * @subpackage Application\Util
 * @object     Application\Util\Collection
 * @author     Kerem Güneş <k-gun@mail.com>
 */
class Collection
   implements \Countable, \IteratorAggregate, \ArrayAccess
{
   /**
    * Data stack.
    *
    * @var array
    */
   protected $data = array();

   /**
    * Constructor.
    *
    * @param array $data;
    */
   public function __construct(array $data = [])
   {
      $this->data = $data;
   }

   /**
    * Set an item.
    *
    * @param  int|string $key
    * @param  any        $value
    * @return void
    */
   public function __set($key, $value)
   {
      return $this->set($key, $value);
   }

   /**
    * Get an item.
    *
    * @param  int|string $key
    * @return any
    */
   public function __get($key)
   {
      return $this->get($key);
   }

   /**
    * Check an item.
    *
    * @param  int|string $key
    * @return bool
    */
   public function __isset($key)
   {
      return $this->offsetExists($key);
   }

   /**
    * Remove an item.
    *
    * @param  int|string $key
    * @return void
    */
   public function __unset($key)
   {
      $this->offsetUnset($key);
   }

   /**
    * Set an item.
    *
    * @param  int|string $key
    * @param  any        $value
    * @return void
    */
   public function set($key, $value)
   {
      if ($key === null) {
         $this->data[] = $value;
      } else {
         $this->data[$key] = $value;
      }
   }

   /**
    * Get an item.
    *
    * @param  int|string $key
    * @param  any        $valueDefault
    * @return any
    */
   public function get($key, $valueDefault = null)
   {
      if ($this->offsetExists($key)) {
         return $this->data[$key];
      }

      return $valueDefault;
   }

   /**
    * Set an item.
    *
    * @param  int|string $key
    * @param  any        $value
    * @return void
    */
   final public function offsetSet($key, $value)
   {
      return $this->set($key, $value);
   }

   /**
    * Get an item.
    *
    * @param  int|string $key
    * @return any
    */
   final public function offsetGet($key)
   {
      return $this->get($key);
   }

   /**
    * Remove an item.
    *
    * @param  any $key
    * @return void
    */
   final public function offsetUnset($key)
   {
      unset($this->data[$key]);
   }

   /**
    * Check an item.
    *
    * @param  int|string $key
    * @return bool
    */
   final public function offsetExists($key): bool
   {
      return array_key_exists($key, $this->data);
   }

   /**
    * Count data (from \Countable).
    *
    * @return int
    */
   final public function count(): int
   {
      return count($this->data);
   }

   /**
    * Generate iterator (from \IteratorAggregate).
    *
    * @return \ArrayIterator
    */
   final public function getIterator(): \ArrayIterator
   {
      return new \ArrayIterator($this->data);
   }

   /**
    * Get all data as array.
    *
    * @return array
    */
   public function toArray(): array
   {
      return $this->data;
   }
}
