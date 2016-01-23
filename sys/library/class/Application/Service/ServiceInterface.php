<?php declare(strict_types=1);
namespace Application\Service;

interface ServiceInterface
{
   const PROTOCOL_SITE       = 'site',
         PROTOCOL_REST       = 'rest';

   const SERVICE_NAME_SUFFIX = 'Service',
         SERVICE_MAIN        = 'MainService',
         SERVICE_FAIL        = 'FailService';

   const METHOD_NAME_PREFIX  = 'do',
         METHOD_INIT         = 'init',
         METHOD_MAIN         = 'main',
         METHOD_ONBEFORE     = 'onBefore',
         METHOD_ONAFTER      = 'onAfter';
}
