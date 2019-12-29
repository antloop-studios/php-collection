<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class FilterMap extends Iterator
{
    private $fn = null;

    public function __construct(iterable $data, callable $fn)
    {
        parent::__construct($data);
        $this->fn = $fn;
    }

    public function rewind()
    {
        while (
            $this->data->valid()
            && ($this->fn)($this->data->key(), $this->data->current()) === null
        ) {
            $this->data->next();
        }
    }

    public function current()
    {
        return ($this->fn)($this->data->key(), $this->data->current());
    }

    public function next()
    {
        $this->data->next();
        $this->rewind();
    }
}
