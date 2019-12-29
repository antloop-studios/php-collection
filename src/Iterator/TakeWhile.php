<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class TakeWhile extends Iterator
{
    private $done = false;
    private $predicate = null;

    public function __construct(iterable $data, callable $predicate)
    {
        parent::__construct($data);
        $this->predicate = $predicate;
        $this->done = !($this->predicate)($this->data->key(), $this->data->current());
    }

    public function current()
    {
        if (!$this->valid()) {
            return null;
        }

        return $this->data->current();
    }

    public function next()
    {
        if (!$this->done) {
            $this->done = !($this->predicate)($this->data->key(), $this->data->current());
            $this->data->next();
        }
    }

    public function valid()
    {
        return ($this->predicate)($this->data->key(), $this->data->current()) && $this->data->valid();
    }
}
