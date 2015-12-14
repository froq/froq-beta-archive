<?php declare(strict_types=1);

namespace Application\Util;

/**
 * @package    Application
 * @subpackage Application\Util
 * @object     Application\Util\Collection
 * @implements \Countable, \IteratorAggregate, \ArrayAccess
 * @author     Kerem! <qeremy@gmail>
 */
class Collection
    implements \Countable, \IteratorAggregate, \ArrayAccess
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
        $this->data = $data;
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
     * Count data (from \Countable).
     *
     * @return int
     */
    final public function count(): int {
        return count($this->data);
    }

    /**
     * Generate iterator (from \IteratorAggregate).
     *
     * @return \ArrayIterator
     */
    final public function getIterator(): \ArrayAccess {
        return new \ArrayIterator($this->data);
    }

    /**
     * Get all data as array.
     *
     * @return array
     */
    public function toArray(): array {
        return $this->data;
    }

    /**
     * Set data from an array.
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
