<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class FlatMap extends Iterator
{
    private $iter;
    private $fn;

    public function __construct(iterable $data, callable $fn)
    {
        parent::__construct($data);
        $this->fn = $fn;
    }

    public function rewind()
    {
        $this->iter = ($this->fn)($this->data->current());
        if (is_iterable($this->iter) && !$this->iter instanceof Iterable) {
            $this->iter = new Iterator($this->iter);
        }
    }

    public function current()
    {
        if (!is_iterable($this->iter)) {
            return $this->iter;
        }

        return $this->iter->current();
    }

    public function next()
    {
        if (is_iterable($this->iter)) {
            $this->iter->next();
        }

        if (!is_iterable($this->iter) || !$this->iter->valid()) {
            $this->data->next();
            $this->rewind();
        }
    }
}
