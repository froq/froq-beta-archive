<?php declare(strict_types=1);
namespace Application\Database\Vendor;

use Application\Util\Traits\SingleTrait;
use Oppa\Configuration;
use Oppa\Database\{Query, Factory};

final class Mysql
{
    use SingleTrait;

    private $db;

    final private function __construct(array $config)
    {
        $this->db = Factory::build(new Configuration($config));
        $this->db->connect();
    }

    final public function __call($method, array $arguments)
    {
        return call_user_func_array([$this->db, $method], $arguments);
    }
}
