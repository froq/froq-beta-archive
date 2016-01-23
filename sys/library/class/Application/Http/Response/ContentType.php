<?php declare(strict_types=1);

namespace Application\Http\Response;

/**
 * @package    Application
 * @subpackage Application\Http\Response
 * @object     Application\Http\Response\ContentType
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
abstract class ContentType
{
   /**
    * Content types.
    * @const string
    */
   const NONE = 'none',
         HTML = 'text/html',
         XML  = 'application/xml',
         JSON = 'application/json';
}
