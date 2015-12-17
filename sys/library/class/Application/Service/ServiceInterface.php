<?php namespace Application\Service;

interface ServiceInterface
{
    const SERVICE_MAIN    = '__MainService',
          SERVICE_FAIL    = '__FailService';

    const METHOD_PREFIX   = 'do',
          METHOD_INIT     = 'init',
          METHOD_MAIN     = 'main',
          METHOD_ONBEFORE = 'onbefore',
          METHOD_ONAFTER  = 'onafter';

    public function main();
}
