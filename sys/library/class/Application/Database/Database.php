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

namespace Application\Database;

use Application\Database\Vendor\{Mysql, Couch, Mongo};

/**
 * @package    Application
 * @subpackage Application\Database
 * @object     Application\Database\Database
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Database
{
   /**
    * Vendor names.
    * @const string
    */
   const VENDOR_MYSQL = 'mysql',
         VENDOR_COUCH = 'couch',
         VENDOR_MONGO = 'mongo';

   /**
    * Database vendor instances.
    * @var array
    */
   private static $instances = [];

   // final public function __construct() {}

   /**
    * Instance creator.
    *
    * @param  string $vendor
    * @return Application\Database\Vendor\{Mysql, Couch, Mongo}
    * @throws \Exception
    */
   final public static function init(string $vendor)
   {
      if (isset(self::$instances[$vendor])) {
         return self::$instances[$vendor];
      }

      $app = app();
      $cfg = $app->config->get('db');
      if (!isset($cfg[$vendor][$app->env])) {
         throw new \Exception("`{$vendor}` options not found in config!");
      }

      switch ($vendor) {
         // only mysql for now
         case self::VENDOR_MYSQL:
            self::$instances[$vendor] = Mysql::init($cfg[$vendor][$app->env]);
            break;
         default:
            throw new \Exception('Unimplemented vendor given!');
      }

      return self::$instances[$vendor];
   }

   /**
    * Create a MySQL worker instance.
    *
    * @return Application\Database\Vendor\Mysql
    */
   final public static function initMysql(): Mysql
   {
      return self::init(self::VENDOR_MYSQL);
   }

   /**
    * Create a CouchDB worker instance.
    *
    * @return Application\Database\Vendor\Couch
    */
   final public static function initCouch(): Couch
   {
      return self::init(self::VENDOR_COUCH);
   }

   /**
    * Create a MongoDB worker instance.
    *
    * @return Application\Database\Vendor\Mongo
    */
   final public static function initMongo(): Mongo
   {
      return self::init(self::VENDOR_MONGO);
   }
}
