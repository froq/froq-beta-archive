<?php namespace Application\Service;

interface ServiceInterface
{
    const DEFAULT_SERVICE = 'HomeService';

    const METHOD_INIT     = '_init',
          METHOD_MAIN     = '_main',
          METHOD_ONBEFORE = '_onbefore',
          METHOD_ONAFTER  = '_onafter';

    public function _main();
}
