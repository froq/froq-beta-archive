<?php declare(strict_types=1);
namespace Application\Http;

use Application\Util\Collection;

/**
 * @package    Application
 * @subpackage Application\Http\Request\Params
 * @object     Application\Http\Request\Params\Cookie
 * @extends    Application\Util\Collection
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Cookie
   extends Collection
{
   /**
    * Constructor.
    *
    * @param array $data
    */
   final public function __construct(array $data = [])
   {
      if (empty($data)) {
         $data = $_COOKIE;
      }
      parent::__construct($data);
   }
}
