<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Enumerate extends Iterator
{
    private $index = 0;

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        $this->data->next();
        $this->index++;
    }
}
