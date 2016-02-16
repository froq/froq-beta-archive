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

namespace Application\Database\Model\Stack;

use Application\Util\Traits\GetterTrait as Getter;

/**
 * @package    Application
 * @subpackage Application\Database\Model\Stack
 * @object     Application\Database\Model\Stack\Stack
 * @author     Kerem Güneş <k-gun@mail.com>
 */
abstract class Stack implements StackInterface
{
   /**
    * Getter.
    * @object Application\Util\Traits\GetterTrait
    */
   use Getter;

   /**
    * Limits.
    * @const int
    */
   const SELECT_LIMIT = 10,
         UPDATE_LIMIT = 1,
         DELETE_LIMIT = 1;

   /**
    * Database object.
    * @var Application\Database\Vendor\Vendor
    */
   protected $db;

   /**
    * Stack name.
    * @var string
    */
   protected $name;

   /**
    * Stack primary.
    * @var string
    */
   protected $primary;

   /**
    * Stack data.
    * @var array
    */
   protected $data = array();

   /**
    * Set a field value.
    *
    * @param string $key
    * @param mixed  $value
    */
   final public function set(string $key, $value): self
   {
      $this->data[$key] = $value;

      return $this;
   }

   /**
    * Get a field value.
    *
    * @param  string $key
    * @return mixed
    */
   final public function get(string $key)
   {
      // return all
      if ($key == '*') {
         return $this->data;
      }

      if (array_key_exists($key, $this->data)) {
         return $this->data[$key];
      }

      return null;
   }

   /**
    * Check a field.
    *
    * @param  string $key
    * @return bool
    */
   final public function isset(string $key): bool
   {
      return array_key_exists($key, $this->data);
   }

   /**
    * Unset a field value.
    *
    * @param  string $key
    * @return void
    */
   final public function unset(string $key)
   {
      unset($this->data[$key]);
   }
}
