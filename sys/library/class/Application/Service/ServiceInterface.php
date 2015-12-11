<?php namespace Application\Service;

interface ServiceInterface
{
    const DEFAULT_NAME = 'HomeService';
    const DEFAULT_METHOD_INIT = '__init__';
    const DEFAULT_METHOD_HOME = '_main'; // main
    const METHOD_BEFORE = '__before__';
    const METHOD_AFTER = '__after__';

    public function _main();
}
