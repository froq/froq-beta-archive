<?php
namespace Application\Service\Protocol;

use Application\Service\Service;

abstract class Rest extends Service
{
    abstract public function get();
    abstract public function post();
    abstract public function patch();
    abstract public function delete();
}
