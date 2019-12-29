<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Filter extends Iterator
{
    private $predicate = null;

    public function __construct(iterable $data, callable $predicate)
    {
        parent::__construct($data);
        $this->predicate = $predicate;
    }

    public function current()
    {
        while (
            $this->data->valid() &&
            !($this->predicate)($this->data->key(), $this->data->current())
        ) {
            $this->data->next();
        }

        return $this->data->current();
    }
}
