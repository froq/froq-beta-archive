<?php declare(strict_types=1);
namespace Application\Database\Vendor;

use Application\Util\Traits\SingleTrait as Single;

abstract class Vendor
{
   use Single;

   protected $db;

   final public function __call($method, array $arguments)
   {
      return call_user_func_array([$this->db, $method], $arguments);
   }
}
