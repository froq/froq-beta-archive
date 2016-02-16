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
 * @object     Application\Util\Config
 * @author     Kerem Güneş <k-gun@mail.com>
 */
final class Config
   extends Collection
{
   /**
    * Constructor.
    *
    * @param array|string $data String if addressed to a config file.
    */
   final public function __construct($data)
   {
      if (is_string($data)) {
         $data = require($data);
      }

      // every config file must return array
      if (!is_array($data)) {
         throw new \RuntimeException(
            'Config data must be array or path to array file!');
      }

      $this->setData($data);
   }

   /**
    * Setter.
    *
    * @param  string $key
    * @param  mixed  $value
    * @return self
    */
   final public function set($key, $value): self
   {
      $this->data[$key] = $value;

      return $this;
   }

   /**
    * Getter.
    *
    * @param  string $key
    * @param  mixed  $valueDefault
    * @return mixed
    */
   final public function get($key, $valueDefault = null)
   {
      return dig($this->data, $key, $valueDefault);
   }

   /**
    * Set all data.
    *
    * @param array $data
    */
   final public function setData(array $data): self
   {
      $this->data = $data;

      return $this;
   }

   /**
    * Get all data.
    *
    * @return array
    */
   final public function getData(): array
   {
      return $this->data;
   }

   /**
    * Merge.
    *
    * @param  array  $source
    * @param  array  $target
    * @return array
    */
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
