<?php declare(strict_types=1);
namespace Application\Database\Model\Model;

use Application\Database\Vendor\Mysql as Database;

final class Mysql extends Model
{
   final public function __construct(Database $db, $name, $primary) {
      $this->db = $db;
      $this->name = $name;
      $this->primary = $primary;
   }

   final public function find() {
      return $this->db->getConnection()->getAgent()->get(
         "SELECT * FROM `{$this->name}` WHERE `{$this->primary}` = ?", [1] /* @todo [$this->id] */);
   }

   final public function findAll() {}

   final public function save() {}

   final public function remove() {}
}
