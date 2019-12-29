<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Chain extends Iterator
{
    private $other = null;
    private $first = true;

    public function __construct(iterable $data, Iterator $other)
    {
        parent::__construct($data);

        $this->other = $other;
        $this->first = $this->other->valid();
    }

    public function next()
    {
        $this->data->next();

        if ($this->first && !$this->data->valid()) {
            $this->data = $this->other;
            $this->first = false;
        }
    }
}
