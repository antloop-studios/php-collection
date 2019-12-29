<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Map extends Iterator
{
    private $fn = null;

    public function __construct(iterable $data, callable $fn)
    {
        parent::__construct($data);
        $this->fn = $fn;
    }

    public function current()
    {
        if (!$this->data->valid()) {
            return null;
        }

        return ($this->fn)($this->data->key(), $this->data->current());
    }
}
