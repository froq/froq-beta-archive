<?php declare(strict_types=1);
namespace Application\Database\Model\Model;

use Application\Database\Model\ModelInterface;

abstract class Model implements ModelInterface
{
   protected $db;
   protected $name;
   protected $primary;

   final public function setDb($db): self
   {
      $this->db = $db;
      return $this;
   }
   final public function getDb()
   {
      return $this->db;
   }

   final public function setName($name): self
   {
      $this->name = $name;
      return $this;
   }
   final public function getName(): string
   {
      return $this->name;
   }

   final public function setPrimary($primary): self
   {
      $this->primary = $primary;
      return $this;
   }
   final public function getPrimary(): string
   {
      return $this->primary;
   }
}
