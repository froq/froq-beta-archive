<?php declare(strict_types=1);
namespace Application\Database\Model\Stack\Agent;

use Application\Database\Vendor\Vendor as Database;
use Application\Database\Model\Stack\{Stack, StackInterface};

final class Mysql extends Stack
{
   final public function __construct(Database $db, $name, $primary) {
      $this->db = $db;
      $this->name = trim($name);
      $this->primary = trim($primary);
   }

   final public function find() {
      $primaryName = $this->primary;
      $primaryValue = $this->data[$primaryName];

      if ($primaryValue === null) {
         return null;
      }

      return $this->db->getConnection()->getAgent()->get(
         "SELECT * FROM `{$this->name}` WHERE `{$primaryName}` = ?", [$primaryValue]);
   }

   final public function findAll(string $where = null, array $params = null, $limit = null, int $order = -1) {
      $agent = $this->db->getConnection()->getAgent();

      if (empty($where)) {
         $query = "SELECT * FROM `{$this->name}` ";
      } else {
         $query = "SELECT * FROM `{$this->name}` WHERE ({$where}) ";
      }

      if ($order == -1) {
         $query .= "ORDER BY `{$this->primary}` DESC ";
      } else {
         $query .= "ORDER BY `{$this->primary}` ASC ";
      }

      $query .= $agent->limit($limit ?: self::SELECT_LIMIT);

      return $agent->getAll($query, $params);
   }

   final public function save() {}

   final public function remove() {}
}
