<?php declare(strict_types=1);
namespace Application\Http;

use Application\Util\Collection;

/**
 * @package    Application
 * @subpackage Application\Http
 * @object     Application\Http\Cookies
 * @extends    Application\Util\Collection
 * @author     Kerem! <qeremy@gmail>
 */
final class Cookies
    extends Collection
{
    /**
     * Object constructor.
     *
     * @param array $cookies
     */
    final public function __construct(array $cookies = []) {
        parent::__construct($cookies);
    }
}
