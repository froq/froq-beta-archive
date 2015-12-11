<?php namespace Application\Service;

interface ServiceInterface
{
    const DEFAULT_NAME = 'HomeService';
    const METHOD_INIT = '_init';
    const METHOD_HOME = '_main'; // main
    const METHOD_BEFORE = '_before';
    const METHOD_AFTER = '_after';

    public function _main();
}
