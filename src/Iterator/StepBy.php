<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class StepBy extends Iterator
{
    private $step = 1;

    public function __construct(iterable $data = [], int $step = 1)
    {
        parent::__construct($data);
        $this->step = $step;
    }

    public function next()
    {
        for ($i = 0; $i < $this->step; $i++) {
            $this->data->next();
        }
    }
}
