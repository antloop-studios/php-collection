<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Inspect extends Iterator
{
    private $fn = null;

    public function __construct(iterable $data, callable $fn)
    {
        parent::__construct($data);
        $this->fn = $fn;
    }

    public function current()
    {
        ($this->fn)($this->data->key(), $this->data->current());

        return $this->data->current();
    }
}
