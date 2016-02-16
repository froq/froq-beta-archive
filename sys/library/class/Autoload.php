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

/**
 * @object  Autoload
 * @author  Kerem Güneş <k-gun@mail.com>
 */
final class Autoload
{
   /**
    * Singleton stuff.
    * @var self
    */
   private static $instance;

   /**
    * Application namespace
    * @var string
    */
   private static $namespace = 'Application';

   /**
    * Forbid idle initializations.
    */
   final private function __clone() {}
   final private function __construct() {}

   /**
    * Unregister auload.
    *
    * @return void
    */
   final public function __destruct()
   {
      spl_autoload_unregister([$this, 'load']);
   }

   /**
    * Get an instance of Autoload.
    *
    * @return self
    */
   final public static function init(): self
   {
      if (self::$instance == null) {
         self::$instance = new self();
      }

      return self::$instance;
   }

   /**
    * Register SPL Autoload.
    *
    * @return bool
    */
   final public function register()
   {
      return spl_autoload_register([$this, 'load']);
   }

   /**
    * Load an object (class/trait/interface) file.
    *
    * @param  string $objectName
    * @return mixed
    * @throws \RuntimeException
    */
   final public static function load($objectName)
   {
      // Autoload::load('./Single')
      // Autoload::load('router/Router/Route')
      if (0 === strpos($objectName, './')) {
         $objectName = str_replace('.', self::$namespace, $objectName);
      }

      // internal Application object invoked
      if (0 === strpos($objectName, self::$namespace)) {
         $objectFile = self::fixSlashes(sprintf(
            '%s/%s/%s.php', __dir__,
               self::$namespace,
                  // remove Application namespace once
                  substr_replace($objectName, '', 0, strlen(self::$namespace))
         ));
      } else {
         // service files
         $objectFile = self::fixSlashes(sprintf(
            '%s/app/service/%s/%s.php', root, $objectName, $objectName
         ));
         // external object invoked with namespace
         if (!is_file($objectFile)) {
            $objectFile = self::fixSlashes(sprintf(
               '%s/app/library/class/%s/%s.php', root,
                  // here namespace a prefix as subdir
                  strtolower(substr($objectName, 0, strpos($objectName, '\\'))),
                     $objectName
            ));
            // try without namespace
            if (!is_file($objectFile)) {
               $objectFile = self::fixSlashes(sprintf(
                  '%s/app/library/class/%s/%s.php', root,
                     // here namespace a prefix as subdir
                     strtolower($objectName),
                        $objectName
               ));
            }
         }
      }

      // check file exists
      if (!is_file($objectFile)) {
         // throw regular exception
         throw new \RuntimeException("Object file not found! file: `{$objectFile}`.");
      }

      // include file
      $return = require($objectFile);

      // !!! REMOVE THESE CONTROLS AFTER BASIC DEVELOPMENT !!!
      $objectName = str_replace('/', '\\', $objectName);

      // check: interface name is same with filaname?
      if (strripos($objectName, 'interface') !== false) {
         if (!interface_exists($objectName, false)) {
            throw new \RuntimeException(
               "Interface file `{$objectFile}` has been loaded but no ".
               "interface found such as `{$objectName}`.");
         }
         return $return;
      }
      // check: trait name is same with filaname?
      if (strripos($objectName, 'trait') !== false) {
         if (!trait_exists($objectName, false)) {
            throw new \RuntimeException(
               "Trait file `{$objectFile}` has been loaded but no ".
               "trait found such as `{$objectName}`.");
         }
         return $return;
      }
      // check: class name is same with filaname?
      if (!class_exists($objectName, false)) {
         throw new \RuntimeException(
            "Class file `{$objectFile}` has been loaded but no ".
            "class found such as `{$objectName}`.");
      }

      return $return;
   }

   /**
    * Prepare file path.
    *
    * @return string
    */
   final public static function fixSlashes($path): string
   {
      return preg_replace(['~\\\\~', '~/+~'], '/', $path);
   }
}

// auto-init as a shorcut for require/include actions
return Autoload::init();
