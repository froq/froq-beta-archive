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
 * @object     Application\Encryption\Oneway\Crypt
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Crypt
{
   /**
    * Default salt lentgh.
    * @const int
    */
   const SALT_LENGTH = 64;

   /**
    * Crypt input.
    * @var string
    */
   private $input;

   /**
    * Crypt salt format.
    * @var string
    */
   private $format = '$2y$10$%s$'; // blowfish

   /**
    * Crypt salt chars.
    * @var string
    */
   private $saltChars = './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

   /**
    * Constructor.
    *
    * @param string $input
    */
   final public function __construct(string $input)
   {
      $this->input = $input;
   }

   /**
    * Generate a salt.
    *
    * @param  int $length
    * @return string
    */
   final public function generateSalt(int $length = self::SALT_LENGTH): string
   {
      return substr(str_shuffle($this->saltChars), 0, $length);
   }

   /**
    * Generate a hash.
    *
    * @param  string $salt
    * @return string
    */
   final public function hash(string $salt = null): string
   {
      if ($salt == '') {
         $salt = $this->generateSalt();
      }

      return crypt($this->input, sprintf($this->format, $salt));
   }

   /**
    * Verify a hash.
    *
    * @param  string $hash
    * @return bool
    */
   final public function verify(string $hash): bool
   {
      return $hash == crypt($this->input, $hash);
   }
}
