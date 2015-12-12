<?php namespace Application\Database\Model;

use Application\Application;
use Application\Application\Exception;
use Application\Database\Model\Table\Mysql,
    Application\Database\Model\Table\Couch,
    Application\Database\Model\Table\Mongo;
use Application\Database\Database;

abstract class Model
{
    protected $vendor;
    protected $table, $tableName, $tablePrimary;
    protected $data = [];

    final public function __construct() {
        switch ($this->vendor) {
            case Database::VENDOR_MYSQL:
                $this->table = new Mysql(Database::init(Database::VENDOR_MYSQL),
                    $this->tableName, $this->tablePrimary);
                break;
            default:
                throw new Exception('Unimplemented vendor given!');
        }
        // copy public vars as data
        $vars = array_diff(
            array_keys(get_object_vars($this)),
            array_keys(get_class_vars(__class__))
        );
        foreach ($vars as $var) {
            $this->data[$var] = $this->{$var};
            unset($this->{$var});
        }
    }

    final public function __set($key, $value) {
        $this->data[$key] = $value;
    }
    final public function __get($key) {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
    }
    final public function __isset($key) {
        return array_key_exists($key, $this->data);
    }
    final public function __unset($key) {
        unset($this->data[$key]);
    }

    final public function setData(array $data) {
        $this->data = $data;
    }
    final public function getData() {
        return $this->data;
    }

    final public function getTable() {
        return $this->table;
    }
    final public function getTableName() {
        return $this->tableName;
    }
    final public function getTablePrimary() {
        return $this->tablePrimary;
    }
    final public function getVendor() {
        return $this->vendor;
    }

    final public function reset() {
        $this->data = [];
    }
}
