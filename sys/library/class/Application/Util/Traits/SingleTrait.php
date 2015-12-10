<?php namespace Application\Util\Traits;
/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\SingleTrait
 * @author     Kerem! <qeremy@gmail>
 *
 * Notice: Do not define `__construct` or `__clone`
 * methods as public if you wanna single user object.
 */
trait SingleTrait
{
    /**
     * Instance holder.
     * @var array
     */
    private static $__instances = [];

    /**
     * Forbid idle initializations.
     */
    private function __clone() {}
    private function __construct() {}

    /**
     * Constructor.
     *
     * @return object
     */
    final public static function init() {
        $className = get_called_class();
        if (!isset(self::$__instances[$className])) {
            // late-static-bound class name (that user subclass)
            self::$__instances[$className] = new static();
        }

        return self::$__instances[$className];
    }
}

