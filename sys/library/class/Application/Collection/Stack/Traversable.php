<?php
namespace Application\Collection\Stack;

/**
 * @package    Application
 * @subpackage Application\Collection\Stack
 * @object     Application\Collection\Stack\Traversable
 * @implements \ArrayAccess
 * @author     Kerem! <qeremy@gmail>
 */
class Traversable
    implements \ArrayAccess
{
    /**
     * Data stack.
     * @var array
     */
    protected $data = [];

    /**
     * Object constructor.
     *
     * @param array $data;
     */
    public function __construct(array $data = []) {
        if (!empty($data)) {
            $this->data = $data;
        }
    }

    /**
     * Set an item.
     *
     * @param  int|string $key
     * @param  any        $value
     * @return void
     */
    public function __set($key, $value) {
        return $this->set($key, $value);
    }

    /**
     * Get an item.
     *
     * @param  int|string $key
     * @return any
     */
    public function __get($key) {
        return $this->get($key);
    }

    /**
     * Check an item.
     *
     * @param  int|string $key
     * @return bool
     */
    public function __isset($key) {
        return $this->offsetExists($key);
    }

    /**
     * Remove an item.
     *
     * @param  int|string $key
     * @return void
     */
    public function __unset($key) {
        $this->offsetUnset($key);
    }

    /**
     * Set an item.
     *
     * @param  int|string $key
     * @param  any        $value
     * @return void
     */
    public function set($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * Get an item.
     *
     * @param  int|string $key
     * @param  any        $valueDefault
     * @return any
     */
    public function get($key, $valueDefault = null) {
        if ($this->offsetExists($key)) {
            return $this->data[$key];
        }

        return $valueDefault;
    }

    /**
     * Set an item.
     *
     * @param  int|string $key
     * @param  any        $value
     * @return void
     */
    public function offsetSet($key, $value) {
        return $this->set($key, $value);
    }

    /**
     * Get an item.
     *
     * @param  int|string $key
     * @return any
     */
    public function offsetGet($key) {
        return $this->get($key);
    }

    /**
     * Remove an item.
     *
     * @param  any $key
     * @return void
     */
    public function offsetUnset($key) {
        unset($this->data[$key]);
    }

    /**
     * Check an item.
     *
     * @param  int|string $key
     * @return bool
     */
    public function offsetExists($key) {
        return array_key_exists($key, $this->data);
    }

    /**
     * Get all data as array.
     *
     * @return array
     */
    public function toArray() {
        return $this->data;
    }

    /**
     * Set all/some data from array.
     *
     * @param  bool $all
     * @return void
     */
    public function fromArray(array $data) {
        foreach ($data as $key => $value) {
            $this->data[$key] = $value;
        }
    }
}
