<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Cycle extends Iterator
{
    private $reset = null;

    public function __construct(iterable $data)
    {
        $this->reset = $data;
        parent::__construct($data);
    }

    public function valid()
    {
        if (!$this->data->valid()) {
            $this->data = clone $this->reset;
        }

        return $this->data->valid();
    }
}
