<?php declare(strict_types=1);
namespace Application\Database;

use Application\Database\Vendor\{Mysql, Couch, Mongo};

final class Database
{
   const VENDOR_MYSQL = 'mysql',
         VENDOR_COUCH = 'couch',
         VENDOR_MONGO = 'mongo';

   private static $instances = [];

   final public function __construct() {}

   final public static function init(string $vendor)
   {
      if (isset(self::$instances[$vendor])) {
         return self::$instances[$vendor];
      }
      $app = app();
      $db = null;
      $dbConfig = $app->config->get('db');
      switch ($vendor) {
         case self::VENDOR_MYSQL:
            if (!isset($dbConfig[self::VENDOR_MYSQL][$app->env])) {
               throw new \Exception('MySQL options not found in config!');
            }
            $db = Mysql::init($dbConfig[self::VENDOR_MYSQL][$app->env]);
            break;
         default:
            throw new \Exception('Unimplemented vendor given!');
      }

      return (self::$instances[$vendor] = $db);
   }

   final public function initMysql(): Mysql
   {
      return self::init(self::VENDOR_MYSQL);
   }
   final public function initCouch(): Couch
   {
      return self::init(self::VENDOR_COUCH);
   }
   final public function initMongo(): Mongo
   {
      return self::init(self::VENDOR_MONGO);
   }
}
