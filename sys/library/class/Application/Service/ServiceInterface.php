<?php namespace Application\Service;

interface ServiceInterface
{
    const DEFAULT_NAME = 'HomeService';
    const DEFAULT_METHOD_INIT = '__init__';
    const DEFAULT_METHOD_HOME = '__home__';

    public function __home__();
}
