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

namespace Application\Encryption;

/**
 * @package    Application
 * @subpackage Application\Encryption
 * @object     Application\Encryption\Salt
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Salt
{
   /**
    * Salt length.
    * @const int
    */
   const LENGTH = 128;

   /**
    * Salt type.
    * @const int
    */
   const TYPE_SELF    = 1,
         TYPE_URANDOM = 2;

   /**
    * Salt chars.
    * @var string
    */
   private static $chars = './0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

   /**
    * Generate a salt.
    *
    * @param  int $type
    * @param  int $length
    * @return string
    */
   final public static function generate(int $type = self::TYPE_URANDOM,
         int $length = self::LENGTH, bool $crop = true): string
   {
      // use urandom method (default)
      if ($type == self::TYPE_URANDOM) {
         $salt = base64_encode(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
         if ($crop) {
            $salt = substr($salt, 0, $length);
         }
      }
      // use self method
      elseif ($type == self::TYPE_SELF) {
         $salt = '';
         for ($i = 0; $i < $length; $i++) {
            $salt .= self::$chars[mt_rand(0, 63)];
         }
      }

      return $salt;
   }
}
