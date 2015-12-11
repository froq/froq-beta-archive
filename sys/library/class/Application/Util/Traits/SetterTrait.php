<?php namespace Application\Util\Traits;
/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\SetterTrait
 * @author     Kerem! <qeremy@gmail>
 *
 * Notice: Do not define `__set` in user objects.
 */
trait SetterTrait
{
    /**
     * Property setter (mutator).
     *
     * @param  string $name
     * @param  any    $value
     * @return any
     * @throws \Exception
     */
    public function __set($name, $value) {
        if (!property_exists($this, $name)) {
            throw new \Exception(sprintf(
                '`%s` property does not exists on `%s` object!', $name, get_class($this)));
        }

        $this->{$name} = $value;
    }
}
