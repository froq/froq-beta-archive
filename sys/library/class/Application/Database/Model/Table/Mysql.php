<?php namespace Application\Database\Model\Table;

use Application\Database\Vendor\Mysql as Database;

final class Mysql extends Table
{
    final public function __construct(Database $db, $name, $primary) {
        $this->db = $db;
        $this->name = $name;
        $this->primary = $primary;
    }

    final public function find() {}
    final public function findAll() {}
    final public function save() {}
    final public function remove() {}
}
