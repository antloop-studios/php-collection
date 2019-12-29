<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Zip extends Iterator
{
    private $other;

    public function __construct(iterable $data, Iterator $other)
    {
        parent::__construct($data);
        $this->other = $other;
    }

    public function current()
    {
        return [$this->data->current(), $this->other->current()];
    }

    public function next()
    {
        $this->data->next();
        $this->other->next();
    }

    public function valid()
    {
        return $this->data->valid() && $this->other->valid();
    }
}
