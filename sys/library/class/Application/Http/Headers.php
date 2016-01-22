<?php declare(strict_types=1);
namespace Application\Http;

use Application\Util\Collection;

/**
 * @package    Application
 * @subpackage Application\Http
 * @object     Application\Http\Headers
 * @extends    Application\Util\Collection
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Headers
   extends Collection
{
   /**
    * Constructor.
    *
    * @param array $headers
    */
   final public function __construct(array $headers = [])
   {
      parent::__construct($headers);
   }
}
