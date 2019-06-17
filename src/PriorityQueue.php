<?php


namespace Panda\Tsp;

use SplPriorityQueue;

class PriorityQueue extends SplPriorityQueue
{
    public function compare($lhs, $rhs)
    {
        if ($lhs === $rhs) {
            return 0;
        }
        return ($lhs < $rhs) ? 1 : -1;
    }
}
