<?php
use Application\Service\Protocol\Site as Service;

class MainService extends Service
{
    public function main(): string
    {
        return 'Hello, Froq!';
    }
}
