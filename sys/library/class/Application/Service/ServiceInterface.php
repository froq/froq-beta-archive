<?php namespace Application\Service;

interface ServiceInterface
{
    const DEFAULT_SERVICE = 'HomeService';

    const METHOD_PREFIX   = 'do',
          METHOD_INIT     = 'init',
          METHOD_MAIN     = 'main',
          METHOD_ONBEFORE = 'onbefore',
          METHOD_ONAFTER  = 'onafter';

    public function main();
}
