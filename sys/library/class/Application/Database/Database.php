<?php namespace Application\Database;

use Application\Database\Vendor\Mysql,
    Application\Database\Vendor\Couch,
    Application\Database\Vendor\Mongo;

final class Database
{
    const VENDOR_MYSQL = 'mysql',
          VENDOR_COUCH = 'couch',
          VENDOR_MONGO = 'mongo';

    private static $instances = [];

    final public function __construct() {}

    final public static function init($vendor) {
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

    final public function initMysql() {
        return self::init(self::VENDOR_MYSQL);
    }
    final public function initCouch() {
        return self::init(self::VENDOR_COUCH);
    }
    final public function initMongo() {
        return self::init(self::VENDOR_MONGO);
    }
}
