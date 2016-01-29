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

namespace Application\Util\Traits;

/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\SingleTrait
 * @author     Kerem Güneş <qeremy@gmail.com>
 *
 * Notice: Do not define `__construct` or `__clone`
 * methods as public if you want a single user object.
 */
trait SingleTrait
{
   /**
    * Instance holder.
    * @var array
    */
   private static $__instances = [];

   /**
    * Forbid idle initializations.
    */
   private function __clone() {}
   private function __construct() {}

   /**
    * Constructor.
    *
    * @param  array $args
    * @return object
    */
   final public static function init(array ...$args)
   {
      $className = get_called_class();
      if (!isset(self::$__instances[$className])) {
         // init without constructor
         $classObject = (new \ReflectionClass($className))
            ->newInstanceWithoutConstructor();

         // call constructor with initial args
         call_user_func_array([$classObject, '__construct'], $args);

         self::$__instances[$className] = $classObject;
      }

      return self::$__instances[$className];
   }
}

