<?php namespace Application\Util\Traits;
/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\GetterTrait
 * @author     Kerem! <qeremy@gmail>
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
    public function __get($name) {
        // get user class
        $object = get_called_class();

        if (!property_exists($object, $name)) {
            throw new \Exception(sprintf(
                '`%s` property does not exists on `%s` object!', $name, $object));
        }

        return $this->{$name};
    }

    /**
     * Property checker.
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name) {
        return property_exists($this, $name);
    }
}
