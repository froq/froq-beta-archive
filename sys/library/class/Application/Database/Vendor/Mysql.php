<?php namespace Application\Database\Vendor;

use Application\Util\Traits\SingleTrait;
use Oppa\Configuration,
    Oppa\Database\Query,
    Oppa\Database\Factory;

final class Mysql
{
    use SingleTrait;

    private $db;

    final private function __construct(array $config)
    {
        $this->db = Factory::build(new Configuration($config));
        $this->db->connect();
    }
}
