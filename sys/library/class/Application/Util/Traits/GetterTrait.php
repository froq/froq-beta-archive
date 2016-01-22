<?php namespace Application\Util\Traits;
/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\GetterTrait
 * @author     Kerem Güneş <qeremy@gmail.com>
 *
 * Notice: Do not define `__get` in user objects.
 */
trait GetterTrait
{
   /**
    * Property getter.
    *
    * @param  string $name
    * @return any
    * @throws \Exception
    */
   public function __get($name)
   {
      if (!property_exists($this, $name)) {
         throw new \Exception(sprintf(
            '`%s` property does not exists on `%s` object!', $name, get_class($this)));
      }

      return $this->{$name};
   }

   /**
    * Property checker.
    *
    * @param  string $name
    * @return bool
    */
   public function __isset($name)
   {
      return property_exists($this, $name);
   }
}
