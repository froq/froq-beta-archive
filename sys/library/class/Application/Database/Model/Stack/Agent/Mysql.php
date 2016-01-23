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

namespace Application\Database\Model\Stack\Agent;

use Application\Database\Vendor\Vendor as Database;
use Application\Database\Model\Stack\{Stack, StackInterface};

/**
 * @package    Application
 * @subpackage Application\Database\Model\Stack\Agent
 * @object     Application\Database\Model\Stack\Agent\Mysql
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Mysql extends Stack
{
   /**
    * Constructor.
    *
    * @param Application\Database\Vendor\Vendor $db
    * @param string                             $name
    * @param string                             $primary
    */
   final public function __construct(Database $db, $name, $primary)
   {
      $this->db = $db;
      $this->name = trim($name);
      $this->primary = trim($primary);
   }

   /**
    * Find an object.
    *
    * @param  string $primaryValue
    * @return mixed
    */
   public function find($primaryValue = null)
   {
      if ($primaryValue === null) {
         $primaryValue = dig($this->data, $this->primary);
      }

      if ($primaryValue === null) {
         return null;
      }

      try {
         return $this->db->getConnection()->getAgent()->get(
            "SELECT * FROM `{$this->name}` WHERE `{$this->primary}` = ?", [$primaryValue]);
      } catch (\Exception $e) { return null; }
   }

   /**
    * Find all object.
    *
    * @param  string    $where
    * @param  array     $params
    * @param  int|array $limit
    * @param  int       $order
    * @return mixed
    */
   public function findAll(string $where = null, array $params = null, $limit = null,
      int $order = -1)
   {
      try {
         $agent = $this->db->getConnection()->getAgent();

         $query = empty($where)
            ? sprintf('SELECT * FROM `%s`', $this->name)
            : sprintf('SELECT * FROM `%s` WHERE (%s)', $this->name, $where);

         $query = (($order == -1)
            ? sprintf('%s ORDER BY `%s` DESC ', $query, $this->primary)
            : sprintf('%s ORDER BY `%s` ASC ', $query, $this->primary)
         ) . $agent->limit($limit ?: self::SELECT_LIMIT);

         return $agent->getAll($query, $params);
      } catch (\Exception $e) { return null; }
   }

   /**
    * Save an object.
    *
    * @return int|null
    */
   public function save()
   {
      try {
         $agent = $this->db->getConnection()->getAgent();

         // insert
         if (!isset($this->data[$this->primary])) {
            return $agent->insert($this->name, $this->data);
         }

         // update
         return $agent->update($this->name, $this->data,
            "`{$this->primary}` = ?", [$this->data[$this->primary]]);
      } catch (\Exception $e) { return null; }
   }

   /**
    * Remove an object.
    *
    * @return int|null
    */
   public function remove()
   {
      try {
         $agent = $this->db->getConnection()->getAgent();

         // check
         if (!isset($this->data[$this->primary])) {
            return null;
         }

         return $agent->delete($this->name,
            "`{$this->primary}` = ?", [$this->data[$this->primary]]);
      } catch (\Exception $e) { return null; }
   }
}
