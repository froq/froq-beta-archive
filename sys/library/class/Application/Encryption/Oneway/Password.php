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

namespace Application\Encryption\Oneway;

/**
 * @package    Application
 * @subpackage Application\Encryption\Oneway
 * @object     Application\Encryption\Oneway\Password
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Password
{
   /**
    * Hashing algorithm.
    * @var int
    */
   private $algo = PASSWORD_DEFAULT;

   /**
    * Hashing options.
    * @var array
    */
   private $options = ['cost' => 10];

   /**
    * Constructor.
    *
    * @param string $data
    * @param int    $algo
    * @param array  $options
    */
   final public function __construct(string $data, int $algo = null, array $options = [])
   {
      $this->data = $data;

      if ($algo != null) {
         $this->algo = $algo;
      }

      $this->options = array_merge($this->options, $options);
   }

   /**
    * Generate a hash.
    *
    * @param  string $salt
    * @return string
    */
   final public function hash(string $salt = null): string
   {
      if ($salt != '') {
         $this->options['salt'] = $salt;
      }

      return password_hash($this->data, $this->algo, $this->options);
   }

   /**
    * Verify a hash.
    *
    * @param  string $hash
    * @return bool
    */
   final public function verify(string $hash): bool
   {
      return password_verify($this->data, $hash);
   }
}
