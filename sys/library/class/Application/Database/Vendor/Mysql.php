<?php declare(strict_types=1);
namespace Application\Database\Vendor;

use Oppa\Configuration;
use Oppa\Database\{Factory, Query};

final class Mysql extends Vendor
{
   final private function __construct(array $config)
   {
      $this->db = Factory::build(new Configuration($config));
      $this->db->connect();
   }
}
