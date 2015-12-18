<?php
namespace Application\Service\Protocol;

use Application\Service\Service;

abstract class Site extends Service
{
    abstract public function main();
}
