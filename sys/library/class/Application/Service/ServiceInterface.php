<?php namespace Application\Service;

interface ServiceInterface
{
    const DEFAULT_NAME = 'HomeService';

    const METHOD_INIT   = '_init',
          METHOD_MAIN   = '_main',
          METHOD_BEFORE = '_before',
          METHOD_AFTER  = '_after';

    public function _main();
}
