<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class SkipWhile extends Iterator
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
        while (!$this->done) {
            $this->data->next();
            $this->done = !($this->predicate)($this->data->key(), $this->data->current());
        }

        return $this->data->current();
    }
}
