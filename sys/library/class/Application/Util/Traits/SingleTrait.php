<?php namespace Application\Util\Traits;
/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\SingleTrait
 * @author     Kerem Güneş <qeremy@gmail.com>
 *
 * Notice: Do not define `__construct` or `__clone`
 * methods as public if you want a single user object.
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
    * @param  array $args
    * @return object
    */
   final public static function init(array ...$args)
   {
      $className = get_called_class();
      if (!isset(self::$__instances[$className])) {
         // init without constructor
         $classObject = (new \ReflectionClass($className))
            ->newInstanceWithoutConstructor();

         // call constructor
         call_user_func_array([$classObject, '__construct'], $args);

         self::$__instances[$className] = $classObject;
      }

      return self::$__instances[$className];
   }
}

