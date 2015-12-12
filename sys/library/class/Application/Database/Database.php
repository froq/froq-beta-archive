<?php namespace Application\Database;

use Application\Application;
use Application\Exception;
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

    final public function model($name) {
        return new $name();
    }

    final public static function init($vendor) {
        if (isset(self::$instances[$vendor])) {
            return self::$instances[$vendor];
        }
        $app = app();
        $db = null;
        $dbConfig = $app->config->get('db');
        switch ($vendor) {
            case self::VENDOR_MYSQL:
                if (!isset($dbConfig[self::VENDOR_MYSQL])) {
                    throw new Exception('MySQL options not found in config!');
                }
                $db = new Mysql($dbConfig);
                break;
            default:
                throw new Exception('Unimplemented vendor given!');
        }

        return (self::$instances[$vendor] = $db);
    }
}
