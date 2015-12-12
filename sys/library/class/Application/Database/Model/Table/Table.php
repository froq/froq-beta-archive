<?php namespace Application\Database\Model\Table;

abstract class Table
{
    protected $db;
    protected $name;
    protected $primary;

    final public function setDb($db) {
        $this->db = $db;
        return $this;
    }
    final public function getDb() {
        return $this->db;
    }

    final public function setName($name) {
        $this->name = $name;
        return $this;
    }
    final public function getName() {
        return $this->name;
    }

    final public function setPrimary($primary) {
        $this->primary = $primary;
        return $this;
    }
    final public function getPrimary() {
        return $this->primary;
    }

    abstract public function find();
    abstract public function findAll();
    abstract public function save();
    abstract public function remove();
}
