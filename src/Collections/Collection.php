<?php

namespace ArgentCrusade\Selectel\CloudStorage\Collections;

use Iterator;
use Countable;
use ArrayAccess;
use JsonSerializable;
use ArgentCrusade\Selectel\CloudStorage\Contracts\Collections\CollectionContract;

class Collection implements CollectionContract, ArrayAccess, Countable, Iterator, JsonSerializable
{
    /**
     * Collection items.
     *
     * @var array
     */
    protected $items = [];

    /**
     * Iterator position.
     *
     * @var int
     */
    protected $position = 0;

    /**
     * Collection keys.
     *
     * @var array
     */
    protected $keys = [];

    /**
     * @param array $items = []
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->position = 0;
        $this->keys = array_keys($items);
    }

    /**
     * Determines if given key exists in current collection.
     *
     * @param mixed $key
     *
     * @return bool
     */
    public function has($key)
    {
        return isset($this->items[$key]);
    }

    /**
     * Retrieves item by given key from current collection.
     *
     * @param mixed $key
     *
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->has($key) ? $this->items[$key] : null;
    }

    /**
     * Collection size.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Determines if given offset exists in current collection.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $this->has($offset);
    }

    /**
     * Puts value to given offset or appends it to current collection.
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }

        $this->keys = array_keys($this->items);
    }

    /**
     * Retrieves given offset from current collection.
     * Returns NULL if no value found.
     *
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset): mixed
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * Drops given offset from current collection.
     *
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);

        $this->keys = array_keys($this->items);
    }

    /**
     * Rewinds iterator back to first position.
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Current iterator item.
     *
     * @return mixed|null
     */
    public function current(): mixed
    {
        $currentKey = $this->keys[$this->position];

        return isset($this->items[$currentKey]) ? $this->items[$currentKey] : null;
    }

    /**
     * Current iterator position.
     *
     * @return mixed
     */
    public function key(): mixed
    {
        return $this->keys[$this->position];
    }

    /**
     * Increments iterator position.
     */
    public function next(): void
    {
        $this->position++;
    }

    /**
     * Determines if there is some value at current iterator position.
     *
     * @return bool
     */
    public function valid(): bool
    {
        if (!isset($this->keys[$this->position])) {
            return false;
        }

        $currentKey = $this->keys[$this->position];

        return isset($this->items[$currentKey]);
    }

    /**
     * JSON representation of collection.
     *
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return $this->items;
    }
}
