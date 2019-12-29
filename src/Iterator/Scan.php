<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Scan extends Iterator
{
    private $state;
    private $fn;

    public function __construct(iterable $data, $seed, callable $fn)
    {
        parent::__construct($data);
        $this->state = $seed;
        $this->fn = $fn;
    }

    public function current()
    {
        if (!$this->data->valid()) {
            return null;
        }

        return ($this->fn)($this->state, $this->data->key(), $this->data->current());
    }
}
