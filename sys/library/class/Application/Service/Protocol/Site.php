<?php
namespace Application\Service\Protocol;

use Application\Service\{Service, ServiceInterface};

abstract class Site extends Service
{
    protected $protocol = ServiceInterface::PROTOCOL_SITE;

    abstract public function main();
}
