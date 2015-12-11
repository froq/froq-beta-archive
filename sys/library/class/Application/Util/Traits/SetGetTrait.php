<?php namespace Application\Util\Traits;
/**
 * @package    Application
 * @subpackage Application\Util\Traits
 * @object     Application\Util\Traits\SetGetTrait
 * @author     Kerem! <qeremy@gmail>
 *
 * Notice: Do not define `__set` in user objects.
 */
trait SetGetTrait
{
    /**
     * Object properties are settable?
     * @var bool
     */
    private $__settable = true;

    /**
     * Object properties are gettable?
     * @var bool
     */
    private $__gettable = true;

    /**
     * Setter method (mutator).
     *
     * @param  string $name
     * @param  any    $value
     * @return any
     * @throws \Exception
     */
    public function __set($name, $value) {
        if (!$this->__settable) {
            throw new \Exception(sprintf(
                '`%s` object is not settable (immutable)!', get_called_class()));
        }

        $this->{$name} = $value;
    }

    /**
     * Getter method.
     *
     * @param  string $name
     * @return any
     * @throws \Exception
     */
    public function __get($name) {
        // get user class
        $object = get_called_class();

        if (!$this->__gettable) {
            throw new \Exception(sprintf(
                '`%s` object is not gettable!', $object));
        }

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
        return isset($this->{$name});
    }

    /**
     * Set/get method for "settable" state.
     *
     * @param  bool $option
     * @return bool
     */
    public function settable($option = null) {
        if ($option !== null) {
            $this->__settable = $option;
        }

        return $this->__settable;
    }

    /**
     * Set/get method for "gettable" state.
     *
     * @param  bool $option
     * @return bool
     */
    public function gettable($option = null) {
        if ($option !== null) {
            $this->__gettable = $option;
        }

        return $this->__gettable;
    }
}
