<?php declare(strict_types=1);
namespace Application\Http\Request\Params;

use Application\Util\Collection;

/**
 * @package    Application
 * @subpackage Application\Http\Request\Params
 * @object     Application\Http\Request\Params\Get
 * @extends    Application\Util\Collection
 * @author     Kerem Güneş <qeremy@gmail.com>
 */
final class Get
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
         $data = $_GET;
      }
      parent::__construct($data);
   }
}
