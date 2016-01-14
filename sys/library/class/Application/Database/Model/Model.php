<?php declare(strict_types=1);
namespace Application\Database\Model;

use Application\Database\Database;
use Application\Database\Model\Table\Mysql,
    Application\Database\Model\Table\Couch,
    Application\Database\Model\Table\Mongo;

abstract class Model
{
    protected $vendor;
    protected $table, $tableName, $tablePrimary;
    protected $data = [];

    final public function __construct()
    {
        switch ($this->vendor) {
            case Database::VENDOR_MYSQL:
                $this->table = new Mysql(Database::init(Database::VENDOR_MYSQL),
                    $this->tableName, $this->tablePrimary);
                break;
            default:
                throw new \Exception('Unimplemented vendor given!');
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

    final public function __set(string $key, $value)
    {
        $this->data[$key] = $value;
    }
    final public function __get(string $key)
    {
        if (array_key_exists($key, $this->data)) {
            return $this->data[$key];
        }
    }
    final public function __isset(string $key)
    {
        return array_key_exists($key, $this->data);
    }
    final public function __unset(string $key)
    {
        unset($this->data[$key]);
    }

    final public function setData(array $data)
    {
        $this->data = $data;
    }
    final public function getData(): array
    {
        return $this->data;
    }

    final public function getTable(): string
    {
        return $this->table;
    }
    final public function getTableName(): string
    {
        return $this->tableName;
    }
    final public function getTablePrimary(): string
    {
        return $this->tablePrimary;
    }
    final public function getVendor(): string
    {
        return $this->vendor;
    }

    final public function reset()
    {
        $this->data = [];
    }
}
