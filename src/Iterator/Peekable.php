<?php

/**
 * @author     evolbug <https://github.com/evolbug>
 * @copyright  2019 Daniels Kursits (evolbug)
 * @license    https://choosealicense.com/licenses/mit/  MIT license
 */


declare(strict_types=1);

namespace Antloop\Collection\Iterator;

class Peekable extends Iterator
{
    private $peeked = null;

    public function current()
    {
        if ($this->peeked !== null) {
            return $this->peeked;
        } else {
            return $this->data->current();
        }
    }

    public function next()
    {
        if ($this->peeked === null) {
            $this->data->next();
        } else {
            $this->peeked = null;
        }
    }

    public function peek()
    {
        if ($this->peeked === null) {
            $this->next();
            $this->peeked = $this->current();
        }

        return $this->peeked;
    }
}
