<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Take extends Iterator
{
    private $n = 0;

    public function __construct(iterable $data, int $n)
    {
        parent::__construct($data);
        $this->n = $n;
    }

    public function next()
    {
        $this->data->next();
        $this->n--;
    }

    public function valid()
    {
        return $this->n > 0 && $this->data->valid();
    }
}
