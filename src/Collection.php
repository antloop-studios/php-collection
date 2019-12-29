<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */

declare(strict_types=1);

namespace Antloop\Collection;

use Countable;
use ArrayAccess;
use JsonSerializable;
use Antloop\Collection\Iterator\Iterator;

class Collection implements JsonSerializable, ArrayAccess, \Iterator, Countable
{
    public $data = [];

    public function __construct(iterable $data = [])
    {
        $this->data = $data instanceof Collection ? $data->data : $data;
    }

    /**
     * JsonSerializable
     */

    public function jsonSerialize(): string
    {
        return json_encode($this->data);
    }

    /**
     * ArrayAccess
     */

    public function offsetGet($offset)
    {
        return $this->data[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->data[] = $value;
        } else {
            $this->data[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }

    /**
     * Iterator
     */

    public function rewind()
    {
        reset($this->data);
    }

    public function current()
    {
        return current($this->data);
    }

    public function key()
    {
        return current($this->data);
    }

    public function next()
    {
        next($this->data);
    }

    public function valid()
    {
        return key($this->data) !== null && current($this->data) !== false;
    }

    /**
     * Countable
     */

    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Iterator
     */

    public function iter(): Iterator
    {
        return new Iterator($this);
    }
}
