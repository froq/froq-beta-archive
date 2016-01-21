<?php declare(strict_types=1);
namespace Application\Database\Vendor;

use Oppa\Configuration;
use Oppa\Database\{Query, Factory};
use Application\Util\Traits\SingleTrait as Single;

final class Mysql
{
    use Single;

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
