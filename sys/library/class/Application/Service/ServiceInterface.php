<?php namespace Application\Service;

interface ServiceInterface
{
    const DEFAULT_NAME = 'HomeService';
    const DEFAULT_METHOD_HOME = 'home';
    const DEFAULT_METHOD_INIT = 'init';

    public function home();
}
