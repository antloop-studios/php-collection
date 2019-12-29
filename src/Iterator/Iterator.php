<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

use ArrayAccess;
use ArrayIterator;
use Countable;
use Antloop\Collection\Collection;
use Exception;

class Iterator implements \Iterator, ArrayAccess, Countable
{
    /**
     * @var Collection|Iterator
     */
    protected $data = [];

    public function __construct(iterable $data)
    {
        if (is_array($data)) {
            $this->data = new Collection($data);
        } else {
            $this->data = clone $data;
        }
    }

    public function __clone()
    {
        $this->data = clone $this->data;
    }

    /**
     * ArrayAccess
     */

    public function offsetGet($offset)
    {
        return isset($this->data[$offset]) ? $this->data[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        throw new Exception('Iterators are immutable');
    }

    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset)
    {
        throw new Exception('Iterators are immutable');
    }

    /**
     * Iterator
     */

    public function rewind()
    {
        // iterators shouldn't rewind
    }

    public function current()
    {
        return $this->data->current();
    }

    public function key()
    {
        return $this->data->key();
    }

    public function next()
    {
        $this->data->next();
    }

    public function valid()
    {
        return $this->data->valid();
    }

    /**
     * Iterator types
     */

    public function stepBy(int $n): StepBy
    {
        return new StepBy($this, $n);
    }

    public function chain(Iterator $other): Chain
    {
        return new Chain($this, $other);
    }

    public function cycle(): Cycle
    {
        return new Cycle($this);
    }

    public function zip(Iterator $other): Zip
    {
        return new Zip($this, $other);
    }

    public function map(callable $fn): Map
    {
        return new Map($this, $fn);
    }

    public function filter(callable $fn): Filter
    {
        return new Filter($this, $fn);
    }

    public function filterMap(callable $fn): FilterMap
    {
        return new FilterMap($this, $fn);
    }

    public function flatten(): Flatten
    {
        return new Flatten($this);
    }

    public function fuse(): Fuse
    {
        return new Fuse($this);
    }

    public function flatMap(callable $fn): FlatMap
    {
        return new FlatMap($this, $fn);
    }

    public function enumerate(): Enumerate
    {
        return new Enumerate($this);
    }

    public function peekable(): Peekable
    {
        return new Peekable($this);
    }

    public function skipWhile($predicate): SkipWhile
    {
        return new SkipWhile($this, $predicate);
    }

    public function takeWhile($predicate): TakeWhile
    {
        return new TakeWhile($this, $predicate);
    }

    public function skip(int $count): Skip
    {
        return new Skip($this, $count);
    }

    public function take(int $count): Take
    {
        return new Take($this, $count);
    }

    public function scan(int $seed, callable $fn): Scan
    {
        return new Scan($this, $seed, $fn);
    }

    public function inspect(callable $fn): Inspect
    {
        return new Inspect($this, $fn);
    }

    /**
     * Consumers
     */

    public function count(): int
    {
        $count = 0;

        while ($this->valid()) {
            $this->next();
            $count++;
        }

        return $count;
    }

    public function last()
    {
        $e = null;

        while ($this->valid()) {
            $e = $this->current();
            $this->next();
        }

        return $e;
    }

    public function nth(int $n)
    {
        for (; $n >= 0; $n--) {
            $e = $this->current();

            if ($e === null) {
                return $e;
            }

            $this->next();
        }

        return $e;
    }

    public function collect($collectionType = null)
    {
        $e = [];
        $isString = true;

        foreach ($this as $v) {
            $e[] = $v;

            if ($isString && !is_string($v)) {
                $isString = false;
            }
        }

        if ($collectionType) {
            return new $collectionType($e);
        }

        return $isString ? implode("", $e) : $e;
    }

    public function forEach(callable $fn)
    {
        foreach ($this as $k => $v) {
            $fn($k, $v);
        }
    }

    /**
     * @param mixed $acc
     * @param callable($acc, $elem):$next $fn
     * @return mixed|null
     */
    public function tryFold($acc, callable $fn)
    {
        foreach ($this as $k => $v) {
            $acc = $fn($acc, $k, $v);

            if ($acc === null) {
                break;
            }
        }

        return $acc;
    }

    /**
     * @param mixed $acc
     * @param callable($acc, $elem):$next $fn
     * @return mixed
     */
    public function fold($acc, callable $fn)
    {
        foreach ($this as $k => $v) {
            $acc = $fn($acc, $k, $v);
        }

        return $acc;
    }

    public function tryForEach(callable $fn)
    {
        foreach ($this as $k => $v) {
            $state = $fn($k, $v);

            if ($state === null) {
                break;
            }
        }
    }

    public function all(callable $predicate)
    {
        foreach ($this as $k => $v) {
            if (!$predicate($k, $v)) {
                return false;
            }
        }

        return true;
    }

    public function any(callable $predicate)
    {
        foreach ($this as $k => $v) {
            if ($predicate($k, $v)) {
                return true;
            }
        }

        return false;
    }

    public function find(callable $predicate)
    {
        foreach ($this as $k => $v) {
            if ($predicate($k, $v)) {
                return $v;
            }
        }
    }

    public function findMap(callable $fn)
    {
        while ($this->valid() && $fn($this->key(), $this->current()) === null) {
            $this->next();
        }

        return $fn($this->key(), $this->current());
    }

    public function position(callable $predicate)
    {
        $i = 0;
        foreach ($this as $k => $v) {
            if ($predicate($k, $v)) {
                return $i;
            }
            $i++;
        }
    }

    public function max()
    {
        return max($this->collect());
    }

    public function min()
    {
        return min($this->collect());
    }

    public function maxByKey(callable $fn)
    {
        if (!$this->valid()) {
            return null;
        }

        $result = [];

        foreach ($this as $k => $v) {
            $result[] = $fn($k, $v);
        }

        return max($result);
    }

    public function minByKey(callable $fn)
    {
        if (!$this->valid()) {
            return null;
        }

        $result = [];

        foreach ($this as $k => $v) {
            $result[] = $fn($k, $v);
        }

        return min($result);
    }

    public function maxBy(callable $cmp)
    {
        if (!$this->valid()) {
            return null;
        }

        $result = $this->collect();
        usort($result, $cmp);

        return end($result);
    }

    public function minBy(callable $cmp)
    {
        if (!$this->valid()) {
            return null;
        }

        $result = $this->collect();
        usort($result, $cmp);

        return $result[0];
    }

    public function unzip()
    {
        $result = [[], []];

        foreach ($this as $e) {
            if (!is_array($e) || count($e) !== 2) {
                throw new Exception('element is not a pair: ' . print_r($e, true));
            }

            $result[0][] = $e[0];
            $result[1][] = $e[1];
        }

        return $result;
    }

    public function cloned()
    {
        $result = [];

        foreach ($this as $e) {
            $result[] = clone $e;
        }

        return $result;
    }


    public function sum()
    {
        return array_sum($this->collect());
    }

    public function product()
    {
        return array_product($this->collect());
    }

    public function cmp(Iterator $other): int
    {
        while (true) {
            if (!$this->valid()) {
                return -$other->valid();
            }

            if (!$other->valid()) {
                return 1;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $a <=> $b;

            if ($result !== 0) {
                return $result;
            }

            $this->next();
            $other->next();
        }
    }

    public function cmpBy(Iterator $other, callable $cmp): int
    {
        while (true) {
            if (!$this->valid()) {
                return -$other->valid();
            }

            if (!$other->valid()) {
                return 1;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $cmp($a, $b);

            if ($result !== 0) {
                return $result;
            }

            $this->next();
            $other->next();
        }
    }

    public function eq(Iterator $other): bool
    {
        while (true) {
            if (!$this->valid()) {
                return !$other->valid();
            }

            if (!$other->valid()) {
                return false;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $a === $b;

            if (!$result) {
                return false;
            }

            $this->next();
            $other->next();
        }
    }

    public function eqBy(Iterator $other, callable $eq): bool
    {
        while (true) {
            if (!$this->valid()) {
                return !$other->valid();
            }

            if (!$other->valid()) {
                return false;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $eq($a, $b);

            if (!$result) {
                return false;
            }

            $this->next();
            $other->next();
        }
    }

    public function ne(Iterator $other): bool
    {
        while (true) {
            if (!$this->valid()) {
                return !$other->valid();
            }

            if (!$other->valid()) {
                return false;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $a !== $b;

            if (!$result) {
                return false;
            }

            $this->next();
            $other->next();
        }
    }

    public function lt(Iterator $other): bool
    {
        while (true) {
            if (!$this->valid()) {
                return !$other->valid();
            }

            if (!$other->valid()) {
                return false;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $a < $b;

            if (!$result) {
                return false;
            }

            $this->next();
            $other->next();
        }
    }

    public function le(Iterator $other): bool
    {
        while (true) {
            if (!$this->valid()) {
                return !$other->valid();
            }

            if (!$other->valid()) {
                return false;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $a <= $b;

            if (!$result) {
                return false;
            }

            $this->next();
            $other->next();
        }
    }

    public function gt(Iterator $other): bool
    {
        while (true) {
            if (!$this->valid()) {
                return !$other->valid();
            }

            if (!$other->valid()) {
                return false;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $a > $b;

            if (!$result) {
                return false;
            }

            $this->next();
            $other->next();
        }
    }

    public function ge(Iterator $other): bool
    {
        while (true) {
            if (!$this->valid()) {
                return !$other->valid();
            }

            if (!$other->valid()) {
                return false;
            }

            $a = $this->current();
            $b = $other->current();

            $result = $a >= $b;

            if (!$result) {
                return false;
            }

            $this->next();
            $other->next();
        }
    }

    public function isSorted(): bool
    {
        if (!$this->valid()) {
            return true;
        }

        $last = $this->current();
        $this->next();

        foreach ($this as $curr) {
            if ($last > $curr) {
                return false;
            }

            $last = $curr;
        }

        return true;
    }

    public function isSortedBy(callable $cmp): bool
    {
        if (!$this->valid()) {
            return true;
        }

        $last = $this->current();
        $this->next();

        foreach ($this as $curr) {
            if ($cmp($last, $curr) === 1) {
                return false;
            }

            $last = $curr;
        }

        return true;
    }

    public function isSortedByKey(callable $fn): bool
    {
        if (!$this->valid()) {
            return true;
        }

        $lastKey = $fn($this->key(), $this->current());
        $this->next();

        foreach ($this as $k => $curr) {
            $currKey = $fn($k, $curr);

            if ($lastKey > $currKey) {
                return false;
            }

            $lastKey = $currKey;
        }

        return true;
    }

    public function partition(callable $predicate): array
    {
        $result = [[], []];

        foreach ($this as $k => $v) {
            $result[$predicate($k, $v) ? 1 : 0] = $v;
        }

        return $result;
    }

    public function isPartitioned(callable $predicate): bool
    {
        foreach ($this as $k => $v) {
            if ($predicate($k, $v) === false) {
                break;
            }
        }

        foreach ($this as $k => $v) {
            if ($predicate($k, $v) === true) {
                return false;
            }
        }

        return true;
    }
}
